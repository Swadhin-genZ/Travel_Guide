<?php
// [TASK 4] General User Controller
require_once 'models/Post.php';
require_once 'models/Comment.php';
require_once 'models/CostEstimate.php';
require_once 'models/Wishlist.php';

class UserController {

    public static function browse() {
        global $conn;
        if (!isset($_SESSION['user_id']) || !$_SESSION['verified']) {
            header("Location: index.php?action=login"); exit;
        }
        $postModel = new Post($conn);
        $posts = $postModel->getApproved();

        // Get unique countries and genres for filters
        $countries = array_unique(array_column($posts, 'country'));
        sort($countries);
        require 'views/user/browse.php';
    }

    public static function postDetail() {
        global $conn;
        if (!isset($_SESSION['user_id']) || !$_SESSION['verified']) {
            header("Location: index.php?action=login"); exit;
        }
        $id = intval($_GET['id'] ?? 0);
        $postModel = new Post($conn);
        $post = $postModel->getById($id);
        if (!$post || $post['status'] !== 'approved') { echo "Post not found."; exit; }

        $cmtModel  = new Comment($conn);
        $comments  = $cmtModel->getByPost($id);

        $costModel = new CostEstimate($conn);
        $costEstimate = $costModel->getByPost($id);

        // Derive base cost from cost_level if no estimate in DB
        if (!$costEstimate) {
            $map = ['low' => 500, 'medium' => 1500, 'high' => 3000];
            $baseCost = $map[$post['cost_level']] ?? 500;
        } else {
            $baseCost = $costEstimate['base_cost'];
        }

        $wishlistModel = new Wishlist($conn);
        $inWishlist = false;
        if ($_SESSION['role'] === 'user') {
            $inWishlist = $wishlistModel->exists($_SESSION['user_id'], $id);
        }

        require 'views/user/post_detail.php';
    }
}