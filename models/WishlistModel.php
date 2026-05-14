<?php
require_once __DIR__ . '/../config/database.php';

class WishlistModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT w.*, p.title, p.country, p.cost_level, p.genre, p.image_path
             FROM wishlist w
             JOIN posts p ON w.post_id = p.id
             WHERE w.user_id = ?
             ORDER BY w.added_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function add(int $userId, int $postId): bool {
        // Check already exists
        $stmt = $this->db->prepare("SELECT id FROM wishlist WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$userId, $postId]);
        if ($stmt->fetch()) return false; // already added

        $stmt = $this->db->prepare("INSERT INTO wishlist (user_id, post_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $postId]);
    }

    public function remove(int $userId, int $postId): bool {
        $stmt = $this->db->prepare("DELETE FROM wishlist WHERE user_id = ? AND post_id = ?");
        return $stmt->execute([$userId, $postId]);
    }

    public function exists(int $userId, int $postId): bool {
        $stmt = $this->db->prepare("SELECT id FROM wishlist WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$userId, $postId]);
        return (bool)$stmt->fetch();
    }
}
