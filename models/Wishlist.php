<?php
//Wishlist model
class Wishlist {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function add($userId, $postId) {
        $stmt = $this->conn->prepare("INSERT IGNORE INTO wishlist (user_id, post_id) VALUES (?,?)");
        $stmt->bind_param("ii", $userId, $postId);
        return $stmt->execute();
    }

    public function remove($userId, $postId) {
        $stmt = $this->conn->prepare("DELETE FROM wishlist WHERE user_id=? AND post_id=?");
        $stmt->bind_param("ii", $userId, $postId);
        return $stmt->execute();
    }

    public function getByUser($userId) {
        $stmt = $this->conn->prepare("SELECT w.*, p.title, p.country, p.cost_level, p.genre FROM wishlist w JOIN posts p ON w.post_id=p.id WHERE w.user_id=? ORDER BY w.added_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function exists($userId, $postId) {
        $stmt = $this->conn->prepare("SELECT id FROM wishlist WHERE user_id=? AND post_id=?");
        $stmt->bind_param("ii", $userId, $postId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
}