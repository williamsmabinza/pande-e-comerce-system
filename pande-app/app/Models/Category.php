<?php
/**
 * Category Model
 */

declare(strict_types=1);

namespace App\Models;

use PDO;

class Category extends BaseModel {
    protected static string $table = 'categories';

    public static function getActiveCategories(): array {
        $db = static::db();
        $stmt = $db->query("
            SELECT c.*, COUNT(p.id) as product_count 
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id AND p.status = 'active'
            WHERE c.is_active = 1
            GROUP BY c.id
            ORDER BY c.sort_order ASC, c.name ASC
        ");
        return $stmt->fetchAll();
    }

    public static function findBySlug(string $slug): ?array {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM categories WHERE slug = :slug AND is_active = 1 LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int {
        $db = static::db();
        $stmt = $db->prepare("
            INSERT INTO categories (parent_id, name, slug, description, icon_class, image_url, sort_order, is_active)
            VALUES (:parent_id, :name, :slug, :description, :icon_class, :image_url, :sort_order, :is_active)
        ");
        $stmt->execute([
            'parent_id' => !empty($data['parent_id']) ? (int)$data['parent_id'] : null,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'icon_class' => $data['icon_class'] ?? 'bi-cpu',
            'image_url' => $data['image_url'] ?? null,
            'sort_order' => (int)($data['sort_order'] ?? 0),
            'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 1
        ]);
        return (int)$db->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $db = static::db();
        $stmt = $db->prepare("
            UPDATE categories 
            SET parent_id = :parent_id,
                name = :name,
                slug = :slug,
                description = :description,
                icon_class = :icon_class,
                sort_order = :sort_order,
                is_active = :is_active
            WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'parent_id' => !empty($data['parent_id']) ? (int)$data['parent_id'] : null,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'icon_class' => $data['icon_class'] ?? 'bi-cpu',
            'sort_order' => (int)($data['sort_order'] ?? 0),
            'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 1
        ]);
    }

    public static function delete(int $id): bool {
        $db = static::db();
        // Check if products exist in category
        $stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE category_id = :id");
        $stmt->execute(['id' => $id]);
        if ($stmt->fetchColumn() > 0) {
            throw new \RuntimeException("Cannot delete category because active products are assigned to it. Please reassign or delete the products first.");
        }

        $stmt = $db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
