<?php
// [TASK 2, 3] Post Requests model
class PostRequest {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($scoutId, $postData, $imagePath, $originalPostId = null) {
        $json = json_encode($postData);
        $stmt = $this->conn->prepare("INSERT INTO post_requests (scout_id, original_post_id, post_data, image_path, status) VALUES (?,?,?,?,'pending')");
        $stmt->bind_param("iiss", $scoutId, $originalPostId, $json, $imagePath);
        return $stmt->execute();
    }

    public function getByScout($scoutId) {
        $stmt = $this->conn->prepare("SELECT * FROM post_requests WHERE scout_id=? ORDER BY requested_at DESC");
        $stmt->bind_param("i", $scoutId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM post_requests WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($id, $scoutId, $postData, $imagePath) {
        $json = json_encode($postData);
        if ($imagePath) {
            $stmt = $this->conn->prepare("UPDATE post_requests SET post_data=?, image_path=? WHERE id=? AND scout_id=? AND status='pending'");
            $stmt->bind_param("ssii", $json, $imagePath, $id, $scoutId);
        } else {
            $stmt = $this->conn->prepare("UPDATE post_requests SET post_data=? WHERE id=? AND scout_id=? AND status='pending'");
            $stmt->bind_param("sii", $json, $id, $scoutId);
        }
        return $stmt->execute();
    }

    public function delete($id, $scoutId) {
        $stmt = $this->conn->prepare("DELETE FROM post_requests WHERE id=? AND scout_id=? AND status='pending'");
        $stmt->bind_param("ii", $id, $scoutId);
        return $stmt->execute();
    }

    public function getPending() {
        $result = $this->conn->query("SELECT pr.*, u.name as scout_name FROM post_requests pr JOIN users u ON pr.scout_id=u.id WHERE pr.status='pending' ORDER BY pr.requested_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateStatus($id, $status, $reason = null) {
        $stmt = $this->conn->prepare("UPDATE post_requests SET status=?, reject_reason=? WHERE id=?");
        $stmt->bind_param("ssi", $status, $reason, $id);
        return $stmt->execute();
    }

    public function countPending() {
        return $this->conn->query("SELECT COUNT(*) as c FROM post_requests WHERE status='pending'")->fetch_assoc()['c'];
    }
}