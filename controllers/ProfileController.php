<?php
require_once __DIR__ . '/../models/UserModel.php';

class ProfileController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function show(): void {
        $this->requireAuth();
        $user    = $this->userModel->findById($_SESSION['user_id']);
        $error   = $_SESSION['flash_error'] ?? null;
        $success = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
        require __DIR__ . '/../views/profile/profile.php';
    }

    public function update(): void {
        $this->requireAuth();

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['flash_error'] = 'Invalid request.';
            header('Location: /profile');
            exit;
        }

        $user   = $this->userModel->findById($_SESSION['user_id']);
        $errors = [];
        $data   = [];

        // Name
        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            $errors[] = 'Name is required.';
        } else {
            $data['name'] = $name;
        }

        // Email
        $email = trim($_POST['email'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required.';
        } elseif ($this->userModel->emailExists($email, $_SESSION['user_id'])) {
            $errors[] = 'Email already used by another account.';
        } else {
            $data['email'] = $email;
        }

        // Password change (optional)
        $currentPw  = $_POST['current_password'] ?? '';
        $newPw      = $_POST['new_password'] ?? '';
        $confirmPw  = $_POST['confirm_password'] ?? '';

        if (!empty($newPw)) {
            if (!password_verify($currentPw, $user['password_hash'])) {
                $errors[] = 'Current password is incorrect.';
            } elseif (strlen($newPw) < 8) {
                $errors[] = 'New password must be at least 8 characters.';
            } elseif ($newPw !== $confirmPw) {
                $errors[] = 'New passwords do not match.';
            } else {
                $data['password_hash'] = password_hash($newPw, PASSWORD_BCRYPT);
            }
        }

        // Profile picture upload
        if (!empty($_FILES['profile_picture']['name'])) {
            $file     = $_FILES['profile_picture'];
            $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize  = 2 * 1024 * 1024; // 2MB

            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, $allowed)) {
                $errors[] = 'Profile picture must be JPEG, PNG, GIF or WEBP.';
            } elseif ($file['size'] > $maxSize) {
                $errors[] = 'Profile picture must be under 2MB.';
            } else {
                $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'profile_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
                $dest     = __DIR__ . '/../public/uploads/profiles/' . $filename;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $data['profile_picture'] = 'public/uploads/profiles/' . $filename;
                } else {
                    $errors[] = 'Failed to upload profile picture.';
                }
            }
        }

        if ($errors) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            header('Location: /profile');
            exit;
        }

        $this->userModel->update($_SESSION['user_id'], $data);

        // Update session name
        if (!empty($data['name'])) $_SESSION['name'] = $data['name'];

        $_SESSION['flash_success'] = 'Profile updated successfully!';
        header('Location: /profile');
        exit;
    }

    private function requireAuth(): void {
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }
}
