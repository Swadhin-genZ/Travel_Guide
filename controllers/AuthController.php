<?php
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // ── REGISTER ──────────────────────────────────────────────
    public function showRegister(): void {
        $error = $_SESSION['flash_error'] ?? null;
        $success = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
        require __DIR__ . '/../views/auth/register.php';
    }

    public function handleRegister(): void {
        // CSRF check
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['flash_error'] = 'Invalid request.';
            header('Location: /register');
            exit;
        }

        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $role     = $_POST['role'] ?? '';

        // Server-side validation
        $errors = [];
        if (empty($name))                          $errors[] = 'Name is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
        if (strlen($password) < 8)                 $errors[] = 'Password must be at least 8 characters.';
        if ($password !== $confirm)                $errors[] = 'Passwords do not match.';
        if (!in_array($role, ['admin','scout','user'])) $errors[] = 'Invalid role selected.';
        if ($this->userModel->emailExists($email)) $errors[] = 'Email already registered.';

        if ($errors) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            header('Location: /register');
            exit;
        }

        $this->userModel->create([
            'name'     => $name,
            'email'    => $email,
            'password' => $password,
            'role'     => $role,
        ]);

        $_SESSION['flash_success'] = 'Registration successful! Please wait for admin approval.';
        header('Location: /login');
        exit;
    }

    // ── LOGIN ─────────────────────────────────────────────────
    public function showLogin(): void {
        $error   = $_SESSION['flash_error'] ?? null;
        $success = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
        require __DIR__ . '/../views/auth/login.php';
    }

    public function handleLogin(): void {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['flash_error'] = 'Invalid request.';
            header('Location: /login');
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember_me']);

        if (empty($email) || empty($password)) {
            $_SESSION['flash_error'] = 'Email and password are required.';
            header('Location: /login');
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $_SESSION['flash_error'] = 'Invalid email or password.';
            header('Location: /login');
            exit;
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['is_verified'] = $user['is_verified'];

        // Remember Me
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $this->userModel->setRememberToken($user['id'], $token);
            setcookie('remember_token', $token, time() + (30 * 24 * 3600), '/', '', false, true);
        }

        header('Location: /home');
        exit;
    }

    // ── LOGOUT ────────────────────────────────────────────────
    public function handleLogout(): void {
        if (isset($_SESSION['user_id'])) {
            $this->userModel->clearRememberToken($_SESSION['user_id']);
        }
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/');
        header('Location: /login');
        exit;
    }
}
