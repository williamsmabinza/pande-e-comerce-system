<?php
/**
 * Checkout & WhatsApp Fulfillment Controller
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;

class CheckoutController extends BaseController {
    public function index(Request $request): void {
        $items = Cart::getItems();
        if (empty($items)) {
            Session::flash('warning', 'Your shopping cart is empty. Please add items before checking out.');
            $this->redirect(APP_URL . '/shop');
            return;
        }

        $totals = Cart::getTotals();
        $user = Auth::user();
        $savedAddresses = $user ? User::getAddresses((int)$user['id']) : [];

        // Generate idempotency key for this checkout attempt
        $idempotencyKey = bin2hex(random_bytes(16));

        $this->render('checkout.index', [
            'pageTitle' => 'Checkout - Pande Computers',
            'cartItems' => $items,
            'totals' => $totals,
            'user' => $user,
            'savedAddresses' => $savedAddresses,
            'idempotencyKey' => $idempotencyKey,
        ]);
    }

    public function process(Request $request): void {
        $items = Cart::getItems();
        if (empty($items)) {
            Session::flash('error', 'Your shopping cart is empty.');
            $this->redirect(APP_URL . '/cart');
            return;
        }

        $validator = new Validator($request->all(), [
            'customer_name' => 'required|min:3|max:100',
            'customer_phone' => 'required|min:9|max:20',
            'delivery_method' => 'required|in:delivery,collection',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError() ?? 'Please fill all required checkout fields correctly.');
            $this->redirect(APP_URL . '/checkout');
            return;
        }

        $deliveryMethod = $request->input('delivery_method');
        $deliveryAddress = $request->input('delivery_address');
        $deliveryCity = $request->input('delivery_city', 'Dar es Salaam');

        if ($deliveryMethod === 'delivery' && empty(trim((string)$deliveryAddress))) {
            Session::flash('error', 'Please provide your delivery street address.');
            $this->redirect(APP_URL . '/checkout');
            return;
        }

        try {
            $order = Order::createOrder([
                'customer_name' => $request->input('customer_name'),
                'customer_phone' => $request->input('customer_phone'),
                'customer_email' => $request->input('customer_email'),
                'delivery_method' => $deliveryMethod,
                'delivery_address' => $deliveryAddress,
                'delivery_city' => $deliveryCity,
                'order_notes' => $request->input('order_notes'),
                'idempotency_key' => $request->input('idempotency_key'),
            ], $items);

            // Empty cart after successful order creation
            Cart::clear();

            Session::flash('success', "Order #{$order['order_number']} created successfully! Please click below to send your order on WhatsApp.");
            $this->redirect(APP_URL . '/order/confirmation/' . urlencode($order['order_number']));

        } catch (\Throwable $e) {
            Session::flash('error', 'Error placing order: ' . $e->getMessage());
            $this->redirect(APP_URL . '/checkout');
        }
    }

    public function confirmation(Request $request, array $params): void {
        $orderNumber = $params['number'] ?? '';
        $order = Order::findByNumber($orderNumber);

        if (!$order) {
            Session::flash('error', 'Order not found.');
            $this->redirect(APP_URL . '/');
            return;
        }

        $this->render('checkout.confirmation', [
            'pageTitle' => "Order #{$order['order_number']} Confirmed - Pande Computers",
            'order' => $order,
        ]);
    }
}
