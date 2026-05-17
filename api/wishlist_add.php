<?php
//AJAX: Add to wishlist
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';
require_once '../models/Wishlist.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user' || !$_SESSION['verified']) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit;
}

$data    = json_decode(file_get_contents('php://input'), true);
$postId  = intval($data['post_id'] ?? 0);

if (!$postId) { echo json_encode(['success' => false, 'message' => 'Invalid post']); exit; }

$model = new Wishlist($conn);
$model->add($_SESSION['user_id'], $postId);
echo json_encode(['success' => true, 'message' => 'Added to wishlist']);