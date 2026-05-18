<?php
//Wishlist Controller
require_once 'models/Wishlist.php';

class WishlistController {
    public static function index() {
        global $conn;
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user' || !$_SESSION['verified']) {
            header("Location: index.php?action=login"); exit;
        }
        $wishlistModel = new Wishlist($conn);
        $items = $wishlistModel->getByUser($_SESSION['user_id']);
        require 'views/wishlist/index.php';
    }
}