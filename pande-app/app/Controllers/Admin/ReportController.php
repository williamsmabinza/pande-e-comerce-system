<?php
/**
 * Admin Reports & Analytics Controller
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Request;

class ReportController extends BaseController {
    public function __construct() {
        Auth::requireStaff();
    }

    public function index(Request $request): void {
        $db = Database::getInstance();

        // 1. Sales summaries
        $dailySales = $db->query("
            SELECT DATE(created_at) as sale_date, COUNT(*) as orders_count, SUM(total_amount) as revenue
            FROM orders
            WHERE payment_status = 'verified' OR order_status = 'completed'
            GROUP BY DATE(created_at)
            ORDER BY sale_date DESC
            LIMIT 14
        ")->fetchAll();

        // 2. Best selling products
        $bestSellers = $db->query("
            SELECT oi.product_name, oi.sku, SUM(oi.quantity) as total_units, SUM(oi.line_total) as total_revenue
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            WHERE o.order_status NOT IN ('cancelled')
            GROUP BY oi.product_name, oi.sku
            ORDER BY total_units DESC
            LIMIT 10
        ")->fetchAll();

        // 3. Inventory Valuation
        $inventoryValuation = $db->query("
            SELECT 
                SUM(stock_quantity) as total_units,
                SUM(stock_quantity * price) as total_retail_value,
                SUM(stock_quantity * COALESCE(cost_price, price * 0.75)) as total_cost_value
            FROM products
            WHERE status = 'active'
        ")->fetch();

        // 4. Low stock count
        $lowStock = $db->query("
            SELECT p.id, p.name, p.sku, p.stock_quantity, p.low_stock_threshold, c.name as category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.stock_quantity <= p.low_stock_threshold AND p.status = 'active'
            ORDER BY p.stock_quantity ASC
        ")->fetchAll();

        $this->render('admin.reports.index', [
            'pageTitle' => 'Store Reports & Analytics - Admin',
            'dailySales' => $dailySales,
            'bestSellers' => $bestSellers,
            'valuation' => $inventoryValuation,
            'lowStock' => $lowStock,
        ], 'admin');
    }

    public function exportOrders(Request $request): void {
        $db = Database::getInstance();
        $orders = $db->query("
            SELECT order_number, customer_name, customer_phone, customer_email, delivery_method, total_amount, payment_status, order_status, created_at
            FROM orders
            ORDER BY id DESC
        ")->fetchAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="pande_orders_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Order Number', 'Customer Name', 'Phone', 'Email', 'Delivery Method', 'Total (TZS)', 'Payment Status', 'Order Status', 'Date']);

        foreach ($orders as $row) {
            fputcsv($output, [
                $row['order_number'],
                $row['customer_name'],
                $row['customer_phone'],
                $row['customer_email'],
                $row['delivery_method'],
                $row['total_amount'],
                $row['payment_status'],
                $row['order_status'],
                $row['created_at'],
            ]);
        }

        fclose($output);
        exit;
    }
}
