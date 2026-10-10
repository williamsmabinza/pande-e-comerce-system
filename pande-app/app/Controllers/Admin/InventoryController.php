<?php
/**
 * Admin Inventory Management Controller
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
use App\Models\Product;

class InventoryController extends BaseController {
    public function __construct() {
        Auth::requireStaff();
    }

    public function index(Request $request): void {
        $db = Database::getInstance();
        $q = trim((string)$request->input('q', ''));
        $filter = $request->input('filter', 'all');

        $where = ["p.status = 'active'"];
        $params = [];

        if ($q !== '') {
            $where[] = "(p.name LIKE :q OR p.sku LIKE :q OR p.brand LIKE :q)";
            $params['q'] = "%{$q}%";
        }

        if ($filter === 'low') {
            $where[] = "p.stock_quantity <= p.low_stock_threshold AND p.stock_quantity > 0";
        } elseif ($filter === 'out') {
            $where[] = "p.stock_quantity = 0";
        }

        $whereSql = implode(' AND ', $where);
        $stmt = $db->prepare("
            SELECT p.id, p.name, p.sku, p.brand, p.price, p.cost_price, p.stock_quantity, p.low_stock_threshold, c.name as category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE {$whereSql}
            ORDER BY p.stock_quantity ASC
        ");
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        $movements = InventoryMovement::getRecent(30);

        $this->render('admin.inventory.index', [
            'pageTitle' => 'Stock & Inventory Management - Admin',
            'products' => $products,
            'movements' => $movements,
            'filter' => $filter,
            'q' => $q,
        ], 'admin');
    }

    public function adjust(Request $request): void {
        $validator = new Validator($request->all(), [
            'product_id' => 'required|numeric',
            'adjustment_type' => 'required|in:add,subtract,set',
            'quantity' => 'required|numeric|min:0',
            'reason' => 'required|min:3|max:255',
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
        $reason = $request->input('reason');

        $newStock = match ($type) {
            'add' => $currentStock + $qty,
            'subtract' => max(0, $currentStock - $qty),
            'set' => $qty,
        };

        $change = $newStock - $currentStock;

        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE products SET stock_quantity = :s WHERE id = :id");
        $stmt->execute(['s' => $newStock, 'id' => $productId]);

        InventoryMovement::record(
            $productId,
            null,
            'adjustment',
            $change,
            $currentStock,
            $newStock,
            "Manual adjustment: " . $reason
        );

        Logger::audit('stock_adjusted', 'product', $productId, "Stock adjusted from {$currentStock} to {$newStock}. Reason: {$reason}");
        Session::flash('success', "Stock for '{$product['name']}' updated to {$newStock} units.");
        $this->redirect(APP_URL . '/admin/inventory');
    }
}
