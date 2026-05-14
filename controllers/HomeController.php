<?php
require_once __DIR__ . '/../models/UserModel.php';

class HomeController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function index(): void {
        $posts = [];

        if (!empty($_SESSION['user_id']) && $_SESSION['is_verified'] == 1) {
            $posts = $this->userModel->getLatestApprovedPosts(6);
        }

        require __DIR__ . '/../views/home/index.php';
    }
}
