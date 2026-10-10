<?php
/**
 * Admin Order Management Controller
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Logger;
use App\Core\Request;
use App\Core\Session;
use App\Models\Order;

class OrderController extends BaseController {
    public function __construct() {
        Auth::requireStaff();
    }

    public function index(Request $request): void {
        $db = Database::getInstance();
        $q = trim((string)$request->input('q', ''));
        $status = trim((string)$request->input('status', ''));
        $paymentStatus = trim((string)$request->input('payment_status', ''));

        $where = ["1=1"];
        $params = [];

        if ($q !== '') {
            $where[] = "(order_number LIKE :q OR customer_name LIKE :q OR customer_phone LIKE :q)";
            $params['q'] = "%{$q}%";
        }
        if ($status !== '') {
            $where[] = "order_status = :status";
            $params['status'] = $status;
        }
        if ($paymentStatus !== '') {
            $where[] = "payment_status = :pstatus";
            $params['pstatus'] = $paymentStatus;
        }

        $whereSql = implode(' AND ', $where);
        $stmt = $db->prepare("SELECT * FROM orders WHERE {$whereSql} ORDER BY id DESC");
        $stmt->execute($params);
        $orders = $stmt->fetchAll();

        $this->render('admin.orders.index', [
            'pageTitle' => 'Orders Management - Admin',
            'orders' => $orders,
            'filters' => ['q' => $q, 'status' => $status, 'payment_status' => $paymentStatus],
        ], 'admin');
    }

    public function detail(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        $order = Order::find($id);

        if (!$order) {
            Session::flash('error', 'Order not found.');
            $this->redirect(APP_URL . '/admin/orders');
            return;
        }

        $items = Order::getItems($id);
        $history = Order::getStatusHistory($id);

        $this->render('admin.orders.detail', [
            'pageTitle' => "Order #{$order['order_number']} Details - Admin",
            'order' => $order,
            'items' => $items,
            'history' => $history,
        ], 'admin');
    }

    public function updateStatus(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        $status = $request->input('order_status');
        $notes = $request->input('notes');

        $allowedStatuses = ['pending', 'confirmed', 'processing', 'ready_collection', 'shipped', 'completed', 'cancelled'];
        if (!in_array($status, $allowedStatuses, true)) {
            Session::flash('error', 'Invalid order status transition.');
            $this->redirect(APP_URL . "/admin/orders/detail/{$id}");
            return;
        }

        $success = Order::updateStatus($id, $status, $notes);
        if ($success) {
            Logger::audit('order_status_updated', 'order', $id, "Status changed to '{$status}'");
            Session::flash('success', "Order status updated to '{$status}'.");
        } else {
            Session::flash('error', 'Failed to update order status.');
        }

        $this->redirect(APP_URL . "/admin/orders/detail/{$id}");
    }

    public function verifyPayment(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        $method = $request->input('payment_method', 'M-Pesa / WhatsApp');
        $reference = $request->input('payment_reference');

        $success = Order::verifyPayment($id, $method, $reference);
        if ($success) {
            Logger::audit('payment_verified', 'order', $id, "Payment verified via {$method}, Ref: {$reference}");
            Session::flash('success', 'Manual payment successfully verified and confirmed.');
        } else {
            Session::flash('error', 'Failed to verify payment.');
        }

        $this->redirect(APP_URL . "/admin/orders/detail/{$id}");
    }

    public function invoice(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        $order = Order::find($id);

        if (!$order) {
            Session::flash('error', 'Order not found.');
            $this->redirect(APP_URL . '/admin/orders');
            return;
        }

        $items = Order::getItems($id);

        // Render clean print layout
        View::render('admin.orders.invoice', [
            'order' => $order,
            'items' => $items,
        ], null);
    }
}
