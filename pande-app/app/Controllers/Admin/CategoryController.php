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

        try {
            $catId = Category::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $request->input('description'),
                'icon_class' => $request->input('icon_class', 'bi-cpu'),
                'sort_order' => (int)$request->input('sort_order', 0),
                'is_active' => $request->input('is_active') ? 1 : 0,
            ]);

            Logger::audit('category_created', 'category', $catId, "Category '{$name}' created");
            Session::flash('success', "Category '{$name}' created successfully.");
        } catch (\Throwable $e) {
            Session::flash('error', 'Error creating category: ' . $e->getMessage());
        }

        $this->redirect(APP_URL . '/admin/categories');
    }

    public function update(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        $name = $request->input('name');
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

        try {
            Category::update($id, [
                'name' => $name,
                'slug' => $slug,
                'description' => $request->input('description'),
                'icon_class' => $request->input('icon_class', 'bi-cpu'),
                'sort_order' => (int)$request->input('sort_order', 0),
                'is_active' => $request->input('is_active') ? 1 : 0,
            ]);

            Logger::audit('category_updated', 'category', $id, "Category '{$name}' updated");
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
