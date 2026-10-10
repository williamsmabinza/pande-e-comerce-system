<?php
/**
 * User Model
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class User extends BaseModel {
    protected static string $table = 'users';

    public static function createCustomer(array $data): int {
        $db = static::db();
        $stmt = $db->prepare("
            INSERT INTO users (role, first_name, last_name, email, phone, password_hash, status)
            VALUES ('customer', :first_name, :last_name, :email, :phone, :password_hash, 'active')
        ");

        $stmt->execute([
            'first_name' => trim($data['first_name']),
            'last_name' => trim($data['last_name']),
            'email' => strtolower(trim($data['email'])),
            'phone' => trim($data['phone']),
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
        ]);

        return (int)$db->lastInsertId();
    }

    public static function findByEmail(string $email): ?array {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => strtolower(trim($email))]);
        return $stmt->fetch() ?: null;
    }

    public static function getAddresses(int $userId): array {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM user_addresses WHERE user_id = :uid ORDER BY is_default DESC, id DESC");
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }

    public static function getAddress(int $id, int $userId): ?array {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM user_addresses WHERE id = :id AND user_id = :uid LIMIT 1");
        $stmt->execute(['id' => $id, 'uid' => $userId]);
        return $stmt->fetch() ?: null;
    }

    public static function addAddress(int $userId, array $data): int {
        $db = static::db();
        if (!empty($data['is_default'])) {
            $reset = $db->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = :uid");
            $reset->execute(['uid' => $userId]);
        }

        $stmt = $db->prepare("
            INSERT INTO user_addresses (user_id, recipient_name, phone, street_address, region_city, district, landmark, is_default)
            VALUES (:uid, :name, :phone, :address, :city, :district, :landmark, :is_default)
        ");

        $stmt->execute([
            'uid' => $userId,
            'name' => trim($data['recipient_name']),
            'phone' => trim($data['phone']),
            'address' => trim($data['street_address']),
            'city' => trim($data['region_city'] ?? 'Dar es Salaam'),
            'district' => trim($data['district'] ?? 'Ilala'),
            'landmark' => !empty($data['landmark']) ? trim($data['landmark']) : null,
            'is_default' => !empty($data['is_default']) ? 1 : 0
        ]);

        return (int)$db->lastInsertId();
    }

    public static function updateAddress(int $id, int $userId, array $data): bool {
        $db = static::db();
        if (!empty($data['is_default'])) {
            $reset = $db->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = :uid");
            $reset->execute(['uid' => $userId]);
        }

        $stmt = $db->prepare("
            UPDATE user_addresses 
            SET recipient_name = :name,
                phone = :phone,
                street_address = :address,
                region_city = :city,
                district = :district,
                landmark = :landmark,
                is_default = :is_default
            WHERE id = :id AND user_id = :uid
        ");

        return $stmt->execute([
            'id' => $id,
            'uid' => $userId,
            'name' => trim($data['recipient_name']),
            'phone' => trim($data['phone']),
            'address' => trim($data['street_address']),
            'city' => trim($data['region_city'] ?? 'Dar es Salaam'),
            'district' => trim($data['district'] ?? 'Ilala'),
            'landmark' => !empty($data['landmark']) ? trim($data['landmark']) : null,
            'is_default' => !empty($data['is_default']) ? 1 : 0
        ]);
    }

    public static function setDefaultAddress(int $id, int $userId): bool {
        $db = static::db();
        $reset = $db->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = :uid");
        $reset->execute(['uid' => $userId]);

        $stmt = $db->prepare("UPDATE user_addresses SET is_default = 1 WHERE id = :id AND user_id = :uid");
        return $stmt->execute(['id' => $id, 'uid' => $userId]);
    }

    public static function deleteAddress(int $id, int $userId): bool {
        $db = static::db();
        $stmt = $db->prepare("DELETE FROM user_addresses WHERE id = :id AND user_id = :uid");
        return $stmt->execute(['id' => $id, 'uid' => $userId]);
    }

    public static function updateProfile(int $userId, array $data): bool {
        $db = static::db();
        $stmt = $db->prepare("
            UPDATE users 
            SET first_name = :fn, 
                last_name = :ln, 
                phone = :phone,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");

        return $stmt->execute([
            'fn' => trim($data['first_name']),
            'ln' => trim($data['last_name']),
            'phone' => trim($data['phone']),
            'id' => $userId
        ]);
    }

    public static function updateAvatar(int $userId, ?string $avatarPath): bool {
        $db = static::db();
        $stmt = $db->prepare("UPDATE users SET avatar = :avatar, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([
            'avatar' => $avatarPath,
            'id' => $userId
        ]);
    }

    public static function verifyPassword(int $userId, string $password): bool {
        $db = static::db();
        $stmt = $db->prepare("SELECT password_hash FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $userId]);
        $row = $stmt->fetch();
        if (!$row) {
            return false;
        }
        return password_verify($password, $row['password_hash']);
    }

    public static function updatePassword(int $userId, string $newPassword): bool {
        $db = static::db();
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $db->prepare("UPDATE users SET password_hash = :hash, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([
            'hash' => $hash,
            'id' => $userId
        ]);
    }

    public static function createPasswordResetToken(string $email): ?string {
        $user = self::findByEmail($email);
        if (!$user) {
            return null;
        }

        $rawToken = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $rawToken);
        $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 hour

        $db = static::db();
        // Invalidate previous tokens
        $db->prepare("UPDATE password_reset_tokens SET is_used = 1 WHERE user_id = :uid")->execute(['uid' => $user['id']]);

        $stmt = $db->prepare("
            INSERT INTO password_reset_tokens (user_id, token_hash, expires_at, is_used)
            VALUES (:uid, :th, :exp, 0)
        ");
        $stmt->execute([
            'uid' => $user['id'],
            'th' => $tokenHash,
            'exp' => $expiresAt
        ]);

        return $rawToken;
    }

    public static function verifyPasswordResetToken(string $token): ?array {
        if (empty($token) || strlen($token) < 20) {
            return null;
        }

        $tokenHash = hash('sha256', $token);
        $db = static::db();
        $stmt = $db->prepare("
            SELECT t.*, u.email, u.first_name, u.last_name 
            FROM password_reset_tokens t
            JOIN users u ON u.id = t.user_id
            WHERE t.token_hash = :th 
              AND t.is_used = 0 
              AND t.expires_at > CURRENT_TIMESTAMP
            ORDER BY t.id DESC 
            LIMIT 1
        ");
        $stmt->execute(['th' => $tokenHash]);
        return $stmt->fetch() ?: null;
    }

    public static function resetPasswordWithToken(string $token, string $newPassword): bool {
        $tokenData = self::verifyPasswordResetToken($token);
        if (!$tokenData) {
            return false;
        }

        $userId = (int)$tokenData['user_id'];
        $db = static::db();

        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $db->prepare("UPDATE users SET password_hash = :hash, updated_at = CURRENT_TIMESTAMP WHERE id = :uid");
        $updated = $stmt->execute(['hash' => $hash, 'uid' => $userId]);

        if ($updated) {
            $db->prepare("UPDATE password_reset_tokens SET is_used = 1 WHERE id = :tid")
                ->execute(['tid' => $tokenData['id']]);
            return true;
        }

        return false;
    }
}
