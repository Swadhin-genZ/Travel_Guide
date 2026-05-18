<?php
//Authentication Controller
require_once 'models/User.php';

class AuthController {

    // Home page
    public static function home() {
        global $conn;
        $user = null;
        $posts = [];

        // auto-login
        if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
            $userModel = new User($conn);
            $tokenHash = hash('sha256', $_COOKIE['remember_token']);
            $user = $userModel->findByRememberToken($tokenHash);
            if ($user) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['name']      = $user['name'];
                $_SESSION['role']      = $user['role'];
                $_SESSION['verified']  = $user['is_verified'];
            }
        }

        if (isset($_SESSION['user_id'])) {
            $user = ['name' => $_SESSION['name'], 'role' => $_SESSION['role'], 'verified' => $_SESSION['verified']];
            if ($_SESSION['verified']) {
                require_once 'models/Post.php';
                $postModel = new Post($conn);
                $posts = $postModel->getLatestApproved(6);
            }
        }
        require 'views/home/index.php';
    }

    // Register
    public static function register() {
        global $conn;
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name  = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $pass  = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            $role  = $_POST['role'] ?? 'user';

            if (!$name)                          $errors[] = "Name is required.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
            if (strlen($pass) < 8)               $errors[] = "Password must be at least 8 characters.";
            if ($pass !== $confirm)              $errors[] = "Passwords do not match.";
            if (!in_array($role, ['admin','scout','user'])) $errors[] = "Invalid role.";

            if (!$errors) {
                $userModel = new User($conn);
                if ($userModel->findByEmail($email)) {
                    $errors[] = "Email already registered.";
                } else {
                    $hash = password_hash($pass, PASSWORD_BCRYPT);
                    $userModel->create($name, $email, $hash, $role);
                    $_SESSION['flash'] = "Registration successful! Please wait for admin approval.";
                    header("Location: index.php?action=login");
                    exit;
                }
            }
        }
        require 'views/auth/register.php';
    }

    // Login
    public static function login() {
        global $conn;
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email  = trim($_POST['email'] ?? '');
            $pass   = $_POST['password'] ?? '';
            $remember = isset($_POST['remember']);

            if (!$email || !$pass) { $errors[] = "All fields required."; }

            if (!$errors) {
                $userModel = new User($conn);
                $user = $userModel->findByEmail($email);
                if ($user && password_verify($pass, $user['password_hash'])) {
                    $_SESSION['user_id']  = $user['id'];
                    $_SESSION['name']     = $user['name'];
                    $_SESSION['role']     = $user['role'];
                    $_SESSION['verified'] = $user['is_verified'];

                    if ($remember) {
                        $token = bin2hex(random_bytes(32));
                        $tokenHash = hash('sha256', $token);
                        $userModel->setRememberToken($user['id'], $tokenHash);
                        setcookie('remember_token', $token, time() + 86400 * 30, '/', '', false, true);
                    }
                    header("Location: index.php?action=home");
                    exit;
                } else {
                    $errors[] = "Invalid email or password.";
                }
            }
        }
        require 'views/auth/login.php';
    }

    // Logout
    public static function logout() {
        global $conn;
        if (isset($_SESSION['user_id'])) {
            $userModel = new User($conn);
            $userModel->clearRememberToken($_SESSION['user_id']);
        }
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/');
        header("Location: index.php?action=login");
        exit;
    }

    // Profile
    public static function profile() {
        global $conn;
        if (!isset($_SESSION['user_id'])) { header("Location: index.php?action=login"); exit; }

        $userModel = new User($conn);
        $user = $userModel->findById($_SESSION['user_id']);
        $errors = []; $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name  = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if (!$name) $errors[] = "Name required.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";

            $picturePath = $user['profile_picture'];
            if (!empty($_FILES['profile_picture']['name'])) {
                $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
                $mime = mime_content_type($_FILES['profile_picture']['tmp_name']);
                if (!in_array($mime, $allowed))    $errors[] = "Invalid image type.";
                if ($_FILES['profile_picture']['size'] > 2 * 1024 * 1024) $errors[] = "Image too large (max 2MB).";
                if (!$errors) {
                    $ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
                    $picturePath = 'public/uploads/profiles/' . uniqid() . '.' . $ext;
                    move_uploaded_file($_FILES['profile_picture']['tmp_name'], $picturePath);
                }
            }

            if (!$errors) {
                $userModel->updateProfile($user['id'], $name, $email, $picturePath);
                // Change password
                if (!empty($_POST['new_password'])) {
                    if (!password_verify($_POST['current_password'], $user['password_hash'])) {
                        $errors[] = "Current password incorrect.";
                    } elseif (strlen($_POST['new_password']) < 8) {
                        $errors[] = "New password too short.";
                    } else {
                        $userModel->updatePassword($user['id'], password_hash($_POST['new_password'], PASSWORD_BCRYPT));
                    }
                }
                if (!$errors) {
                    $success = "Profile updated successfully.";
                    $_SESSION['name'] = $name;
                    $user = $userModel->findById($_SESSION['user_id']);
                }
            }
        }
        require 'views/profile/index.php';
    }
}