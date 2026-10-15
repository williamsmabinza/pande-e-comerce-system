<?php
/**
 * Admin Customer Accounts & CRM Controller
 */
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Logger;
use App\Core\Request;
use App\Core\Session;
use App\Core\Response;
use App\Core\Validator;
use PDO;

class CustomerManagementController extends BaseController {

    public function __construct() {
        Auth::requireStaff();
    }

    /**
     * Customer directory listing with real spend metrics and order counts
     */
    public function index(Request $request): void {
        $db = Database::getInstance();

        $q = trim((string)$request->input('q', ''));
        $status = $request->input('status', 'all');
        $sort = $request->input('sort', 'newest');

        $where = ["u.role = 'customer'"];
        $params = [];

        if ($q !== '') {
            $where[] = "(u.first_name LIKE :q OR u.last_name LIKE :q OR u.email LIKE :q OR u.phone LIKE :q)";
            $params['q'] = "%{$q}%";
        }

        if ($status !== 'all' && $status !== '') {
            if ($status === 'suspended') {
                $where[] = "u.status IN ('suspended', 'inactive')";
            } else {
                $where[] = "u.status = :status";
                $params['status'] = $status;
            }
        }

        $orderBy = match ($sort) {
            'orders_desc' => 'order_count DESC, u.id DESC',
            'spent_desc' => 'total_spent DESC, u.id DESC',
            'name_asc' => 'u.first_name ASC, u.last_name ASC',
            default => 'u.id DESC',
        };

        $whereSql = implode(' AND ', $where);

        $stmt = $db->prepare("
            SELECT u.id, u.role, u.first_name, u.last_name, u.email, u.phone, u.status, u.created_at, u.avatar,
                   (SELECT COUNT(*) FROM orders o WHERE o.user_id = u.id OR LOWER(o.customer_email) = LOWER(u.email)) as order_count,
                   (SELECT COALESCE(SUM(total_amount), 0) FROM orders o 
                    WHERE (o.user_id = u.id OR LOWER(o.customer_email) = LOWER(u.email)) 
                      AND (o.payment_status = 'verified' OR o.order_status = 'completed')) as total_spent,
                   (SELECT MAX(created_at) FROM orders o WHERE o.user_id = u.id OR LOWER(o.customer_email) = LOWER(u.email)) as last_order_date
            FROM users u
            WHERE {$whereSql}
            ORDER BY {$orderBy}
        ");
        $stmt->execute($params);
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Real customer metrics
        $totalCustomers = (int)$db->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
        $activeCustomers = (int)$db->query("SELECT COUNT(*) FROM users WHERE role = 'customer' AND status = 'active'")->fetchColumn();
        $suspendedCustomers = (int)$db->query("SELECT COUNT(*) FROM users WHERE role = 'customer' AND status IN ('suspended', 'inactive')")->fetchColumn();
        $totalCustomerSpend = (float)$db->query("
            SELECT COALESCE(SUM(total_amount), 0) 
            FROM orders 
            WHERE payment_status = 'verified' OR order_status = 'completed'
        ")->fetchColumn();

        $this->render('admin.customers.index', [
            'pageTitle' => 'Customer Accounts Management - Pande Computers',
            'customers' => $customers,
            'filters' => [
                'q' => $q,
                'status' => $status,
                'sort' => $sort,
            ],
            'kpis' => [
                'totalCustomers' => $totalCustomers,
                'activeCustomers' => $activeCustomers,
                'suspendedCustomers' => $suspendedCustomers,
                'totalCustomerSpend' => $totalCustomerSpend,
            ],
        ], 'admin');
    }

    /**
     * Customer details endpoint (supports AJAX modal or direct page view)
     */
    public function detail(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT id, role, first_name, last_name, email, phone, status, created_at, avatar FROM users WHERE id = :id AND role = 'customer'");
        $stmt->execute(['id' => $id]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$customer) {
            if ($request->isAjax()) {
                Response::json(['error' => 'Customer account not found.'], 404);
                return;
            }
            Session::flash('error', 'Customer account not found.');
            $this->redirect(APP_URL . '/admin/customers');
            return;
        }

        // Customer addresses
        $addrStmt = $db->prepare("SELECT * FROM user_addresses WHERE user_id = :id ORDER BY is_default DESC, id DESC");
        $addrStmt->execute(['id' => $id]);
        $addresses = $addrStmt->fetchAll(PDO::FETCH_ASSOC);

        // Customer order history
        $orderStmt = $db->prepare("
            SELECT id, order_number, total_amount, payment_status, order_status, created_at,
                   (SELECT COUNT(*) FROM order_items WHERE order_id = orders.id) as items_count
            FROM orders
            WHERE user_id = :id OR LOWER(customer_email) = LOWER(:email)
            ORDER BY id DESC
        ");
        $orderStmt->execute(['id' => $id, 'email' => $customer['email']]);
        $orders = $orderStmt->fetchAll(PDO::FETCH_ASSOC);

        if ($request->isAjax()) {
            Response::json([
                'customer' => $customer,
                'addresses' => $addresses,
                'orders' => $orders,
            ]);
            return;
        }

        $this->render('admin.customers.detail', [
            'pageTitle' => "Customer: {$customer['first_name']} {$customer['last_name']} - Admin",
            'customer' => $customer,
            'addresses' => $addresses,
            'orders' => $orders,
        ], 'admin');
    }

    /**
     * Create a new customer account securely
     */
    public function store(Request $request): void {
        $validator = new Validator($request->all(), [
            'first_name' => 'required|min:2|max:50',
            'last_name' => 'required|min:2|max:50',
            'email' => 'required|email',
            'phone' => 'required|min:9|max:20',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/admin/customers');
            return;
        }

        $db = Database::getInstance();
        $email = strtolower(trim((string)$request->input('email')));
        $phone = trim((string)$request->input('phone'));

        // Check duplicate email
        $chkEmail = $db->prepare("SELECT COUNT(*) FROM users WHERE LOWER(email) = :e");
        $chkEmail->execute(['e' => $email]);
        if ($chkEmail->fetchColumn() > 0) {
            Session::flash('error', "A user account with email '{$email}' already exists.");
            $this->redirect(APP_URL . '/admin/customers');
            return;
        }

        // Check duplicate phone
        $chkPhone = $db->prepare("SELECT COUNT(*) FROM users WHERE phone = :p");
        $chkPhone->execute(['p' => $phone]);
        if ($chkPhone->fetchColumn() > 0) {
            Session::flash('error', "A user account with phone number '{$phone}' already exists.");
            $this->redirect(APP_URL . '/admin/customers');
            return;
        }

        $rawPassword = (string)$request->input('password', '');
        $autoGenerated = false;
        if (trim($rawPassword) === '') {
            $rawPassword = 'Pande' . bin2hex(random_bytes(3)) . '!';
            $autoGenerated = true;
        } elseif (strlen($rawPassword) < 6) {
            Session::flash('error', 'Customer password must be at least 6 characters.');
            $this->redirect(APP_URL . '/admin/customers');
            return;
        }

        $status = trim((string)$request->input('status', 'active'));
        if (!in_array($status, ['active', 'suspended'], true)) {
            $status = 'active';
        }

        try {
            $stmt = $db->prepare("
                INSERT INTO users (role, first_name, last_name, email, phone, password_hash, status, created_at)
                VALUES ('customer', :first_name, :last_name, :email, :phone, :hash, :status, CURRENT_TIMESTAMP)
            ");
            $stmt->execute([
                'first_name' => trim((string)$request->input('first_name')),
                'last_name' => trim((string)$request->input('last_name')),
                'email' => $email,
                'phone' => $phone,
                'hash' => password_hash($rawPassword, PASSWORD_BCRYPT),
                'status' => $status,
            ]);

            $newId = (int)$db->lastInsertId();

            Logger::audit(
                'customer_created',
                'user',
                $newId,
                "New customer account created for {$email} ({$request->input('first_name')} {$request->input('last_name')})"
            );

            $msg = "Customer account for '{$request->input('first_name')} {$request->input('last_name')}' created successfully.";
            if ($autoGenerated) {
                $msg .= " Temporary password assigned: {$rawPassword}";
            }

            Session::flash('success', $msg);
        } catch (\Throwable $e) {
            Session::flash('error', 'Failed to create customer: ' . $e->getMessage());
        }

        $this->redirect(APP_URL . '/admin/customers');
    }

    /**
     * Update customer details
     */
    public function update(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id AND role = 'customer'");
        $stmt->execute(['id' => $id]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$customer) {
            Session::flash('error', 'Customer account not found.');
            $this->redirect(APP_URL . '/admin/customers');
            return;
        }

        $validator = new Validator($request->all(), [
            'first_name' => 'required|min:2|max:50',
            'last_name' => 'required|min:2|max:50',
            'email' => 'required|email',
            'phone' => 'required|min:9|max:20',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/admin/customers');
            return;
        }

        $email = strtolower(trim((string)$request->input('email')));
        $phone = trim((string)$request->input('phone'));

        // Check duplicate email excluding this customer
        $chkEmail = $db->prepare("SELECT COUNT(*) FROM users WHERE LOWER(email) = :e AND id != :id");
        $chkEmail->execute(['e' => $email, 'id' => $id]);
        if ($chkEmail->fetchColumn() > 0) {
            Session::flash('error', "Another account is already registered with email '{$email}'.");
            $this->redirect(APP_URL . '/admin/customers');
            return;
        }

        $status = trim((string)$request->input('status', $customer['status']));
        if (!in_array($status, ['active', 'suspended'], true)) {
            $status = $customer['status'];
        }

        $password = (string)$request->input('password', '');

        try {
            if (trim($password) !== '') {
                if (strlen($password) < 6) {
                    Session::flash('error', 'Password must be at least 6 characters.');
                    $this->redirect(APP_URL . '/admin/customers');
                    return;
                }
                $updStmt = $db->prepare("
                    UPDATE users
                    SET first_name = :fn, last_name = :ln, email = :em, phone = :ph, status = :st, password_hash = :pw, updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id
                ");
                $updStmt->execute([
                    'fn' => trim((string)$request->input('first_name')),
                    'ln' => trim((string)$request->input('last_name')),
                    'em' => $email,
                    'ph' => $phone,
                    'st' => $status,
                    'pw' => password_hash($password, PASSWORD_BCRYPT),
                    'id' => $id,
                ]);
            } else {
                $updStmt = $db->prepare("
                    UPDATE users
                    SET first_name = :fn, last_name = :ln, email = :em, phone = :ph, status = :st, updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id
                ");
                $updStmt->execute([
                    'fn' => trim((string)$request->input('first_name')),
                    'ln' => trim((string)$request->input('last_name')),
                    'em' => $email,
                    'ph' => $phone,
                    'st' => $status,
                    'id' => $id,
                ]);
            }

            Logger::audit('customer_updated', 'user', $id, "Customer {$email} details updated by administrator");
            Session::flash('success', "Customer '{$request->input('first_name')} {$request->input('last_name')}' updated successfully.");
        } catch (\Throwable $e) {
            Session::flash('error', 'Failed to update customer: ' . $e->getMessage());
        }

        $this->redirect(APP_URL . '/admin/customers');
    }

    /**
     * Activate or Suspend customer account
     */
    public function toggleStatus(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT id, email, status, first_name, last_name FROM users WHERE id = :id AND role = 'customer'");
        $stmt->execute(['id' => $id]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$customer) {
            Session::flash('error', 'Customer account not found.');
            $this->redirect(APP_URL . '/admin/customers');
            return;
        }

        $newStatus = ($customer['status'] === 'active') ? 'suspended' : 'active';

        $upd = $db->prepare("UPDATE users SET status = :s, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $upd->execute(['s' => $newStatus, 'id' => $id]);

        $actionWord = ($newStatus === 'active') ? 'Activated' : 'Suspended';
        Logger::audit('customer_status_toggled', 'user', $id, "Customer {$customer['email']} account {$actionWord}");

        Session::flash('success', "Customer account for '{$customer['first_name']} {$customer['last_name']}' has been {$actionWord}.");
        $this->redirect(APP_URL . '/admin/customers');
    }

    /**
     * Safe customer deletion with order history protection
     */
    public function delete(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id AND role = 'customer'");
        $stmt->execute(['id' => $id]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$customer) {
            Session::flash('error', 'Customer account not found.');
            $this->redirect(APP_URL . '/admin/customers');
            return;
        }

        // Check if customer has historical orders
        $orderCount = (int)$db->query("
            SELECT COUNT(*) FROM orders 
            WHERE user_id = {$id} OR LOWER(customer_email) = LOWER(" . $db->quote($customer['email']) . ")
        ")->fetchColumn();

        if ($orderCount > 0) {
            // SAFE SUSPENSION: Preserve order records and accounting history
            $upd = $db->prepare("UPDATE users SET status = 'suspended', updated_at = CURRENT_TIMESTAMP WHERE id = :id");
            $upd->execute(['id' => $id]);

            Logger::audit(
                'customer_suspended_protected',
                'user',
                $id,
                "Customer {$customer['email']} has {$orderCount} order records. Suspended instead of permanently deleted to protect invoice history."
            );

            Session::flash('warning', "Customer '{$customer['first_name']} {$customer['last_name']}' is linked to {$orderCount} historical order(s). To protect invoice records and accounting reports, the account has been safely Suspended & Deactivated rather than permanently removed.");
            $this->redirect(APP_URL . '/admin/customers');
            return;
        }

        // Safe to remove unreferenced customer
        try {
            $db->beginTransaction();
            $db->exec("DELETE FROM user_addresses WHERE user_id = {$id}");
            $db->exec("DELETE FROM carts WHERE user_id = {$id}");
            $db->exec("DELETE FROM users WHERE id = {$id}");
            $db->commit();

            Logger::audit('customer_deleted', 'user', $id, "Permanently deleted unreferenced customer account {$customer['email']}");
            Session::flash('success', "Customer account for '{$customer['first_name']} {$customer['last_name']}' was permanently removed.");
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            Session::flash('error', 'Failed to delete customer account: ' . $e->getMessage());
        }

        $this->redirect(APP_URL . '/admin/customers');
    }
}
