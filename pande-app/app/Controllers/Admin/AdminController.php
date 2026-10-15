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

        // Filter Period Selection (all, today, 7days, 30days, this_month)
        $selectedPeriod = trim((string)($request->query('period', 'all')));
        if (!in_array($selectedPeriod, ['all', 'today', '7days', '30days', 'this_month'])) {
            $selectedPeriod = 'all';
        }

        // Period WHERE clause for financial summary
        $periodWhere = match($selectedPeriod) {
            'today' => "AND date(created_at) = {$todayDateFunc}",
            '7days' => "AND created_at >= " . ($driver === 'sqlite' ? "datetime('now', '-7 days')" : "DATE_SUB(NOW(), INTERVAL 7 DAY)"),
            '30days' => "AND created_at >= " . ($driver === 'sqlite' ? "datetime('now', '-30 days')" : "DATE_SUB(NOW(), INTERVAL 30 DAY)"),
            'this_month' => "AND {$monthFunc}",
            default => ""
        };

        // 1. Order Status Metrics (Real counts)
        $totalOrders = (int)$db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        $todayOrders = (int)$db->query("SELECT COUNT(*) FROM orders WHERE date(created_at) = {$todayDateFunc}")->fetchColumn();
        $pendingOrders = (int)$db->query("SELECT COUNT(*) FROM orders WHERE order_status = 'pending'")->fetchColumn();
        $awaitingPayment = (int)$db->query("SELECT COUNT(*) FROM orders WHERE payment_status = 'awaiting_confirmation'")->fetchColumn();
        $verifiedPayments = (int)$db->query("SELECT COUNT(*) FROM orders WHERE payment_status = 'verified'")->fetchColumn();
        $failedPayments = (int)$db->query("SELECT COUNT(*) FROM orders WHERE payment_status = 'failed'")->fetchColumn();
        $processingOrders = (int)$db->query("SELECT COUNT(*) FROM orders WHERE order_status IN ('confirmed', 'processing')")->fetchColumn();
        $dispatchedOrders = (int)$db->query("SELECT COUNT(*) FROM orders WHERE order_status IN ('ready_collection', 'shipped', 'dispatched')")->fetchColumn();
        $completedOrders = (int)$db->query("SELECT COUNT(*) FROM orders WHERE order_status = 'completed'")->fetchColumn();
        $cancelledOrders = (int)$db->query("SELECT COUNT(*) FROM orders WHERE order_status = 'cancelled'")->fetchColumn();
        $refundRequests = 0; // Refunds supported placeholder: 0 active requests

        // 2. Financial Metrics (TZS) - Period Aware & Historical
        $periodSales = (float)$db->query("
            SELECT COALESCE(SUM(total_amount), 0) 
            FROM orders 
            WHERE (payment_status = 'verified' OR order_status = 'completed') 
              {$periodWhere}
        ")->fetchColumn();

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

        $sales7Days = (float)$db->query("
            SELECT COALESCE(SUM(total_amount), 0)
            FROM orders
            WHERE (payment_status = 'verified' OR order_status = 'completed')
              AND created_at >= " . ($driver === 'sqlite' ? "datetime('now', '-7 days')" : "DATE_SUB(NOW(), INTERVAL 7 DAY)") . "
        ")->fetchColumn();

        $prev7DaysSales = (float)$db->query("
            SELECT COALESCE(SUM(total_amount), 0)
            FROM orders
            WHERE (payment_status = 'verified' OR order_status = 'completed')
              AND created_at >= " . ($driver === 'sqlite' ? "datetime('now', '-14 days')" : "DATE_SUB(NOW(), INTERVAL 14 DAY)") . "
              AND created_at < " . ($driver === 'sqlite' ? "datetime('now', '-7 days')" : "DATE_SUB(NOW(), INTERVAL 7 DAY)") . "
        ")->fetchColumn();

        $sales30Days = (float)$db->query("
            SELECT COALESCE(SUM(total_amount), 0)
            FROM orders
            WHERE (payment_status = 'verified' OR order_status = 'completed')
              AND created_at >= " . ($driver === 'sqlite' ? "datetime('now', '-30 days')" : "DATE_SUB(NOW(), INTERVAL 30 DAY)") . "
        ")->fetchColumn();

        $verifiedOrdersCount = (int)$db->query("
            SELECT COUNT(*) FROM orders WHERE payment_status = 'verified' OR order_status = 'completed'
        ")->fetchColumn();
        $avgOrderValue = $verifiedOrdersCount > 0 ? (float)($totalSales / $verifiedOrdersCount) : 0.0;

        // 3. Catalog & Inventory Metrics
        $totalActiveProducts = (int)$db->query("SELECT COUNT(*) FROM products WHERE status = 'active'")->fetchColumn();
        $totalDiscontinuedProducts = (int)$db->query("SELECT COUNT(*) FROM products WHERE status IN ('discontinued', 'archived')")->fetchColumn();
        $totalCostValuation = (float)$db->query("SELECT COALESCE(SUM(COALESCE(cost_price, price * 0.75) * stock_quantity), 0) FROM products WHERE status = 'active'")->fetchColumn();
        $inStockProducts = (int)$db->query("SELECT COUNT(*) FROM products WHERE stock_quantity > 0 AND status = 'active'")->fetchColumn();
        $lowStockProducts = (int)$db->query("SELECT COUNT(*) FROM products WHERE stock_quantity <= low_stock_threshold AND stock_quantity > 0 AND status = 'active'")->fetchColumn();
        $outOfStockProducts = (int)$db->query("SELECT COUNT(*) FROM products WHERE stock_quantity = 0 AND status = 'active'")->fetchColumn();
        $totalInventoryUnits = (int)$db->query("SELECT COALESCE(SUM(stock_quantity), 0) FROM products WHERE status = 'active'")->fetchColumn();
        $totalRetailValuation = (float)$db->query("SELECT COALESCE(SUM(price * stock_quantity), 0) FROM products WHERE status = 'active'")->fetchColumn();
        $totalCategories = (int)$db->query("SELECT COUNT(*) FROM categories")->fetchColumn();

        // Detect products with incomplete specifications/information
        $incompleteProducts = (int)$db->query("
            SELECT COUNT(*) FROM products 
            WHERE (description IS NULL OR description = '' OR brand IS NULL OR brand = '' OR sku IS NULL OR sku = '')
              AND status = 'active'
        ")->fetchColumn();

        // 4. Users, Customers & Staff Metrics
        $totalCustomers = (int)$db->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
        $activeCustomers = (int)$db->query("SELECT COUNT(*) FROM users WHERE role = 'customer' AND status = 'active'")->fetchColumn();
        $suspendedCustomers = (int)$db->query("SELECT COUNT(*) FROM users WHERE role = 'customer' AND status IN ('suspended', 'inactive')")->fetchColumn();
        $todayCustomers = (int)$db->query("SELECT COUNT(*) FROM users WHERE role = 'customer' AND date(created_at) = {$todayDateFunc}")->fetchColumn();
        $totalStaff = (int)$db->query("SELECT COUNT(*) FROM users WHERE role IN ('admin', 'staff')")->fetchColumn();

        // Repeat customers count (placed > 1 order)
        $repeatCustomersCount = (int)$db->query("
            SELECT COUNT(*) FROM (
                SELECT customer_phone FROM orders 
                WHERE order_status != 'cancelled' 
                GROUP BY customer_phone 
                HAVING COUNT(*) > 1
            ) as t
        ")->fetchColumn();

        // 5. Recent Orders with items count & details (limited to 15)
        $recentOrders = $db->query("
            SELECT o.*,
                   (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as items_count
            FROM orders o
            ORDER BY o.id DESC
            LIMIT 15
        ")->fetchAll();

        // 6. Critical Stock Alerts (Items needing immediate restock)
        $criticalStockProducts = $db->query("
            SELECT p.id, p.name, p.sku, p.brand, p.price, p.stock_quantity, p.low_stock_threshold, c.name as category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE (p.stock_quantity <= p.low_stock_threshold OR p.stock_quantity = 0) AND p.status = 'active'
            ORDER BY p.stock_quantity ASC, p.id DESC
            LIMIT 12
        ")->fetchAll();

        // 7. Recent Inventory Stock Movements History
        $recentStockMovements = $db->query("
            SELECT im.*, p.name as product_name, p.sku, u.first_name, u.last_name
            FROM inventory_movements im
            LEFT JOIN products p ON im.product_id = p.id
            LEFT JOIN users u ON im.user_id = u.id
            ORDER BY im.id DESC
            LIMIT 8
        ")->fetchAll();

        // Recent Product Updates
        $recentProductUpdates = $db->query("
            SELECT p.id, p.name, p.sku, p.price, p.stock_quantity, p.status, p.updated_at, c.name as category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY COALESCE(p.updated_at, p.created_at) DESC, p.id DESC
            LIMIT 8
        ")->fetchAll();

        // 8. Catalog Quick Products for Fast Price & Stock Adjust
        $catalogQuickProducts = $db->query("
            SELECT p.id, p.name, p.sku, p.price, p.compare_at_price, p.stock_quantity, p.status, c.name as category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'active'
            ORDER BY p.id DESC
            LIMIT 25
        ")->fetchAll();

        // 9. Top Best-Selling Products (with image, units sold, revenue, stock)
        $bestSellers = $db->query("
            SELECT oi.product_id, oi.product_name, oi.sku, 
                   SUM(oi.quantity) as total_sold, 
                   SUM(oi.line_total) as total_revenue,
                   p.stock_quantity, p.price, p.brand,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE o.order_status NOT IN ('cancelled')
            GROUP BY oi.product_id, oi.product_name, oi.sku
            ORDER BY total_sold DESC
            LIMIT 6
        ")->fetchAll();

        // 10. Sales Breakdown by Category
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

        // 11. Daily Sales Trend (Recent 7 days chronological for Chart.js)
        $dailySalesTrend = $db->query("
            SELECT date(created_at) as sale_date,
                   COUNT(*) as orders_count,
                   COALESCE(SUM(total_amount), 0) as daily_revenue
            FROM orders
            WHERE (payment_status = 'verified' OR order_status = 'completed')
              AND created_at >= " . ($driver === 'sqlite' ? "datetime('now', '-7 days')" : "DATE_SUB(NOW(), INTERVAL 7 DAY)") . "
            GROUP BY date(created_at)
            ORDER BY date(created_at) ASC
        ")->fetchAll();

        // 12. Monthly Performance Trend (Last 6 Months)
        $monthlyPerformance = $db->query("
            SELECT " . ($driver === 'sqlite' ? "strftime('%Y-%m', created_at)" : "DATE_FORMAT(created_at, '%Y-%m')") . " as period_month,
                   COUNT(*) as orders_count,
                   COALESCE(SUM(total_amount), 0) as month_revenue
            FROM orders
            WHERE (payment_status = 'verified' OR order_status = 'completed')
            GROUP BY period_month
            ORDER BY period_month DESC
            LIMIT 6
        ")->fetchAll();

        // 13. Payment Methods Breakdown
        $paymentMethodStats = $db->query("
            SELECT payment_method, COUNT(*) as count, COALESCE(SUM(total_amount), 0) as total_amount
            FROM orders
            WHERE order_status NOT IN ('cancelled')
            GROUP BY payment_method
            ORDER BY count DESC
        ")->fetchAll();

        // 14. Recent Registered Customers
        $recentCustomers = $db->query("
            SELECT u.id, u.first_name, u.last_name, u.email, u.phone, u.status, u.created_at,
                   (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as orders_placed
            FROM users u
            WHERE u.role = 'customer'
            ORDER BY u.id DESC
            LIMIT 8
        ")->fetchAll();

        // 15. Recent Audit Logs
        $recentActivityLogs = $db->query("
            SELECT l.*, u.first_name, u.last_name, u.role as user_role
            FROM admin_activity_logs l
            LEFT JOIN users u ON l.user_id = u.id
            ORDER BY l.id DESC
            LIMIT 10
        ")->fetchAll();

        // 16. System Health Metrics & Backup Verifier
        $backupDir = dirname(__DIR__, 3) . '/storage/backups';
        $lastBackupTime = null;
        $lastBackupName = null;
        if (is_dir($backupDir)) {
            $files = glob("{$backupDir}/*.sqlite");
            if (!empty($files)) {
                rsort($files);
                $latestFile = $files[0];
                $lastBackupTime = date('d M Y, H:i', filemtime($latestFile));
                $lastBackupName = basename($latestFile);
            }
        }

        // 17. Store Settings
        $storeAnnouncement = Setting::get('store_announcement', 'Kariakoo Store Open Mon - Sat (8:30 AM - 6:30 PM). Genuine Laptops, PCs & Accessories with Same-Day Delivery in Dar es Salaam.');
        $storeStatus = Setting::get('store_status', 'open');
        $storePhone = Setting::get('store_phone', '+255 787 311 342');
        $whatsappNumber = Setting::get('whatsapp_number', '+255787311342');
        $storeAddress = Setting::get('store_address', 'Ndanda & Aggrey Street, Kariakoo, Dar es Salaam');

        $heroConfig = [
            'badge' => Setting::get('hero_badge', 'Authorized Tanzanian IT Retailer'),
            'title1' => Setting::get('hero_title_line1', 'YOUR TECH.'),
            'title2' => Setting::get('hero_title_line2', 'YOUR WAY.'),
            'subtitle' => Setting::get('hero_subtitle', 'Shop laptops, desktops, components and IT essentials online. Genuine computing hardware delivered across Dar es Salaam and nationwide.'),
            'btnPrimary' => Setting::get('hero_btn_primary_text', 'Shop Now'),
            'btnPrimaryUrl' => Setting::get('hero_btn_primary_url', APP_URL . '/shop'),
            'btnSecondary' => Setting::get('hero_btn_secondary_text', 'Explore Categories'),
            'btnSecondaryUrl' => Setting::get('hero_btn_secondary_url', '#browse-categories'),
            'showcaseImage' => Setting::get('hero_showcase_image', 'src/assets/images/regenerated_image_1791995491732.png'),
            'bgImage' => Setting::get('hero_bg_image', 'assets/images/hero-tech-bg.svg'),
        ];

        $this->render('admin.dashboard', [
            'pageTitle' => 'Overview - Admin Command Center',
            'selectedPeriod' => $selectedPeriod,
            'stats' => [
                'periodSales' => $periodSales,
                'totalOrders' => $totalOrders,
                'todayOrders' => $todayOrders,
                'pendingOrders' => $pendingOrders,
                'awaitingPayment' => $awaitingPayment,
                'verifiedPayments' => $verifiedPayments,
                'failedPayments' => $failedPayments,
                'processingOrders' => $processingOrders,
                'dispatchedOrders' => $dispatchedOrders,
                'completedOrders' => $completedOrders,
                'cancelledOrders' => $cancelledOrders,
                'refundRequests' => $refundRequests,
                'totalSales' => $totalSales,
                'todaySales' => $todaySales,
                'thisMonthSales' => $thisMonthSales,
                'sales7Days' => $sales7Days,
                'prev7DaysSales' => $prev7DaysSales,
                'sales30Days' => $sales30Days,
                'avgOrderValue' => $avgOrderValue,
                'totalCustomers' => $totalCustomers,
                'todayCustomers' => $todayCustomers,
                'repeatCustomersCount' => $repeatCustomersCount,
                'totalStaff' => $totalStaff,
                'totalActiveProducts' => $totalActiveProducts,
                'activeProducts' => $totalActiveProducts,
                'inStockProducts' => $inStockProducts,
                'lowStockProducts' => $lowStockProducts,
                'outOfStockProducts' => $outOfStockProducts,
                'incompleteProducts' => $incompleteProducts,
                'totalInventoryUnits' => $totalInventoryUnits,
                'totalRetailValuation' => $totalRetailValuation,
                'totalCategories' => $totalCategories,
                'totalDiscontinuedProducts' => $totalDiscontinuedProducts,
                'totalCostValuation' => $totalCostValuation,
                'activeCustomers' => $activeCustomers,
                'suspendedCustomers' => $suspendedCustomers,
            ],
            'recentOrders' => $recentOrders,
            'criticalStockProducts' => $criticalStockProducts,
            'recentStockMovements' => $recentStockMovements,
            'catalogQuickProducts' => $catalogQuickProducts,
            'bestSellers' => $bestSellers,
            'salesByCategory' => $salesByCategory,
            'dailySalesTrend' => $dailySalesTrend,
            'monthlyPerformance' => $monthlyPerformance,
            'paymentMethodStats' => $paymentMethodStats,
            'recentCustomers' => $recentCustomers,
            'recentProductUpdates' => $recentProductUpdates,
            'recentActivityLogs' => $recentActivityLogs,
            'systemHealth' => [
                'lastBackupTime' => $lastBackupTime,
                'lastBackupName' => $lastBackupName,
            ],
            'storeConfig' => [
                'announcement' => $storeAnnouncement,
                'status' => $storeStatus,
                'phone' => $storePhone,
                'whatsapp' => $whatsappNumber,
                'address' => $storeAddress,
            ],
            'heroConfig' => $heroConfig,
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

        public function uploadHeroImage(Request $request): void {
        Auth::requireAdmin();
        $file = $request->file('hero_image_file');

        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $errorCode = $file['error'] ?? UPLOAD_ERR_NO_FILE;
            $msg = match($errorCode) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Uploaded image exceeds maximum allowable file size (max 8MB).',
                UPLOAD_ERR_NO_FILE => 'Please select an image file from your computer before clicking upload.',
                default => 'File upload encountered an error. Please try again.'
            };
            Session::flash('error', $msg);
            $this->redirect(APP_URL . '/admin#heroContentEditor');
            return;
        }

        // Validate MIME type safely
        $mimeType = '';
        if (class_exists('\finfo')) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = (string)$finfo->file($file['tmp_name']);
        } elseif (function_exists('finfo_open')) {
            $fres = finfo_open(FILEINFO_MIME_TYPE);
            if ($fres) {
                $mimeType = (string)finfo_file($fres, $file['tmp_name']);
                finfo_close($fres);
            }
        } elseif (function_exists('mime_content_type')) {
            $mimeType = (string)mime_content_type($file['tmp_name']);
        }
        $allowedMimes = [
            'image/jpeg' => 'jpg',
            'image/pjpeg' => 'jpg',
            'image/png'  => 'png',
            'image/x-png' => 'png',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
            'image/avif' => 'avif',
            'image/gif'  => 'gif'
        ];

        if (!isset($allowedMimes[$mimeType])) {
            // Also check file extension fallback for systems with missing mime info
            $pathExt = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
            $extMap = ['jpg' => 'jpg', 'jpeg' => 'jpg', 'png' => 'png', 'webp' => 'webp', 'svg' => 'svg', 'avif' => 'avif'];
            if (isset($extMap[$pathExt])) {
                $ext = $extMap[$pathExt];
            } else {
                Session::flash('error', 'Invalid file type (' . htmlspecialchars($mimeType) . '). Supported formats: JPG, PNG, WebP, SVG, AVIF.');
                $this->redirect(APP_URL . '/admin#heroContentEditor');
                return;
            }
        } else {
            $ext = $allowedMimes[$mimeType];
        }

        // Validate file size (max 8MB)
        if ($file['size'] > 8 * 1024 * 1024) {
            Session::flash('error', 'Image size exceeds maximum allowable limit of 8MB.');
            $this->redirect(APP_URL . '/admin#heroContentEditor');
            return;
        }

        $uploadDir = PUBLIC_DIR . '/assets/uploads/hero';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $targetType = trim((string)$request->input('hero_upload_target', 'showcase'));
        $prefix = $targetType === 'background' ? 'hero_bg_' : 'hero_showcase_';
        $filename = $prefix . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destination = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Also copy to React dev public asset directory if it exists for seamless preview
            $devPublicHeroDir = dirname(PUBLIC_DIR) . '/../public/assets/uploads/hero';
            if (is_dir(dirname($devPublicHeroDir))) {
                @mkdir($devPublicHeroDir, 0755, true);
                @copy($destination, $devPublicHeroDir . '/' . $filename);
            }

            $relativeAssetPath = 'assets/uploads/hero/' . $filename;
            
            if ($targetType === 'background') {
                Setting::set('hero_bg_image', $relativeAssetPath, 'homepage');
                $logMsg = "Homepage hero background uploaded: {$filename}";
                $successMsg = "Hero background picture successfully uploaded and activated! File: {$file['name']}";
            } else {
                Setting::set('hero_showcase_image', $relativeAssetPath, 'homepage');
                $logMsg = "Homepage hero showcase image uploaded: {$filename}";
                $successMsg = "Hero product showcase image successfully uploaded from your computer and activated on the homepage! File: {$file['name']}";
            }

            Logger::audit('hero_image_uploaded', 'homepage', null, $logMsg);
            Session::flash('success', $successMsg);
        } else {
            Session::flash('error', 'Failed to save uploaded image to destination directory. Check server permissions.');
        }

        $this->redirect(APP_URL . '/admin#heroContentEditor');
    }

public function quickHeroSettings(Request $request): void {
        $badge = trim((string)$request->input('hero_badge', 'Authorized Tanzanian IT Retailer'));
        $title1 = trim((string)$request->input('hero_title_line1', 'YOUR TECH.'));
        $title2 = trim((string)$request->input('hero_title_line2', 'YOUR WAY.'));
        $subtitle = trim((string)$request->input('hero_subtitle', ''));
        $btnPrimary = trim((string)$request->input('hero_btn_primary_text', 'Shop Now'));
        $btnPrimaryUrl = trim((string)$request->input('hero_btn_primary_url', APP_URL . '/shop'));
        $btnSecondary = trim((string)$request->input('hero_btn_secondary_text', 'Explore Categories'));
        $btnSecondaryUrl = trim((string)$request->input('hero_btn_secondary_url', '#browse-categories'));
        $showcaseImage = trim((string)$request->input('hero_showcase_image', 'assets/images/hero-products-showcase.svg'));
        $bgImage = trim((string)$request->input('hero_bg_image', 'assets/images/hero-tech-bg.svg'));

        Setting::set('hero_badge', $badge, 'homepage');
        Setting::set('hero_title_line1', $title1, 'homepage');
        Setting::set('hero_title_line2', $title2, 'homepage');
        Setting::set('hero_subtitle', $subtitle, 'homepage');
        Setting::set('hero_btn_primary_text', $btnPrimary, 'homepage');
        Setting::set('hero_btn_primary_url', $btnPrimaryUrl, 'homepage');
        Setting::set('hero_btn_secondary_text', $btnSecondary, 'homepage');
        Setting::set('hero_btn_secondary_url', $btnSecondaryUrl, 'homepage');
        Setting::set('hero_showcase_image', $showcaseImage, 'homepage');
        Setting::set('hero_bg_image', $bgImage, 'homepage');

        Logger::audit('hero_updated', 'homepage', null, "Homepage hero content & visual images updated by staff");
        Session::flash('success', 'Homepage hero images, banner artwork and headlines updated successfully!');
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