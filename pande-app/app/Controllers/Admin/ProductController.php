<?php
/**
 * Admin Product Catalog & Inventory Controller
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

class ProductController extends BaseController {

    public function __construct() {
        Auth::requireStaff();
    }

    /**
     * Display product catalog with advanced search, status filters, and stock filters
     */
    public function index(Request $request): void {
        $db = Database::getInstance();

        $q = trim((string)$request->input('q', ''));
        $cat = $request->input('category', '');
        $status = $request->input('status', 'all');
        $stockStatus = $request->input('stock_status', 'all');

        $where = ['1=1'];
        $params = [];

        if ($q !== '') {
            $where[] = '(p.name LIKE :q OR p.sku LIKE :q OR p.brand LIKE :q OR p.model_number LIKE :q)';
            $params['q'] = "%{$q}%";
        }

        if ($cat !== '' && is_numeric($cat)) {
            $where[] = 'p.category_id = :cat';
            $params['cat'] = (int)$cat;
        }

        if ($status !== 'all' && $status !== '') {
            $where[] = 'p.status = :status';
            $params['status'] = $status;
        }

        if ($stockStatus === 'in_stock') {
            $where[] = 'p.stock_quantity > p.low_stock_threshold';
        } elseif ($stockStatus === 'low_stock') {
            $where[] = 'p.stock_quantity <= p.low_stock_threshold AND p.stock_quantity > 0';
        } elseif ($stockStatus === 'out_of_stock') {
            $where[] = 'p.stock_quantity = 0';
        }

        $whereSql = implode(' AND ', $where);

        $stmt = $db->prepare("
            SELECT p.*, c.name as category_name,
                   (SELECT image_path FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image,
                   (SELECT COUNT(*) FROM order_items WHERE product_id = p.id) as order_count
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE {$whereSql}
            ORDER BY p.id DESC
        ");
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Catalog-wide summary counters for executive stats
        $totalActive = (int)$db->query("SELECT COUNT(*) FROM products WHERE status = 'active'")->fetchColumn();
        $totalDiscontinued = (int)$db->query("SELECT COUNT(*) FROM products WHERE status IN ('discontinued', 'archived')")->fetchColumn();
        $totalLowStock = (int)$db->query("SELECT COUNT(*) FROM products WHERE status = 'active' AND stock_quantity <= low_stock_threshold AND stock_quantity > 0")->fetchColumn();
        $totalOutOfStock = (int)$db->query("SELECT COUNT(*) FROM products WHERE status = 'active' AND stock_quantity = 0")->fetchColumn();
        $totalCatalogValuation = (float)$db->query("SELECT COALESCE(SUM(price * stock_quantity), 0) FROM products WHERE status = 'active'")->fetchColumn();
        $totalCostValuation = (float)$db->query("SELECT COALESCE(SUM(COALESCE(cost_price, price * 0.75) * stock_quantity), 0) FROM products WHERE status = 'active'")->fetchColumn();

        $categories = Category::all();

        $this->render('admin.products.index', [
            'pageTitle' => 'Product Catalog & Full Admin Control - Pande Computers',
            'products' => $products,
            'categories' => $categories,
            'filters' => [
                'q' => $q,
                'category' => $cat,
                'status' => $status,
                'stock_status' => $stockStatus,
            ],
            'kpis' => [
                'totalActive' => $totalActive,
                'totalDiscontinued' => $totalDiscontinued,
                'totalLowStock' => $totalLowStock,
                'totalOutOfStock' => $totalOutOfStock,
                'totalCatalogValuation' => $totalCatalogValuation,
                'totalCostValuation' => $totalCostValuation,
            ],
        ], 'admin');
    }

    /**
     * Show form to create a new product
     */
    public function create(Request $request): void {
        $categories = Category::all();

        $this->render('admin.products.form', [
            'pageTitle' => 'Add New Product - Pande Computers Admin',
            'product' => null,
            'images' => [],
            'orderCount' => 0,
            'categories' => $categories,
            'action' => APP_URL . '/admin/products/store',
        ], 'admin');
    }

    /**
     * Persist a newly created product
     */
    public function store(Request $request): void {
        $validator = new Validator($request->all(), [
            'name' => 'required|min:3|max:255',
            'sku' => 'required|min:3|max:100',
            'category_id' => 'required|numeric',
            'brand' => 'required|min:2|max:100',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/admin/products/create');
            return;
        }

        $db = Database::getInstance();
        $sku = strtoupper(trim((string)$request->input('sku')));

        // Duplicate SKU prevention
        $skuCheck = $db->prepare('SELECT COUNT(*) FROM products WHERE UPPER(sku) = :sku');
        $skuCheck->execute(['sku' => $sku]);
        if ($skuCheck->fetchColumn() > 0) {
            Session::flash('error', "A product with SKU '{$sku}' already exists in the catalog. Please assign a unique SKU.");
            $this->redirect(APP_URL . '/admin/products/create');
            return;
        }

        $name = trim((string)$request->input('name'));
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

        // Ensure unique slug
        $checkSlug = $db->prepare('SELECT COUNT(*) FROM products WHERE slug = :s');
        $checkSlug->execute(['s' => $slug]);
        if ($checkSlug->fetchColumn() > 0) {
            $slug .= '-' . bin2hex(random_bytes(2));
        }

        $price = (float)$request->input('price');
        $comparePrice = $request->input('compare_at_price') !== '' && $request->input('compare_at_price') !== null ? (float)$request->input('compare_at_price') : null;
        $costPrice = $request->input('cost_price') !== '' && $request->input('cost_price') !== null ? (float)$request->input('cost_price') : null;
        $initialStock = (int)$request->input('stock_quantity', 0);
        $threshold = (int)$request->input('low_stock_threshold', 5);
        $status = trim((string)$request->input('status', 'active'));
        if (!in_array($status, ['active', 'out_of_stock', 'discontinued', 'archived', 'draft'], true)) {
            $status = 'active';
        }

        try {
            $productId = Product::create([
                'category_id' => (int)$request->input('category_id'),
                'name' => $name,
                'slug' => $slug,
                'sku' => $sku,
                'model_number' => trim((string)$request->input('model_number', '')),
                'brand' => trim((string)$request->input('brand')),
                'short_description' => trim((string)$request->input('short_description', '')),
                'description' => trim((string)$request->input('description', '')),
                'price' => $price,
                'compare_at_price' => $comparePrice,
                'cost_price' => $costPrice,
                'stock_quantity' => $initialStock,
                'low_stock_threshold' => $threshold,
                'is_featured' => $request->input('is_featured') ? 1 : 0,
                'is_new_arrival' => $request->input('is_new_arrival') ? 1 : 0,
                'status' => $status,
                'warranty_info' => trim((string)$request->input('warranty_info', '1 Year Official Warranty')),
                'specifications' => trim((string)$request->input('specifications', '')),
            ]);

            // Handle image upload if provided
            $this->handleImageUpload($request, $productId);

            // Log initial stock movement
            if ($initialStock > 0) {
                InventoryMovement::record(
                    $productId,
                    null,
                    'initial_stock',
                    $initialStock,
                    0,
                    $initialStock,
                    'Initial stock entry upon product creation in catalog'
                );
            }

            Logger::audit('product_created', 'product', $productId, "Product '{$name}' (SKU: {$sku}) created with initial stock {$initialStock} units");
            Session::flash('success', "Product '{$name}' created successfully in the catalog.");
            $this->redirect(APP_URL . '/admin/products');
        } catch (\Throwable $e) {
            Session::flash('error', 'Error creating product: ' . $e->getMessage());
            $this->redirect(APP_URL . '/admin/products/create');
        }
    }

    /**
     * Edit existing product
     */
    public function edit(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        $product = Product::find($id);

        if (!$product) {
            Session::flash('error', 'Product not found.');
            $this->redirect(APP_URL . '/admin/products');
            return;
        }

        $db = Database::getInstance();
        $orderCount = (int)$db->query("SELECT COUNT(*) FROM order_items WHERE product_id = {$id}")->fetchColumn();

        $categories = Category::all();
        $images = Product::getImages($id);

        $this->render('admin.products.form', [
            'pageTitle' => "Edit Product: {$product['name']} - Admin",
            'product' => $product,
            'images' => $images,
            'orderCount' => $orderCount,
            'categories' => $categories,
            'action' => APP_URL . "/admin/products/update/{$id}",
        ], 'admin');
    }

    /**
     * Update an existing product
     */
    public function update(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        $product = Product::find($id);

        if (!$product) {
            Session::flash('error', 'Product not found.');
            $this->redirect(APP_URL . '/admin/products');
            return;
        }

        $validator = new Validator($request->all(), [
            'name' => 'required|min:3|max:255',
            'sku' => 'required|min:3|max:100',
            'category_id' => 'required|numeric',
            'brand' => 'required|min:2|max:100',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . "/admin/products/edit/{$id}");
            return;
        }

        $db = Database::getInstance();
        $sku = strtoupper(trim((string)$request->input('sku')));

        // Duplicate SKU prevention excluding this product
        $skuCheck = $db->prepare('SELECT COUNT(*) FROM products WHERE UPPER(sku) = :sku AND id != :id');
        $skuCheck->execute(['sku' => $sku, 'id' => $id]);
        if ($skuCheck->fetchColumn() > 0) {
            Session::flash('error', "Another product already uses SKU '{$sku}'. Please assign a unique SKU.");
            $this->redirect(APP_URL . "/admin/products/edit/{$id}");
            return;
        }

        $name = trim((string)$request->input('name'));
        $slug = $product['slug']; // Maintain stable permalink

        $price = (float)$request->input('price');
        $comparePrice = $request->input('compare_at_price') !== '' && $request->input('compare_at_price') !== null ? (float)$request->input('compare_at_price') : null;
        $costPrice = $request->input('cost_price') !== '' && $request->input('cost_price') !== null ? (float)$request->input('cost_price') : null;
        $threshold = (int)$request->input('low_stock_threshold', 5);

        $status = trim((string)$request->input('status', $product['status']));
        if (!in_array($status, ['active', 'out_of_stock', 'discontinued', 'archived', 'draft'], true)) {
            $status = $product['status'];
        }

        // Check if stock quantity was adjusted in the form
        $currentStock = (int)$product['stock_quantity'];
        $newStock = $request->input('stock_quantity') !== null && $request->input('stock_quantity') !== ''
            ? max(0, (int)$request->input('stock_quantity'))
            : $currentStock;

        try {
            $updateData = [
                'category_id' => (int)$request->input('category_id'),
                'name' => $name,
                'slug' => $slug,
                'sku' => $sku,
                'model_number' => trim((string)$request->input('model_number', '')),
                'brand' => trim((string)$request->input('brand')),
                'short_description' => trim((string)$request->input('short_description', '')),
                'description' => trim((string)$request->input('description', '')),
                'price' => $price,
                'compare_at_price' => $comparePrice,
                'cost_price' => $costPrice,
                'stock_quantity' => $newStock,
                'low_stock_threshold' => $threshold,
                'is_featured' => $request->input('is_featured') ? 1 : 0,
                'is_new_arrival' => $request->input('is_new_arrival') ? 1 : 0,
                'status' => $status,
                'warranty_info' => trim((string)$request->input('warranty_info', '1 Year Official Warranty')),
                'specifications' => trim((string)$request->input('specifications', '')),
            ];

            Product::update($id, $updateData);

            // Record stock delta if changed
            if ($newStock !== $currentStock) {
                $diff = $newStock - $currentStock;
                InventoryMovement::record(
                    $id,
                    null,
                    'adjustment',
                    $diff,
                    $currentStock,
                    $newStock,
                    'Stock updated during product edit'
                );
            }

            $this->handleImageUpload($request, $id);

            Logger::audit('product_updated', 'product', $id, "Product '{$name}' (SKU: {$sku}) updated by administrator");
            Session::flash('success', "Product '{$name}' updated successfully.");
            $this->redirect(APP_URL . '/admin/products');
        } catch (\Throwable $e) {
            Session::flash('error', 'Error updating product: ' . $e->getMessage());
            $this->redirect(APP_URL . "/admin/products/edit/{$id}");
        }
    }

    /**
     * Delete product safely with historical reference protection
     */
    public function delete(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        $product = Product::find($id);

        if (!$product) {
            Session::flash('error', 'Product not found.');
            $this->redirect(APP_URL . '/admin/products');
            return;
        }

        $db = Database::getInstance();

        // Check if product is referenced in customer orders
        $orderCount = (int)$db->query("SELECT COUNT(*) FROM order_items WHERE product_id = {$id}")->fetchColumn();

        if ($orderCount > 0) {
            // SAFE DISCONTINUATION / ARCHIVAL: Preserve historical order records & invoices
            $stmt = $db->prepare("UPDATE products SET status = 'discontinued' WHERE id = :id");
            $stmt->execute(['id' => $id]);

            Logger::audit(
                'product_discontinued_protected',
                'product',
                $id,
                "Product '{$product['name']}' has {$orderCount} historical order items. Discontinued & archived to protect invoice records."
            );

            Session::flash('warning', "Product '{$product['name']}' is linked to {$orderCount} customer order record(s). To preserve invoice records and audit integrity, it cannot be permanently purged from database tables, but has been safely Discontinued & Archived (removed from public storefront).");
            $this->redirect(APP_URL . '/admin/products');
            return;
        }

        // No orders exist: safe to permanently remove
        try {
            $db->beginTransaction();

            // 1. Remove product images
            $images = $db->query("SELECT image_path FROM product_images WHERE product_id = {$id}")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($images as $img) {
                $imgPath = PUBLIC_DIR . '/' . $img['image_path'];
                if (file_exists($imgPath) && !str_contains($img['image_path'], 'assets/images/products/')) {
                    @unlink($imgPath);
                }
            }
            $db->exec("DELETE FROM product_images WHERE product_id = {$id}");

            // 2. Remove inventory movements
            $db->exec("DELETE FROM inventory_movements WHERE product_id = {$id}");

            // 3. Remove cart items
            $db->exec("DELETE FROM cart_items WHERE product_id = {$id}");

            // 4. Remove product record
            $db->exec("DELETE FROM products WHERE id = {$id}");

            $db->commit();

            Logger::audit('product_deleted', 'product', $id, "Permanently removed unreferenced product '{$product['name']}' (SKU: {$product['sku']})");
            Session::flash('success', "Product '{$product['name']}' was permanently removed from the catalog.");
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            Session::flash('error', 'Failed to delete product: ' . $e->getMessage());
        }

        $this->redirect(APP_URL . '/admin/products');
    }

    /**
     * Change product status (active, discontinued, archived, draft)
     */
    public function updateStatus(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        $product = Product::find($id);

        if (!$product) {
            Session::flash('error', 'Product not found.');
            $this->redirect(APP_URL . '/admin/products');
            return;
        }

        $newStatus = trim((string)$request->input('status'));
        if (!in_array($newStatus, ['active', 'out_of_stock', 'discontinued', 'archived', 'draft'], true)) {
            Session::flash('error', 'Invalid product status specified.');
            $this->redirect(APP_URL . '/admin/products');
            return;
        }

        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE products SET status = :s WHERE id = :id');
        $stmt->execute(['s' => $newStatus, 'id' => $id]);

        $statusLabel = match($newStatus) {
            'active' => 'Active (Available for purchase)',
            'discontinued' => 'Discontinued (Hidden from storefront, order history preserved)',
            'archived' => 'Archived (Hidden from catalog)',
            'draft' => 'Draft (Unpublished)',
            'out_of_stock' => 'Out of Stock',
        };

        Logger::audit('product_status_changed', 'product', $id, "Status changed to {$newStatus} for '{$product['name']}'");
        Session::flash('success', "Product '{$product['name']}' status set to: {$statusLabel}.");
        $this->redirect(APP_URL . '/admin/products');
    }

    /**
     * Perform bulk operations on multiple selected products
     */
    public function bulkAction(Request $request): void {
        $action = trim((string)$request->input('bulk_action'));
        $ids = $request->input('product_ids');

        if (empty($ids) || !is_array($ids)) {
            Session::flash('error', 'Please select at least one product using the checkboxes.');
            $this->redirect(APP_URL . '/admin/products');
            return;
        }

        $validActions = ['discontinue', 'archive', 'activate', 'delete'];
        if (!in_array($action, $validActions, true)) {
            Session::flash('error', 'Invalid bulk action specified.');
            $this->redirect(APP_URL . '/admin/products');
            return;
        }

        $db = Database::getInstance();
        $processedCount = 0;
        $protectedCount = 0;

        foreach ($ids as $rawId) {
            $id = (int)$rawId;
            if ($id <= 0) continue;

            $product = Product::find($id);
            if (!$product) continue;

            if ($action === 'activate') {
                $stmt = $db->prepare("UPDATE products SET status = 'active' WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $processedCount++;
            } elseif ($action === 'discontinue') {
                $stmt = $db->prepare("UPDATE products SET status = 'discontinued' WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $processedCount++;
            } elseif ($action === 'archive') {
                $stmt = $db->prepare("UPDATE products SET status = 'archived' WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $processedCount++;
            } elseif ($action === 'delete') {
                $orderCount = (int)$db->query("SELECT COUNT(*) FROM order_items WHERE product_id = {$id}")->fetchColumn();
                if ($orderCount > 0) {
                    $stmt = $db->prepare("UPDATE products SET status = 'discontinued' WHERE id = :id");
                    $stmt->execute(['id' => $id]);
                    $protectedCount++;
                } else {
                    $db->exec("DELETE FROM product_images WHERE product_id = {$id}");
                    $db->exec("DELETE FROM inventory_movements WHERE product_id = {$id}");
                    $db->exec("DELETE FROM cart_items WHERE product_id = {$id}");
                    $db->exec("DELETE FROM products WHERE id = {$id}");
                    $processedCount++;
                }
            }
        }

        Logger::audit('product_bulk_action', 'products', null, "Bulk action '{$action}' applied to {$processedCount} products (Protected: {$protectedCount})");

        if ($action === 'delete' && $protectedCount > 0) {
            Session::flash('warning', "Bulk delete completed: {$processedCount} products permanently removed, and {$protectedCount} products were safely Discontinued/Archived because they have existing order records.");
        } else {
            Session::flash('success', "Bulk operation '{$action}' successfully executed on {$processedCount} products.");
        }

        $this->redirect(APP_URL . '/admin/products');
    }

    /**
     * Handle local computer image upload
     */
    private function handleImageUpload(Request $request, int $productId): void {
        $file = $request->file('product_image');
        if (!$file || empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return;
        }

        // Validate image mime type & extensions
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedMimes, true)) {
            Session::flash('error', 'Only JPEG, PNG, and WebP images are permitted for products.');
            return;
        }

        // Limit size to 5MB
        if ($file['size'] > 5 * 1024 * 1024) {
            Session::flash('error', 'Product image file cannot exceed 5MB.');
            return;
        }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg'
        };

        $uploadDir = PUBLIC_DIR . '/assets/uploads/products';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = 'prod_' . $productId . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $destination = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $db = Database::getInstance();
            $stmt = $db->prepare("
                INSERT INTO product_images (product_id, image_path, is_primary, sort_order)
                VALUES (:pid, :path, 1, 0)
            ");
            $stmt->execute([
                'pid' => $productId,
                'path' => 'assets/uploads/products/' . $filename,
            ]);
        }
    }
}
