<?php
// [TASK 2] Edit Request view
require 'views/layouts/header.php';
?>
<div class="form-container wide">
    <h2>Edit Request</h2>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php foreach ($errors as $e): ?>
        <div class="alert alert-error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="POST" action="index.php?action=scout_edit&id=<?= $request['id'] ?>" enctype="multipart/form-data" id="scoutForm" novalidate>
        <div class="form-group">
            <label>Title *</label>
            <input type="text" name="title" value="<?= htmlspecialchars($data['title'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Short History *</label>
            <textarea name="short_history" rows="5" required><?= htmlspecialchars($data['short_history'] ?? '') ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Country</label>
                <input type="text" name="country" value="<?= htmlspecialchars($data['country'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Genre</label>
                <select name="genre" required>
                    <?php foreach (['beach','mountain','city','historical','other'] as $g): ?>
                        <option value="<?= $g ?>" <?= ($data['genre'] ?? '') === $g ? 'selected' : '' ?>><?= ucfirst($g) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Cost Level</label>
                <select name="cost_level" required>
                    <?php foreach (['low','medium','high'] as $c): ?>
                        <option value="<?= $c ?>" <?= ($data['cost_level'] ?? '') === $c ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Travel Medium Info</label>
            <textarea name="travel_medium_info" rows="3"><?= htmlspecialchars($data['travel_medium_info'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>New Image (optional)</label>
            <input type="file" name="image" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Update Request</button>
        <a href="index.php?action=scout_my_requests" class="btn btn-outline btn-block">Cancel</a>
    </form>
</div>
<script src="/travel_guide/public/js/scout.js"></script>
<?php require 'views/layouts/footer.php'; ?>