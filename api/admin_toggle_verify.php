<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../controllers/AdminController.php';
startSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed.'], 405);
}

$id   = intval($_POST['id'] ?? 0);
$ctrl = new AdminController();
$ctrl->toggleVerify($id);
