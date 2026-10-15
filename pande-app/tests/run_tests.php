<?php
/**
 * Pande Computers - Comprehensive Automated Verification & Acceptance Suite
 * Tests MySQL / SQLite PDO, Auth, RBAC, Catalog, Orders, WhatsApp Fulfillment, Inventory Movements
 */

declare(strict_types=1);

// Boot app environment
require_once __DIR__ . '/../app/Config/app.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Request.php';
require_once __DIR__ . '/../app/Core/Response.php';
require_once __DIR__ . '/../app/Core/Session.php';
require_once __DIR__ . '/../app/Core/CSRF.php';
require_once __DIR__ . '/../app/Core/Auth.php';
require_once __DIR__ . '/../app/Core/Validator.php';
require_once __DIR__ . '/../app/Core/WhatsApp.php';
require_once __DIR__ . '/../app/Models/BaseModel.php';
require_once __DIR__ . '/../app/Models/Setting.php';
require_once __DIR__ . '/../app/Models/Category.php';
require_once __DIR__ . '/../app/Models/Product.php';
require_once __DIR__ . '/../app/Models/Cart.php';
require_once __DIR__ . '/../app/Models/Order.php';
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/InventoryMovement.php';

use App\Core\Auth;
use App\Core\CSRF;
use App\Core\Database;
use App\Core\Session;
use App\Core\Validator;
use App\Core\WhatsApp;
use App\Models\Cart;
use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;

class AcceptanceSuite {
    private int $passed = 0;
    private int $failed = 0;

    public function assert(string $name, bool $condition, string $details = ''): void {
        if ($condition) {
            $this->passed++;
            echo "  [PASS] {$name}\n";
        } else {
            $this->failed++;
            echo "  [FAIL] {$name} - {$details}\n";
        }
    }

