<?php
session_start();
require_once 'config/db.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    // Auth
    case 'home':         require 'controllers/AuthController.php'; AuthController::home(); break;
    case 'login':        require 'controllers/AuthController.php'; AuthController::login(); break;
    case 'register':     require 'controllers/AuthController.php'; AuthController::register(); break;
    case 'logout':       require 'controllers/AuthController.php'; AuthController::logout(); break;
    case 'profile':      require 'controllers/AuthController.php'; AuthController::profile(); break;

    // Wishlist
    case 'wishlist':     require 'controllers/WishlistController.php'; WishlistController::index(); break;

    // Scout
    case 'scout_dashboard':     require 'controllers/ScoutController.php'; ScoutController::dashboard(); break;
    case 'scout_create':        require 'controllers/ScoutController.php'; ScoutController::createRequest(); break;
    case 'scout_my_requests':   require 'controllers/ScoutController.php'; ScoutController::myRequests(); break;
    case 'scout_edit':          require 'controllers/ScoutController.php'; ScoutController::editRequest(); break;

    // Admin
    case 'admin_dashboard':     require 'controllers/AdminController.php'; AdminController::dashboard(); break;
    case 'admin_users':         require 'controllers/AdminController.php'; AdminController::users(); break;
    case 'admin_add_user':      require 'controllers/AdminController.php'; AdminController::addUser(); break;
    case 'admin_delete_user':   require 'controllers/AdminController.php'; AdminController::deleteUser(); break;
    case 'admin_posts':         require 'controllers/AdminController.php'; AdminController::posts(); break;
    case 'admin_edit_post':     require 'controllers/AdminController.php'; AdminController::editPost(); break;
    case 'admin_delete_post':   require 'controllers/AdminController.php'; AdminController::deletePost(); break;
    case 'admin_comments':      require 'controllers/AdminController.php'; AdminController::comments(); break;

    // General User
    case 'browse':       require 'controllers/UserController.php'; UserController::browse(); break;
    case 'post_detail':  require 'controllers/UserController.php'; UserController::postDetail(); break;

    default:
        http_response_code(404);
        echo "Page not found.";
}