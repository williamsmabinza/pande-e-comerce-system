<?php
/**
 * Product Model
 */

declare(strict_types=1);

namespace App\Models;

use PDO;

class Product extends BaseModel {
    protected static string $table = 'products';

    public static function getFeatured(int $limit = 8): array {
        $db = static::db();
        $stmt = $db->prepare("
            SELECT p.*, c.name as category_name, c.slug as category_slug,
                   (SELECT image_path FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'active' AND p.is_featured = 1
            ORDER BY p.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getNewArrivals(int $limit = 8): array {
        $db = static::db();
        $stmt = $db->prepare("
            SELECT p.*, c.name as category_name, c.slug as category_slug,
                   (SELECT image_path FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'active' AND p.is_new_arrival = 1
            ORDER BY p.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getTopSelling(int $limit = 8): array {
        $db = static::db();
        $stmt = $db->prepare("
            SELECT p.*, c.name as category_name, c.slug as category_slug,
                   COALESCE((SELECT SUM(oi.quantity) FROM order_items oi WHERE oi.product_id = p.id), 0) as total_sold,
                   (SELECT image_path FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'active'
            ORDER BY total_sold DESC, p.is_featured DESC, p.stock_quantity ASC, p.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findBySlug(string $slug): ?array {
        $db = static::db();
        $stmt = $db->prepare("
            SELECT p.*, c.name as category_name, c.slug as category_slug
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.slug = :slug AND p.status = 'active'
            LIMIT 1
        ");
        $stmt->execute(['slug' => $slug]);
        $product = $stmt->fetch();
        if ($product) {
            $product['images'] = self::getImages((int)$product['id']);
            $product['primary_image'] = !empty($product['images']) ? $product['images'][0]['image_path'] : null;
            $product['specs_array'] = !empty($product['specifications']) ? json_decode($product['specifications'], true) : [];
        }
        return $product ?: null;
    }

    public static function getImages(int $productId): array {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM product_images WHERE product_id = :id ORDER BY is_primary DESC, sort_order ASC");
        $stmt->execute(['id' => $productId]);
        return $stmt->fetchAll();
    }

    public static function getRelated(int $categoryId, int $currentProductId, int $limit = 4): array {
        $db = static::db();
        $stmt = $db->prepare("
            SELECT p.*, c.name as category_name,
                   (SELECT image_path FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.category_id = :cat_id AND p.id != :prod_id AND p.status = 'active'
            ORDER BY p.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':cat_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':prod_id', $currentProductId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function search(array $filters, int $page = 1, int $perPage = 12): array {
        $db = static::db();
        $where = ["p.status = 'active'"];
        $params = [];

        if (!empty($filters['query'])) {
            $where[] = "(p.name LIKE :q OR p.brand LIKE :q OR p.sku LIKE :q OR p.short_description LIKE :q)";
            $params['q'] = '%' . $filters['query'] . '%';
        }

        if (!empty($filters['category_id'])) {
            $where[] = "p.category_id = :cat_id";
            $params['cat_id'] = (int)$filters['category_id'];
        }

        if (!empty($filters['brand'])) {
            $where[] = "p.brand = :brand";
            $params['brand'] = $filters['brand'];
        }

        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $where[] = "p.price >= :min_price";
            $params['min_price'] = (float)$filters['min_price'];
        }

        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $where[] = "p.price <= :max_price";
            $params['max_price'] = (float)$filters['max_price'];
        }

        if (!empty($filters['in_stock'])) {
            $where[] = "p.stock_quantity > 0";
        }

        $whereClause = implode(' AND ', $where);

        // Sorting
        $orderBy = match ($filters['sort'] ?? 'newest') {
            'price_asc' => 'p.price ASC',
            'price_desc' => 'p.price DESC',
            'name_asc' => 'p.name ASC',
            default => 'p.id DESC',
        };

        // Total count
        $countStmt = $db->prepare("SELECT COUNT(*) FROM products p WHERE {$whereClause}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // Paginated results
        $offset = ($page - 1) * $perPage;
        $sql = "
            SELECT p.*, c.name as category_name, c.slug as category_slug,
                   (SELECT image_path FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE {$whereClause}
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(":{$k}", $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();

        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($total / $perPage),
        ];
    }

    public static function create(array $data): int {
        $db = static::db();
        $stmt = $db->prepare("
            INSERT INTO products (
                category_id, name, slug, sku, model_number, brand, 
                short_description, description, price, compare_at_price, 
                cost_price, stock_quantity, low_stock_threshold, is_featured, 
                is_new_arrival, status, warranty_info, specifications
            ) VALUES (
                :category_id, :name, :slug, :sku, :model_number, :brand,
                :short_description, :description, :price, :compare_at_price,
                :cost_price, :stock_quantity, :low_stock_threshold, :is_featured,
                :is_new_arrival, :status, :warranty_info, :specifications
            )
        ");

        $stmt->execute([
            'category_id' => (int)$data['category_id'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'sku' => strtoupper($data['sku']),
            'model_number' => $data['model_number'] ?? null,
            'brand' => $data['brand'],
            'short_description' => $data['short_description'] ?? '',
            'description' => $data['description'] ?? '',
            'price' => (float)$data['price'],
            'compare_at_price' => !empty($data['compare_at_price']) ? (float)$data['compare_at_price'] : null,
            'cost_price' => !empty($data['cost_price']) ? (float)$data['cost_price'] : null,
            'stock_quantity' => (int)($data['stock_quantity'] ?? 0),
            'low_stock_threshold' => (int)($data['low_stock_threshold'] ?? 5),
            'is_featured' => !empty($data['is_featured']) ? 1 : 0,
            'is_new_arrival' => !empty($data['is_new_arrival']) ? 1 : 0,
            'status' => $data['status'] ?? 'active',
            'warranty_info' => $data['warranty_info'] ?? '1 Year Warranty',
            'specifications' => is_array($data['specifications'] ?? null) ? json_encode($data['specifications']) : ($data['specifications'] ?? null),
        ]);

        $productId = (int)$db->lastInsertId();

        // Record initial stock movement
        if ((int)($data['stock_quantity'] ?? 0) > 0) {
            InventoryMovement::record(
                $productId,
                null,
                'initial',
                (int)$data['stock_quantity'],
                0,
                (int)$data['stock_quantity'],
                'Initial stock entered upon product creation'
            );
        }

        return $productId;
    }

    public static function update(int $id, array $data): bool {
        $db = static::db();
        $existing = static::find($id);
        if (!$existing) {
            return false;
        }
        $merged = array_merge($existing, $data);

        $stmt = $db->prepare("
            UPDATE products SET
                category_id = :category_id,
                name = :name,
                slug = :slug,
                sku = :sku,
                model_number = :model_number,
                brand = :brand,
                short_description = :short_description,
                description = :description,
                price = :price,
                compare_at_price = :compare_at_price,
                cost_price = :cost_price,
                stock_quantity = :stock_quantity,
                low_stock_threshold = :low_stock_threshold,
                is_featured = :is_featured,
                is_new_arrival = :is_new_arrival,
                status = :status,
                warranty_info = :warranty_info,
                specifications = :specifications
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'category_id' => (int)$merged['category_id'],
            'name' => $merged['name'],
            'slug' => $merged['slug'],
            'sku' => strtoupper($merged['sku']),
            'model_number' => $merged['model_number'] ?? null,
            'brand' => $merged['brand'],
            'short_description' => $merged['short_description'] ?? '',
            'description' => $merged['description'] ?? '',
            'price' => (float)$merged['price'],
            'compare_at_price' => !empty($merged['compare_at_price']) ? (float)$merged['compare_at_price'] : null,
            'cost_price' => !empty($merged['cost_price']) ? (float)$merged['cost_price'] : null,
            'stock_quantity' => (int)($merged['stock_quantity'] ?? 0),
            'low_stock_threshold' => (int)($merged['low_stock_threshold'] ?? 5),
            'is_featured' => !empty($merged['is_featured']) ? 1 : 0,
            'is_new_arrival' => !empty($merged['is_new_arrival']) ? 1 : 0,
            'status' => $merged['status'] ?? 'active',
            'warranty_info' => $merged['warranty_info'] ?? '1 Year Warranty',
            'specifications' => is_array($merged['specifications'] ?? null) ? json_encode($merged['specifications']) : ($merged['specifications'] ?? null),
        ]);
    }

    public static function deductStock(int $id, int $quantity, int $orderId): bool {
        $db = static::db();
        $forUpdate = \App\Core\Database::getDriver() === 'mysql' ? ' FOR UPDATE' : '';
        $stmt = $db->prepare("SELECT stock_quantity FROM products WHERE id = :id" . $forUpdate);
        $stmt->execute(['id' => $id]);
        $currentStock = (int)$stmt->fetchColumn();

        if ($currentStock < $quantity) {
            return false;
        }

        $newStock = $currentStock - $quantity;
        $upd = $db->prepare("UPDATE products SET stock_quantity = :new_stock WHERE id = :id");
        $upd->execute(['new_stock' => $newStock, 'id' => $id]);

        InventoryMovement::record(
            $id,
            $orderId,
            'sale',
            -$quantity,
            $currentStock,
            $newStock,
            "Stock deducted for Order #{$orderId}"
        );

        return true;
    }

    public static function restoreStock(int $id, int $quantity, int $orderId, string $reason): void {
        $db = static::db();
        $stmt = $db->prepare("SELECT stock_quantity FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $currentStock = (int)$stmt->fetchColumn();

        $newStock = $currentStock + $quantity;
        $upd = $db->prepare("UPDATE products SET stock_quantity = :new_stock WHERE id = :id");
        $upd->execute(['new_stock' => $newStock, 'id' => $id]);

        InventoryMovement::record(
            $id,
            $orderId,
            'cancellation',
            $quantity,
            $currentStock,
            $newStock,
            $reason
        );
    }
}
