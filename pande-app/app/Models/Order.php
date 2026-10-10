<?php
/**
 * Order Model
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Auth;
use App\Core\Database;
use App\Core\WhatsApp;
use PDO;
use RuntimeException;

class Order extends BaseModel {
    protected static string $table = 'orders';

    public static function generateOrderNumber(): string {
        $db = static::db();
        $prefix = Setting::get('order_prefix', 'PAN-2026-');
        $random = strtoupper(bin2hex(random_bytes(3))); // 6 hex chars
        $orderNum = $prefix . $random;

        // Ensure uniqueness
        $stmt = $db->prepare("SELECT COUNT(*) FROM orders WHERE order_number = :num");
        $stmt->execute(['num' => $orderNum]);
        if ($stmt->fetchColumn() > 0) {
            return self::generateOrderNumber();
        }

        return $orderNum;
    }

    public static function createOrder(array $orderData, array $cartItems): array {
        $db = static::db();

        // Idempotency check if idempotency_key is passed
        if (!empty($orderData['idempotency_key'])) {
            $check = $db->prepare("SELECT * FROM orders WHERE idempotency_key = :key LIMIT 1");
            $check->execute(['key' => $orderData['idempotency_key']]);
            $existing = $check->fetch();
            if ($existing) {
                return $existing;
            }
        }

        Database::beginTransaction();

        try {
            $orderNumber = self::generateOrderNumber();
            $subtotal = 0.00;
            $itemsToInsert = [];

            // 1. Validate items and verify live stock
            foreach ($cartItems as $item) {
                $product = Product::find((int)$item['product_id']);
                if (!$product || $product['status'] !== 'active') {
                    throw new RuntimeException("Product '{$item['name']}' is no longer active or available.");
                }

                $qty = (int)$item['quantity'];
                if ($qty <= 0) {
                    throw new RuntimeException("Invalid quantity for product '{$product['name']}'.");
                }

                if ($product['stock_quantity'] < $qty) {
                    throw new RuntimeException("Insufficient stock for '{$product['name']}'. Available: {$product['stock_quantity']}.");
                }

                $price = (float)$product['price']; // Always trust server database price, never client
                $lineTotal = $price * $qty;
                $subtotal += $lineTotal;

                $itemsToInsert[] = [
                    'product_id' => (int)$product['id'],
                    'product_name' => $product['name'],
                    'sku' => $product['sku'],
                    'unit_price' => $price,
                    'quantity' => $qty,
                    'line_total' => $lineTotal,
                    'specs' => $product['specifications'] ?? null
                ];
            }

            if (empty($itemsToInsert)) {
                throw new RuntimeException("Your shopping cart is empty.");
            }

            // 2. Calculate delivery and totals
            $deliveryMethod = $orderData['delivery_method'] ?? 'delivery';
            $deliveryFee = 0.00;
            if ($deliveryMethod === 'delivery') {
                $freeThreshold = (float)Setting::get('free_delivery_threshold', 600000);
                if ($subtotal < $freeThreshold) {
                    $deliveryFee = (float)Setting::get('delivery_fee_default', 10000);
                }
            }

            $discount = 0.00;
            $totalAmount = $subtotal + $deliveryFee - $discount;

            $driver = Database::getDriver();
            $nowFunc = $driver === 'sqlite' ? "datetime('now')" : "NOW()";

            // 3. Insert Order Record
            $sql = "
                INSERT INTO orders (
                    order_number, user_id, customer_name, customer_phone, customer_email,
                    delivery_method, delivery_address, delivery_city, order_notes,
                    subtotal, delivery_fee, discount_amount, total_amount,
                    payment_status, order_status, payment_method,
                    idempotency_key, created_at, updated_at
                ) VALUES (
                    :order_number, :user_id, :customer_name, :customer_phone, :customer_email,
                    :delivery_method, :delivery_address, :delivery_city, :order_notes,
                    :subtotal, :delivery_fee, :discount_amount, :total_amount,
                    'awaiting_confirmation', 'pending', 'WhatsApp Direct / Mobile Money',
                    :idempotency_key, {$nowFunc}, {$nowFunc}
                )
            ";

            $stmt = $db->prepare($sql);
            $stmt->execute([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'customer_name' => $orderData['customer_name'],
                'customer_phone' => $orderData['customer_phone'],
                'customer_email' => $orderData['customer_email'] ?? null,
                'delivery_method' => $deliveryMethod,
                'delivery_address' => $orderData['delivery_address'] ?? null,
                'delivery_city' => $orderData['delivery_city'] ?? 'Dar es Salaam',
                'order_notes' => $orderData['order_notes'] ?? null,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'discount_amount' => $discount,
                'total_amount' => $totalAmount,
                'idempotency_key' => $orderData['idempotency_key'] ?? null,
            ]);

            $orderId = (int)$db->lastInsertId();

            // 4. Insert Order Items Snapshot & Deduct Stock
            $itemStmt = $db->prepare("
                INSERT INTO order_items (
                    order_id, product_id, product_name, sku, unit_price, quantity, line_total, product_specs, created_at
                ) VALUES (
                    :order_id, :product_id, :product_name, :sku, :unit_price, :quantity, :line_total, :product_specs, {$nowFunc}
                )
            ");

            foreach ($itemsToInsert as $item) {
                $itemStmt->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'sku' => $item['sku'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                    'product_specs' => $item['specs'],
                ]);

                // Deduct stock in real-time
                $deducted = Product::deductStock($item['product_id'], $item['quantity'], $orderId);
                if (!$deducted) {
                    throw new RuntimeException("Failed to reserve stock for {$item['product_name']}. Please try again.");
                }
            }

            // 5. Generate official WhatsApp click-to-chat message & link
            $orderRecord = self::find($orderId);
            $businessWhatsApp = Setting::get('whatsapp_number', STORE_WHATSAPP);
            $waMessage = WhatsApp::buildOrderMessage($orderRecord, $itemsToInsert);
            $waLink = WhatsApp::generateClickToChatLink($businessWhatsApp, $waMessage);

            $upd = $db->prepare("UPDATE orders SET whatsapp_link = :link WHERE id = :id");
            $upd->execute(['link' => $waLink, 'id' => $orderId]);
            $orderRecord['whatsapp_link'] = $waLink;

            // 6. Record Status History
            $historyStmt = $db->prepare("
                INSERT INTO order_status_history (order_id, changed_by, old_status, new_status, notes, created_at)
                VALUES (:order_id, :changed_by, 'none', 'pending', 'Order placed by customer via WhatsApp checkout', {$nowFunc})
            ");
            $historyStmt->execute([
                'order_id' => $orderId,
                'changed_by' => Auth::id()
            ]);

            Database::commit();

            return $orderRecord;

        } catch (\Throwable $e) {
            Database::rollBack();
            throw $e;
        }
    }

    public static function findByNumber(string $orderNumber): ?array {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM orders WHERE order_number = :num LIMIT 1");
        $stmt->execute(['num' => $orderNumber]);
        $order = $stmt->fetch();
        if ($order) {
            $order['items'] = self::getItems((int)$order['id']);
            $order['history'] = self::getStatusHistory((int)$order['id']);
        }
        return $order ?: null;
    }

    public static function getItems(int $orderId): array {
        $db = static::db();
        $stmt = $db->prepare("
            SELECT oi.*, p.slug as product_slug 
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = :id
            ORDER BY oi.id ASC
        ");
        $stmt->execute(['id' => $orderId]);
        return $stmt->fetchAll();
    }

    public static function getStatusHistory(int $orderId): array {
        $db = static::db();
        $stmt = $db->prepare("
            SELECT osh.*, u.first_name, u.last_name
            FROM order_status_history osh
            LEFT JOIN users u ON osh.changed_by = u.id
            WHERE osh.order_id = :id
            ORDER BY osh.id ASC
        ");
        $stmt->execute(['id' => $orderId]);
        return $stmt->fetchAll();
    }

    public static function updateStatus(int $id, string $newStatus, ?string $notes = null): bool {
        $db = static::db();
        $order = self::find($id);
        if (!$order) {
            return false;
        }

        $oldStatus = $order['order_status'];
        if ($oldStatus === $newStatus) {
            return true;
        }

        // If transitioning to cancelled from non-cancelled, restore stock
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            $items = self::getItems($id);
            foreach ($items as $item) {
                if (!empty($item['product_id'])) {
                    Product::restoreStock(
                        (int)$item['product_id'],
                        (int)$item['quantity'],
                        $id,
                        "Restored stock from cancelled Order #{$order['order_number']}"
                    );
                }
            }
        }

        $driver = Database::getDriver();
        $nowFunc = $driver === 'sqlite' ? "datetime('now')" : "NOW()";

        $stmt = $db->prepare("UPDATE orders SET order_status = :status, updated_at = {$nowFunc} WHERE id = :id");
        $success = $stmt->execute(['status' => $newStatus, 'id' => $id]);

        if ($success) {
            $hist = $db->prepare("
                INSERT INTO order_status_history (order_id, changed_by, old_status, new_status, notes, created_at)
                VALUES (:order_id, :changed_by, :old_status, :new_status, :notes, {$nowFunc})
            ");
            $hist->execute([
                'order_id' => $id,
                'changed_by' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'notes' => $notes ?? "Order status changed from {$oldStatus} to {$newStatus}"
            ]);
        }

        return $success;
    }

    public static function verifyPayment(int $id, string $method, ?string $reference = null): bool {
        $db = static::db();
        $driver = Database::getDriver();
        $nowFunc = $driver === 'sqlite' ? "datetime('now')" : "NOW()";

        $stmt = $db->prepare("
            UPDATE orders SET
                payment_status = 'verified',
                order_status = 'confirmed',
                payment_method = :method,
                payment_reference = :ref,
                payment_verified_by = :user_id,
                payment_verified_at = {$nowFunc},
                updated_at = {$nowFunc}
            WHERE id = :id
        ");

        $res = $stmt->execute([
            'id' => $id,
            'method' => $method,
            'ref' => $reference,
            'user_id' => Auth::id()
        ]);

        if ($res) {
            $hist = $db->prepare("
                INSERT INTO order_status_history (order_id, changed_by, old_status, new_status, notes, created_at)
                VALUES (:order_id, :changed_by, 'awaiting_confirmation', 'confirmed', :notes, {$nowFunc})
            ");
            $hist->execute([
                'order_id' => $id,
                'changed_by' => Auth::id(),
                'notes' => "Payment verified manually by administrator. Ref: " . ($reference ?: 'Direct Cash/WhatsApp')
            ]);
        }

        return $res;
    }

    public static function getCustomerOrders(int $userId): array {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = :uid ORDER BY id DESC");
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }
}
