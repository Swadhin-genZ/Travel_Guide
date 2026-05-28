<?php
// [TASK 4] AJAX: Delete own comment
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';
require_once '../models/Comment.php';

if (!isset($_SESSION['user_id'])) { echo json_encode(['success' => false]); exit; }

$data = json_decode(file_get_contents('php://input'), true);
$id   = intval($data['id'] ?? 0);

$model = new Comment($conn);
// Admin can delete any; user only their own
$userId = ($_SESSION['role'] === 'admin') ? null : $_SESSION['user_id'];
$model->delete($id, $userId);
echo json_encode(['success' => true]);