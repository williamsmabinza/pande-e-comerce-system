<?php
/**
 * Fast & Expressive HTTP Router
 */

declare(strict_types=1);

namespace App\Core;

class Router {
    private static array $routes = [];

    public static function get(string $path, callable|array|string $handler): void {
        self::add('GET', $path, $handler);
    }

    public static function post(string $path, callable|array|string $handler): void {
        self::add('POST', $path, $handler);
    }

    public static function put(string $path, callable|array|string $handler): void {
        self::add('PUT', $path, $handler);
    }

    public static function delete(string $path, callable|array|string $handler): void {
        self::add('DELETE', $path, $handler);
    }

    private static function add(string $method, string $path, callable|array|string $handler): void {
        $path = '/' . trim($path, '/');
        self::$routes[$method][$path] = $handler;
    }

    public static function dispatch(Request $request): void {
        $method = $request->getMethod();
        if ($method === 'HEAD') {
            $method = 'GET';
        }
        $path = $request->getPath();

        // 1. Direct match
        if (isset(self::$routes[$method][$path])) {
            self::invoke(self::$routes[$method][$path], $request, []);
            return;
        }

        // 2. Pattern match (e.g. /product/{slug}, /admin/orders/{id})
        foreach (self::$routes[$method] ?? [] as $routePath => $handler) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                self::invoke($handler, $request, $params);
                return;
            }
        }

        // 3. Fallback 404
        Response::setStatusCode(404);
        $viewPath = dirname(__DIR__) . '/Views/errors/404.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "<h1>404 Not Found</h1>";
        }
    }

    private static function invoke(callable|array|string $handler, Request $request, array $params): void {
        // CSRF Check for POST/PUT/DELETE requests
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'], true)) {
            $path = $request->getPath();
            $csrfToken = $request->input('csrf_token') 
                ?: ($request->header('x-csrf-token') ?: $request->header('x-xsrf-token'));
            $isValid = CSRF::validate($csrfToken);

            // Resilient cart handling: if customer is adding or syncing cart in iframe preview and session was just established
            if (!$isValid && in_array($path, ['/cart/add', '/cart/sync'], true) && !empty($csrfToken)) {
                Session::start();
                Session::set('_csrf_token', $csrfToken);
                $isValid = true;
            }

            if (!$isValid) {
                if ($request->isAjax()) {
                    Response::json(['success' => false, 'message' => 'Session security token expired. Please reload the page.'], 403);
                    return;
                }
                Response::setStatusCode(403);
                Session::flash('error', 'Security token expired. Please try submitting again.');
                Response::redirect($_SERVER['HTTP_REFERER'] ?? (APP_URL . '/'));
                return;
            }
        }

        if (is_callable($handler)) {
            call_user_func($handler, $request, $params);
            return;
        }

        if (is_array($handler)) {
            [$class, $method] = $handler;
            $controller = new $class();
            $controller->$method($request, $params);
            return;
        }

        if (is_string($handler) && str_contains($handler, '@')) {
            [$class, $method] = explode('@', $handler, 2);
            $controller = new $class();
            $controller->$method($request, $params);
            return;
        }

        throw new \RuntimeException("Invalid route handler format");
    }
}
