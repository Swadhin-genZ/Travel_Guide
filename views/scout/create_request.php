<?php
// [TASK 2] Create Post Request view
require 'views/layouts/header.php';
?>
<div class="form-container wide">
    <h2>Submit Travel Post Request</h2>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php foreach ($errors as $e): ?>
        <div class="alert alert-error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="POST" action="index.php?action=scout_create" enctype="multipart/form-data" id="scoutForm" novalidate>
        <input type="hidden" name="original_post_id" value="<?= intval($_GET['original'] ?? 0) ?>">
        <div class="form-group">
            <label>Title *</label>
            <input type="text" name="title" required maxlength="200">
            <span class="field-error" id="titleError"></span>
        </div>
        <div class="form-group">
            <label>Short History / Description *</label>
            <textarea name="short_history" rows="5" required></textarea>
            <span class="field-error" id="historyError"></span>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Country *</label>
                <input type="text" name="country" required>
            </div>
            <div class="form-group">
                <label>Genre *</label>
                <select name="genre" required>
                    <option value="">Select genre</option>
                    <option value="beach">Beach</option>
                    <option value="mountain">Mountain</option>
                    <option value="city">City</option>
                    <option value="historical">Historical</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Cost Level *</label>
                <select name="cost_level" required>
                    <option value="">Select level</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Travel Medium Info</label>
            <textarea name="travel_medium_info" rows="3" placeholder="e.g. Flight from Dhaka, then local bus..."></textarea>
        </div>
        <div class="form-group">
            <label>Image (optional)</label>
            <input type="file" name="image" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Submit Request</button>
    </form>
</div>
<script src="/travel_guide/public/js/scout.js"></script>
<?php require 'views/layouts/footer.php'; ?>