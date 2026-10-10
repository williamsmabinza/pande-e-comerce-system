<?php
/**
 * Session & Flash Message Manager
 */

declare(strict_types=1);

namespace App\Core;

class Session {
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            // Check for explicit session ID if cookies are restricted in third-party iframe
            $rawSid = $_COOKIE[session_name()] 
                ?? $_SERVER['HTTP_X_SESSION_ID'] 
                ?? $_POST['_session_id'] 
                ?? $_GET['_session_id'] 
                ?? $_GET['sid'] 
                ?? null;

            if (!empty($rawSid) && is_string($rawSid)) {
                $sanitizedSid = preg_replace('/[^a-zA-Z0-9,-]/', '', $rawSid);
                if (strlen($sanitizedSid) >= 16 && strlen($sanitizedSid) <= 128) {
                    @session_id($sanitizedSid);
                }
            }
            @session_start();
            if (session_status() === PHP_SESSION_ACTIVE && !headers_sent()) {
                header('X-Session-ID: ' . session_id());
            }
        }
        if (!isset($_SESSION)) {
            $_SESSION = [];
        }
    }

    public static function getId(): string {
        self::start();
        return session_id() ?: '';
    }

    public static function set(string $key, mixed $value): void {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function flash(string $type, string $message): void {
        self::start();
        $_SESSION['flash'][$type][] = $message;
    }

    public static function getFlash(string $type): array {
        self::start();
        $messages = $_SESSION['flash'][$type] ?? [];
        unset($_SESSION['flash'][$type]);
        return $messages;
    }

    public static function getFlashMessage(string $type): ?string {
        $messages = self::getFlash($type);
        return !empty($messages) ? (string)$messages[0] : null;
    }

    public static function hasFlash(string $type): bool {
        self::start();
        return !empty($_SESSION['flash'][$type]);
    }

    public static function regenerate(): void {
        self::start();
        if (session_status() === PHP_SESSION_ACTIVE && !headers_sent()) {
            @session_regenerate_id(true);
        }
    }

    public static function destroy(): void {
        self::start();
        $_SESSION = [];
        if (ini_get("session.use_cookies") && !headers_sent()) {
            $params = session_get_cookie_params();
            @setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        if (session_status() === PHP_SESSION_ACTIVE) {
            @session_destroy();
        }
    }
}
