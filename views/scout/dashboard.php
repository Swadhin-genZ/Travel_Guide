<?php
$pageTitle = 'Scout Dashboard';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/PostRequestModel.php';
startSession();
requireLogin();
if ($_SESSION['role'] !== 'scout') { header('Location: ' . BASE_URL . '/views/shared/unauthorized.php'); exit; }
if (!$_SESSION['is_verified']) { header('Location: ' . BASE_URL . '/views/auth/pending.php'); exit; }

$prm      = new PostRequestModel();
$requests = $prm->getByScout($_SESSION['user_id']);
$pending  = array_filter($requests, fn($r) => $r['status'] === 'pending');
$approved = array_filter($requests, fn($r) => $r['status'] === 'approved');
$rejected = array_filter($requests, fn($r) => $r['status'] === 'rejected');

require_once __DIR__ . '/../shared/header.php';
?>
<div class="container">
    <div class="page-header">
        <h1>Scout Dashboard</h1>
        <p>Welcome, <?= e($_SESSION['name']) ?>! Manage your travel content.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-blue">
            <div class="stat-icon">📝</div>
            <div class="stat-info">
                <div class="stat-num"><?= count($requests) ?></div>
                <div class="stat-label">Total Requests</div>
            </div>
        </div>
        <div class="stat-card stat-yellow">
            <div class="stat-icon">⏳</div>
            <div class="stat-info">
                <div class="stat-num"><?= count($pending) ?></div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
        <div class="stat-card stat-green">
            <div class="stat-icon">✅</div>
            <div class="stat-info">
                <div class="stat-num"><?= count($approved) ?></div>
                <div class="stat-label">Approved</div>
            </div>
        </div>
        <div class="stat-card stat-red">
            <div class="stat-icon">❌</div>
            <div class="stat-info">
                <div class="stat-num"><?= count($rejected) ?></div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>
    </div>

    <div class="dashboard-actions">
        <a href="<?= BASE_URL ?>/views/scout/create_request.php" class="btn btn-primary btn-lg">+ Submit New Place</a>
        <a href="<?= BASE_URL ?>/views/scout/my_requests.php" class="btn btn-outline btn-lg">View All Requests</a>
    </div>

    <?php if (!empty($pending)): ?>
    <div class="card mt-2">
        <h3 class="card-title">Pending Requests</h3>
        <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>Title</th><th>Country</th><th>Genre</th><th>Submitted</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($pending as $r):
                $pd = json_decode($r['post_data'], true); ?>
            <tr>
                <td><?= e($pd['title'] ?? 'N/A') ?></td>
                <td><?= e($pd['country'] ?? 'N/A') ?></td>
                <td><?= ucfirst(e($pd['genre'] ?? 'N/A')) ?></td>
                <td><?= date('M d, Y', strtotime($r['requested_at'])) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/views/scout/edit_request.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
                    <button class="btn btn-sm btn-danger del-req" data-id="<?= $r['id'] ?>">Delete</button>
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
document.querySelectorAll('.del-req').forEach(function(btn) {
    btn.addEventListener('click', function() {
        if (!confirm('Delete this request?')) return;
        var id  = this.getAttribute('data-id');
        var row = this.closest('tr');
        var xhr = new XMLHttpRequest();
        xhr.open('POST', BASE_URL + '/api/scout_delete_request.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res.success) { row.remove(); }
                    else alert(res.message || 'Delete failed.');
                } catch(e) {}
            }
        };
        xhr.send('id=' + encodeURIComponent(id));
    });
});
</script>
<?php require_once __DIR__ . '/../shared/footer.php'; ?>
