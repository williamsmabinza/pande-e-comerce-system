<?php
/**
 * Inventory Movement Model
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Auth;
use App\Core\Database;
use PDO;

class InventoryMovement extends BaseModel {
    protected static string $table = 'inventory_movements';

    public static function record(
        int $productId,
        ?int $orderId,
        string $movementType,
        int $quantityChange,
        int $previousStock,
        int $newStock,
        string $reason
    ): int {
        $db = static::db();
        $userId = Auth::id();

        $driver = Database::getDriver();
        $nowFunc = $driver === 'sqlite' ? "datetime('now')" : "NOW()";

        $stmt = $db->prepare("
            INSERT INTO inventory_movements (
                product_id, order_id, user_id, movement_type, 
                quantity_change, previous_stock, new_stock, reason, created_at
            ) VALUES (
                :product_id, :order_id, :user_id, :movement_type,
                :quantity_change, :previous_stock, :new_stock, :reason, {$nowFunc}
            )
        ");

        $stmt->execute([
            'product_id' => $productId,
            'order_id' => $orderId,
            'user_id' => $userId,
            'movement_type' => $movementType,
            'quantity_change' => $quantityChange,
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'reason' => $reason
        ]);

        return (int)$db->lastInsertId();
    }

    public static function getRecent(int $limit = 50): array {
        $db = static::db();
        $stmt = $db->prepare("
            SELECT im.*, p.name as product_name, p.sku as product_sku, u.first_name, u.last_name
            FROM inventory_movements im
            JOIN products p ON im.product_id = p.id
            LEFT JOIN users u ON im.user_id = u.id
            ORDER BY im.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
