<?php
// [TASK 3] AJAX: Approve or reject post request
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';
require_once '../models/PostRequest.php';
require_once '../models/Post.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false]); exit;
}

$data   = json_decode(file_get_contents('php://input'), true);
$id     = intval($data['id'] ?? 0);
$action = $data['action'] ?? ''; // 'approve' or 'reject'
$reason = trim($data['reason'] ?? '');

$prModel = new PostRequest($conn);
$req = $prModel->getById($id);
if (!$req) { echo json_encode(['success' => false, 'message' => 'Not found']); exit; }

if ($action === 'approve') {
    $postData = json_decode($req['post_data'], true);
    $postModel = new Post($conn);
    $postModel->createFromRequest($req['scout_id'], $postData, $req['image_path']);
    $prModel->updateStatus($id, 'approved');
    echo json_encode(['success' => true]);
} elseif ($action === 'reject') {
    $prModel->updateStatus($id, 'rejected', $reason);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}