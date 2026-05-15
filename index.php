<?php
session_start();
define('ROOT', __DIR__);

require_once ROOT . '/config/database.php';
require_once ROOT . '/models/User.php';
require_once ROOT . '/models/Wishlist.php';
require_once ROOT . '/controllers/AuthController.php';
require_once ROOT . '/controllers/ProfileController.php';
require_once ROOT . '/controllers/WishlistController.php';

$userModel     = new UserModel($conn);
$wishlistModel = new WishlistModel($conn);

// Auto-login via Remember Me cookie
if (empty($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $user = $userModel->findByToken($_COOKIE['remember_token']);
    if ($user) {
        $_SESSION['user_id']     = $user['id'];
        $_SESSION['name']        = $user['name'];
        $_SESSION['role']        = $user['role'];
        $_SESSION['is_verified'] = $user['is_verified'];
    }
}

$page   = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$auth   = new AuthController($userModel);

// Pages anyone can access
if ($page === 'register') { $method==='POST' ? $auth->register() : $auth->showRegister(); exit; }
if ($page === 'login')    { $method==='POST' ? $auth->login()    : $auth->showLogin();    exit; }
if ($page === 'logout')   { $auth->logout(); exit; }

// Home page — anyone can see it (guest, unverified, verified)
if ($page === 'home') {
    $posts = !empty($_SESSION['is_verified']) ? $userModel->getApprovedPosts(6) : [];
    require ROOT . '/views/home.php'; exit;
}

// Everything below needs login
if (empty($_SESSION['user_id'])) {
    header('Location: index.php?page=login'); exit;
}

if ($page === 'profile') {
    $p = new ProfileController($userModel);
    $method==='POST' ? $p->update() : $p->show(); exit;
}

if ($page === 'wishlist') {
    if ($_SESSION['role']!=='user' || !$_SESSION['is_verified']) {
        header('Location: index.php?page=home'); exit;
    }
    (new WishlistController($wishlistModel))->show(); exit;
}

if ($page === 'api_wishlist') {
    if ($_SESSION['role']!=='user' || !$_SESSION['is_verified']) {
        header('Content-Type: application/json');
        echo json_encode(['success'=>false,'message'=>'Unauthorized.']); exit;
    }
    $w = new WishlistController($wishlistModel);
    if ($action==='add')    $w->add();
    if ($action==='remove') $w->remove();
    exit;
}

header('Location: index.php?page=home');