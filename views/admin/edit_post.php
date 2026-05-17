<?php
// [TASK 3] Admin Edit Post
require 'views/layouts/header.php';
?>
<div class="form-container wide">
    <h2>Edit Post</h2>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php foreach ($errors as $e): ?><div class="alert alert-error"><?= $e ?></div><?php endforeach; ?>
    <form method="POST" action="index.php?action=admin_edit_post&id=<?= $post['id'] ?>">
        <div class="form-group"><label>Title</label><input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required></div>
        <div class="form-group"><label>Short History</label><textarea name="short_history" rows="5" required><?= htmlspecialchars($post['short_history']) ?></textarea></div>
        <div class="form-row">
            <div class="form-group"><label>Country</label><input type="text" name="country" value="<?= htmlspecialchars($post['country']) ?>" required></div>
            <div class="form-group"><label>Genre</label>
                <select name="genre">
                    <?php foreach (['beach','mountain','city','historical','other'] as $g): ?>
                        <option value="<?= $g ?>" <?= $post['genre'] === $g ? 'selected' : '' ?>><?= ucfirst($g) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label>Cost Level</label>
                <select name="cost_level">
                    <?php foreach (['low','medium','high'] as $c): ?>
                        <option value="<?= $c ?>" <?= $post['cost_level'] === $c ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group"><label>Travel Medium Info</label><textarea name="travel_medium_info" rows="3"><?= htmlspecialchars($post['travel_medium_info'] ?? '') ?></textarea></div>
        <button type="submit" class="btn btn-primary btn-block">Update Post</button>
        <a href="index.php?action=admin_posts" class="btn btn-outline btn-block">Back</a>
    </form>
</div>
<?php require 'views/layouts/footer.php'; ?>