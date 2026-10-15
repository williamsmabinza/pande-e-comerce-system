<?php
/**
 * Admin Inventory & Stock Control Controller
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
use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Product;
use PDO;

class InventoryController extends BaseController {

    public function __construct() {
        Auth::requireStaff();
    }

    /**
     * Stock overview, threshold monitors, and stock movement ledger
     */
    public function index(Request $request): void {
        $db = Database::getInstance();

        $q = trim((string)$request->input('q', ''));
        $filter = $request->input('filter', 'all');
        $catId = $request->input('category_id', '');

        $where = ["p.status = 'active'"];
        $params = [];

        if ($q !== '') {
            $where[] = "(p.name LIKE :q OR p.sku LIKE :q OR p.brand LIKE :q)";
            $params['q'] = "%{$q}%";
        }

        if ($catId !== '' && is_numeric($catId)) {
            $where[] = "p.category_id = :cat";
            $params['cat'] = (int)$catId;
        }

        if ($filter === 'low') {
            $where[] = "p.stock_quantity <= p.low_stock_threshold AND p.stock_quantity > 0";
        } elseif ($filter === 'out') {
            $where[] = "p.stock_quantity = 0";
        } elseif ($filter === 'healthy') {
            $where[] = "p.stock_quantity > p.low_stock_threshold";
        }

        $whereSql = implode(' AND ', $where);

        $stmt = $db->prepare("
            SELECT p.id, p.name, p.sku, p.brand, p.price, p.cost_price, p.stock_quantity, p.low_stock_threshold, p.status,
                   c.name as category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE {$whereSql}
            ORDER BY p.stock_quantity ASC, p.name ASC
        ");
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Inventory KPIs
        $totalItems = (int)$db->query("SELECT COUNT(*) FROM products WHERE status = 'active'")->fetchColumn();
        $healthyCount = (int)$db->query("SELECT COUNT(*) FROM products WHERE status = 'active' AND stock_quantity > low_stock_threshold")->fetchColumn();
        $lowCount = (int)$db->query("SELECT COUNT(*) FROM products WHERE status = 'active' AND stock_quantity <= low_stock_threshold AND stock_quantity > 0")->fetchColumn();
        $outCount = (int)$db->query("SELECT COUNT(*) FROM products WHERE status = 'active' AND stock_quantity = 0")->fetchColumn();
        $totalUnits = (int)$db->query("SELECT COALESCE(SUM(stock_quantity), 0) FROM products WHERE status = 'active'")->fetchColumn();
        $retailValuation = (float)$db->query("SELECT COALESCE(SUM(price * stock_quantity), 0) FROM products WHERE status = 'active'")->fetchColumn();
        $costValuation = (float)$db->query("SELECT COALESCE(SUM(COALESCE(cost_price, price * 0.75) * stock_quantity), 0) FROM products WHERE status = 'active'")->fetchColumn();

        // Recent Movements with responsible administrator / staff
        $movements = InventoryMovement::getRecent(50);
        $categories = Category::all();

        $this->render('admin.inventory.index', [
            'pageTitle' => 'Stock & Inventory Management - Pande Computers',
            'products' => $products,
            'movements' => $movements,
            'categories' => $categories,
            'filter' => $filter,
            'q' => $q,
            'catId' => $catId,
            'kpis' => [
                'totalItems' => $totalItems,
                'healthyCount' => $healthyCount,
                'lowCount' => $lowCount,
                'outCount' => $outCount,
                'totalUnits' => $totalUnits,
                'retailValuation' => $retailValuation,
                'costValuation' => $costValuation,
            ],
        ], 'admin');
    }

    /**
     * Record stock movement with reason, transaction safety, and non-negative stock constraint
     */
    public function adjust(Request $request): void {
        $validator = new Validator($request->all(), [
            'product_id' => 'required|numeric',
            'adjustment_type' => 'required|in:add,subtract,set',
            'quantity' => 'required|numeric|min:0',
            'reason_type' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/admin/inventory');
            return;
        }

        $productId = (int)$request->input('product_id');
        $product = Product::find($productId);

        if (!$product) {
            Session::flash('error', 'Product not found.');
            $this->redirect(APP_URL . '/admin/inventory');
            return;
        }

        $currentStock = (int)$product['stock_quantity'];
        $qty = (int)$request->input('quantity');
        $type = $request->input('adjustment_type');
        $reasonType = trim((string)$request->input('reason_type'));
        $notes = trim((string)$request->input('notes', ''));

        // Compose comprehensive reason description
        $reasonDesc = $reasonType . ($notes !== '' ? " — {$notes}" : "");

        $newStock = match ($type) {
            'add' => $currentStock + $qty,
            'subtract' => max(0, $currentStock - $qty),
            'set' => max(0, $qty),
        };

        $change = $newStock - $currentStock;

        $db = Database::getInstance();

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("UPDATE products SET stock_quantity = :s, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
            $stmt->execute(['s' => $newStock, 'id' => $productId]);

            InventoryMovement::record(
                $productId,
                null,
                match($type) {
                    'add' => 'supplier_received',
                    'subtract' => 'inventory_reduction',
                    'set' => 'audit_correction',
                },
                $change,
                $currentStock,
                $newStock,
                $reasonDesc
            );

            $diffStr = ($change >= 0 ? '+' : '') . $change;
            Logger::audit(
                'stock_adjusted',
                'product',
                $productId,
                "Stock adjusted for '{$product['name']}' from {$currentStock} to {$newStock} ({$diffStr} units). Reason: {$reasonDesc}"
            );

            $db->commit();

            Session::flash('success', "Stock for '{$product['name']}' successfully updated from {$currentStock} to {$newStock} units.");
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            Session::flash('error', 'Failed to update stock: ' . $e->getMessage());
        }

        $this->redirect(APP_URL . '/admin/inventory');
    }

    /**
     * Update safety low-stock alert threshold
     */
    public function updateThreshold(Request $request): void {
        $validator = new Validator($request->all(), [
            'product_id' => 'required|numeric',
            'threshold' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/admin/inventory');
            return;
        }

        $productId = (int)$request->input('product_id');
        $threshold = (int)$request->input('threshold');
        $product = Product::find($productId);

        if (!$product) {
            Session::flash('error', 'Product not found.');
            $this->redirect(APP_URL . '/admin/inventory');
            return;
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE products SET low_stock_threshold = :t WHERE id = :id");
        $stmt->execute(['t' => $threshold, 'id' => $productId]);

        Logger::audit(
            'threshold_updated',
            'product',
            $productId,
            "Low-stock threshold for '{$product['name']}' updated to {$threshold} units"
        );

        Session::flash('success', "Low-stock threshold for '{$product['name']}' set to {$threshold} units.");
        $this->redirect(APP_URL . '/admin/inventory');
    }
}
