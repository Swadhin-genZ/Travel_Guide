<?php
session_start();

// Auto-login via Remember Me cookie
if (empty($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    require_once __DIR__ . '/models/UserModel.php';
    $userModel = new UserModel();
    $user = $userModel->findByRememberToken($_COOKIE['remember_token']);
    if ($user) {
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['name']       = $user['name'];
        $_SESSION['role']       = $user['role'];
        $_SESSION['is_verified'] = $user['is_verified'];
    }
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// ── Routes ────────────────────────────────────────────────────
switch (true) {

    // Home
    case $uri === '/' || $uri === '/home':
        require_once __DIR__ . '/controllers/HomeController.php';
        (new HomeController())->index();
        break;

    // Register
    case $uri === '/register' && $method === 'GET':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->showRegister();
        break;
    case $uri === '/register' && $method === 'POST':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->handleRegister();
        break;

    // Login
    case $uri === '/login' && $method === 'GET':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->showLogin();
        break;
    case $uri === '/login' && $method === 'POST':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->handleLogin();
        break;

    // Logout
    case $uri === '/logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->handleLogout();
        break;

    // Profile
    case $uri === '/profile' && $method === 'GET':
        require_once __DIR__ . '/controllers/ProfileController.php';
        (new ProfileController())->show();
        break;
    case $uri === '/profile' && $method === 'POST':
        require_once __DIR__ . '/controllers/ProfileController.php';
        (new ProfileController())->update();
        break;

    // Wishlist page
    case $uri === '/wishlist' && $method === 'GET':
        require_once __DIR__ . '/controllers/WishlistController.php';
        (new WishlistController())->index();
        break;

    // Wishlist AJAX endpoints
    case $uri === '/api/wishlist/add' && $method === 'POST':
        require_once __DIR__ . '/controllers/WishlistController.php';
        (new WishlistController())->add();
        break;
    case $uri === '/api/wishlist/remove' && $method === 'DELETE':
        require_once __DIR__ . '/controllers/WishlistController.php';
        (new WishlistController())->remove();
        break;

    // 404
    default:
        http_response_code(404);
        echo '<h1>404 - Page Not Found</h1>';
        break;
}
