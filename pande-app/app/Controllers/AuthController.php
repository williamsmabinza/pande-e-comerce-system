<?php
/**
 * Authentication Controller
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;
use App\Models\User;

class AuthController extends BaseController {
    public function showLogin(Request $request): void {
        if (Auth::check()) {
            $this->redirect(Auth::isAdmin() ? APP_URL . '/admin' : APP_URL . '/customer/dashboard');
            return;
        }

        $this->render('auth.login', [
            'pageTitle' => 'Sign In - Pande Computers',
        ], 'auth');
    }

    public function login(Request $request): void {
        $validator = new Validator($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/login');
            return;
        }

        $email = $request->input('email');
        $password = $request->input('password');

        if (Auth::attempt($email, $password)) {
            Session::flash('success', 'Welcome back, ' . Auth::name() . '!');
            if (Auth::isAdmin() || Auth::isStaff()) {
                $this->redirect(APP_URL . '/admin');
            } else {
                $this->redirect(APP_URL . '/customer/dashboard');
            }
            return;
        }

        Session::flash('error', 'Invalid email address or password.');
        $this->redirect(APP_URL . '/login');
    }

    public function showAdminLogin(Request $request): void {
        if (Auth::check() && (Auth::isAdmin() || Auth::isStaff())) {
            $this->redirect(APP_URL . '/admin');
            return;
        }

        $this->render('auth.admin_login', [
            'pageTitle' => 'Admin & Staff Portal - Pande Computers',
        ], 'auth');
    }

    public function adminLogin(Request $request): void {
        $validator = new Validator($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/admin/login');
            return;
        }

        $email = $request->input('email');
        $password = $request->input('password');

        if (Auth::attempt($email, $password)) {
            if (Auth::isAdmin() || Auth::isStaff()) {
                Session::flash('success', 'Welcome to Admin Console, ' . Auth::name() . '!');
                $this->redirect(APP_URL . '/admin');
            } else {
                Session::flash('error', 'Customer accounts do not have access to the administrative console.');
                $this->redirect(APP_URL . '/customer/dashboard');
            }
            return;
        }

        Session::flash('error', 'Invalid administrator email address or password.');
        $this->redirect(APP_URL . '/admin/login');
    }

    public function showRegister(Request $request): void {
        if (Auth::check()) {
            $this->redirect(APP_URL . '/customer/dashboard');
            return;
        }

        $this->render('auth.register', [
            'pageTitle' => 'Create Account - Pande Computers',
        ], 'auth');
    }

    public function register(Request $request): void {
        $validator = new Validator($request->all(), [
            'first_name' => 'required|min:2|max:50',
            'last_name' => 'required|min:2|max:50',
            'email' => 'required|email|max:100',
            'phone' => 'required|min:9|max:20',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|matches:password',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/register');
            return;
        }

        $email = strtolower(trim($request->input('email')));
        if (User::findByEmail($email)) {
            Session::flash('error', 'An account with this email address already exists.');
            $this->redirect(APP_URL . '/register');
            return;
        }

        try {
            $userId = User::createCustomer([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $email,
                'phone' => $request->input('phone'),
                'password' => $request->input('password'),
            ]);

            Auth::attempt($email, $request->input('password'));
            Session::flash('success', 'Account registered successfully! Welcome to Pande Computers.');
            $this->redirect(APP_URL . '/customer/dashboard');

        } catch (\Throwable $e) {
            Session::flash('error', 'Registration error: ' . $e->getMessage());
            $this->redirect(APP_URL . '/register');
        }
    }

    public function logout(Request $request): void {
        Auth::logout();
        Session::flash('info', 'You have been logged out.');
        $this->redirect(APP_URL . '/');
    }

    public function forgotPassword(Request $request): void {
        $this->render('auth.forgot_password', [
            'pageTitle' => 'Forgot Password - Pande Computers',
            'resetLink' => Session::getFlashMessage('reset_link'),
            'resetEmail' => Session::getFlashMessage('reset_email'),
        ], 'auth');
    }

    public function sendResetLink(Request $request): void {
        $validator = new Validator($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/forgot-password');
            return;
        }

        $email = strtolower(trim((string)$request->input('email')));
        $token = User::createPasswordResetToken($email);

        if ($token) {
            $resetUrl = APP_URL . '/reset-password?token=' . urlencode($token);
            Session::flash('reset_link', $resetUrl);
            Session::flash('reset_email', $email);
            Session::flash('success', 'Password reset link generated! Use the link below to set your new password.');
        } else {
            // For security, still say link sent if email exists or give helpful message
            Session::flash('info', 'If an account exists with that email, a password reset link has been generated.');
        }

        $this->redirect(APP_URL . '/forgot-password');
    }

    public function showResetPassword(Request $request): void {
        $token = (string)$request->input('token', '');
        if (empty($token)) {
            Session::flash('error', 'Missing password reset token.');
            $this->redirect(APP_URL . '/forgot-password');
            return;
        }

        $tokenData = User::verifyPasswordResetToken($token);
        if (!$tokenData) {
            Session::flash('error', 'Invalid or expired password reset link. Please request a new one.');
            $this->redirect(APP_URL . '/forgot-password');
            return;
        }

        $this->render('auth.reset_password', [
            'pageTitle' => 'Reset Password - Pande Computers',
            'token' => $token,
            'tokenUser' => $tokenData,
        ], 'auth');
    }

    public function resetPassword(Request $request): void {
        $validator = new Validator($request->all(), [
            'token' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|matches:password',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $token = urlencode((string)$request->input('token'));
            $this->redirect(APP_URL . '/reset-password?token=' . $token);
            return;
        }

        $token = (string)$request->input('token');
        $password = (string)$request->input('password');

        if (User::resetPasswordWithToken($token, $password)) {
            Session::flash('success', 'Your password has been successfully reset! Please sign in with your new password.');
            $this->redirect(APP_URL . '/login');
            return;
        }

        Session::flash('error', 'Unable to reset password. The link may have expired or already been used.');
        $this->redirect(APP_URL . '/forgot-password');
    }
}
