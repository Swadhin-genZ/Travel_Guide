<?php
// [TASK 3] Admin Controller
require_once 'models/User.php';
require_once 'models/Post.php';
require_once 'models/PostRequest.php';
require_once 'models/Comment.php';

class AdminController {

    private static function gate() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?action=login"); exit;
        }
    }

    public static function dashboard() {
        self::gate();
        global $conn;
        $userModel = new User($conn);
        $postModel = new Post($conn);
        $prModel   = new PostRequest($conn);
        $cmtModel  = new Comment($conn);

        $userCounts    = $userModel->getCounts();
        $totalPosts    = $postModel->count();
        $pendingReqs   = $prModel->countPending();
        $totalComments = $cmtModel->count();
        $pendingRequests = $prModel->getPending();

        require 'views/admin/dashboard.php';
    }

    public static function users() {
        self::gate();
        global $conn;
        $userModel = new User($conn);
        $users = $userModel->getAll();
        require 'views/admin/users.php';
    }

    public static function addUser() {
        self::gate();
        global $conn;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name  = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $pass  = $_POST['password'] ?? '';
            $role  = $_POST['role'] ?? 'user';
            $errors = [];

            if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 8) $errors[] = "Invalid input.";
            if (!in_array($role, ['scout','user'])) $errors[] = "Invalid role.";

            if (!$errors) {
                $userModel = new User($conn);
                if ($userModel->findByEmail($email)) {
                    $_SESSION['flash_error'] = "Email already exists.";
                } else {
                    $hash = password_hash($pass, PASSWORD_BCRYPT);
                    $userModel->createVerified($name, $email, $hash, $role);
                    $_SESSION['flash'] = "User created.";
                }
            }
        }
        header("Location: index.php?action=admin_users"); exit;
    }

    public static function deleteUser() {
        self::gate();
        global $conn;
        $id = intval($_POST['id'] ?? 0);
        if ($id === $_SESSION['user_id']) { $_SESSION['flash_error'] = "Cannot delete yourself."; header("Location: index.php?action=admin_users"); exit; }
        $userModel = new User($conn);
        $userModel->delete($id);
        $_SESSION['flash'] = "User deleted.";
        header("Location: index.php?action=admin_users"); exit;
    }

    public static function posts() {
        self::gate();
        global $conn;
        $postModel = new Post($conn);
        $prModel   = new PostRequest($conn);
        $posts   = $postModel->getAll();
        $pending = $prModel->getPending();
        require 'views/admin/posts.php';
    }

    public static function editPost() {
        self::gate();
        global $conn;
        $id = intval($_GET['id'] ?? 0);
        $postModel = new Post($conn);
        $post = $postModel->getById($id);
        $errors = []; $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title       = trim($_POST['title'] ?? '');
            $history     = trim($_POST['short_history'] ?? '');
            $country     = trim($_POST['country'] ?? '');
            $genre       = $_POST['genre'] ?? '';
            $cost_level  = $_POST['cost_level'] ?? '';
            $travel_info = trim($_POST['travel_medium_info'] ?? '');
            if (!$title || !$history || !$country) $errors[] = "Required fields missing.";
            if (!$errors) {
                $postModel->update($id, $title, $history, $country, $genre, $cost_level, $travel_info);
                $success = "Post updated.";
                $post = $postModel->getById($id);
            }
        }
        require 'views/admin/edit_post.php';
    }

    public static function deletePost() {
        self::gate();
        global $conn;
        $id = intval($_POST['id'] ?? 0);
        $postModel = new Post($conn);
        $postModel->delete($id);
        $_SESSION['flash'] = "Post deleted.";
        header("Location: index.php?action=admin_posts"); exit;
    }

    public static function comments() {
        self::gate();
        global $conn;
        $cmtModel = new Comment($conn);
        $comments = $cmtModel->getAll();
        require 'views/admin/comments.php';
    }
}