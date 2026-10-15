<?php
/**
 * Admin Category Management Controller
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

class CategoryController extends BaseController {
    public function __construct() {
        Auth::requireStaff();
    }

    public function index(Request $request): void {
        $categories = Category::all();
        $this->render('admin.categories.index', [
            'pageTitle' => 'Category Management - Admin',
            'categories' => $categories,
        ], 'admin');
    }

    private function handleCategoryImageUpload(Request $request): ?string {
        $file = $request->file('category_image_file');
        if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
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
            ];
            $ext = $allowedMimes[$mimeType] ?? strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'], true)) {
                $uploadDir = PUBLIC_DIR . '/assets/uploads/categories';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0755, true);
                }
                $filename = 'cat_' . date('Ymd_His') . '_' . bin2hex(random_bytes(3)) . '.' . ($ext === 'jpeg' ? 'jpg' : $ext);
                $dest = $uploadDir . '/' . $filename;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    return 'assets/uploads/categories/' . $filename;
                }
            }
        }

        $url = trim((string)$request->input('image_url', ''));
        return $url !== '' ? $url : null;
    }

    public function store(Request $request): void {
        $validator = new Validator($request->all(), [
            'name' => 'required|min:2|max:100',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/admin/categories');
            return;
        }

        $name = $request->input('name');
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

        $db = Database::getInstance();
        $check = $db->prepare("SELECT COUNT(*) FROM categories WHERE slug = :s");
        $check->execute(['s' => $slug]);
        if ($check->fetchColumn() > 0) {
            $slug .= '-' . bin2hex(random_bytes(2));
        }

        $imageUrl = $this->handleCategoryImageUpload($request);

        try {
            $catId = Category::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $request->input('description'),
                'icon_class' => $request->input('icon_class', 'bi-cpu'),
                'image_url' => $imageUrl,
                'sort_order' => (int)$request->input('sort_order', 0),
                'is_active' => $request->input('is_active') ? 1 : 0,
            ]);

            Logger::audit('category_created', 'category', $catId, "Category '{$name}' created with custom visual image");
            Session::flash('success', "Category '{$name}' created successfully with visual media.");
        } catch (\Throwable $e) {
            Session::flash('error', 'Error creating category: ' . $e->getMessage());
        }

        $this->redirect(APP_URL . '/admin/categories');
    }

    public function update(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        $category = Category::find($id);
        if (!$category) {
            Session::flash('error', 'Category not found.');
            $this->redirect(APP_URL . '/admin/categories');
            return;
        }

        $name = trim((string)$request->input('name', $category['name']));
        $slug = trim((string)$request->input('slug', ''));
        if ($slug === '') {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        }

        // Image handling: new upload, new URL, or retain existing
        $newUploadedImage = $this->handleCategoryImageUpload($request);
        $imageUrl = $newUploadedImage ?? $category['image_url'];

        // If clear image was requested
        if ($request->input('remove_image') === '1') {
            $imageUrl = null;
        }

        try {
            Category::update($id, [
                'name' => $name,
                'slug' => $slug,
                'description' => $request->input('description'),
                'icon_class' => $request->input('icon_class', 'bi-cpu'),
                'image_url' => $imageUrl,
                'sort_order' => (int)$request->input('sort_order', 0),
                'is_active' => $request->input('is_active') ? 1 : 0,
            ]);

            Logger::audit('category_updated', 'category', $id, "Category '{$name}' updated with image: " . ($imageUrl ?? 'none'));
            Session::flash('success', "Category '{$name}' updated successfully.");
        } catch (\Throwable $e) {
            Session::flash('error', 'Error updating category: ' . $e->getMessage());
        }

        $this->redirect(APP_URL . '/admin/categories');
    }

    public function delete(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);

        try {
            Category::delete($id);
            Logger::audit('category_deleted', 'category', $id, "Category deleted");
            Session::flash('success', 'Category deleted successfully.');
        } catch (\Throwable $e) {
            Session::flash('error', $e->getMessage());
        }

        $this->redirect(APP_URL . '/admin/categories');
    }
}
