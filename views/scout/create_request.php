<?php
$pageTitle = 'Submit New Place';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/PostModel.php';
require_once __DIR__ . '/../../controllers/ScoutController.php';
startSession();
requireLogin();
if ($_SESSION['role'] !== 'scout' || !$_SESSION['is_verified']) {
    header('Location: ' . BASE_URL . '/views/shared/unauthorized.php'); exit;
}

// Pre-fill original_post_id for change requests
$originalPostId = intval($_GET['original_post_id'] ?? 0);
$originalPost   = null;
if ($originalPostId) {
    $pm = new PostModel();
    $originalPost = $pm->getById($originalPostId);
}

$ctrl   = new ScoutController();
$errors = $ctrl->createRequest();

require_once __DIR__ . '/../shared/header.php';
?>
<div class="container">
    <div class="page-header">
        <h1><?= $originalPost ? 'Request Changes to: ' . e($originalPost['title']) : 'Submit New Destination' ?></h1>
        <p><?= $originalPost ? 'Your change request will be reviewed by an admin.' : 'Fill in the details about the travel destination.' ?></p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $err): ?><p><?= e($err) ?></p><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="card form-card">
        <form id="requestForm" method="POST" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <?php if ($originalPost): ?>
                <input type="hidden" name="original_post_id" value="<?= $originalPostId ?>">
                <div class="alert alert-info">📌 This is a change request for the approved post: <strong><?= e($originalPost['title']) ?></strong></div>
            <?php endif; ?>

            <div class="form-row">
                <div class="form-group flex-2">
                    <label>Place Title *</label>
                    <input type="text" name="title" id="fTitle" class="form-control"
                           value="<?= e($_POST['title'] ?? $originalPost['title'] ?? '') ?>"
                           placeholder="e.g. Bali Sunsets & Sacred Temples" required>
                    <span class="field-error" id="titleErr"></span>
                </div>
                <div class="form-group flex-1">
                    <label>Country *</label>
                    <input type="text" name="country" id="fCountry" class="form-control"
                           value="<?= e($_POST['country'] ?? $originalPost['country'] ?? '') ?>"
                           placeholder="e.g. Indonesia" required>
                    <span class="field-error" id="countryErr"></span>
                </div>
            </div>

            <div class="form-group">
                <label>Short History / Description *</label>
                <textarea name="short_history" id="fHistory" class="form-control" rows="5"
                          placeholder="Describe the place, its cultural significance, history and what makes it special..."
                          required><?= e($_POST['short_history'] ?? $originalPost['short_history'] ?? '') ?></textarea>
                <span class="field-error" id="historyErr"></span>
            </div>

            <div class="form-row">
                <div class="form-group flex-1">
                    <label>Genre *</label>
                    <select name="genre" id="fGenre" class="form-control">
                        <?php $genres = ['beach','mountain','city','historical','wildlife','cultural','adventure','religious'];
                        $selGenre = $_POST['genre'] ?? $originalPost['genre'] ?? '';
                        foreach ($genres as $g): ?>
                            <option value="<?= $g ?>" <?= $selGenre === $g ? 'selected' : '' ?>><?= ucfirst($g) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group flex-1">
                    <label>Cost Level *</label>
                    <select name="cost_level" id="fCost" class="form-control">
                        <?php $selCost = $_POST['cost_level'] ?? $originalPost['cost_level'] ?? '';
                        foreach (['low','medium','high'] as $c): ?>
                            <option value="<?= $c ?>" <?= $selCost === $c ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Travel Medium Information *</label>
                <input type="text" name="travel_medium_info" id="fTravel" class="form-control"
                       value="<?= e($_POST['travel_medium_info'] ?? $originalPost['travel_medium_info'] ?? '') ?>"
                       placeholder="e.g. Direct flights from major cities; local buses available on-site" required>
                <span class="field-error" id="travelErr"></span>
            </div>

            <div class="form-group">
                <label>Place Image (optional)</label>
                <input type="file" name="image" id="fImage" class="form-control" accept="image/*">
                <small>JPEG, PNG, GIF or WebP — max 5MB</small>
                <span class="field-error" id="imageErr"></span>
                <div id="imagePreview" class="image-preview-wrap" style="display:none">
                    <img id="previewImg" src="" alt="Preview" class="image-preview">
                </div>
            </div>

            <div class="form-actions">
                <a href="<?= BASE_URL ?>/views/scout/my_requests.php" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit Request</button>
            </div>
        </form>
    </div>
</div>

<script>
// Image preview
document.getElementById('fImage').addEventListener('change', function() {
    var file = this.files[0];
    var errSpan = document.getElementById('imageErr');
    errSpan.textContent = '';
    if (!file) { document.getElementById('imagePreview').style.display = 'none'; return; }
    var allowed = ['image/jpeg','image/png','image/gif','image/webp'];
    if (!allowed.includes(file.type)) { errSpan.textContent = 'Invalid file type.'; this.value = ''; return; }
    if (file.size > 5 * 1024 * 1024) { errSpan.textContent = 'File exceeds 5MB.'; this.value = ''; return; }
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('imagePreview').style.display = 'block';
    };
    reader.readAsDataURL(file);
});

// Form validation
document.getElementById('requestForm').addEventListener('submit', function(e) {
    var valid = true;
    ['titleErr','countryErr','historyErr','travelErr'].forEach(function(id) {
        document.getElementById(id).textContent = '';
    });

    if (!document.getElementById('fTitle').value.trim()) {
        document.getElementById('titleErr').textContent = 'Title is required.'; valid = false;
    }
    if (!document.getElementById('fCountry').value.trim()) {
        document.getElementById('countryErr').textContent = 'Country is required.'; valid = false;
    }
    if (document.getElementById('fHistory').value.trim().length < 20) {
        document.getElementById('historyErr').textContent = 'Description must be at least 20 characters.'; valid = false;
    }
    if (!document.getElementById('fTravel').value.trim()) {
        document.getElementById('travelErr').textContent = 'Travel info is required.'; valid = false;
    }
    if (!valid) e.preventDefault();
});
</script>
<?php require_once __DIR__ . '/../shared/footer.php'; ?>
