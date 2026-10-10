<?php
/**
 * Base Model Class
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

abstract class BaseModel {
    protected static string $table = '';

    protected static function db(): PDO {
        return Database::getInstance();
    }

    public static function all(): array {
        $db = static::db();
        $stmt = $db->query("SELECT * FROM " . static::$table . " ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM " . static::$table . " WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function count(string $where = '', array $params = []): int {
        $db = static::db();
        $sql = "SELECT COUNT(*) FROM " . static::$table;
        if ($where !== '') {
            $sql .= " WHERE {$where}";
        }
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }
}
