<?php
/**
 * Shopping Cart Service & Model
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Session;

class Cart {
    private const SESSION_KEY = 'shopping_cart';

    public static function getItems(): array {
        Session::start();
        $items = Session::get(self::SESSION_KEY, []);

        if (empty($items)) {
            return [];
        }

        // Revalidate against live database prices and stock
        $validatedItems = [];
        foreach ($items as $productId => $item) {
            $product = Product::find((int)$productId);
            if (!$product || $product['status'] !== 'active') {
                continue; // Skip inactive/deleted
            }

            $qty = min((int)$item['quantity'], (int)$product['stock_quantity']);
            if ($qty <= 0) {
                continue;
            }

            $price = (float)$product['price'];
            $images = Product::getImages((int)$product['id']);
            $primaryImage = !empty($images) ? $images[0]['image_path'] : 'assets/images/products/laptop.svg';

            $validatedItems[$productId] = [
                'product_id' => (int)$product['id'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'sku' => $product['sku'],
                'brand' => $product['brand'],
                'image' => $primaryImage,
                'price' => $price,
                'compare_at_price' => $product['compare_at_price'] ? (float)$product['compare_at_price'] : null,
                'quantity' => $qty,
                'max_stock' => (int)$product['stock_quantity'],
                'line_total' => $price * $qty,
            ];
        }

        Session::set(self::SESSION_KEY, $validatedItems);
        return $validatedItems;
    }

    public static function add(int $productId, int $quantity = 1): bool {
        $product = Product::find($productId);
        if (!$product || $product['status'] !== 'active' || $product['stock_quantity'] <= 0) {
            return false;
        }

        Session::start();
        $items = Session::get(self::SESSION_KEY, []);

        $currentQty = isset($items[$productId]) ? (int)$items[$productId]['quantity'] : 0;
        $newQty = min($currentQty + $quantity, (int)$product['stock_quantity']);

        $items[$productId] = [
            'product_id' => $productId,
            'name' => $product['name'],
            'quantity' => $newQty,
        ];

        Session::set(self::SESSION_KEY, $items);
        return true;
    }

    public static function updateQuantity(int $productId, int $quantity): bool {
        Session::start();
        $items = Session::get(self::SESSION_KEY, []);

        if ($quantity <= 0) {
            unset($items[$productId]);
            Session::set(self::SESSION_KEY, $items);
            return true;
        }

        $product = Product::find($productId);
        if (!$product || $product['status'] !== 'active') {
            unset($items[$productId]);
            Session::set(self::SESSION_KEY, $items);
            return false;
        }

        $actualQty = min($quantity, (int)$product['stock_quantity']);
        $items[$productId] = [
            'product_id' => $productId,
            'name' => $product['name'],
            'quantity' => $actualQty,
        ];

        Session::set(self::SESSION_KEY, $items);
        return true;
    }

    public static function remove(int $productId): void {
        Session::start();
        $items = Session::get(self::SESSION_KEY, []);
        unset($items[$productId]);
        Session::set(self::SESSION_KEY, $items);
    }

    public static function clear(): void {
        Session::start();
        Session::remove(self::SESSION_KEY);
    }

    public static function getTotals(): array {
        $items = self::getItems();
        $subtotal = 0.00;
        $totalItemsCount = 0;

        foreach ($items as $item) {
            $subtotal += $item['line_total'];
            $totalItemsCount += $item['quantity'];
        }

        $freeThreshold = (float)Setting::get('free_delivery_threshold', 600000);
        $deliveryFee = ($subtotal > 0 && $subtotal < $freeThreshold) ? (float)Setting::get('delivery_fee_default', 10000) : 0.00;
        $total = $subtotal + $deliveryFee;

        return [
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total' => $total,
            'count' => $totalItemsCount,
            'free_delivery_threshold' => $freeThreshold,
            'amount_to_free_delivery' => max(0, $freeThreshold - $subtotal),
        ];
    }
}
