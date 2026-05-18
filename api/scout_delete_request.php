<?php
// [TASK 2] AJAX: Delete pending request
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';
require_once '../models/PostRequest.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'scout') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id   = intval($data['id'] ?? 0);

$model = new PostRequest($conn);
$ok = $model->delete($id, $_SESSION['user_id']);
echo json_encode(['success' => $ok]);