<?php
/**
 * Base Controller
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\CSRF;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Setting;

abstract class BaseController {
    protected function render(string $view, array $data = [], ?string $layout = 'main'): void {
        $cartTotals = Cart::getTotals();
        $categories = Category::getActiveCategories();

        $defaultData = [
            'currentUser' => Auth::user(),
            'isAuthenticated' => Auth::check(),
            'isAdmin' => Auth::isAdmin(),
            'isStaff' => Auth::isStaff(),
            'cartTotals' => $cartTotals,
            'headerCategories' => array_slice($categories, 0, 8),
            'allCategories' => $categories,
            'storePhone' => Setting::get('store_phone', STORE_PHONE),
            'storeWhatsApp' => Setting::get('whatsapp_number', STORE_WHATSAPP),
            'storeLocation' => Setting::get('store_address', STORE_LOCATION),
            'storeEmail' => Setting::get('store_email', STORE_EMAIL),
            'csrfInput' => CSRF::input(),
            'csrfToken' => CSRF::getToken(),
        ];

        View::render($view, array_merge($defaultData, $data), $layout);
    }

    protected function json(mixed $data, int $code = 200): void {
        Response::json($data, $code);
    }

    protected function redirect(string $url): void {
        Response::redirect($url);
    }
}
