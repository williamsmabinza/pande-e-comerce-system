<?php
/**
 * Admin Staff & Users Controller
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Logger;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;

class StaffController extends BaseController {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function index(Request $request): void {
        $db = Database::getInstance();
        $users = $db->query("SELECT id, role, first_name, last_name, email, phone, status, created_at FROM users ORDER BY id DESC")->fetchAll();

        $this->render('admin.users.index', [
            'pageTitle' => 'Users & Staff Management - Admin',
            'users' => $users,
        ], 'admin');
    }

    public function createStaff(Request $request): void {
        $validator = new Validator($request->all(), [
            'first_name' => 'required|min:2|max:50',
            'last_name' => 'required|min:2|max:50',
            'email' => 'required|email',
            'phone' => 'required|min:9|max:20',
            'role' => 'required|in:admin,staff',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/admin/users');
            return;
        }

        $db = Database::getInstance();
        $email = strtolower(trim($request->input('email')));
        $check = $db->prepare("SELECT COUNT(*) FROM users WHERE email = :e");
        $check->execute(['e' => $email]);
        if ($check->fetchColumn() > 0) {
            Session::flash('error', 'A user with this email address already exists.');
            $this->redirect(APP_URL . '/admin/users');
            return;
        }

        $stmt = $db->prepare("
            INSERT INTO users (role, first_name, last_name, email, phone, password_hash, status)
            VALUES (:role, :first_name, :last_name, :email, :phone, :password_hash, 'active')
        ");
        $stmt->execute([
            'role' => $request->input('role'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $email,
            'phone' => $request->input('phone'),
            'password_hash' => password_hash($request->input('password'), PASSWORD_BCRYPT),
        ]);

        Logger::audit('staff_created', 'user', (int)$db->lastInsertId(), "Created {$request->input('role')} user: {$email}");
        Session::flash('success', "New {$request->input('role')} account created successfully.");
        $this->redirect(APP_URL . '/admin/users');
    }

    public function toggleStatus(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        if ($id === (int)Auth::id()) {
            Session::flash('error', 'You cannot deactivate your own account.');
            $this->redirect(APP_URL . '/admin/users');
            return;
        }

        $db = Database::getInstance();
        $user = $db->prepare("SELECT status FROM users WHERE id = :id");
        $user->execute(['id' => $id]);
        $status = $user->fetchColumn();

        $newStatus = ($status === 'active') ? 'inactive' : 'active';
        $upd = $db->prepare("UPDATE users SET status = :s WHERE id = :id");
        $upd->execute(['s' => $newStatus, 'id' => $id]);

        Logger::audit('user_status_toggled', 'user', $id, "Status changed to {$newStatus}");
        Session::flash('success', "User account status updated to {$newStatus}.");
        $this->redirect(APP_URL . '/admin/users');
    }
}
