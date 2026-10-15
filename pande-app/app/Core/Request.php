<?php
/**
 * HTTP Request Handler
 */

declare(strict_types=1);

namespace App\Core;

class Request {
    private array $get;
    private array $post;
    private array $server;
    private array $files;

    public function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
    }

    public function getMethod(): string {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function getPath(): string {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        
        // Strip base path if hosted in subdirectory
        $scriptName = dirname($this->server['SCRIPT_NAME'] ?? '');
        if ($scriptName !== '/' && $scriptName !== '\\' && str_starts_with($path, $scriptName)) {
            $path = substr($path, strlen($scriptName));
        }

        return '/' . trim($path, '/');
    }

    public function query(string $key, mixed $default = null): mixed {
        if (isset($this->get[$key])) {
            return is_string($this->get[$key]) ? trim($this->get[$key]) : $this->get[$key];
        }
        return $default;
    }

    public function input(string $key, mixed $default = null): mixed {
        if (isset($this->post[$key])) {
            return is_string($this->post[$key]) ? trim($this->post[$key]) : $this->post[$key];
        }
        if (isset($this->get[$key])) {
            return is_string($this->get[$key]) ? trim($this->get[$key]) : $this->get[$key];
        }
        return $default;
    }

    public function all(): array {
        return array_merge($this->get, $this->post);
    }

    public function file(string $key): ?array {
        return $this->files[$key] ?? null;
    }

    public function isPost(): bool {
        return $this->getMethod() === 'POST';
    }

    public function isGet(): bool {
        return $this->getMethod() === 'GET';
    }

    public function header(string $key, ?string $default = null): ?string {
        $normalized = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $this->server[$normalized] ?? $default;
    }

    public function isAjax(): bool {
        return (!empty($this->server['HTTP_X_REQUESTED_WITH']) && 
                strtolower($this->server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (!empty($this->server['HTTP_ACCEPT']) && 
                str_contains(strtolower($this->server['HTTP_ACCEPT']), 'application/json'))
            || ($this->input('ajax') === '1');
    }

    public function getIp(): string {
        return $this->server['HTTP_X_FORWARDED_FOR'] ?? $this->server['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}
