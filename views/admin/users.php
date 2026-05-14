<?php
$pageTitle = 'Manage Users';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../controllers/AdminController.php';
startSession();
requireRole('admin');

$ctrl  = new AdminController();
$users = $ctrl->getAllUsers();
$flash = getFlash();

require_once __DIR__ . '/../shared/header.php';
?>
<div class="container">
    <div class="page-header-row">
        <div>
            <h1>User Management</h1>
            <p>Manage all registered users</p>
        </div>
        <a href="<?= BASE_URL ?>/views/admin/add_user.php" class="btn btn-primary">+ Add User</a>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th><th>Name</th><th>Email</th><th>Role</th>
                    <th>Status</th><th>Joined</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u): ?>
            <tr id="user-row-<?= $u['id'] ?>">
                <td><?= $u['id'] ?></td>
                <td>
                    <div class="user-name-cell">
                        <div class="mini-avatar"><?= strtoupper(substr($u['name'], 0, 1)) ?></div>
                        <?= e($u['name']) ?>
                    </div>
                </td>
                <td><?= e($u['email']) ?></td>
                <td><span class="role-badge role-<?= e($u['role']) ?>"><?= ucfirst(e($u['role'])) ?></span></td>
                <td>
                    <button class="toggle-verify-btn btn btn-sm <?= $u['is_verified'] ? 'btn-success' : 'btn-warning' ?>"
                            data-id="<?= $u['id'] ?>"
                            data-verified="<?= $u['is_verified'] ?>">
                        <?= $u['is_verified'] ? '✅ Verified' : '⏳ Pending' ?>
                    </button>
                </td>
                <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                <td>
                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                    <button class="btn btn-sm btn-danger del-user-btn" data-id="<?= $u['id'] ?>" data-name="<?= e($u['name']) ?>">🗑 Delete</button>
                    <?php else: ?>
                    <span class="text-muted">You</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<script>
var BASE_URL = '<?= BASE_URL ?>';

// Toggle verification (AJAX)
document.querySelectorAll('.toggle-verify-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id       = this.getAttribute('data-id');
        var verified = this.getAttribute('data-verified') === '1';
        var self     = this;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', BASE_URL + '/api/admin_toggle_verify.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res.success) {
                        var nowVerified = !verified;
                        self.setAttribute('data-verified', nowVerified ? '1' : '0');
                        self.textContent = nowVerified ? '✅ Verified' : '⏳ Pending';
                        self.className = 'toggle-verify-btn btn btn-sm ' + (nowVerified ? 'btn-success' : 'btn-warning');
                    }
                } catch(e) {}
            }
        };
        xhr.send('id=' + encodeURIComponent(id));
    });
});

// Delete user (AJAX)
document.querySelectorAll('.del-user-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id   = this.getAttribute('data-id');
        var name = this.getAttribute('data-name');
        if (!confirm('Delete user "' + name + '"? This will remove all their data.')) return;
        var row = document.getElementById('user-row-' + id);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', BASE_URL + '/api/admin_delete_user.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res.success) {
                        row.style.opacity = '0';
                        row.style.transition = 'opacity 0.3s';
                        setTimeout(function() { row.remove(); }, 300);
                    } else { alert(res.message || 'Failed.'); }
                } catch(e) {}
            }
        };
        xhr.send('id=' + encodeURIComponent(id));
    });
});
</script>
<?php require_once __DIR__ . '/../shared/footer.php'; ?>
