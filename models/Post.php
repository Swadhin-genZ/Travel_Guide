<?php
// [TASK 3, 4] Posts model
class Post {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getApproved() {
        $result = $this->conn->query("SELECT p.*, u.name as scout_name FROM posts p JOIN users u ON p.scout_id=u.id WHERE p.status='approved' ORDER BY p.created_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getLatestApproved($limit = 6) {
        $stmt = $this->conn->prepare("SELECT * FROM posts WHERE status='approved' ORDER BY created_at DESC LIMIT ?");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT p.*, u.name as scout_name FROM posts p JOIN users u ON p.scout_id=u.id WHERE p.id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAll() {
        $result = $this->conn->query("SELECT p.*, u.name as scout_name FROM posts p JOIN users u ON p.scout_id=u.id ORDER BY p.created_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $title, $history, $country, $genre, $cost_level, $travel_info) {
        $stmt = $this->conn->prepare("UPDATE posts SET title=?, short_history=?, country=?, genre=?, cost_level=?, travel_medium_info=?, updated_at=NOW() WHERE id=?");
        $stmt->bind_param("ssssssi", $title, $history, $country, $genre, $cost_level, $travel_info, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM posts WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function createFromRequest($scoutId, $data, $imagePath) {
        $stmt = $this->conn->prepare("INSERT INTO posts (scout_id, title, short_history, country, genre, cost_level, travel_medium_info, image_path, status) VALUES (?,?,?,?,?,?,?,?,'approved')");
        $stmt->bind_param("isssssss", $scoutId, $data['title'], $data['short_history'], $data['country'], $data['genre'], $data['cost_level'], $data['travel_medium_info'], $imagePath);
        return $stmt->execute();
    }

    public function count() {
        return $this->conn->query("SELECT COUNT(*) as c FROM posts")->fetch_assoc()['c'];
    }

    public function searchByTitle($q) {
        $like = "%$q%";
        $stmt = $this->conn->prepare("SELECT * FROM posts WHERE status='approved' AND (title LIKE ? OR country LIKE ?)");
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function filter($country, $genre, $cost) {
        $sql = "SELECT * FROM posts WHERE status='approved'";
        $params = []; $types = "";
        if ($country) { $sql .= " AND country=?"; $params[] = $country; $types .= "s"; }
        if ($genre)   { $sql .= " AND genre=?";   $params[] = $genre;   $types .= "s"; }
        if ($cost)    { $sql .= " AND cost_level=?"; $params[] = $cost;  $types .= "s"; }
        $stmt = $this->conn->prepare($sql);
        if ($params) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getByScout($scoutId) {
        $stmt = $this->conn->prepare("SELECT * FROM posts WHERE scout_id=? AND status='approved'");
        $stmt->bind_param("i", $scoutId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}