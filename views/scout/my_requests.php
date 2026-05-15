<?php
$pageTitle = 'My Requests';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../controllers/ScoutController.php';
startSession();
requireLogin();
if ($_SESSION['role'] !== 'scout' || !$_SESSION['is_verified']) {
    header('Location: ' . BASE_URL . '/views/shared/unauthorized.php'); exit;
}

$ctrl     = new ScoutController();
$requests = $ctrl->getMyRequests();
$flash    = getFlash();

require_once __DIR__ . '/../shared/header.php';
?>
<div class="container">
    <div class="page-header-row">
        <div>
            <h1>My Post Requests</h1>
            <p>All destinations you've submitted</p>
        </div>
        <a href="<?= BASE_URL ?>/views/scout/create_request.php" class="btn btn-primary">+ New Request</a>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>

    <?php if (empty($requests)): ?>
        <div class="empty-state">
            <div class="empty-icon">📝</div>
            <h3>No requests yet</h3>
            <p>Submit your first travel destination to get started.</p>
            <a href="<?= BASE_URL ?>/views/scout/create_request.php" class="btn btn-primary">Submit Destination</a>
        </div>
    <?php else: ?>
        <div class="requests-list">
            <?php foreach ($requests as $r):
                $pd     = json_decode($r['post_data'], true);
                $status = $r['status'];
            ?>
            <div class="request-card" id="req-<?= $r['id'] ?>">
                <div class="request-main">
                    <div class="request-info">
                        <h3><?= e($pd['title'] ?? 'Untitled') ?></h3>
                        <p class="request-meta">
                            📍 <?= e($pd['country'] ?? '') ?> &nbsp;|&nbsp;
                            🎭 <?= ucfirst(e($pd['genre'] ?? '')) ?> &nbsp;|&nbsp;
                            💵 <?= ucfirst(e($pd['cost_level'] ?? '')) ?> &nbsp;|&nbsp;
                            🗓 <?= date('M d, Y', strtotime($r['requested_at'])) ?>
                            <?php if ($r['original_post_id']): ?>
                                &nbsp;| <span class="badge badge-info">Change Request</span>
                            <?php endif; ?>
                        </p>
                        <p class="request-excerpt"><?= e(substr($pd['short_history'] ?? '', 0, 120)) ?>...</p>
                        <?php if ($status === 'rejected' && $r['rejection_reason']): ?>
                            <p class="rejection-reason">❌ Rejection reason: <?= e($r['rejection_reason']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="request-status-wrap">
                        <span class="status-badge status-<?= $status ?>"><?= ucfirst($status) ?></span>
                    </div>
                </div>
                <?php if ($status === 'pending'): ?>
                <div class="request-actions">
                    <a href="<?= BASE_URL ?>/views/scout/edit_request.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline">✏️ Edit</a>
                    <button class="btn btn-sm btn-danger del-req" data-id="<?= $r['id'] ?>">🗑 Delete</button>
                </div>
                <?php elseif ($status === 'approved'): ?>
                <div class="request-actions">
                    <a href="<?= BASE_URL ?>/views/scout/create_request.php?original_post_id=<?= $r['id'] ?>" class="btn btn-sm btn-outline">📝 Request Changes</a>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
var BASE_URL = '<?= BASE_URL ?>';
document.querySelectorAll('.del-req').forEach(function(btn) {
    btn.addEventListener('click', function() {
        if (!confirm('Are you sure you want to delete this request?')) return;
        var id  = this.getAttribute('data-id');
        var card = document.getElementById('req-' + id);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', BASE_URL + '/api/scout_delete_request.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res.success) {
                        card.style.opacity = '0';
                        card.style.transition = 'opacity 0.3s';
                        setTimeout(function() { card.remove(); }, 300);
                    } else { alert(res.message || 'Could not delete.'); }
                } catch(e) { alert('Error.'); }
            }
        };
        xhr.send('id=' + encodeURIComponent(id));
    });
});
</script>
<?php require_once __DIR__ . '/../shared/footer.php'; ?>
