<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/PostRequestModel.php';
require_once __DIR__ . '/../models/OtherModels.php';

class AdminController {
    private $userModel;
    private $postModel;
    private $prModel;
    private $commentModel;

    public function __construct() {
        startSession();
        $this->userModel    = new UserModel();
        $this->postModel    = new PostModel();
        $this->prModel      = new PostRequestModel();
        $this->commentModel = new CommentModel();
    }

    private function gateCheck() {
        requireRole('admin');
    }

    public function getDashboardStats() {
        $this->gateCheck();
        return [
            'user_counts'    => $this->userModel->counts(),
            'pending_reqs'   => $this->prModel->countPending(),
            'total_posts'    => $this->postModel->countAll(),
            'total_comments' => $this->commentModel->countAll(),
        ];
    }

    // User management
    public function addUser() {
        $this->gateCheck();
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) { $errors[] = 'Invalid token.'; return $errors; }
            $name     = trim($_POST['name'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role     = $_POST['role'] ?? 'user';
            $verified = isset($_POST['is_verified']) ? 1 : 0;

            if (empty($name))    $errors[] = 'Name required.';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';
            if (strlen($password) < 8) $errors[] = 'Password min 8 chars.';
            if (!in_array($role, ['admin','scout','user'])) $errors[] = 'Invalid role.';

            if (empty($errors)) {
                if ($this->userModel->findByEmail($email)) {
                    $errors[] = 'Email already exists.';
                } else {
                    $this->userModel->adminCreate($name, $email, $password, $role, $verified);
                    flashMessage('success', 'User created successfully.');
                    header('Location: ' . BASE_URL . '/views/admin/users.php');
                    exit;
                }
            }
        }
        return $errors;
    }

    public function toggleVerify($userId) {
        $this->gateCheck();
        $this->userModel->toggleVerify($userId);
        // Reflect if it's the current user's session
        jsonResponse(['success' => true]);
    }

    public function deleteUser($userId) {
        $this->gateCheck();
        if ($userId == $_SESSION['user_id']) {
            jsonResponse(['success' => false, 'message' => 'Cannot delete your own account.'], 403);
        }
        $this->userModel->delete($userId);
        jsonResponse(['success' => true]);
    }

    // Post moderation
    public function approveRequest($requestId) {
        $this->gateCheck();
        $req = $this->prModel->getById($requestId);
        if (!$req || $req['status'] !== 'pending') {
            jsonResponse(['success' => false, 'message' => 'Not found.'], 404);
        }
        $data = json_decode($req['post_data'], true);
        // Move to posts table
        $postId = $this->postModel->createFromRequest($req['scout_id'], $data);
        $this->prModel->approve($requestId);
        jsonResponse(['success' => true, 'post_id' => $postId]);
    }

    public function rejectRequest($requestId) {
        $this->gateCheck();
        $reason = trim($_POST['reason'] ?? '');
        $this->prModel->reject($requestId, $reason);
        jsonResponse(['success' => true]);
    }

    public function editPost($postId) {
        $this->gateCheck();
        $errors = [];
        $post = $this->postModel->getById($postId);
        if (!$post) {
            flashMessage('error', 'Post not found.');
            header('Location: ' . BASE_URL . '/views/admin/posts.php');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) { $errors[] = 'Invalid token.'; }
            else {
                $data = [
                    'title'              => trim($_POST['title'] ?? ''),
                    'short_history'      => trim($_POST['short_history'] ?? ''),
                    'country'            => trim($_POST['country'] ?? ''),
                    'genre'              => $_POST['genre'] ?? '',
                    'cost_level'         => $_POST['cost_level'] ?? '',
                    'travel_medium_info' => trim($_POST['travel_medium_info'] ?? ''),
                ];
                foreach ($data as $k => $v) {
                    if (empty($v)) $errors[] = ucfirst(str_replace('_', ' ', $k)) . ' is required.';
                }
                if (empty($errors)) {
                    $this->postModel->update($postId, $data);
                    flashMessage('success', 'Post updated.');
                    header('Location: ' . BASE_URL . '/views/admin/posts.php');
                    exit;
                }
            }
        }
        return ['errors' => $errors, 'post' => $post];
    }

    public function deletePost($postId) {
        $this->gateCheck();
        $this->postModel->delete($postId);
        jsonResponse(['success' => true]);
    }

    public function deleteComment($commentId) {
        $this->gateCheck();
        $this->commentModel->delete($commentId);
        jsonResponse(['success' => true]);
    }

    public function getAllUsers()    { $this->gateCheck(); return $this->userModel->getAll(); }
    public function getAllPosts()    { $this->gateCheck(); return $this->postModel->getAll(); }
    public function getPendingReqs() { $this->gateCheck(); return $this->prModel->getPending(); }
    public function getAllReqs()     { $this->gateCheck(); return $this->prModel->getAll(); }
    public function getAllComments() { $this->gateCheck(); return $this->commentModel->getAll(); }
}
