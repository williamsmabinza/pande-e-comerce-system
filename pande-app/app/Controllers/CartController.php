<?php
/**
 * Cart Controller
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Models\Cart;

class CartController extends BaseController {
    public function index(Request $request): void {
        $items = Cart::getItems();
        $totals = Cart::getTotals();

        $this->render('cart.index', [
            'pageTitle' => 'Shopping Cart - Pande Computers',
            'cartItems' => $items,
            'totals' => $totals,
            'sessionId' => Session::getId(),
        ]);
    }

    public function sync(Request $request): void {
        $rawItems = $request->input('items');
        if (is_string($rawItems)) {
            $rawItems = json_decode($rawItems, true) ?: [];
        }
        if (is_array($rawItems)) {
            foreach ($rawItems as $item) {
                $productId = (int)($item['product_id'] ?? $item['id'] ?? 0);
                $qty = max(1, (int)($item['quantity'] ?? $item['qty'] ?? 1));
                if ($productId > 0) {
                    Cart::add($productId, $qty);
                }
            }
        }

        $totals = Cart::getTotals();
        $this->json([
            'success' => true,
            'count' => $totals['count'],
            'subtotal' => formatTZS($totals['subtotal']),
            'sessionId' => Session::getId(),
            'message' => 'Cart synchronized successfully.'
        ]);
    }

    public function add(Request $request): void {
        $productId = (int)$request->input('product_id');
        $quantity = max(1, (int)$request->input('quantity', 1));
        $product = \App\Models\Product::find($productId);

        $added = Cart::add($productId, $quantity);

        if ($request->isAjax()) {
            if ($added) {
                $totals = Cart::getTotals();
                $images = \App\Models\Product::getImages($productId);
                $imgPath = !empty($images) ? $images[0]['image_path'] : 'assets/images/products/laptop.svg';

                $this->json([
                    'success' => true,
                    'count' => $totals['count'],
                    'subtotal' => formatTZS($totals['subtotal']),
                    'product_name' => $product['name'] ?? 'Electronics Item',
                    'product_price' => formatTZS($product['price'] ?? 0),
                    'product_image' => asset($imgPath),
                    'sessionId' => Session::getId(),
                    'message' => 'Item added to shopping cart!'
                ]);
            } else {
                $this->json(['success' => false, 'message' => 'Product is currently out of stock or unavailable.'], 400);
            }
            return;
        }

        if ($added) {
            Session::flash('success', 'Product added to your cart.');
        } else {
            Session::flash('error', 'Sorry, this product is currently out of stock.');
        }

        $this->redirect($this->cartRedirectUrl());
    }

    public function update(Request $request): void {
        $productId = (int)$request->input('product_id');
        $quantity = (int)$request->input('quantity');

        Cart::updateQuantity($productId, $quantity);
        Session::flash('success', 'Cart updated successfully.');
        $this->redirect($this->cartRedirectUrl());
    }

    public function remove(Request $request): void {
        $productId = (int)$request->input('product_id');
        Cart::remove($productId);
        Session::flash('success', 'Item removed from your cart.');
        $this->redirect($this->cartRedirectUrl());
    }

    public function clear(Request $request): void {
        Cart::clear();
        Session::flash('info', 'Your cart has been cleared.');
        $this->redirect($this->cartRedirectUrl());
    }

    private function cartRedirectUrl(): string {
        $sid = Session::getId();
        $url = APP_URL . '/cart';
        if (!empty($sid)) {
            $url .= '?sid=' . urlencode($sid);
        }
        return $url;
    }
}
