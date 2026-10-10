<?php
/**
 * Database Connection Manager (PDO)
 * Supports MySQL & SQLite with prepared statements and transactions
 */

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

class Database {
    private static ?PDO $instance = null;
    private static string $activeDriver = 'mysql';

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            self::connect();
        }
        return self::$instance;
    }

    public static function getDriver(): string {
        if (self::$instance === null) {
            self::connect();
        }
        return self::$activeDriver;
    }

    private static function connect(): void {
        $config = require dirname(__DIR__) . '/Config/database.php';
        $driver = $config['driver'] ?? 'sqlite';
        self::$activeDriver = $driver;

        try {
            if ($driver === 'mysql') {
                $c = $config['mysql'];
                $dsn = "mysql:host={$c['host']};port={$c['port']};dbname={$c['database']};charset={$c['charset']}";
                self::$instance = new PDO($dsn, $c['username'], $c['password'], $c['options']);
            } elseif ($driver === 'sqlite') {
                $c = $config['sqlite'];
                $dbPath = $c['database'];
                $dir = dirname($dbPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                $dsn = "sqlite:{$dbPath}";
                self::$instance = new PDO($dsn, null, null, $c['options']);
                self::$instance->exec('PRAGMA foreign_keys = ON;');
            } else {
                throw new RuntimeException("Unsupported database driver: {$driver}");
            }
        } catch (PDOException $e) {
            // If MySQL fails and in dev mode, fallback to SQLite for resilience
            if ($driver === 'mysql' && (getenv('APP_ENV') === 'development' || !getenv('APP_ENV'))) {
                error_log("MySQL connection failed, falling back to SQLite: " . $e->getMessage());
                self::$activeDriver = 'sqlite';
                $dbPath = dirname(__DIR__, 2) . '/storage/database.sqlite';
                self::$instance = new PDO("sqlite:{$dbPath}", null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
                self::$instance->exec('PRAGMA foreign_keys = ON;');
                return;
            }
            throw new RuntimeException("Database connection error: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public static function beginTransaction(): bool {
        return self::getInstance()->beginTransaction();
    }

    public static function commit(): bool {
        return self::getInstance()->commit();
    }

    public static function rollBack(): bool {
        return self::getInstance()->rollBack();
    }

    public static function lastInsertId(): string|false {
        return self::getInstance()->lastInsertId();
    }
}
