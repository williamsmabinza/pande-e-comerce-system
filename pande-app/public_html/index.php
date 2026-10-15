<?php
/**
 * Pande Computers - Front Controller Entry Point
 * Shared cPanel & Apache Hosting Compatible
 */

declare(strict_types=1);

// Static file bypass for PHP built-in server (dev mode)
if (php_sapi_name() === 'cli-server') {
    $requestedPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    $staticFile = __DIR__ . $requestedPath;
    if ($requestedPath !== '/' && is_file($staticFile)) {
        return false;
    }
}

// Error reporting configuration
error_reporting(E_ALL);
ini_set('display_errors', '0'); // Safe for production; errors logged to storage/logs

// Core application boot
require_once dirname(__DIR__) . '/app/Config/app.php';
require_once dirname(__DIR__) . '/app/Core/Database.php';
require_once dirname(__DIR__) . '/app/Core/Request.php';
require_once dirname(__DIR__) . '/app/Core/Response.php';
require_once dirname(__DIR__) . '/app/Core/Session.php';
require_once dirname(__DIR__) . '/app/Core/CSRF.php';
require_once dirname(__DIR__) . '/app/Core/Auth.php';
require_once dirname(__DIR__) . '/app/Core/Router.php';
require_once dirname(__DIR__) . '/app/Core/View.php';
require_once dirname(__DIR__) . '/app/Core/Validator.php';
require_once dirname(__DIR__) . '/app/Core/Logger.php';
require_once dirname(__DIR__) . '/app/Core/WhatsApp.php';

// Models
require_once dirname(__DIR__) . '/app/Models/BaseModel.php';
require_once dirname(__DIR__) . '/app/Models/Setting.php';
require_once dirname(__DIR__) . '/app/Models/Category.php';
require_once dirname(__DIR__) . '/app/Models/Product.php';
require_once dirname(__DIR__) . '/app/Models/Cart.php';
require_once dirname(__DIR__) . '/app/Models/Order.php';
require_once dirname(__DIR__) . '/app/Models/User.php';
require_once dirname(__DIR__) . '/app/Models/InventoryMovement.php';

// Controllers
require_once dirname(__DIR__) . '/app/Controllers/BaseController.php';
require_once dirname(__DIR__) . '/app/Controllers/HomeController.php';
require_once dirname(__DIR__) . '/app/Controllers/CatalogController.php';
require_once dirname(__DIR__) . '/app/Controllers/CartController.php';
require_once dirname(__DIR__) . '/app/Controllers/CheckoutController.php';
require_once dirname(__DIR__) . '/app/Controllers/AuthController.php';
require_once dirname(__DIR__) . '/app/Controllers/CustomerController.php';
require_once dirname(__DIR__) . '/app/Controllers/PageController.php';

// Admin Controllers
require_once dirname(__DIR__) . '/app/Controllers/Admin/AdminController.php';
require_once dirname(__DIR__) . '/app/Controllers/Admin/ProductController.php';
require_once dirname(__DIR__) . '/app/Controllers/Admin/CategoryController.php';
require_once dirname(__DIR__) . '/app/Controllers/Admin/OrderController.php';
require_once dirname(__DIR__) . '/app/Controllers/Admin/InventoryController.php';
require_once dirname(__DIR__) . '/app/Controllers/Admin/ReportController.php';
require_once dirname(__DIR__) . '/app/Controllers/Admin/SettingsController.php';
require_once dirname(__DIR__) . '/app/Controllers/Admin/StaffController.php';
require_once dirname(__DIR__) . '/app/Controllers/Admin/CustomerManagementController.php';

use App\Core\Request;
use App\Core\Router;
use App\Core\Session;

Session::start();

// ---------------------------------------------------------
// Route Registrations
// ---------------------------------------------------------

// Storefront & Catalog
Router::get('/', [App\Controllers\HomeController::class, 'index']);
Router::get('/shop', [App\Controllers\CatalogController::class, 'index']);
Router::get('/category/{slug}', [App\Controllers\CatalogController::class, 'category']);
Router::get('/product/{slug}', [App\Controllers\CatalogController::class, 'detail']);

