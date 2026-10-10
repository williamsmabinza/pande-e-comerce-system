<?php
/**
 * Database Migration & Seeder CLI Utility
 * Pande Computers - E-Commerce System
 */

declare(strict_types=1);

require_once __DIR__ . '/../app/Config/app.php';
require_once __DIR__ . '/../app/Core/Database.php';

use App\Core\Database;

echo "=== Pande Computers Database Setup ===\n";

try {
    $db = Database::getInstance();
    $driver = Database::getDriver();
    echo "Connecting via PDO Driver: [{$driver}]...\n";

    $schemaFile = __DIR__ . '/schema.sql';
    $seedFile = __DIR__ . '/seed.sql';

    if (!file_exists($schemaFile)) {
        throw new RuntimeException("Schema file not found at: {$schemaFile}");
    }

    $schemaSql = file_get_contents($schemaFile);

    // If SQLite, adjust MySQL-specific dialect
    if ($driver === 'sqlite') {
        echo "Adjusting schema dialect for SQLite compatibility...\n";
        $schemaSql = preg_replace('/SET FOREIGN_KEY_CHECKS\s*=\s*[01];?/i', '', $schemaSql);
        $schemaSql = preg_replace('/ENGINE\s*=\s*InnoDB/i', '', $schemaSql);
        $schemaSql = preg_replace('/DEFAULT CHARSET\s*=\s*[a-zA-Z0-9_]+/i', '', $schemaSql);
        $schemaSql = preg_replace('/COLLATE\s*=\s*[a-zA-Z0-9_]+/i', '', $schemaSql);
        $schemaSql = preg_replace('/INT UNSIGNED AUTO_INCREMENT PRIMARY KEY/i', 'INTEGER PRIMARY KEY AUTOINCREMENT', $schemaSql);
        $schemaSql = preg_replace('/INT UNSIGNED/i', 'INTEGER', $schemaSql);
        $schemaSql = preg_replace('/ENUM\([^)]+\)/i', 'VARCHAR(50)', $schemaSql);
        $schemaSql = preg_replace('/ON UPDATE CURRENT_TIMESTAMP/i', '', $schemaSql);
        $schemaSql = preg_replace('/UNIQUE KEY\s+[a-zA-Z0-9_]+\s*\(/i', 'UNIQUE (', $schemaSql);
        $schemaSql = preg_replace('/,\s*INDEX\s+idx_[a-zA-Z0-9_]+\s*\([^)]+\)/i', '', $schemaSql);
        $schemaSql = preg_replace('/INDEX\s+idx_[a-zA-Z0-9_]+\s*\([^)]+\),?/i', '', $schemaSql);
        $schemaSql = preg_replace('/,\s*\)/', ')', $schemaSql);
    }

    echo "Executing schema...\n";
    $statements = array_filter(array_map('trim', explode(';', $schemaSql)));
    foreach ($statements as $stmt) {
        if (!empty($stmt)) {
            $db->exec($stmt);
        }
    }
    echo "Schema created successfully.\n";

    echo "Importing seed data...\n";
    $seedSql = file_get_contents($seedFile);
    if ($driver === 'sqlite') {
        $seedSql = str_replace('NOW()', "datetime('now')", $seedSql);
    }

    $statements = array_filter(array_map('trim', explode(';', $seedSql)));
    foreach ($statements as $stmt) {
        if (!empty($stmt)) {
            $db->exec($stmt);
        }
    }
    echo "Seed data imported successfully!\n";

    $categoriesCount = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    $productsCount = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $usersCount = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();

    echo "Stats:\n";
    echo "- Categories: {$categoriesCount}\n";
    echo "- Products:   {$productsCount}\n";
    echo "- Users:      {$usersCount}\n";
    echo "Database setup completed successfully!\n";

} catch (Throwable $e) {
    echo "Error during database migration: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
