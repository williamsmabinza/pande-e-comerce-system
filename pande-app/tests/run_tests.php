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

        // Test 20: Server-Side Input Validator
        $validData = ['name' => 'HP Laptop', 'price' => '150000', 'email' => 'test@pande.co.tz'];
        $validator = new Validator($validData, ['name' => 'required|min:3', 'price' => 'required|numeric', 'email' => 'required|email']);
        $this->assert("Test 20: Input Validator", $validator->passes(), "Validation passed correctly");

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
