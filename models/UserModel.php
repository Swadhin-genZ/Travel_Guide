<?php
require_once __DIR__ . '/../config/database.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([strtolower(trim($email))]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByRememberToken(string $token): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE remember_token = ? LIMIT 1");
        $stmt->execute([hash('sha256', $token)]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password_hash, role, is_verified)
             VALUES (:name, :email, :password_hash, :role, 0)"
        );
        return $stmt->execute([
            ':name'          => htmlspecialchars(trim($data['name']), ENT_QUOTES, 'UTF-8'),
            ':email'         => strtolower(trim($data['email'])),
            ':password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':role'          => $data['role'],
        ]);
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $params = [':id' => $id];

        if (!empty($data['name'])) {
            $fields[] = "name = :name";
            $params[':name'] = htmlspecialchars(trim($data['name']), ENT_QUOTES, 'UTF-8');
        }
        if (!empty($data['email'])) {
            $fields[] = "email = :email";
            $params[':email'] = strtolower(trim($data['email']));
        }
        if (!empty($data['password_hash'])) {
            $fields[] = "password_hash = :password_hash";
            $params[':password_hash'] = $data['password_hash'];
        }
        if (isset($data['profile_picture'])) {
            $fields[] = "profile_picture = :profile_picture";
            $params[':profile_picture'] = $data['profile_picture'];
        }

        if (empty($fields)) return false;

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function setRememberToken(int $id, string $token): void {
        $stmt = $this->db->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
        $stmt->execute([hash('sha256', $token), $id]);
    }

    public function clearRememberToken(int $id): void {
        $stmt = $this->db->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function emailExists(string $email, int $excludeId = 0): bool {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([strtolower(trim($email)), $excludeId]);
        return (bool)$stmt->fetch();
    }

    public function getLatestApprovedPosts(int $limit = 6): array {
        $stmt = $this->db->prepare(
            "SELECT p.*, u.name AS scout_name
             FROM posts p
             JOIN users u ON p.scout_id = u.id
             WHERE p.status = 'approved'
             ORDER BY p.created_at DESC
             LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
