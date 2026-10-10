<?php
/**
 * Application Logger & Admin Audit Trail
 */

declare(strict_types=1);

namespace App\Core;

class Logger {
    public static function log(string $level, string $message, array $context = []): void {
        $logDir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $logFile = "{$logDir}/app-{$date}.log";

        $contextStr = !empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_SLASHES) : '';
        $line = "[{$time}] [{$level}] {$message}{$contextStr}\n";

        file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
    }

    public static function info(string $message, array $context = []): void {
        self::log('INFO', $message, $context);
    }

    public static function error(string $message, array $context = []): void {
        self::log('ERROR', $message, $context);
    }

    public static function audit(string $action, string $entityType, ?int $entityId = null, ?string $details = null): void {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("
                INSERT INTO admin_activity_logs (user_id, action, entity_type, entity_id, details, ip_address, created_at)
                VALUES (:user_id, :action, :entity_type, :entity_id, :details, :ip, NOW())
            ");
            $driver = Database::getDriver();
            $nowFunc = $driver === 'sqlite' ? "datetime('now')" : "NOW()";
            
            $stmt = $db->prepare("
                INSERT INTO admin_activity_logs (user_id, action, entity_type, entity_id, details, ip_address, created_at)
                VALUES (:user_id, :action, :entity_type, :entity_id, :details, :ip, {$nowFunc})
            ");

            $stmt->execute([
                'user_id' => Auth::id(),
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'details' => $details,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
            ]);
        } catch (\Throwable $e) {
            self::error("Failed to write audit log: " . $e->getMessage());
        }
    }
}
