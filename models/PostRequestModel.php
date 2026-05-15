<?php
require_once __DIR__ . '/../config/app.php';

class PostRequestModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function create($scoutId, $postData, $originalPostId = null) {
        $stmt = $this->db->prepare(
            "INSERT INTO post_requests (scout_id, post_data, original_post_id, status)
             VALUES (?, ?, ?, 'pending')"
        );
        $stmt->execute([$scoutId, json_encode($postData), $originalPostId]);
        return $this->db->lastInsertId();
    }

    public function getByScout($scoutId) {
        $stmt = $this->db->prepare(
            "SELECT pr.*, u.name AS scout_name FROM post_requests pr
             JOIN users u ON pr.scout_id = u.id
             WHERE pr.scout_id=? ORDER BY pr.requested_at DESC"
        );
        $stmt->execute([$scoutId]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare(
            "SELECT pr.*, u.name AS scout_name FROM post_requests pr
             JOIN users u ON pr.scout_id = u.id WHERE pr.id=?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $postData) {
        $stmt = $this->db->prepare(
            "UPDATE post_requests SET post_data=? WHERE id=? AND status='pending'"
        );
        $stmt->execute([json_encode($postData), $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM post_requests WHERE id=? AND status='pending'");
        $stmt->execute([$id]);
    }

    public function getPending() {
        $stmt = $this->db->query(
            "SELECT pr.*, u.name AS scout_name FROM post_requests pr
             JOIN users u ON pr.scout_id = u.id
             WHERE pr.status='pending' ORDER BY pr.requested_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function getAll() {
        $stmt = $this->db->query(
            "SELECT pr.*, u.name AS scout_name FROM post_requests pr
             JOIN users u ON pr.scout_id = u.id ORDER BY pr.requested_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function approve($id) {
        $stmt = $this->db->prepare("UPDATE post_requests SET status='approved' WHERE id=?");
        $stmt->execute([$id]);
    }

    public function reject($id, $reason = '') {
        $stmt = $this->db->prepare(
            "UPDATE post_requests SET status='rejected', rejection_reason=? WHERE id=?"
        );
        $stmt->execute([$reason, $id]);
    }

    public function countPending() {
        return $this->db->query("SELECT COUNT(*) FROM post_requests WHERE status='pending'")->fetchColumn();
    }
}
