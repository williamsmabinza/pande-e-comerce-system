<?php
/**
 * Catalog & Product Details Controller
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Models\Category;
use App\Models\Product;

class CatalogController extends BaseController {
    public function index(Request $request): void {
        $page = max(1, (int)$request->input('page', 1));
        $filters = [
            'query' => $request->input('q'),
            'category_id' => $request->input('category'),
            'brand' => $request->input('brand'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'in_stock' => $request->input('in_stock'),
            'sort' => $request->input('sort', 'newest'),
        ];

        $results = Product::search($filters, $page, 12);
        $selectedCategory = !empty($filters['category_id']) ? Category::find((int)$filters['category_id']) : null;

        $title = 'Shop Genuine Technology & Electronics - Pande Computers';
        if ($selectedCategory) {
            $title = $selectedCategory['name'] . ' - Pande Computers';
        } elseif (!empty($filters['query'])) {
            $title = 'Search results for "' . htmlspecialchars($filters['query']) . '" - Pande Computers';
        }

        $this->render('catalog.index', [
            'pageTitle' => $title,
            'products' => $results['items'],
            'total' => $results['total'],
            'currentPage' => $results['page'],
            'totalPages' => $results['total_pages'],
            'filters' => $filters,
            'selectedCategory' => $selectedCategory,
        ]);
    }

    public function category(Request $request, array $params): void {
        $slug = $params['slug'] ?? '';
        $category = Category::findBySlug($slug);

        if (!$category) {
            $this->redirect(APP_URL . '/shop');
            return;
        }

        $page = max(1, (int)$request->input('page', 1));
        $filters = [
            'category_id' => $category['id'],
            'query' => $request->input('q'),
            'brand' => $request->input('brand'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'in_stock' => $request->input('in_stock'),
            'sort' => $request->input('sort', 'newest'),
        ];

        $results = Product::search($filters, $page, 12);

        $this->render('catalog.index', [
            'pageTitle' => $category['name'] . ' - Pande Computers',
            'metaDescription' => $category['description'] ?? "Buy genuine {$category['name']} at unbeatable prices at Pande Computers in Kariakoo, Dar es Salaam.",
            'products' => $results['items'],
            'total' => $results['total'],
            'currentPage' => $results['page'],
            'totalPages' => $results['total_pages'],
            'filters' => $filters,
            'selectedCategory' => $category,
        ]);
    }

    public function detail(Request $request, array $params): void {
        $slug = $params['slug'] ?? '';
        $product = Product::findBySlug($slug);

        if (!$product) {
            $this->redirect(APP_URL . '/shop');
            return;
        }

        $related = Product::getRelated((int)$product['category_id'], (int)$product['id'], 4);

        $this->render('catalog.detail', [
            'pageTitle' => $product['name'] . ' - Pande Computers',
            'metaDescription' => function_exists('mb_substr') ? mb_substr(strip_tags($product['short_description']), 0, 155) : substr(strip_tags($product['short_description']), 0, 155),
            'product' => $product,
            'relatedProducts' => $related,
        ]);
    }
}
