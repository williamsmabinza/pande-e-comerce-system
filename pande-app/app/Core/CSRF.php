<?php
/**
 * Cross-Site Request Forgery (CSRF) Protection
 */

declare(strict_types=1);

namespace App\Core;

class CSRF {
    private const TOKEN_KEY = '_csrf_token';

    public static function generateToken(): string {
        Session::start();
        $token = bin2hex(random_bytes(32));
        Session::set(self::TOKEN_KEY, $token);
        return $token;
    }

    public static function getToken(): string {
        Session::start();
        $token = Session::get(self::TOKEN_KEY);
        if (!$token) {
            $token = self::generateToken();
        }
        return $token;
    }

    public static function validate(?string $submittedToken): bool {
        Session::start();
        $storedToken = Session::get(self::TOKEN_KEY);
        if (empty($storedToken) || empty($submittedToken)) {
            return false;
        }
        return hash_equals($storedToken, $submittedToken);
    }

    public static function input(): string {
        $token = self::getToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}