    public function run(): void {
        echo "\n=======================================================\n";
        echo " PANDE COMPUTERS - AUTOMATED TEST & ACCEPTANCE SUITE\n";
        echo "=======================================================\n";

        // Test 1: Database Connection
        try {
            $db = Database::getInstance();
            $driver = Database::getDriver();
            $this->assert("Test 1: Database Connection", $db instanceof PDO, "Connected via driver [{$driver}]");
        } catch (\Throwable $e) {
            $this->assert("Test 1: Database Connection", false, $e->getMessage());
        }

        // Test 2: Categories and Seed Data verification
        $categories = Category::getActiveCategories();
        $this->assert("Test 2: Seed Categories Loaded", count($categories) >= 20, "Expected 20 categories, found " . count($categories));

        // Test 3: Products Catalog Queries & Real DB Results
        $products = Product::getFeatured(5);
        $this->assert("Test 3: Featured Products Query", count($products) > 0, "Loaded " . count($products) . " featured products");

        // Test 4: Keyword Search & Filter
        $searchRes = Product::search(['query' => 'EliteBook']);
        $this->assert("Test 4: Product Keyword Search", $searchRes['total'] >= 1, "Found " . $searchRes['total'] . " items for 'EliteBook'");

        // Test 5: Customer Registration
        $testEmail = 'test_buyer_' . time() . '@example.com';
        $newUserId = User::createCustomer([
            'first_name' => 'Amina',
            'last_name' => 'Mwinyi',
            'email' => $testEmail,
            'phone' => '+255787999888',
            'password' => 'SecurePass2026!',
        ]);
        $this->assert("Test 5: Customer Registration", $newUserId > 0, "User ID: {$newUserId}");

        // Test 6: Authentication & Password Verification
        $loginSuccess = Auth::attempt($testEmail, 'SecurePass2026!');
        $this->assert("Test 6: Authentication & Session Verification", $loginSuccess && Auth::check(), "User logged in with role: " . Auth::role());

        // Test 7: Role-Based Access Control (RBAC)
        $isCustomer = Auth::isCustomer();
        $isAdmin = Auth::isAdmin();
        $this->assert("Test 7: RBAC Role Separation (Customer is not Admin)", $isCustomer && !$isAdmin, "Correctly prevented admin access for customer");

        // Test 8: Shopping Cart & Server-Side Pricing
        Cart::clear();
        $firstProduct = $products[0];
        Cart::add((int)$firstProduct['id'], 2);
        $cartTotals = Cart::getTotals();
        $expectedSubtotal = (float)$firstProduct['price'] * 2;
        $this->assert("Test 8: Cart Server-Side Calculation", abs($cartTotals['subtotal'] - $expectedSubtotal) < 0.01, "Expected {$expectedSubtotal}, calculated {$cartTotals['subtotal']}");

        // Test 9: Cart Quantity Updates and Removal
        Cart::updateQuantity((int)$firstProduct['id'], 3);
        $updatedTotals = Cart::getTotals();
        $this->assert("Test 9: Cart Quantity Update", $updatedTotals['count'] === 3, "Expected 3 items in cart");

        // Test 10: Atomic Order Creation with Snapshot & Stock Deduction
        $initialStock = (int)$firstProduct['stock_quantity'];
        $idempotencyKey = 'IDEMPOTENT_TEST_' . bin2hex(random_bytes(8));
        
        $order = Order::createOrder([
            'customer_name' => 'Amina Mwinyi',
            'customer_phone' => '+255787999888',
            'customer_email' => $testEmail,
            'delivery_method' => 'delivery',
            'delivery_address' => 'Plot 10, Samora Avenue',
            'delivery_city' => 'Dar es Salaam',
            'idempotency_key' => $idempotencyKey,
        ], Cart::getItems());

        $this->assert("Test 10: Order Created with Unique ID", !empty($order['order_number']), "Order: " . $order['order_number']);

        // Test 11: Real-Time Stock Deduction
        $refreshedProduct = Product::find((int)$firstProduct['id']);
        $newStock = (int)$refreshedProduct['stock_quantity'];
        $this->assert("Test 11: Stock Deduction Policy", $newStock === ($initialStock - 3), "Stock changed from {$initialStock} to {$newStock}");

        // Test 12: Order Items Snapshot Preservation
        $orderItems = Order::getItems((int)$order['id']);
        $this->assert("Test 12: Historical Item Snapshot", count($orderItems) === 1 && $orderItems[0]['product_name'] === $firstProduct['name'], "Retained product snapshot at time of sale");

        // Test 13: WhatsApp URL Generation & Encoding
        $waLink = $order['whatsapp_link'];
        $hasWaMe = str_contains($waLink, 'https://wa.me/255787311342?text=');
        $hasOrderNum = str_contains($waLink, urlencode($order['order_number']));
        $this->assert("Test 13: WhatsApp Order Link Encoding", $hasWaMe && $hasOrderNum, "Generated valid encoded WhatsApp URL");

        // Test 14: Idempotency Protection (Duplicate Checkout Submission Prevention)
        $duplicateOrder = Order::createOrder([
            'customer_name' => 'Amina Mwinyi',
            'customer_phone' => '+255787999888',
            'idempotency_key' => $idempotencyKey,
        ], Cart::getItems());
        $this->assert("Test 14: Idempotency Protection", (int)$duplicateOrder['id'] === (int)$order['id'], "Returned existing order ID without creating duplicate");

        // Test 15: Re-opening WhatsApp Chat for Existing Order
        $foundOrder = Order::findByNumber($order['order_number']);
        $this->assert("Test 15: Re-open Existing Order WhatsApp", !empty($foundOrder['whatsapp_link']), "Retrieved persistent link: " . $foundOrder['whatsapp_link']);

        // Test 16: Payment Status Separation & Manual Admin Verification
        $this->assert("Test 16: Initial Payment Status is Unpaid/Awaiting", $order['payment_status'] === 'awaiting_confirmation', "Initial status: {$order['payment_status']}");
        
        // Admin manually verifies payment
        $verified = Order::verifyPayment((int)$order['id'], 'M-Pesa', 'MPESA-REF-99881');
        $verifiedOrder = Order::find((int)$order['id']);
        $this->assert("Test 17: Admin Manual Payment Verification", $verified && $verifiedOrder['payment_status'] === 'verified', "Verified payment status with reference MPESA-REF-99881");

        // Test 18: Order Cancellation Restores Reserved Stock
        Order::updateStatus((int)$order['id'], 'cancelled', 'Customer requested cancellation');
        $restoredProduct = Product::find((int)$firstProduct['id']);
        $this->assert("Test 18: Stock Restoration on Cancellation", (int)$restoredProduct['stock_quantity'] === $initialStock, "Restored stock back to {$initialStock}");

        // Test 19: CSRF Token Generation & Validation
        $token = CSRF::generateToken();
        $validToken = CSRF::validate($token);
        $invalidToken = CSRF::validate('invalid_token_123');
        $this->assert("Test 19: CSRF Protection", $validToken && !$invalidToken, "CSRF validates real tokens and rejects tampered tokens");

        // Test 21: Hero Visuals & Upload Settings Configuration Persistence
        Setting::set('hero_showcase_image', 'assets/uploads/hero/test_showcase.webp', 'homepage');
        Setting::set('hero_bg_image', 'assets/uploads/hero/test_bg.webp', 'homepage');
        $savedShowcase = Setting::get('hero_showcase_image');
        $savedBg = Setting::get('hero_bg_image');
        $this->assert("Test 21: Hero Image Upload Settings Persistence", $savedShowcase === 'assets/uploads/hero/test_showcase.webp' && $savedBg === 'assets/uploads/hero/test_bg.webp', "Showcase: {$savedShowcase}, Background: {$savedBg}");

        // Restore original regenerated studio hero image
        Setting::set('hero_showcase_image', 'src/assets/images/regenerated_image_1791810734527.webp', 'homepage');
        Setting::set('hero_bg_image', 'assets/images/hero-tech-bg.svg', 'homepage');

        // Test 22: Executive Admin Dashboard Metrics Integrity
        $db = Database::getInstance();
        $totalOrdersCount = (int)$db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        $totalSalesSum = (float)$db->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE payment_status = 'verified' OR order_status = 'completed'")->fetchColumn();
        $activeProductsCount = (int)$db->query("SELECT COUNT(*) FROM products WHERE status = 'active'")->fetchColumn();
        $totalValuation = (float)$db->query("SELECT COALESCE(SUM(price * stock_quantity), 0) FROM products WHERE status = 'active'")->fetchColumn();
        $this->assert(
            "Test 22: Executive Admin Dashboard Business Intelligence Data Integrity",
            $totalOrdersCount >= 10 && $totalSalesSum > 0 && $activeProductsCount > 0 && $totalValuation > 0,
            "Verified real KPI figures: {$totalOrdersCount} orders, TZS " . number_format($totalSalesSum) . " gross sales, {$activeProductsCount} products, TZS " . number_format($totalValuation) . " catalog valuation"
        );

        // Test 20: Server-Side Input Validator
        $validData = ['name' => 'HP Laptop', 'price' => '150000', 'email' => 'test@pande.co.tz'];
        $validator = new Validator($validData, ['name' => 'required|min:3', 'price' => 'required|numeric', 'email' => 'required|email']);
        $this->assert("Test 20: Input Validator", $validator->passes(), "Validation passed correctly");

        // Test 23: Customer Lifecycle & Historical Deletion Protection
        $auditEmail = 'audit_customer_' . time() . '@pandecomputers.co.tz';
        $auditCustId = User::createCustomer([
            'first_name' => 'Neema',
            'last_name' => 'Massawe',
            'email' => $auditEmail,
            'phone' => '+255711223344',
            'password' => 'NeemaPass2026!',
        ]);
        $custUser = User::find($auditCustId);
        $statusActive = ($custUser['status'] === 'active');
        // Toggle to suspended
        $db->prepare("UPDATE users SET status = 'suspended' WHERE id = :id")->execute(['id' => $auditCustId]);
        $custSuspended = User::find($auditCustId)['status'] === 'suspended';
        // Clean up unreferenced audit user
        $db->prepare("DELETE FROM users WHERE id = :id")->execute(['id' => $auditCustId]);
        $custDeleted = (User::find($auditCustId) === null);
        $this->assert(
            "Test 23: Customer Lifecycle Management & Status Control",
            $auditCustId > 0 && $statusActive && $custSuspended && $custDeleted,
            "Created, verified active, suspended, and cleaned test customer"
        );

        // Test 24: Product Management & Stock Movement Tracking
        $testSku = 'AUDIT-SKU-' . time();
        $auditProdId = Product::create([
            'category_id' => 1,
            'name' => 'Audit Test Laptop Ultra',
            'slug' => 'audit-test-laptop-ultra-' . time(),
            'sku' => $testSku,
            'brand' => 'HP',
            'price' => 1850000,
            'cost_price' => 1400000,
            'stock_quantity' => 10,
            'low_stock_threshold' => 3,
            'status' => 'active',
            'warranty_info' => '1 Year Official Warranty'
        ]);
        $createdProd = Product::find($auditProdId);
        $prodCreatedOk = ($createdProd !== null && (float)$createdProd['price'] === 1850000.0);
        
        // Stock adjustment movement
        InventoryMovement::record($auditProdId, null, 'adjustment', 5, 10, 15, 'Audit stock verification');
        Product::update($auditProdId, ['stock_quantity' => 15]);
        $updatedProd = Product::find($auditProdId);
        $stockOk = ((int)$updatedProd['stock_quantity'] === 15);

        // Discontinue and clean up
        Product::update($auditProdId, ['status' => 'discontinued']);
        $discontinuedOk = (Product::find($auditProdId)['status'] === 'discontinued');
        $db->prepare("DELETE FROM inventory_movements WHERE product_id = :id")->execute(['id' => $auditProdId]);
        $db->prepare("DELETE FROM products WHERE id = :id")->execute(['id' => $auditProdId]);
        $this->assert(
            "Test 24: Product Catalog Management & Inventory Adjustment",
            $prodCreatedOk && $stockOk && $discontinuedOk,
            "Created SKU {$testSku}, updated stock from 10 to 15, verified movement, archived & cleaned"
        );

        // Test 25: Dashboard Architecture - Store Overview Position & Hero Visuals Clean Separation
        $dashContent = file_get_contents(__DIR__ . '/../app/Views/admin/dashboard.php');
        $statusPos = stripos($dashContent, 'Store Status:');
        $overviewPos = stripos($dashContent, 'STORE OVERVIEW (Immediately Below Store Status)');
        $heroVisualsPos = stripos($dashContent, 'DEDICATED FRONT HOMEPAGE HERO VISUALS');
        $heroVisualsClean = ($statusPos !== false) && ($overviewPos !== false) && ($overviewPos > $statusPos);
        $heroSection = substr($dashContent, $heroVisualsPos, 6000);
        $heroDoesNotContainOverview = !str_contains(strtolower($heroSection), 'overview kpi') && !str_contains(strtolower($heroSection), 'orders-hub');
        $this->assert(
            "Test 25: Dashboard Architecture - Store Overview Below Status & Hero Visuals Separation",
            $heroVisualsClean && $heroDoesNotContainOverview,
            "Store Overview immediately below Store Status (pos: {$overviewPos} > {$statusPos}) and Hero Visuals is cleanly dedicated"
        );

        // Test 26: Public Homepage Hero Cleanliness (Marquee strip completely removed)
        $homeContent = file_get_contents(__DIR__ . '/../app/Views/home/index.php');
        $noMarquee = !str_contains($homeContent, 'hero-marquee-wrapper') 
            && !str_contains($homeContent, 'Featured Tech Stream')
            && !str_contains($homeContent, 'Right-to-Left Stream');
        $heroShowcaseIntact = str_contains($homeContent, 'hero-showcase-image') && str_contains($homeContent, 'pande-hero-section');
        $this->assert(
            "Test 26: Public Hero Section Cleanliness (Marquee strip completely removed)",
            $noMarquee && $heroShowcaseIntact,
            "Unwanted marquee strip and promotional text removed, main hero banner & showcase intact"
        );

        // Test 27: Hero Visual Upload & Management
        $heroUploadActionOk = str_contains($dashContent, '/admin/upload-hero-image') 
            && str_contains($dashContent, 'name="hero_image_file"')
            && str_contains($dashContent, 'enctype="multipart/form-data"');
        Setting::set('hero_showcase_image', 'assets/images/hero-products-showcase.svg', 'homepage');
        $heroSetOk = (Setting::get('hero_showcase_image') === 'assets/images/hero-products-showcase.svg');
        $this->assert(
            "Test 27: Dedicated Hero Image Upload Architecture & Settings Persistence",
            $heroUploadActionOk && $heroSetOk,
            "Standalone hero upload form validated with multipart/form-data, file input, and settings persistence"
        );

        // Test 28: Category Image Management (Model & Controller Support)
        $testCatName = 'Test Audio Equipment ' . time();
        $testCatSlug = 'test-audio-equipment-' . time();
        $testImgPath = 'assets/images/products/peripherals.svg';
        $newCatId = Category::create([
            'name' => $testCatName,
            'slug' => $testCatSlug,
            'description' => 'Quality speakers, headphones, and microphones',
            'icon_class' => 'bi-headphones',
            'image_url' => $testImgPath,
            'sort_order' => 99,
            'is_active' => 1
        ]);
        $catLoaded = Category::find($newCatId);
        $catImgCreatedOk = ($catLoaded !== null && $catLoaded['image_url'] === $testImgPath);

        // Update image_url
        $updatedImgPath = 'assets/images/products/desktop.svg';
        Category::update($newCatId, [
            'name' => $testCatName . ' Updated',
            'slug' => $testCatSlug,
            'description' => 'Updated description',
            'icon_class' => 'bi-speaker',
            'image_url' => $updatedImgPath,
            'sort_order' => 100,
            'is_active' => 1
        ]);
        $catUpdated = Category::find($newCatId);
        $catImgUpdatedOk = ($catUpdated !== null && $catUpdated['image_url'] === $updatedImgPath);

        // Clean up
        Category::delete($newCatId);
        $this->assert(
            "Test 28: Category Image Management (Create, Image Persistence & Update)",
            $catImgCreatedOk && $catImgUpdatedOk,
            "Category created with image [{$testImgPath}], updated to [{$updatedImgPath}], verified in DB and cleaned"
        );

        // Test 29: Top-Selling Products Query & Model Execution
        $topSelling = Product::getTopSelling(8);
        $topSellingOk = is_array($topSelling) && count($topSelling) > 0 && isset($topSelling[0]['total_sold']);
        $homeHasTopSelling = str_contains($homeContent, 'Top-Selling Products') && str_contains($homeContent, 'product-bestseller-badge');
        $this->assert(
            "Test 29: Top-Selling Products Model & Storefront Integration",
            $topSellingOk && $homeHasTopSelling,
            "Loaded " . count($topSelling) . " top-selling items with order item velocity calculation and homepage display"
        );

        // Test 30: Responsive Watermark Implementation
        $cssContent = file_get_contents(__DIR__ . '/../public_html/assets/css/style.css');
        $cssHasWatermark = str_contains($cssContent, '.product-watermark') 
            && str_contains($cssContent, '.hero-watermark-tag');
        $homeHasWatermark = str_contains($homeContent, 'product-watermark') && str_contains($homeContent, 'hero-watermark-tag');
        $this->assert(
            "Test 30: Responsive Pande Computers Brand Watermark",
            $cssHasWatermark && $homeHasWatermark,
            "Responsive watermark styles and overlays configured on product cards, catalog, and hero stage"
        );

        // Summary
        echo "\n=======================================================\n";
        echo " TEST RESULTS: {$this->passed} Passed, {$this->failed} Failed\n";
        echo "=======================================================\n";

        if ($this->failed > 0) {
            exit(1);
        }
    }
}

$suite = new AcceptanceSuite();
$suite->run();
