<?php
/**
 * Home Controller
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Models\Category;
use App\Models\Product;

class HomeController extends BaseController {
    public function index(Request $request): void {
        $featuredProducts = Product::getFeatured(8);
        $newArrivals = Product::getNewArrivals(8);
        $categories = Category::getActiveCategories();

        $this->render('home.index', [
            'pageTitle' => 'Pande Computers - Your Trusted Tech Partner in Tanzania',
            'metaDescription' => 'Shop genuine laptops, desktop PCs, monitors, printers, networking equipment and accessories at Pande Computers in Kariakoo, Dar es Salaam. Uncompromised Power. Unbeatable Prices.',
            'featuredProducts' => $featuredProducts,
            'newArrivals' => $newArrivals,
            'popularCategories' => array_slice($categories, 0, 12),
        ]);
    }
}
