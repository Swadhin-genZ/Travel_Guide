<?php
// [TASK 4, 3] Comments model
class Comment {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getByPost($postId) {
        $stmt = $this->conn->prepare("SELECT c.*, u.name as user_name FROM comments c JOIN users u ON c.user_id=u.id WHERE c.post_id=? ORDER BY c.created_at DESC");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function add($postId, $userId, $content) {
        $stmt = $this->conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?,?,?)");
        $stmt->bind_param("iis", $postId, $userId, $content);
        return $stmt->execute();
    }

    public function getLastId() {
        return $this->conn->insert_id;
    }

    public function delete($id, $userId = null) {
        if ($userId) {
            $stmt = $this->conn->prepare("DELETE FROM comments WHERE id=? AND user_id=?");
            $stmt->bind_param("ii", $id, $userId);
        } else {
            $stmt = $this->conn->prepare("DELETE FROM comments WHERE id=?");
            $stmt->bind_param("i", $id);
        }
        return $stmt->execute();
    }

    public function getAll() {
        $result = $this->conn->query("SELECT c.*, u.name as user_name, p.title as post_title FROM comments c JOIN users u ON c.user_id=u.id JOIN posts p ON c.post_id=p.id ORDER BY c.created_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function count() {
        return $this->conn->query("SELECT COUNT(*) as c FROM comments")->fetch_assoc()['c'];
    }
}