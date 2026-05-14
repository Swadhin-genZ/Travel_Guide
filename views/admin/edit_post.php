<?php
$pageTitle = 'Edit Post';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/PostModel.php';
require_once __DIR__ . '/../../controllers/AdminController.php';
startSession();
requireRole('admin');

$id   = intval($_GET['id'] ?? 0);
$ctrl = new AdminController();
$res  = $ctrl->editPost($id);
$errors = $res['errors'];
$post   = $res['post'];

require_once __DIR__ . '/../shared/header.php';
?>
<div class="container">
    <div class="page-header">
        <h1>Edit Post</h1>
        <p>Update destination: <strong><?= e($post['title']) ?></strong></p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $err): ?><p><?= e($err) ?></p><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="card form-card">
        <form id="editPostForm" method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <div class="form-row">
                <div class="form-group flex-2">
                    <label>Title *</label>
                    <input type="text" name="title" id="pTitle" class="form-control"
                           value="<?= e($_POST['title'] ?? $post['title']) ?>" required>
                    <span class="field-error" id="pTitleErr"></span>
                </div>
                <div class="form-group flex-1">
                    <label>Country *</label>
                    <input type="text" name="country" id="pCountry" class="form-control"
                           value="<?= e($_POST['country'] ?? $post['country']) ?>" required>
                    <span class="field-error" id="pCountryErr"></span>
                </div>
            </div>

            <div class="form-group">
                <label>Short History / Description *</label>
                <textarea name="short_history" id="pHistory" class="form-control" rows="6" required><?= e($_POST['short_history'] ?? $post['short_history']) ?></textarea>
                <span class="field-error" id="pHistoryErr"></span>
            </div>

            <div class="form-row">
                <div class="form-group flex-1">
                    <label>Genre *</label>
                    <select name="genre" class="form-control">
                        <?php $genres = ['beach','mountain','city','historical','wildlife','cultural','adventure','religious'];
                        $selG = $_POST['genre'] ?? $post['genre'];
                        foreach ($genres as $g): ?>
                            <option value="<?= $g ?>" <?= $selG === $g ? 'selected' : '' ?>><?= ucfirst($g) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group flex-1">
                    <label>Cost Level *</label>
                    <select name="cost_level" class="form-control">
                        <?php $selC = $_POST['cost_level'] ?? $post['cost_level'];
                        foreach (['low','medium','high'] as $c): ?>
                            <option value="<?= $c ?>" <?= $selC === $c ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Travel Medium Info *</label>
                <input type="text" name="travel_medium_info" id="pTravel" class="form-control"
                       value="<?= e($_POST['travel_medium_info'] ?? $post['travel_medium_info']) ?>" required>
                <span class="field-error" id="pTravelErr"></span>
            </div>

            <div class="form-actions">
                <a href="<?= BASE_URL ?>/views/admin/posts.php" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('editPostForm').addEventListener('submit', function(e) {
    var valid = true;
    ['pTitleErr','pCountryErr','pHistoryErr','pTravelErr'].forEach(function(id) { document.getElementById(id).textContent = ''; });
    if (!document.getElementById('pTitle').value.trim())   { document.getElementById('pTitleErr').textContent   = 'Required.'; valid = false; }
    if (!document.getElementById('pCountry').value.trim()) { document.getElementById('pCountryErr').textContent = 'Required.'; valid = false; }
    if (document.getElementById('pHistory').value.trim().length < 20) { document.getElementById('pHistoryErr').textContent = 'Min 20 chars.'; valid = false; }
    if (!document.getElementById('pTravel').value.trim())  { document.getElementById('pTravelErr').textContent  = 'Required.'; valid = false; }
    if (!valid) e.preventDefault();
});
</script>
<?php require_once __DIR__ . '/../shared/footer.php'; ?>
