<?php
// [TASK 2] Scout Controller
require_once 'models/PostRequest.php';
require_once 'models/Post.php';

class ScoutController {

    private static function gate() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'scout' || !$_SESSION['verified']) {
            header("Location: index.php?action=login"); exit;
        }
    }

    public static function dashboard() {
        self::gate();
        global $conn;
        $prModel = new PostRequest($conn);
        $requests = $prModel->getByScout($_SESSION['user_id']);
        require 'views/scout/dashboard.php';
    }

    public static function myRequests() {
        self::gate();
        global $conn;
        $prModel = new PostRequest($conn);
        $postModel = new Post($conn);
        $requests = $prModel->getByScout($_SESSION['user_id']);
        $approvedPosts = $postModel->getByScout($_SESSION['user_id']);
        require 'views/scout/my_requests.php';
    }

    public static function createRequest() {
        self::gate();
        global $conn;
        $errors = []; $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title       = trim($_POST['title'] ?? '');
            $history     = trim($_POST['short_history'] ?? '');
            $country     = trim($_POST['country'] ?? '');
            $genre       = $_POST['genre'] ?? '';
            $cost_level  = $_POST['cost_level'] ?? '';
            $travel_info = trim($_POST['travel_medium_info'] ?? '');
            $originalPostId = intval($_POST['original_post_id'] ?? 0) ?: null;

            if (!$title)      $errors[] = "Title required.";
            if (!$history)    $errors[] = "Short history required.";
            if (!$country)    $errors[] = "Country required.";
            if (!in_array($genre, ['beach','mountain','city','historical','other'])) $errors[] = "Invalid genre.";
            if (!in_array($cost_level, ['low','medium','high'])) $errors[] = "Invalid cost level.";

            $imagePath = null;
            if (!empty($_FILES['image']['name'])) {
                $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
                $mime = mime_content_type($_FILES['image']['tmp_name']);
                if (!in_array($mime, $allowed))   $errors[] = "Invalid image type.";
                if ($_FILES['image']['size'] > 5 * 1024 * 1024) $errors[] = "Image too large (max 5MB).";
                if (!$errors) {
                    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $imagePath = 'public/uploads/posts/' . uniqid() . '.' . $ext;
                    move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
                }
            }

            if (!$errors) {
                $data = compact('title','history','country','genre','cost_level','travel_info');
                $data['short_history'] = $history;
                $data['travel_medium_info'] = $travel_info;
                $prModel = new PostRequest($conn);
                $prModel->create($_SESSION['user_id'], $data, $imagePath, $originalPostId);
                $success = "Request submitted successfully!";
            }
        }
        require 'views/scout/create_request.php';
    }

    public static function editRequest() {
        self::gate();
        global $conn;
        $id = intval($_GET['id'] ?? 0);
        $prModel = new PostRequest($conn);
        $request = $prModel->getById($id);

        if (!$request || $request['scout_id'] != $_SESSION['user_id'] || $request['status'] !== 'pending') {
            header("Location: index.php?action=scout_my_requests"); exit;
        }

        $data = json_decode($request['post_data'], true);
        $errors = []; $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title       = trim($_POST['title'] ?? '');
            $history     = trim($_POST['short_history'] ?? '');
            $country     = trim($_POST['country'] ?? '');
            $genre       = $_POST['genre'] ?? '';
            $cost_level  = $_POST['cost_level'] ?? '';
            $travel_info = trim($_POST['travel_medium_info'] ?? '');

            if (!$title || !$history || !$country) $errors[] = "Required fields missing.";
            if (!in_array($genre, ['beach','mountain','city','historical','other'])) $errors[] = "Invalid genre.";

            $imagePath = null;
            if (!empty($_FILES['image']['name'])) {
                $mime = mime_content_type($_FILES['image']['tmp_name']);
                if (!in_array($mime, ['image/jpeg','image/png','image/gif','image/webp'])) $errors[] = "Invalid image.";
                else {
                    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $imagePath = 'public/uploads/posts/' . uniqid() . '.' . $ext;
                    move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
                }
            }

            if (!$errors) {
                $newData = ['title'=>$title,'short_history'=>$history,'country'=>$country,'genre'=>$genre,'cost_level'=>$cost_level,'travel_medium_info'=>$travel_info];
                $prModel->update($id, $_SESSION['user_id'], $newData, $imagePath);
                $success = "Request updated.";
                $data = $newData;
            }
        }
        require 'views/scout/edit_request.php';
    }
}