<?php
/**
 * Database Configuration
 * Pande Computers - E-Commerce System
 */

declare(strict_types=1);

return [
    // Driver can be 'mysql' (standard production for cPanel) or 'sqlite' (for dev/testing)
    'driver' => getenv('DB_CONNECTION') ?: 'sqlite',

    // MySQL Configuration
    'mysql' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int)(getenv('DB_PORT') ?: 3306),
        'database' => getenv('DB_DATABASE') ?: 'pande_ecommerce',
        'username' => getenv('DB_USERNAME') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
        ],
    ],

    // SQLite Configuration (Zero-setup testing & fallback)
    'sqlite' => [
        'database' => getenv('DB_DATABASE') ?: dirname(__DIR__, 2) . '/storage/database.sqlite',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ],
    ],
];
