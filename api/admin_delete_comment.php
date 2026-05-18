<?php
// [TASK 3] AJAX: Admin delete any comment
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';
require_once '../models/Comment.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false]); exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id   = intval($data['id'] ?? 0);
$model = new Comment($conn);
$model->delete($id);
echo json_encode(['success' => true]);