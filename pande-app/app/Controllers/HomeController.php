<?php
/**
 * Home Controller
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;

class HomeController extends BaseController {
    public function index(Request $request): void {
        $featuredProducts = Product::getFeatured(8);
        $topSellingProducts = Product::getTopSelling(8);
        $newArrivals = Product::getNewArrivals(8);
        $categories = Category::getActiveCategories();

        $this->render('home.index', [
            'pageTitle' => 'Pande Computers - Your Trusted Tech Partner in Tanzania',
            'metaDescription' => 'Shop genuine laptops, desktop PCs, monitors, printers, networking equipment and accessories at Pande Computers in Kariakoo, Dar es Salaam. Uncompromised Power. Unbeatable Prices.',
            'featuredProducts' => $featuredProducts,
            'topSellingProducts' => $topSellingProducts,
            'newArrivals' => $newArrivals,
            'allCategories' => $categories,
            'popularCategories' => array_slice($categories, 0, 12),
            'heroBadge' => Setting::get('hero_badge', 'Authorized Tanzanian IT Retailer'),
            'heroTitleLine1' => Setting::get('hero_title_line1', 'YOUR TECH.'),
            'heroTitleLine2' => Setting::get('hero_title_line2', 'YOUR WAY.'),
            'heroSubtitle' => Setting::get('hero_subtitle', 'Shop laptops, desktops, components and IT essentials online. Genuine computing hardware delivered across Dar es Salaam and nationwide.'),
            'heroBtnPrimaryText' => Setting::get('hero_btn_primary_text', 'Shop Now'),
            'heroBtnPrimaryUrl' => Setting::get('hero_btn_primary_url', APP_URL . '/shop'),
            'heroBtnSecondaryText' => Setting::get('hero_btn_secondary_text', 'Explore Categories'),
            'heroBtnSecondaryUrl' => Setting::get('hero_btn_secondary_url', '#browse-categories'),
            'heroShowcaseImage' => Setting::get('hero_showcase_image', 'src/assets/images/regenerated_image_1791995491732.png'),
            'heroBgImage' => Setting::get('hero_bg_image', 'assets/images/hero-tech-bg.svg'),
        ]);
    }
}
