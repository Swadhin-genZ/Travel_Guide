<?php
$pageTitle = 'Moderate Comments';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../controllers/AdminController.php';
startSession();
requireRole('admin');

$ctrl     = new AdminController();
$comments = $ctrl->getAllComments();

require_once __DIR__ . '/../shared/header.php';
?>
<div class="container">
    <div class="page-header">
        <h1>Comment Moderation</h1>
        <p>Review and delete user comments</p>
    </div>

    <div id="alertBox" class="alert" style="display:none"></div>

    <?php if (empty($comments)): ?>
        <div class="empty-state"><p>No comments yet.</p></div>
    <?php else: ?>
    <div class="card">
        <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>#</th><th>Comment</th><th>User</th><th>Post</th><th>Date</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php foreach ($comments as $c): ?>
            <tr id="comment-row-<?= $c['id'] ?>">
                <td><?= $c['id'] ?></td>
                <td class="comment-content-cell"><?= e(substr($c['content'], 0, 80)) ?><?= strlen($c['content']) > 80 ? '...' : '' ?></td>
                <td><?= e($c['user_name']) ?></td>
                <td><a href="<?= BASE_URL ?>/views/user/post_detail.php?id=<?= $c['post_id'] ?>" target="_blank"><?= e($c['post_title']) ?></a></td>
                <td><?= date('M d, Y', strtotime($c['created_at'])) ?></td>
                <td>
                    <button class="btn btn-sm btn-danger del-comment-btn" data-id="<?= $c['id'] ?>">🗑 Delete</button>
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

document.querySelectorAll('.del-comment-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id  = this.getAttribute('data-id');
        var row = document.getElementById('comment-row-' + id);
        if (!confirm('Delete this comment?')) return;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', BASE_URL + '/api/admin_delete_comment.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res.success) {
                        row.style.opacity = '0';
                        row.style.transition = 'opacity 0.3s';
                        setTimeout(function() { row.remove(); }, 300);
                        showAlert('Comment deleted.', 'success');
                    } else { showAlert('Failed.', 'error'); }
                } catch(e) {}
            }
        };
        xhr.send('id=' + encodeURIComponent(id));
    });
});
</script>
<?php require_once __DIR__ . '/../shared/footer.php'; ?>
