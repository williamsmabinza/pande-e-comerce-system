<?php
/**
 * Application Configuration
 * Pande Computers - E-Commerce System
 */

declare(strict_types=1);

// Set default timezone for Tanzania
date_default_timezone_set('Africa/Dar_es_Salaam');

// Load environment variables if .env exists
$envFile = dirname(__DIR__, 2) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            if (!isset($_SERVER[$key]) && !isset($_ENV[$key])) {
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// Global Application Constants
if (!defined('APP_NAME')) define('APP_NAME', getenv('APP_NAME') ?: 'PANDE COMPUTERS');
if (!defined('APP_ENV')) define('APP_ENV', getenv('APP_ENV') ?: 'production');
if (!defined('APP_DEBUG')) define('APP_DEBUG', (getenv('APP_DEBUG') === 'true' || getenv('APP_DEBUG') === '1'));
if (!defined('APP_URL')) define('APP_URL', getenv('APP_URL') ?: '');
if (!defined('STORE_PHONE')) define('STORE_PHONE', '+255 787 311 342');
if (!defined('STORE_WHATSAPP')) define('STORE_WHATSAPP', '255787311342');
if (!defined('STORE_LOCATION')) define('STORE_LOCATION', 'Ndanda & Aggrey Street, Kariakoo, Dar es Salaam, Tanzania');
if (!defined('STORE_EMAIL')) define('STORE_EMAIL', 'sales@pandecomputers.co.tz');
if (!defined('CURRENCY_CODE')) define('CURRENCY_CODE', 'TZS');
if (!defined('CURRENCY_SYMBOL')) define('CURRENCY_SYMBOL', 'TZS');
if (!defined('APP_ROOT')) define('APP_ROOT', dirname(__DIR__, 2));
if (!defined('APP_DIR')) define('APP_DIR', __DIR__ . '/..');
if (!defined('STORAGE_DIR')) define('STORAGE_DIR', APP_ROOT . '/storage');
if (!defined('PUBLIC_DIR')) define('PUBLIC_DIR', APP_ROOT . '/public_html');

// Determine if request is running over HTTPS (directly or through Cloud Run / reverse proxy)
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on')
    || (getenv('APP_ENV') === 'production');

if ($isHttps) {
    $_SERVER['HTTPS'] = 'on';
}

// Session security defaults
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '0');
    ini_set('session.cookie_path', '/');
    if ($isHttps) {
        // Essential for AI Studio iframe previews: third-party cookies require SameSite=None and Secure=1
        ini_set('session.cookie_samesite', 'None');
        ini_set('session.cookie_secure', '1');
    } else {
        ini_set('session.cookie_samesite', 'Lax');
        ini_set('session.cookie_secure', '0');
    }
}

/**
 * Helper function to format TZS currency
 */
function formatTZS(float|int|string|null $amount): string {
    $num = (float)($amount ?? 0);
    return 'TZS ' . number_format($num, 0, '.', ',');
}

/**
 * Helper to escape HTML safely (prevents XSS)
 */
function e(?string $string): string {
    return htmlspecialchars((string)($string ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Helper to get clean asset URL
 */
function asset(string $path): string {
    $baseUrl = rtrim(APP_URL, '/');
    return $baseUrl . '/' . ltrim($path, '/');
}

/**
 * Helper for URL redirection
 */
function redirect(string $url): void {
    header("Location: {$url}");
    exit;
}
