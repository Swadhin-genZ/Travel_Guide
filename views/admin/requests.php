<?php
$pageTitle = 'Post Requests';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../controllers/AdminController.php';
startSession();
requireRole('admin');

$ctrl     = new AdminController();
$requests = $ctrl->getPendingReqs();

require_once __DIR__ . '/../shared/header.php';
?>
<div class="container">
    <div class="page-header">
        <h1>Post Requests</h1>
        <p>Review and approve or reject scout submissions</p>
    </div>

    <div id="alertBox" class="alert" style="display:none"></div>

    <?php if (empty($requests)): ?>
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <h3>No pending requests</h3>
            <p>All requests have been reviewed.</p>
        </div>
    <?php else: ?>
        <div class="requests-list" id="requestsList">
            <?php foreach ($requests as $r):
                $pd = json_decode($r['post_data'], true);
            ?>
            <div class="request-card admin-req-card" id="admin-req-<?= $r['id'] ?>">
                <div class="request-main">
                    <div class="request-info">
                        <h3><?= e($pd['title'] ?? 'Untitled') ?></h3>
                        <p class="request-meta">
                            👤 Scout: <strong><?= e($r['scout_name']) ?></strong> &nbsp;|&nbsp;
                            📍 <?= e($pd['country'] ?? '') ?> &nbsp;|&nbsp;
                            🎭 <?= ucfirst(e($pd['genre'] ?? '')) ?> &nbsp;|&nbsp;
                            💵 <?= ucfirst(e($pd['cost_level'] ?? '')) ?> &nbsp;|&nbsp;
                            🗓 <?= date('M d, Y H:i', strtotime($r['requested_at'])) ?>
                            <?php if ($r['original_post_id']): ?>
                                &nbsp;| <span class="badge badge-info">Change Request</span>
                            <?php endif; ?>
                        </p>
                        <p class="request-excerpt"><?= e(substr($pd['short_history'] ?? '', 0, 150)) ?>...</p>
                        <p><strong>Travel Info:</strong> <?= e($pd['travel_medium_info'] ?? '') ?></p>
                        <?php if (!empty($pd['image'])): ?>
                            <img src="<?= BASE_URL ?>/public/uploads/<?= e($pd['image']) ?>" alt="Place image" class="req-preview-img">
                        <?php endif; ?>
                    </div>
                    <div class="req-mod-actions">
                        <button class="btn btn-success approve-req" data-id="<?= $r['id'] ?>">✅ Approve</button>
                        <button class="btn btn-danger reject-req" data-id="<?= $r['id'] ?>">❌ Reject</button>
                    </div>
                </div>
                <div class="reject-form" id="reject-form-<?= $r['id'] ?>" style="display:none">
                    <input type="text" class="form-control reject-reason" placeholder="Rejection reason (optional)">
                    <button class="btn btn-sm btn-danger confirm-reject" data-id="<?= $r['id'] ?>">Confirm Reject</button>
                    <button class="btn btn-sm btn-outline cancel-reject" data-id="<?= $r['id'] ?>">Cancel</button>
                </div>
            </div>
            <?php endforeach; ?>
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

// Approve
document.querySelectorAll('.approve-req').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id   = this.getAttribute('data-id');
        var card = document.getElementById('admin-req-' + id);
        if (!confirm('Approve this request and publish the post?')) return;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', BASE_URL + '/api/admin_approve_request.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res.success) {
                        showAlert('Request approved and post published!', 'success');
                        card.style.opacity = '0';
                        card.style.transition = 'opacity 0.3s';
                        setTimeout(function() { card.remove(); }, 300);
                    } else { showAlert(res.message || 'Failed.', 'error'); }
                } catch(e) { showAlert('Error.', 'error'); }
            }
        };
        xhr.send('id=' + encodeURIComponent(id));
    });
});

// Reject — show form
document.querySelectorAll('.reject-req').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id   = this.getAttribute('data-id');
        document.getElementById('reject-form-' + id).style.display = 'flex';
    });
});

// Cancel reject
document.querySelectorAll('.cancel-reject').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        document.getElementById('reject-form-' + id).style.display = 'none';
    });
});

// Confirm reject
document.querySelectorAll('.confirm-reject').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id     = this.getAttribute('data-id');
        var card   = document.getElementById('admin-req-' + id);
        var reason = document.querySelector('#reject-form-' + id + ' .reject-reason').value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', BASE_URL + '/api/admin_reject_request.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res.success) {
                        showAlert('Request rejected.', 'success');
                        card.style.opacity = '0';
                        card.style.transition = 'opacity 0.3s';
                        setTimeout(function() { card.remove(); }, 300);
                    } else { showAlert(res.message || 'Failed.', 'error'); }
                } catch(e) { showAlert('Error.', 'error'); }
            }
        };
        xhr.send('id=' + encodeURIComponent(id) + '&reason=' + encodeURIComponent(reason));
    });
});
</script>
<?php require_once __DIR__ . '/../shared/footer.php'; ?>
