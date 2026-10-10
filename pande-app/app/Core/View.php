<?php
/**
 * View Rendering Engine with Layouts and Partials
 */

declare(strict_types=1);

namespace App\Core;

class View {
    private static string $viewsDir = '';

    private static function init(): void {
        if (empty(self::$viewsDir)) {
            self::$viewsDir = dirname(__DIR__) . '/Views';
        }
    }

    public static function render(string $view, array $data = [], ?string $layout = 'main'): void {
        self::init();
        $viewFile = self::$viewsDir . '/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View file not found: {$viewFile}");
        }

        extract($data);

        // Capture view content
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout !== null) {
            $layoutFile = self::$viewsDir . '/layouts/' . $layout . '.php';
            if (!file_exists($layoutFile)) {
                throw new \RuntimeException("Layout file not found: {$layoutFile}");
            }
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    public static function partial(string $partial, array $data = []): void {
        self::init();
        $partialFile = self::$viewsDir . '/partials/' . str_replace('.', '/', $partial) . '.php';

        if (!file_exists($partialFile)) {
            throw new \RuntimeException("Partial file not found: {$partialFile}");
        }

        extract($data);
        require $partialFile;
    }
}