// Shopping Cart
Router::get('/cart', [App\Controllers\CartController::class, 'index']);
Router::post('/cart/add', [App\Controllers\CartController::class, 'add']);
Router::post('/cart/sync', [App\Controllers\CartController::class, 'sync']);
Router::post('/cart/update', [App\Controllers\CartController::class, 'update']);
Router::post('/cart/remove', [App\Controllers\CartController::class, 'remove']);
Router::post('/cart/clear', [App\Controllers\CartController::class, 'clear']);

// Checkout & WhatsApp Fulfillment
Router::get('/checkout', [App\Controllers\CheckoutController::class, 'index']);
Router::post('/checkout/process', [App\Controllers\CheckoutController::class, 'process']);
Router::get('/order/confirmation/{number}', [App\Controllers\CheckoutController::class, 'confirmation']);

// Customer Authentication
Router::get('/login', [App\Controllers\AuthController::class, 'showLogin']);
Router::post('/login', [App\Controllers\AuthController::class, 'login']);
Router::get('/register', [App\Controllers\AuthController::class, 'showRegister']);
Router::post('/register', [App\Controllers\AuthController::class, 'register']);
Router::post('/logout', [App\Controllers\AuthController::class, 'logout']);
Router::get('/forgot-password', [App\Controllers\AuthController::class, 'forgotPassword']);
Router::post('/forgot-password', [App\Controllers\AuthController::class, 'sendResetLink']);
Router::get('/reset-password', [App\Controllers\AuthController::class, 'showResetPassword']);
Router::post('/reset-password', [App\Controllers\AuthController::class, 'resetPassword']);

// Customer Account Portal
Router::get('/customer/dashboard', [App\Controllers\CustomerController::class, 'dashboard']);
Router::get('/customer/orders', [App\Controllers\CustomerController::class, 'orders']);
Router::get('/customer/order/{number}', [App\Controllers\CustomerController::class, 'orderDetail']);
Router::get('/customer/profile', [App\Controllers\CustomerController::class, 'profile']);
Router::post('/customer/profile', [App\Controllers\CustomerController::class, 'profile']);
Router::post('/customer/password', [App\Controllers\CustomerController::class, 'changePassword']);
Router::get('/customer/addresses', [App\Controllers\CustomerController::class, 'addresses']);
Router::post('/customer/addresses', [App\Controllers\CustomerController::class, 'addresses']);
Router::post('/customer/address/update/{id}', [App\Controllers\CustomerController::class, 'updateAddress']);
Router::post('/customer/address/default/{id}', [App\Controllers\CustomerController::class, 'setDefaultAddress']);
Router::post('/customer/address/delete/{id}', [App\Controllers\CustomerController::class, 'deleteAddress']);

// Admin Authentication & Access
Router::get('/admin/login', [App\Controllers\AuthController::class, 'showAdminLogin']);
Router::post('/admin/login', [App\Controllers\AuthController::class, 'adminLogin']);

// Admin Dashboard & Operations
Router::get('/admin', [App\Controllers\Admin\AdminController::class, 'index']);
Router::get('/admin/dashboard', [App\Controllers\Admin\AdminController::class, 'index']);
Router::post('/admin/quick-order-status', [App\Controllers\Admin\AdminController::class, 'quickOrderStatus']);
Router::post('/admin/quick-verify-payment', [App\Controllers\Admin\AdminController::class, 'quickVerifyPayment']);
Router::post('/admin/quick-stock-adjust', [App\Controllers\Admin\AdminController::class, 'quickStockAdjust']);
Router::post('/admin/quick-price-update', [App\Controllers\Admin\AdminController::class, 'quickPriceUpdate']);
Router::post('/admin/quick-announcement', [App\Controllers\Admin\AdminController::class, 'quickAnnouncement']);
Router::post('/admin/quick-hero-settings', [App\Controllers\Admin\AdminController::class, 'quickHeroSettings']);
Router::post('/admin/update-hero-content', [App\Controllers\Admin\AdminController::class, 'quickHeroSettings']);
Router::post('/admin/upload-hero-image', [App\Controllers\Admin\AdminController::class, 'uploadHeroImage']);
Router::post('/admin/backup-database', [App\Controllers\Admin\AdminController::class, 'backupDatabase']);
Router::post('/admin/clear-cache', [App\Controllers\Admin\AdminController::class, 'clearCache']);
Router::get('/admin/products', [App\Controllers\Admin\ProductController::class, 'index']);
Router::get('/admin/products/create', [App\Controllers\Admin\ProductController::class, 'create']);
Router::post('/admin/products/store', [App\Controllers\Admin\ProductController::class, 'store']);
Router::get('/admin/products/edit/{id}', [App\Controllers\Admin\ProductController::class, 'edit']);
Router::post('/admin/products/update/{id}', [App\Controllers\Admin\ProductController::class, 'update']);
Router::post('/admin/products/delete/{id}', [App\Controllers\Admin\ProductController::class, 'delete']);
Router::post('/admin/products/status/{id}', [App\Controllers\Admin\ProductController::class, 'updateStatus']);
Router::post('/admin/products/bulk', [App\Controllers\Admin\ProductController::class, 'bulkAction']);

