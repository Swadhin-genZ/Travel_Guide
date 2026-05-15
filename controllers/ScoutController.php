<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/PostRequestModel.php';
require_once __DIR__ . '/../models/PostModel.php';

class ScoutController {
    private $prModel;
    private $postModel;

    public function __construct() {
        startSession();
        $this->prModel   = new PostRequestModel();
        $this->postModel = new PostModel();
    }

    private function gateCheck() {
        requireLogin();
        if ($_SESSION['role'] !== 'scout' || !$_SESSION['is_verified']) {
            header('Location: ' . BASE_URL . '/views/shared/unauthorized.php');
            exit;
        }
    }

    public function createRequest() {
        $this->gateCheck();
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Invalid CSRF token.';
            } else {
                $title          = trim($_POST['title'] ?? '');
                $short_history  = trim($_POST['short_history'] ?? '');
                $country        = trim($_POST['country'] ?? '');
                $genre          = $_POST['genre'] ?? '';
                $cost_level     = $_POST['cost_level'] ?? '';
                $travel_info    = trim($_POST['travel_medium_info'] ?? '');
                $original_post_id = intval($_POST['original_post_id'] ?? 0);

                if (empty($title))         $errors[] = 'Title is required.';
                if (empty($short_history)) $errors[] = 'Short history is required.';
                if (empty($country))       $errors[] = 'Country is required.';
                if (!in_array($genre, ['beach','mountain','city','historical','wildlife','cultural','adventure','religious']))
                    $errors[] = 'Invalid genre.';
                if (!in_array($cost_level, ['low','medium','high']))
                    $errors[] = 'Invalid cost level.';
                if (empty($travel_info))   $errors[] = 'Travel medium info is required.';

                $imagePath = null;
                if (!empty($_FILES['image']['name'])) {
                    $result = uploadFile($_FILES['image'], 'posts');
                    if (isset($result['error'])) $errors[] = $result['error'];
                    else $imagePath = $result['filename'];
                }

                if (empty($errors)) {
                    $postData = compact('title', 'short_history', 'country', 'genre', 'cost_level', 'travel_info');
                    $postData['travel_medium_info'] = $travel_info;
                    $postData['image'] = $imagePath;
                    $origId = $original_post_id > 0 ? $original_post_id : null;
                    $this->prModel->create($_SESSION['user_id'], $postData, $origId);
                    flashMessage('success', 'Post request submitted successfully!');
                    header('Location: ' . BASE_URL . '/views/scout/my_requests.php');
                    exit;
                }
            }
        }
        return $errors;
    }

    public function updateRequest($id) {
        $this->gateCheck();
        $errors = [];
        $req = $this->prModel->getById($id);
        if (!$req || $req['scout_id'] != $_SESSION['user_id'] || $req['status'] !== 'pending') {
            flashMessage('error', 'Request not found or cannot be edited.');
            header('Location: ' . BASE_URL . '/views/scout/my_requests.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Invalid CSRF token.';
            } else {
                $title         = trim($_POST['title'] ?? '');
                $short_history = trim($_POST['short_history'] ?? '');
                $country       = trim($_POST['country'] ?? '');
                $genre         = $_POST['genre'] ?? '';
                $cost_level    = $_POST['cost_level'] ?? '';
                $travel_info   = trim($_POST['travel_medium_info'] ?? '');

                if (empty($title))         $errors[] = 'Title is required.';
                if (empty($short_history)) $errors[] = 'Short history is required.';
                if (empty($country))       $errors[] = 'Country is required.';
                if (!in_array($genre, ['beach','mountain','city','historical','wildlife','cultural','adventure','religious']))
                    $errors[] = 'Invalid genre.';
                if (!in_array($cost_level, ['low','medium','high']))
                    $errors[] = 'Invalid cost level.';
                if (empty($travel_info))   $errors[] = 'Travel medium info is required.';

                if (empty($errors)) {
                    $postData = [
                        'title' => $title, 'short_history' => $short_history,
                        'country' => $country, 'genre' => $genre,
                        'cost_level' => $cost_level, 'travel_medium_info' => $travel_info
                    ];
                    $this->prModel->update($id, $postData);
                    flashMessage('success', 'Request updated.');
                    header('Location: ' . BASE_URL . '/views/scout/my_requests.php');
                    exit;
                }
            }
        }
        return ['errors' => $errors, 'req' => $req];
    }

    public function deleteRequest($id) {
        $this->gateCheck();
        $req = $this->prModel->getById($id);
        if ($req && $req['scout_id'] == $_SESSION['user_id'] && $req['status'] === 'pending') {
            $this->prModel->delete($id);
            jsonResponse(['success' => true]);
        } else {
            jsonResponse(['success' => false, 'message' => 'Cannot delete.'], 403);
        }
    }

    public function getMyRequests() {
        $this->gateCheck();
        return $this->prModel->getByScout($_SESSION['user_id']);
    }

    public function getApprovedPosts() {
        $this->gateCheck();
        $stmt = getDB()->prepare(
            "SELECT * FROM posts WHERE scout_id=? AND status='approved' ORDER BY created_at DESC"
        );
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetchAll();
    }
}
