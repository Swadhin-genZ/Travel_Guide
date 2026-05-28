<?php
// [TASK 4] AJAX: Filter posts
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';
require_once '../models/Post.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['verified']) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit;
}

$country = trim($_GET['country'] ?? '');
$genre   = trim($_GET['genre'] ?? '');
$cost    = trim($_GET['cost'] ?? '');

$model = new Post($conn);
$posts = $model->filter($country ?: null, $genre ?: null, $cost ?: null);
echo json_encode(['success' => true, 'posts' => $posts]);