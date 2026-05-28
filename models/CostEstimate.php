<?php
// [TASK 4] Cost Estimates model
class CostEstimate {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getByPost($postId) {
        $stmt = $this->conn->prepare("SELECT * FROM cost_estimates WHERE post_id=?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function upsert($postId, $baseCost, $currency = 'USD') {
        $stmt = $this->conn->prepare("INSERT INTO cost_estimates (post_id, base_cost, currency) VALUES (?,?,?) ON DUPLICATE KEY UPDATE base_cost=?, currency=?");
        $stmt->bind_param("idsds", $postId, $baseCost, $currency, $baseCost, $currency);
        return $stmt->execute();
    }
}