<?php
// [TASK 3] AJAX: Toggle user verification
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';
require_once '../models/User.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false]); exit;
}

$data   = json_decode(file_get_contents('php://input'), true);
$id     = intval($data['id'] ?? 0);
$status = intval($data['status'] ?? 0);

$model = new User($conn);
$model->toggleVerify($id, $status);
echo json_encode(['success' => true, 'new_status' => $status]);