Router::get('/admin/categories', [App\Controllers\Admin\CategoryController::class, 'index']);
Router::post('/admin/categories/store', [App\Controllers\Admin\CategoryController::class, 'store']);
Router::post('/admin/categories/update/{id}', [App\Controllers\Admin\CategoryController::class, 'update']);
Router::post('/admin/categories/delete/{id}', [App\Controllers\Admin\CategoryController::class, 'delete']);

Router::get('/admin/orders', [App\Controllers\Admin\OrderController::class, 'index']);
Router::get('/admin/orders/detail/{id}', [App\Controllers\Admin\OrderController::class, 'detail']);
Router::post('/admin/orders/update-status/{id}', [App\Controllers\Admin\OrderController::class, 'updateStatus']);
Router::post('/admin/orders/verify-payment/{id}', [App\Controllers\Admin\OrderController::class, 'verifyPayment']);
Router::get('/admin/orders/invoice/{id}', [App\Controllers\Admin\OrderController::class, 'invoice']);

Router::get('/admin/inventory', [App\Controllers\Admin\InventoryController::class, 'index']);
Router::post('/admin/inventory/adjust', [App\Controllers\Admin\InventoryController::class, 'adjust']);
Router::post('/admin/inventory/threshold', [App\Controllers\Admin\InventoryController::class, 'updateThreshold']);

Router::get('/admin/reports', [App\Controllers\Admin\ReportController::class, 'index']);
Router::get('/admin/reports/export-orders', [App\Controllers\Admin\ReportController::class, 'exportOrders']);

Router::get('/admin/settings', [App\Controllers\Admin\SettingsController::class, 'index']);
Router::post('/admin/settings/update', [App\Controllers\Admin\SettingsController::class, 'update']);

Router::get('/admin/users', [App\Controllers\Admin\StaffController::class, 'index']);
Router::post('/admin/users/create-staff', [App\Controllers\Admin\StaffController::class, 'createStaff']);
Router::post('/admin/users/toggle/{id}', [App\Controllers\Admin\StaffController::class, 'toggleStatus']);

// Admin Customer Accounts Management
Router::get('/admin/customers', [App\Controllers\Admin\CustomerManagementController::class, 'index']);
Router::get('/admin/customers/detail/{id}', [App\Controllers\Admin\CustomerManagementController::class, 'detail']);
Router::post('/admin/customers/store', [App\Controllers\Admin\CustomerManagementController::class, 'store']);
Router::post('/admin/customers/update/{id}', [App\Controllers\Admin\CustomerManagementController::class, 'update']);
Router::post('/admin/customers/toggle-status/{id}', [App\Controllers\Admin\CustomerManagementController::class, 'toggleStatus']);
Router::post('/admin/customers/delete/{id}', [App\Controllers\Admin\CustomerManagementController::class, 'delete']);

// Static Informational Pages
Router::get('/about', [App\Controllers\PageController::class, 'about']);
Router::get('/contact', [App\Controllers\PageController::class, 'contact']);
Router::get('/terms-and-conditions', [App\Controllers\PageController::class, 'terms']);
Router::get('/privacy-policy', [App\Controllers\PageController::class, 'privacy']);
Router::get('/delivery-and-returns', [App\Controllers\PageController::class, 'delivery']);

// Execute Request
$request = new Request();
Router::dispatch($request);
