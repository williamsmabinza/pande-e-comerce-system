<?php
/**
 * Admin Product Management Controller
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
use App\Models\Product;
use PDO;

class ProductController extends BaseController {
    public function __construct() {
        Auth::requireStaff();
    }

    public function index(Request $request): void {
        $db = Database::getInstance();
        $q = trim((string)$request->input('q', ''));
        $cat = (int)$request->input('category', 0);
        $status = trim((string)$request->input('status', ''));

        $where = ["1=1"];
        $params = [];

        if ($q !== '') {
            $where[] = "(p.name LIKE :q OR p.sku LIKE :q OR p.brand LIKE :q)";
            $params['q'] = "%{$q}%";
        }
        if ($cat > 0) {
            $where[] = "p.category_id = :cat";
            $params['cat'] = $cat;
        }
        if ($status !== '') {
            $where[] = "p.status = :status";
            $params['status'] = $status;
        }

        $whereSql = implode(' AND ', $where);
        $stmt = $db->prepare("
            SELECT p.*, c.name as category_name 
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE {$whereSql}
            ORDER BY p.id DESC
        ");
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        $categories = Category::all();

        $this->render('admin.products.index', [
            'pageTitle' => 'Product Catalog - Admin',
            'products' => $products,
            'categories' => $categories,
            'filters' => ['q' => $q, 'category' => $cat, 'status' => $status],
        ], 'admin');
    }

    public function create(Request $request): void {
        $categories = Category::all();
        $this->render('admin.products.form', [
            'pageTitle' => 'Add New Product - Admin',
            'product' => null,
            'categories' => $categories,
            'action' => APP_URL . '/admin/products/store',
        ], 'admin');
    }

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

        $name = $request->input('name');
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        
        // Ensure unique slug
        $db = Database::getInstance();
        $check = $db->prepare("SELECT COUNT(*) FROM products WHERE slug = :s");
        $check->execute(['s' => $slug]);
        if ($check->fetchColumn() > 0) {
            $slug .= '-' . bin2hex(random_bytes(2));
        }

        try {
            $productId = Product::create([
                'category_id' => $request->input('category_id'),
                'name' => $name,
                'slug' => $slug,
                'sku' => $request->input('sku'),
                'model_number' => $request->input('model_number'),
                'brand' => $request->input('brand'),
                'short_description' => $request->input('short_description'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'compare_at_price' => $request->input('compare_at_price'),
                'cost_price' => $request->input('cost_price'),
                'stock_quantity' => $request->input('stock_quantity'),
                'low_stock_threshold' => $request->input('low_stock_threshold', 5),
                'is_featured' => $request->input('is_featured') ? 1 : 0,
                'is_new_arrival' => $request->input('is_new_arrival') ? 1 : 0,
                'status' => $request->input('status', 'active'),
                'warranty_info' => $request->input('warranty_info', '1 Year Warranty'),
            ]);

            // Handle image upload if present
            $this->handleImageUpload($request, $productId);

            Logger::audit('product_created', 'product', $productId, "Product '{$name}' created with SKU {$request->input('sku')}");
            Session::flash('success', "Product '{$name}' created successfully.");
            $this->redirect(APP_URL . '/admin/products');

        } catch (\Throwable $e) {
            Session::flash('error', 'Error creating product: ' . $e->getMessage());
            $this->redirect(APP_URL . '/admin/products/create');
        }
    }

    public function edit(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        $product = Product::find($id);

        if (!$product) {
            Session::flash('error', 'Product not found.');
            $this->redirect(APP_URL . '/admin/products');
            return;
        }

        $categories = Category::all();
        $images = Product::getImages($id);

        $this->render('admin.products.form', [
            'pageTitle' => "Edit Product: {$product['name']} - Admin",
            'product' => $product,
            'images' => $images,
            'categories' => $categories,
            'action' => APP_URL . "/admin/products/update/{$id}",
        ], 'admin');
    }

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

        $name = $request->input('name');
        $slug = $product['slug']; // keep stable unless needed

        try {
            Product::update($id, [
                'category_id' => $request->input('category_id'),
                'name' => $name,
                'slug' => $slug,
                'sku' => $request->input('sku'),
                'model_number' => $request->input('model_number'),
                'brand' => $request->input('brand'),
                'short_description' => $request->input('short_description'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'compare_at_price' => $request->input('compare_at_price'),
                'cost_price' => $request->input('cost_price'),
                'low_stock_threshold' => $request->input('low_stock_threshold', 5),
                'is_featured' => $request->input('is_featured') ? 1 : 0,
                'is_new_arrival' => $request->input('is_new_arrival') ? 1 : 0,
                'status' => $request->input('status', 'active'),
                'warranty_info' => $request->input('warranty_info', '1 Year Warranty'),
            ]);

            $this->handleImageUpload($request, $id);

            Logger::audit('product_updated', 'product', $id, "Product '{$name}' updated");
            Session::flash('success', "Product '{$name}' updated successfully.");
            $this->redirect(APP_URL . '/admin/products');

        } catch (\Throwable $e) {
            Session::flash('error', 'Error updating product: ' . $e->getMessage());
            $this->redirect(APP_URL . "/admin/products/edit/{$id}");
        }
    }

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
            Session::flash('error', 'Only JPEG, PNG, and WebP images are permitted.');
            return;
        }

        // Limit size to 5MB
        if ($file['size'] > 5 * 1024 * 1024) {
            Session::flash('error', 'Image file cannot exceed 5MB.');
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
