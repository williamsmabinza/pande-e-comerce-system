<?php
/**
 * Setting Model
 */

declare(strict_types=1);

namespace App\Models;

use PDO;

class Setting extends BaseModel {
    protected static string $table = 'store_settings';
    private static array $cache = [];

    public static function get(string $key, mixed $default = null): mixed {
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $db = static::db();
        $stmt = $db->prepare("SELECT setting_value FROM store_settings WHERE setting_key = :k LIMIT 1");
        $stmt->execute(['k' => $key]);
        $val = $stmt->fetchColumn();

        if ($val !== false && $val !== null) {
            self::$cache[$key] = $val;
            return $val;
        }

        return $default;
    }

    public static function set(string $key, mixed $value, string $group = 'general'): void {
        $db = static::db();
        $driver = \App\Core\Database::getDriver();
        $nowFunc = $driver === 'sqlite' ? "datetime('now')" : "NOW()";

        // Upsert
        if ($driver === 'sqlite') {
            $stmt = $db->prepare("
                INSERT INTO store_settings (setting_key, setting_value, setting_group, updated_at)
                VALUES (:k, :v, :g, {$nowFunc})
                ON CONFLICT(setting_key) DO UPDATE SET setting_value = :v, updated_at = {$nowFunc}
            ");
        } else {
            $stmt = $db->prepare("
                INSERT INTO store_settings (setting_key, setting_value, setting_group, updated_at)
                VALUES (:k, :v, :g, {$nowFunc})
                ON DUPLICATE KEY UPDATE setting_value = :v, updated_at = {$nowFunc}
            ");
        }

        $stmt->execute(['k' => $key, 'v' => (string)$value, 'g' => $group]);
        self::$cache[$key] = (string)$value;
    }

    public static function getAllGrouped(): array {
        $db = static::db();
        $stmt = $db->query("SELECT * FROM store_settings ORDER BY setting_group ASC, id ASC");
        $rows = $stmt->fetchAll();
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['setting_group']][$row['setting_key']] = $row;
        }
        return $grouped;
    }
}
