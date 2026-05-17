<?php
class ScoutController {
    private $model;
    public function __construct($model) { $this->model = $model; }

    private function gate() {
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'scout' || !$_SESSION['is_verified']) {
            header('Location: index.php?page=login'); exit;
        }
    }

    public function showCreate() {
        $this->gate();
        $error   = $_SESSION['error'] ?? '';
        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['error'], $_SESSION['success']);
        require ROOT . '/views/scout/create.php';
    }

    public function create() {
        $this->gate();

        $title   = trim($_POST['title'] ?? '');
        $history = trim($_POST['history'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $genre   = $_POST['genre'] ?? '';
        $cost    = $_POST['cost'] ?? '';
        $travel  = trim($_POST['travel'] ?? '');

        if (!$title || !$history || !$country || !$genre || !$cost || !$travel) {
            $_SESSION['error'] = 'All fields are required.';
            header('Location: index.php?page=scout_create'); exit;
        }
        if (!in_array($genre, ['beach','mountain','city','historical','other'])) {
            $_SESSION['error'] = 'Invalid genre.';
            header('Location: index.php?page=scout_create'); exit;
        }
        if (!in_array($cost, ['low','medium','high'])) {
            $_SESSION['error'] = 'Invalid cost level.';
            header('Location: index.php?page=scout_create'); exit;
        }

        // Image upload (optional)
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $file    = $_FILES['image'];
            $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
            $finfo   = finfo_open(FILEINFO_MIME_TYPE);
            $mime    = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            if (!in_array($mime, $allowed)) {
                $_SESSION['error'] = 'Only JPEG, PNG, GIF, WEBP allowed.';
                header('Location: index.php?page=scout_create'); exit;
            }
            if ($file['size'] > 2 * 1024 * 1024) {
                $_SESSION['error'] = 'Image must be under 2MB.';
                header('Location: index.php?page=scout_create'); exit;
            }
            $ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename  = 'post_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
            $imagePath = $filename;
            move_uploaded_file($file['tmp_name'], ROOT . '/public/uploads/posts/' . $filename);
        }

        $data = [
            'title'   => $title,
            'history' => $history,
            'country' => $country,
            'genre'   => $genre,
            'cost'    => $cost,
            'travel'  => $travel,
            'image'   => $imagePath
        ];

        $this->model->create($_SESSION['user_id'], $data, $imagePath);
        $_SESSION['success'] = 'Post request submitted!';
        header('Location: index.php?page=scout_requests'); exit;
    }

    public function myRequests() {
        $this->gate();
        $requests = $this->model->getByScout($_SESSION['user_id']);
        require ROOT . '/views/scout/my_requests.php';
    }

    public function showEdit() {
        $this->gate();
        $id      = (int)($_GET['id'] ?? 0);
        $request = $this->model->getById($id);
        if (!$request || $request['scout_id'] != $_SESSION['user_id'] || $request['status'] !== 'pending') {
            $_SESSION['error'] = 'Not found or not editable.';
            header('Location: index.php?page=scout_requests'); exit;
        }
        $postData = json_decode($request['post_data'], true);
        $error    = $_SESSION['error'] ?? '';
        $success  = $_SESSION['success'] ?? '';
        unset($_SESSION['error'], $_SESSION['success']);
        require ROOT . '/views/scout/edit.php';
    }

    public function update() {
        $this->gate();
        $id      = (int)($_POST['id'] ?? 0);
        $title   = trim($_POST['title'] ?? '');
        $history = trim($_POST['history'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $genre   = $_POST['genre'] ?? '';
        $cost    = $_POST['cost'] ?? '';
        $travel  = trim($_POST['travel'] ?? '');

        if (!$title || !$history || !$country || !$genre || !$cost || !$travel) {
            $_SESSION['error'] = 'All fields are required.';
            header('Location: index.php?page=scout_edit&id=' . $id); exit;
        }

        $data = [
            'title'   => $title,
            'history' => $history,
            'country' => $country,
            'genre'   => $genre,
            'cost'    => $cost,
            'travel'  => $travel
        ];

        $this->model->update($id, $_SESSION['user_id'], $data);
        $_SESSION['success'] = 'Request updated!';
        header('Location: index.php?page=scout_requests'); exit;
    }

    // AJAX DELETE
    public function delete() {
        header('Content-Type: application/json');
        $this->gate();
        $data = json_decode(file_get_contents('php://input'), true);
        $id   = (int)($data['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']); exit;
        }
        $result = $this->model->delete($id, $_SESSION['user_id']);
        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Deleted.' : 'Could not delete.'
        ]);
        exit;
    }

    public function approvedPosts() {
        $this->gate();
        $posts = $this->model->getApprovedByScout($_SESSION['user_id']);
        require ROOT . '/views/scout/approved_posts.php';
    }

    public function requestChange() {
        $this->gate();
        $postId  = (int)($_POST['post_id'] ?? 0);
        $title   = trim($_POST['title'] ?? '');
        $history = trim($_POST['history'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $genre   = $_POST['genre'] ?? '';
        $cost    = $_POST['cost'] ?? '';
        $travel  = trim($_POST['travel'] ?? '');

        if (!$postId || !$title || !$history || !$country || !$genre || !$cost || !$travel) {
            $_SESSION['error'] = 'All fields are required.';
            header('Location: index.php?page=scout_approved'); exit;
        }

        $data = [
            'title'   => $title,
            'history' => $history,
            'country' => $country,
            'genre'   => $genre,
            'cost'    => $cost,
            'travel'  => $travel
        ];

        $this->model->requestChange($_SESSION['user_id'], $postId, $data);
        $_SESSION['success'] = 'Change request submitted!';
        header('Location: index.php?page=scout_approved'); exit;
    }
}