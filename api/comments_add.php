<?php
// [TASK 4] AJAX: Add comment
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';
require_once '../models/Comment.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user' || !$_SESSION['verified']) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit;
}

$data    = json_decode(file_get_contents('php://input'), true);
$postId  = intval($data['post_id'] ?? 0);
$content = trim($data['content'] ?? '');

if (!$postId || !$content) { echo json_encode(['success' => false, 'message' => 'Content required']); exit; }
$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

$model = new Comment($conn);
$model->add($postId, $_SESSION['user_id'], $content);
$id = $model->getLastId();

echo json_encode(['success' => true, 'comment' => [
    'id'        => $id,
    'user_name' => $_SESSION['name'],
    'content'   => $content,
    'created_at'=> date('Y-m-d H:i:s')
]]);