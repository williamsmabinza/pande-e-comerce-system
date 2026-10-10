<?php
/**
 * HTTP Response Handler
 */

declare(strict_types=1);

namespace App\Core;

class Response {
    public static function setStatusCode(int $code): void {
        http_response_code($code);
    }

    public static function json(mixed $data, int $code = 200): void {
        self::setStatusCode($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function redirect(string $url, int $code = 302): void {
        self::setStatusCode($code);
        header("Location: {$url}");
        exit;
    }
}
