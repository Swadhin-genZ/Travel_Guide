<?php
$pageTitle = 'Edit Request';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../controllers/ScoutController.php';
startSession();
requireLogin();
if ($_SESSION['role'] !== 'scout' || !$_SESSION['is_verified']) {
    header('Location: ' . BASE_URL . '/views/shared/unauthorized.php'); exit;
}

$id   = intval($_GET['id'] ?? 0);
$ctrl = new ScoutController();
$res  = $ctrl->updateRequest($id);
$errors = $res['errors'];
$req    = $res['req'];
$pd     = json_decode($req['post_data'], true);

require_once __DIR__ . '/../shared/header.php';
?>
<div class="container">
    <div class="page-header">
        <h1>Edit Post Request</h1>
        <p>Update the details of your pending submission</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $err): ?><p><?= e($err) ?></p><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="card form-card">
        <form id="editForm" method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <div class="form-row">
                <div class="form-group flex-2">
                    <label>Place Title *</label>
                    <input type="text" name="title" id="fTitle" class="form-control"
                           value="<?= e($_POST['title'] ?? $pd['title'] ?? '') ?>" required>
                    <span class="field-error" id="titleErr"></span>
                </div>
                <div class="form-group flex-1">
                    <label>Country *</label>
                    <input type="text" name="country" id="fCountry" class="form-control"
                           value="<?= e($_POST['country'] ?? $pd['country'] ?? '') ?>" required>
                    <span class="field-error" id="countryErr"></span>
                </div>
            </div>

            <div class="form-group">
                <label>Description *</label>
                <textarea name="short_history" id="fHistory" class="form-control" rows="5" required><?= e($_POST['short_history'] ?? $pd['short_history'] ?? '') ?></textarea>
                <span class="field-error" id="historyErr"></span>
            </div>

            <div class="form-row">
                <div class="form-group flex-1">
                    <label>Genre *</label>
                    <select name="genre" class="form-control">
                        <?php $genres = ['beach','mountain','city','historical','wildlife','cultural','adventure','religious'];
                        $selGenre = $_POST['genre'] ?? $pd['genre'] ?? '';
                        foreach ($genres as $g): ?>
                            <option value="<?= $g ?>" <?= $selGenre === $g ? 'selected' : '' ?>><?= ucfirst($g) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group flex-1">
                    <label>Cost Level *</label>
                    <select name="cost_level" class="form-control">
                        <?php $selCost = $_POST['cost_level'] ?? $pd['cost_level'] ?? '';
                        foreach (['low','medium','high'] as $c): ?>
                            <option value="<?= $c ?>" <?= $selCost === $c ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Travel Medium Info *</label>
                <input type="text" name="travel_medium_info" id="fTravel" class="form-control"
                       value="<?= e($_POST['travel_medium_info'] ?? $pd['travel_medium_info'] ?? '') ?>" required>
                <span class="field-error" id="travelErr"></span>
            </div>

            <div class="form-actions">
                <a href="<?= BASE_URL ?>/views/scout/my_requests.php" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Request</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('editForm').addEventListener('submit', function(e) {
    var valid = true;
    ['titleErr','countryErr','historyErr','travelErr'].forEach(function(id) { document.getElementById(id).textContent = ''; });
    if (!document.getElementById('fTitle').value.trim())   { document.getElementById('titleErr').textContent   = 'Required.'; valid = false; }
    if (!document.getElementById('fCountry').value.trim()) { document.getElementById('countryErr').textContent = 'Required.'; valid = false; }
    if (document.getElementById('fHistory').value.trim().length < 20) { document.getElementById('historyErr').textContent = 'Min 20 chars.'; valid = false; }
    if (!document.getElementById('fTravel').value.trim())  { document.getElementById('travelErr').textContent  = 'Required.'; valid = false; }
    if (!valid) e.preventDefault();
});
</script>
<?php require_once __DIR__ . '/../shared/footer.php'; ?>
