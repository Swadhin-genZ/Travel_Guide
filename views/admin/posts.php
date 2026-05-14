<?php
$pageTitle = 'Manage Posts';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../controllers/AdminController.php';
startSession();
requireRole('admin');

$ctrl  = new AdminController();
$posts = $ctrl->getAllPosts();
$flash = getFlash();

require_once __DIR__ . '/../shared/header.php';
?>
<div class="container">
    <div class="page-header">
        <h1>Manage Posts</h1>
        <p>Edit or delete published travel destinations</p>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>
    <div id="alertBox" class="alert" style="display:none"></div>

    <?php if (empty($posts)): ?>
        <div class="empty-state"><p>No posts yet.</p></div>
    <?php else: ?>
    <div class="card">
        <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>#</th><th>Title</th><th>Scout</th><th>Country</th><th>Genre</th><th>Status</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($posts as $p): ?>
            <tr id="post-row-<?= $p['id'] ?>">
                <td><?= $p['id'] ?></td>
                <td><strong><?= e($p['title']) ?></strong></td>
                <td><?= e($p['scout_name']) ?></td>
                <td>📍 <?= e($p['country']) ?></td>
                <td><span class="badge badge-genre"><?= ucfirst(e($p['genre'])) ?></span></td>
                <td><span class="status-badge status-<?= e($p['status']) ?>"><?= ucfirst(e($p['status'])) ?></span></td>
                <td><?= date('M d, Y', strtotime($p['created_at'])) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/views/admin/edit_post.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline">✏️ Edit</a>
                    <button class="btn btn-sm btn-danger del-post-btn" data-id="<?= $p['id'] ?>" data-title="<?= e($p['title']) ?>">🗑 Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
var BASE_URL = '<?= BASE_URL ?>';

function showAlert(msg, type) {
    var box = document.getElementById('alertBox');
    box.className = 'alert alert-' + type;
    box.textContent = msg;
    box.style.display = 'block';
    setTimeout(function() { box.style.display = 'none'; }, 4000);
}

document.querySelectorAll('.del-post-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id    = this.getAttribute('data-id');
        var title = this.getAttribute('data-title');
        if (!confirm('Delete "' + title + '"? All comments and wishlist entries will be removed.')) return;
        var row = document.getElementById('post-row-' + id);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', BASE_URL + '/api/admin_delete_post.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res.success) {
                        row.style.opacity = '0';
                        row.style.transition = 'opacity 0.3s';
                        setTimeout(function() { row.remove(); }, 300);
                        showAlert('Post deleted.', 'success');
                    } else { showAlert(res.message || 'Failed.', 'error'); }
                } catch(e) {}
            }
        };
        xhr.send('id=' + encodeURIComponent(id));
    });
});
</script>
<?php require_once __DIR__ . '/../shared/footer.php'; ?>
