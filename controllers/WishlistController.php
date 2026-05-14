<?php
require_once __DIR__ . '/../models/WishlistModel.php';

class WishlistController {
    private $wishlistModel;

    public function __construct() {
        $this->wishlistModel = new WishlistModel();
    }

    public function index(): void {
        $this->requireVerifiedUser();
        $items = $this->wishlistModel->getByUser($_SESSION['user_id']);
        require __DIR__ . '/../views/wishlist/wishlist.php';
    }

    // AJAX: POST /api/wishlist/add
    public function add(): void {
        header('Content-Type: application/json');
        $this->requireVerifiedUserJson();

        $data   = json_decode(file_get_contents('php://input'), true);
        $postId = (int)($data['post_id'] ?? 0);

        if ($postId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid post ID.']);
            exit;
        }

        $result = $this->wishlistModel->add($_SESSION['user_id'], $postId);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Added to wishlist.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Already in wishlist.']);
        }
        exit;
    }

    // AJAX: DELETE /api/wishlist/remove
    public function remove(): void {
        header('Content-Type: application/json');
        $this->requireVerifiedUserJson();

        $data   = json_decode(file_get_contents('php://input'), true);
        $postId = (int)($data['post_id'] ?? 0);

        if ($postId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid post ID.']);
            exit;
        }

        $result = $this->wishlistModel->remove($_SESSION['user_id'], $postId);
        echo json_encode(['success' => $result, 'message' => $result ? 'Removed from wishlist.' : 'Not found.']);
        exit;
    }

    private function requireVerifiedUser(): void {
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user' || !$_SESSION['is_verified']) {
            header('Location: /login');
            exit;
        }
    }

    private function requireVerifiedUserJson(): void {
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user' || !$_SESSION['is_verified']) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
            exit;
        }
    }
}
