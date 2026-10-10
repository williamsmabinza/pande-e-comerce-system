<?php
/**
 * Customer Account & Portal Controller
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Order;
use App\Models\User;
use App\Models\Setting;

class CustomerController extends BaseController {
    public function __construct() {
        Auth::requireAuth();
    }

    public function dashboard(Request $request): void {
        $userId = (int)Auth::id();
        $orders = Order::getCustomerOrders($userId);
        $user = Auth::user();
        $addresses = User::getAddresses($userId);

        // Calculate comprehensive customer metrics
        $totalOrders = count($orders);
        $pendingOrders = 0;
        $completedOrders = 0;
        $totalSpent = 0.0;

        foreach ($orders as $ord) {
            $status = strtolower($ord['order_status'] ?? '');
            if (in_array($status, ['pending', 'awaiting_confirmation', 'confirmed', 'processing', 'dispatched'], true)) {
                $pendingOrders++;
            } elseif (in_array($status, ['delivered', 'completed'], true)) {
                $completedOrders++;
            }

            if (($ord['payment_status'] ?? '') === 'verified' || in_array($status, ['delivered', 'confirmed', 'dispatched'], true)) {
                $totalSpent += (float)($ord['total_amount'] ?? 0);
            }
        }

        // Identify primary address
        $defaultAddress = null;
        foreach ($addresses as $addr) {
            if (!empty($addr['is_default'])) {
                $defaultAddress = $addr;
                break;
            }
        }
        if (!$defaultAddress && !empty($addresses)) {
            $defaultAddress = $addresses[0];
        }

        $this->render('customer.dashboard', [
            'pageTitle' => 'Customer Dashboard - Pande Computers',
            'user' => $user,
            'orders' => array_slice($orders, 0, 5),
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'completedOrders' => $completedOrders,
            'totalSpent' => $totalSpent,
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
            'storePhone' => Setting::get('store_phone', STORE_PHONE),
            'storeWhatsApp' => Setting::get('whatsapp_number', STORE_WHATSAPP),
            'storeLocation' => Setting::get('store_address', STORE_LOCATION),
            'storeEmail' => Setting::get('store_email', STORE_EMAIL),
        ]);
    }

    public function orders(Request $request): void {
        $userId = (int)Auth::id();
        $orders = Order::getCustomerOrders($userId);

        $this->render('customer.orders', [
            'pageTitle' => 'My Order History - Pande Computers',
            'orders' => $orders,
        ]);
    }

    public function orderDetail(Request $request, array $params): void {
        $orderNumber = $params['number'] ?? '';
        $order = Order::findByNumber($orderNumber);

        if (!$order) {
            Session::flash('error', 'Order not found.');
            $this->redirect(APP_URL . '/customer/orders');
            return;
        }

        // STRICT SECURITY: Ownership verification
        if ((int)$order['user_id'] !== (int)Auth::id() && !Auth::isAdmin()) {
            Response::setStatusCode(403);
            Session::flash('error', 'Unauthorized access to order.');
            $this->redirect(APP_URL . '/customer/orders');
            return;
        }

        $this->render('customer.order_detail', [
            'pageTitle' => "Order #{$order['order_number']} - Pande Computers",
            'order' => $order,
        ]);
    }

    public function profile(Request $request): void {
        $userId = (int)Auth::id();
        $user = Auth::user();

        if ($request->isPost()) {
            // Handle Avatar Removal if requested
            if ($request->input('remove_avatar') === '1') {
                if (!empty($user['avatar'])) {
                    $oldPath = PUBLIC_DIR . '/' . ltrim($user['avatar'], '/');
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                    User::updateAvatar($userId, null);
                }
                Session::flash('success', 'Profile photo removed.');
                $this->redirect(APP_URL . '/customer/profile');
                return;
            }

            // Validation for personal info
            $validator = new Validator($request->all(), [
                'first_name' => 'required|min:2|max:50',
                'last_name' => 'required|min:2|max:50',
                'phone' => 'required|min:9|max:20',
            ]);

            if ($validator->fails()) {
                Session::flash('error', $validator->firstError());
                $this->redirect(APP_URL . '/customer/profile');
                return;
            }

            User::updateProfile($userId, [
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'phone' => $request->input('phone'),
            ]);

            // Handle Profile Image Upload
            $file = $request->file('avatar');
            if ($file && !empty($file['tmp_name']) && $file['error'] === UPLOAD_ERR_OK) {
                $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mimeType, $allowedMimes, true)) {
                    Session::flash('error', 'Invalid image format. Allowed formats: JPG, PNG, WEBP.');
                    $this->redirect(APP_URL . '/customer/profile');
                    return;
                }

                if ($file['size'] > 5 * 1024 * 1024) {
                    Session::flash('error', 'Profile image size must not exceed 5MB.');
                    $this->redirect(APP_URL . '/customer/profile');
                    return;
                }

                $ext = match ($mimeType) {
                    'image/jpeg', 'image/jpg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp',
                    default => 'jpg'
                };

                $uploadDir = PUBLIC_DIR . '/assets/uploads/avatars';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Delete old avatar file if present
                if (!empty($user['avatar'])) {
                    $oldPath = PUBLIC_DIR . '/' . ltrim($user['avatar'], '/');
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $filename = 'avatar_' . $userId . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                $destination = $uploadDir . '/' . $filename;

                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    User::updateAvatar($userId, 'assets/uploads/avatars/' . $filename);
                } else {
                    Session::flash('error', 'Failed to save uploaded image. Please try again.');
                    $this->redirect(APP_URL . '/customer/profile');
                    return;
                }
            }

            Session::flash('success', 'Profile updated successfully.');
            $this->redirect(APP_URL . '/customer/profile');
            return;
        }

        $addresses = User::getAddresses($userId);

        $this->render('customer.profile', [
            'pageTitle' => 'Customer Profile Settings - Pande Computers',
            'user' => Auth::user(),
            'addresses' => $addresses,
        ]);
    }

    public function changePassword(Request $request): void {
        $userId = (int)Auth::id();

        if (!$request->isPost()) {
            $this->redirect(APP_URL . '/customer/profile');
            return;
        }

        $validator = new Validator($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'new_password_confirmation' => 'required|matches:new_password',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/customer/profile#password');
            return;
        }

        $currentPassword = (string)$request->input('current_password');
        $newPassword = (string)$request->input('new_password');

        if (!User::verifyPassword($userId, $currentPassword)) {
            Session::flash('error', 'The current password you entered is incorrect.');
            $this->redirect(APP_URL . '/customer/profile#password');
            return;
        }

        User::updatePassword($userId, $newPassword);

        Session::flash('success', 'Your password has been changed successfully.');
        $this->redirect(APP_URL . '/customer/profile');
    }

    public function addresses(Request $request): void {
        $userId = (int)Auth::id();

        if ($request->isPost()) {
            $validator = new Validator($request->all(), [
                'recipient_name' => 'required|min:3|max:100',
                'phone' => 'required|min:9|max:20',
                'street_address' => 'required|min:5|max:255',
                'district' => 'required|min:2|max:100',
            ]);

            if ($validator->fails()) {
                Session::flash('error', $validator->firstError());
                $this->redirect(APP_URL . '/customer/addresses');
                return;
            }

            User::addAddress($userId, [
                'recipient_name' => $request->input('recipient_name'),
                'phone' => $request->input('phone'),
                'street_address' => $request->input('street_address'),
                'region_city' => $request->input('region_city', 'Dar es Salaam'),
                'district' => $request->input('district'),
                'landmark' => $request->input('landmark'),
                'is_default' => $request->input('is_default') ? 1 : 0,
            ]);

            Session::flash('success', 'New delivery address added successfully.');
            $this->redirect(APP_URL . '/customer/addresses');
            return;
        }

        $addresses = User::getAddresses($userId);

        $this->render('customer.addresses', [
            'pageTitle' => 'Manage Delivery Addresses - Pande Computers',
            'addresses' => $addresses,
        ]);
    }

    public function updateAddress(Request $request, array $params): void {
        $userId = (int)Auth::id();
        $addressId = (int)($params['id'] ?? 0);

        if (!$request->isPost() || $addressId <= 0) {
            $this->redirect(APP_URL . '/customer/addresses');
            return;
        }

        $existing = User::getAddress($addressId, $userId);
        if (!$existing) {
            Session::flash('error', 'Address not found or unauthorized.');
            $this->redirect(APP_URL . '/customer/addresses');
            return;
        }

        $validator = new Validator($request->all(), [
            'recipient_name' => 'required|min:3|max:100',
            'phone' => 'required|min:9|max:20',
            'street_address' => 'required|min:5|max:255',
            'district' => 'required|min:2|max:100',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/customer/addresses');
            return;
        }

        User::updateAddress($addressId, $userId, [
            'recipient_name' => $request->input('recipient_name'),
            'phone' => $request->input('phone'),
            'street_address' => $request->input('street_address'),
            'region_city' => $request->input('region_city', 'Dar es Salaam'),
            'district' => $request->input('district'),
            'landmark' => $request->input('landmark'),
            'is_default' => $request->input('is_default') ? 1 : 0,
        ]);

        Session::flash('success', 'Address updated successfully.');
        $this->redirect(APP_URL . '/customer/addresses');
    }

    public function setDefaultAddress(Request $request, array $params): void {
        $userId = (int)Auth::id();
        $addressId = (int)($params['id'] ?? 0);

        if (!$request->isPost() || $addressId <= 0) {
            $this->redirect(APP_URL . '/customer/addresses');
            return;
        }

        $existing = User::getAddress($addressId, $userId);
        if (!$existing) {
            Session::flash('error', 'Address not found or unauthorized.');
            $this->redirect(APP_URL . '/customer/addresses');
            return;
        }

        User::setDefaultAddress($addressId, $userId);
        Session::flash('success', 'Default address updated.');
        $this->redirect(APP_URL . '/customer/addresses');
    }

    public function deleteAddress(Request $request, array $params): void {
        $userId = (int)Auth::id();
        $addressId = (int)($params['id'] ?? 0);

        if (!$request->isPost() || $addressId <= 0) {
            $this->redirect(APP_URL . '/customer/addresses');
            return;
        }

        $existing = User::getAddress($addressId, $userId);
        if (!$existing) {
            Session::flash('error', 'Address not found or unauthorized.');
            $this->redirect(APP_URL . '/customer/addresses');
            return;
        }

        User::deleteAddress($addressId, $userId);
        Session::flash('success', 'Address deleted successfully.');
        $this->redirect(APP_URL . '/customer/addresses');
    }
}
