<?php
/**
 * Executive Admin Dashboard Controller
 * Pande Computers - Kariakoo, Dar es Salaam
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Logger;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use PDO;

class AdminController extends BaseController {
    public function __construct() {
        Auth::requireStaff();
    }

    public function index(Request $request): void {
        $db = Database::getInstance();
        $driver = Database::getDriver();
        $todayDateFunc = $driver === 'sqlite' ? "date('now')" : "CURDATE()";
        $monthFunc = $driver === 'sqlite' ? "strftime('%Y-%m', created_at) = strftime('%Y-%m', 'now')" : "DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')";

        // 1. Order Status Counts
        $totalOrders = (int)$db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        $pendingOrders = (int)$db->query("SELECT COUNT(*) FROM orders WHERE order_status = 'pending'")->fetchColumn();
        $awaitingPayment = (int)$db->query("SELECT COUNT(*) FROM orders WHERE payment_status = 'awaiting_confirmation'")->fetchColumn();
        $processingOrders = (int)$db->query("SELECT COUNT(*) FROM orders WHERE order_status IN ('confirmed', 'processing')")->fetchColumn();
        $completedOrders = (int)$db->query("SELECT COUNT(*) FROM orders WHERE order_status = 'completed'")->fetchColumn();
        $cancelledOrders = (int)$db->query("SELECT COUNT(*) FROM orders WHERE order_status = 'cancelled'")->fetchColumn();

        // 2. Financial Metrics (TZS)
        $totalSales = (float)$db->query("
            SELECT COALESCE(SUM(total_amount), 0) 
            FROM orders 
            WHERE payment_status = 'verified' OR order_status = 'completed'
        ")->fetchColumn();

        $todaySales = (float)$db->query("
            SELECT COALESCE(SUM(total_amount), 0) 
            FROM orders 
            WHERE (payment_status = 'verified' OR order_status = 'completed')
              AND date(created_at) = {$todayDateFunc}
        ")->fetchColumn();

        $thisMonthSales = (float)$db->query("
            SELECT COALESCE(SUM(total_amount), 0) 
            FROM orders 
            WHERE (payment_status = 'verified' OR order_status = 'completed')
              AND {$monthFunc}
        ")->fetchColumn();

        $verifiedOrdersCount = (int)$db->query("
            SELECT COUNT(*) FROM orders WHERE payment_status = 'verified' OR order_status = 'completed'
        ")->fetchColumn();
        $avgOrderValue = $verifiedOrdersCount > 0 ? (float)($totalSales / $verifiedOrdersCount) : 0.0;

        // 3. Catalog & Inventory Metrics
        $activeProducts = (int)$db->query("SELECT COUNT(*) FROM products WHERE status = 'active'")->fetchColumn();
        $lowStockProducts = (int)$db->query("SELECT COUNT(*) FROM products WHERE stock_quantity <= low_stock_threshold AND stock_quantity > 0 AND status = 'active'")->fetchColumn();
        $outOfStockProducts = (int)$db->query("SELECT COUNT(*) FROM products WHERE stock_quantity = 0 AND status = 'active'")->fetchColumn();
        $totalCategories = (int)$db->query("SELECT COUNT(*) FROM categories")->fetchColumn();

        // 4. Users & Roles
        $totalCustomers = (int)$db->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
        $totalStaff = (int)$db->query("SELECT COUNT(*) FROM users WHERE role IN ('admin', 'staff')")->fetchColumn();

        // 5. Recent 12 Orders with items count & phone
        $recentOrders = $db->query("
            SELECT o.*,
                   (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as items_count
            FROM orders o
            ORDER BY o.id DESC
            LIMIT 12
        ")->fetchAll();

        // 6. Critical Stock Alerts (Items needing immediate restock)
        $criticalStockProducts = $db->query("
            SELECT p.id, p.name, p.sku, p.brand, p.price, p.stock_quantity, p.low_stock_threshold, c.name as category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE (p.stock_quantity <= p.low_stock_threshold OR p.stock_quantity = 0) AND p.status = 'active'
            ORDER BY p.stock_quantity ASC, p.id DESC
            LIMIT 10
        ")->fetchAll();

        // 7. Catalog Quick Products for Fast Price & Stock Adjust
        $catalogQuickProducts = $db->query("
            SELECT p.id, p.name, p.sku, p.price, p.compare_at_price, p.stock_quantity, p.status, c.name as category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'active'
            ORDER BY p.id DESC
            LIMIT 20
        ")->fetchAll();

        // 8. Top 5 Best-Selling Products
        $bestSellers = $db->query("
            SELECT oi.product_id, oi.product_name, oi.sku, SUM(oi.quantity) as total_sold, SUM(oi.line_total) as total_revenue
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            WHERE o.order_status NOT IN ('cancelled')
            GROUP BY oi.product_id, oi.product_name, oi.sku
            ORDER BY total_sold DESC
            LIMIT 5
        ")->fetchAll();

        // 9. Sales Breakdown by Category
        $salesByCategory = $db->query("
            SELECT c.name as category_name, COUNT(DISTINCT oi.id) as units_sold, COALESCE(SUM(oi.line_total), 0) as category_revenue
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            LEFT JOIN order_items oi ON p.id = oi.product_id
            LEFT JOIN orders o ON oi.order_id = o.id AND o.order_status NOT IN ('cancelled')
            GROUP BY c.id, c.name
            HAVING category_revenue > 0
            ORDER BY category_revenue DESC
            LIMIT 6
        ")->fetchAll();

        // 10. Recent Registered Customers
        $recentCustomers = $db->query("
            SELECT u.id, u.first_name, u.last_name, u.email, u.phone, u.status, u.created_at,
                   (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as orders_placed
            FROM users u
            WHERE u.role = 'customer'
            ORDER BY u.id DESC
            LIMIT 6
        ")->fetchAll();

        // 11. Recent Audit Logs
        $recentActivityLogs = $db->query("
            SELECT l.*, u.first_name, u.last_name, u.role as user_role
            FROM admin_activity_logs l
            LEFT JOIN users u ON l.user_id = u.id
            ORDER BY l.id DESC
            LIMIT 8
        ")->fetchAll();

        // 12. Store Settings
        $storeAnnouncement = Setting::get('store_announcement', 'Kariakoo Store Open Mon - Sat (8:30 AM - 6:30 PM). Genuine Laptops, PCs & Accessories with Same-Day Delivery in Dar es Salaam.');
        $storeStatus = Setting::get('store_status', 'open');
        $storePhone = Setting::get('store_phone', '+255 787 311 342');
        $whatsappNumber = Setting::get('whatsapp_number', '+255787311342');
        $storeAddress = Setting::get('store_address', 'Ndanda & Aggrey Street, Kariakoo, Dar es Salaam');

        $this->render('admin.dashboard', [
            'pageTitle' => 'Admin Control Center - Pande Computers',
            'stats' => [
                'totalOrders' => $totalOrders,
                'pendingOrders' => $pendingOrders,
                'awaitingPayment' => $awaitingPayment,
                'processingOrders' => $processingOrders,
                'completedOrders' => $completedOrders,
                'cancelledOrders' => $cancelledOrders,
                'totalSales' => $totalSales,
                'todaySales' => $todaySales,
                'thisMonthSales' => $thisMonthSales,
                'avgOrderValue' => $avgOrderValue,
                'totalCustomers' => $totalCustomers,
                'totalStaff' => $totalStaff,
                'activeProducts' => $activeProducts,
                'lowStockProducts' => $lowStockProducts,
                'outOfStockProducts' => $outOfStockProducts,
                'totalCategories' => $totalCategories,
            ],
            'recentOrders' => $recentOrders,
            'criticalStockProducts' => $criticalStockProducts,
            'catalogQuickProducts' => $catalogQuickProducts,
            'bestSellers' => $bestSellers,
            'salesByCategory' => $salesByCategory,
            'recentCustomers' => $recentCustomers,
            'recentActivityLogs' => $recentActivityLogs,
            'storeConfig' => [
                'announcement' => $storeAnnouncement,
                'status' => $storeStatus,
                'phone' => $storePhone,
                'whatsapp' => $whatsappNumber,
                'address' => $storeAddress,
            ],
        ], 'admin');
    }

    public function quickOrderStatus(Request $request): void {
        $id = (int)$request->input('order_id');
        $status = $request->input('order_status');
        $notes = $request->input('notes');

        $allowedStatuses = ['pending', 'confirmed', 'processing', 'ready_collection', 'shipped', 'completed', 'cancelled'];
        if (!in_array($status, $allowedStatuses, true)) {
            Session::flash('error', 'Invalid order status selected.');
            $this->redirect(APP_URL . '/admin#orders-hub');
            return;
        }

        $order = Order::find($id);
        if (!$order) {
            Session::flash('error', 'Order not found.');
            $this->redirect(APP_URL . '/admin#orders-hub');
            return;
        }

        $success = Order::updateStatus($id, $status, $notes);
        if ($success) {
            Logger::audit('order_status_updated', 'order', $id, "Status changed to '{$status}' via Quick Control");
            Session::flash('success', "Order #{$order['order_number']} status changed to " . strtoupper($status) . ".");
        } else {
            Session::flash('error', 'Failed to update order status.');
        }

        $this->redirect(APP_URL . '/admin#orders-hub');
    }

    public function quickVerifyPayment(Request $request): void {
        $id = (int)$request->input('order_id');
        $method = $request->input('payment_method', 'M-Pesa / WhatsApp');
        $reference = trim((string)$request->input('payment_reference', ''));

        $order = Order::find($id);
        if (!$order) {
            Session::flash('error', 'Order not found.');
            $this->redirect(APP_URL . '/admin#orders-hub');
            return;
        }

        $success = Order::verifyPayment($id, $method, $reference);
        if ($success) {
            Logger::audit('payment_verified', 'order', $id, "Payment verified via {$method}, Ref: {$reference}");
            Session::flash('success', "Payment successfully verified for Order #{$order['order_number']}.");
        } else {
            Session::flash('error', 'Failed to verify payment.');
        }

        $this->redirect(APP_URL . '/admin#orders-hub');
    }

    public function quickStockAdjust(Request $request): void {
        $productId = (int)$request->input('product_id');
        $type = $request->input('adjustment_type', 'add');
        $qty = (int)$request->input('quantity', 0);
        $reason = trim((string)$request->input('reason', 'Quick Restock via Admin Control Center'));

        $product = Product::find($productId);
        if (!$product) {
            Session::flash('error', 'Product not found.');
            $this->redirect(APP_URL . '/admin#inventory-hub');
            return;
        }

        $currentStock = (int)$product['stock_quantity'];
        $newStock = match ($type) {
            'add' => $currentStock + $qty,
            'subtract' => max(0, $currentStock - $qty),
            'set' => max(0, $qty),
            default => $currentStock,
        };

        $change = $newStock - $currentStock;
        if ($change === 0) {
            Session::flash('info', 'No stock change made.');
            $this->redirect(APP_URL . '/admin#inventory-hub');
            return;
        }

        $db = Database::getInstance();
        $driver = Database::getDriver();
        $nowFunc = $driver === 'sqlite' ? "datetime('now')" : "NOW()";

        $stmt = $db->prepare("UPDATE products SET stock_quantity = :stock, updated_at = {$nowFunc} WHERE id = :id");
        $stmt->execute(['stock' => $newStock, 'id' => $productId]);

        InventoryMovement::record(
            $productId,
            null,
            $change > 0 ? 'restock' : 'adjustment',
            abs($change),
            $currentStock,
            $newStock,
            $reason
        );

        Logger::audit('quick_stock_adjust', 'product', $productId, "Stock adjusted from {$currentStock} to {$newStock} ({$type} {$qty})");
        Session::flash('success', "Stock for {$product['name']} updated: {$newStock} units available.");
        $this->redirect(APP_URL . '/admin#inventory-hub');
    }

    public function quickPriceUpdate(Request $request): void {
        $productId = (int)$request->input('product_id');
        $price = (float)$request->input('price');
        $comparePrice = $request->input('compare_at_price') ? (float)$request->input('compare_at_price') : null;

        if ($price <= 0) {
            Session::flash('error', 'Price must be greater than zero.');
            $this->redirect(APP_URL . '/admin#catalog-hub');
            return;
        }

        $product = Product::find($productId);
        if (!$product) {
            Session::flash('error', 'Product not found.');
            $this->redirect(APP_URL . '/admin#catalog-hub');
            return;
        }

        $db = Database::getInstance();
        $driver = Database::getDriver();
        $nowFunc = $driver === 'sqlite' ? "datetime('now')" : "NOW()";

        $stmt = $db->prepare("UPDATE products SET price = :p, compare_at_price = :cp, updated_at = {$nowFunc} WHERE id = :id");
        $stmt->execute([
            'p' => $price,
            'cp' => $comparePrice,
            'id' => $productId
        ]);

        Logger::audit('quick_price_update', 'product', $productId, "Price updated from TZS " . number_format((float)$product['price']) . " to TZS " . number_format($price));
        Session::flash('success', "Price for {$product['name']} updated to " . formatTZS($price) . ".");
        $this->redirect(APP_URL . '/admin#catalog-hub');
    }

    public function quickAnnouncement(Request $request): void {
        $announcement = trim((string)$request->input('store_announcement', ''));
        $status = trim((string)$request->input('store_status', 'open'));

        Setting::set('store_announcement', $announcement);
        Setting::set('store_status', $status);

        Logger::audit('announcement_updated', 'settings', null, "Store notice updated: {$announcement} (Status: {$status})");
        Session::flash('success', 'Store notice banner and operation status updated successfully.');
        $this->redirect(APP_URL . '/admin');
    }

    public function backupDatabase(Request $request): void {
        Auth::requireAdmin();

        $sourceFile = dirname(__DIR__, 3) . '/storage/database.sqlite';
        $backupDir = dirname(__DIR__, 3) . '/storage/backups';

        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $timestamp = date('Ymd_His');
        $backupFile = "{$backupDir}/pande_backup_{$timestamp}.sqlite";

        if (file_exists($sourceFile)) {
            copy($sourceFile, $backupFile);
            Logger::audit('database_backup', 'system', null, "Backup created: pande_backup_{$timestamp}.sqlite");
            Session::flash('success', "Database backup created successfully: pande_backup_{$timestamp}.sqlite");
        } else {
            Session::flash('error', 'Database source file not found.');
        }

        $this->redirect(APP_URL . '/admin#system-hub');
    }

    public function clearCache(Request $request): void {
        Auth::requireAdmin();

        $logsDir = dirname(__DIR__, 3) . '/storage/logs';
        if (is_dir($logsDir)) {
            $files = glob("{$logsDir}/*.log");
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== 'app-' . date('Y-m-d') . '.log') {
                    @unlink($file);
                }
            }
        }

        Logger::audit('cache_cleared', 'system', null, 'System log archives and buffers purged');
        Session::flash('success', 'Application buffers and rotated logs cleared successfully.');
        $this->redirect(APP_URL . '/admin#system-hub');
    }
}
