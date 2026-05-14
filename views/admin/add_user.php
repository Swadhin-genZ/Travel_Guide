<?php
$pageTitle = 'Add User';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../controllers/AdminController.php';
startSession();
requireRole('admin');

$ctrl   = new AdminController();
$errors = $ctrl->addUser();

require_once __DIR__ . '/../shared/header.php';
?>
<div class="container">
    <div class="page-header">
        <h1>Add New User</h1>
        <p>Create a user account manually</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $err): ?><p><?= e($err) ?></p><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="card form-card form-card-sm">
        <form id="addUserForm" method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="name" id="uName" class="form-control"
                       value="<?= e($_POST['name'] ?? '') ?>" required>
                <span class="field-error" id="uNameErr"></span>
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" id="uEmail" class="form-control"
                       value="<?= e($_POST['email'] ?? '') ?>" required>
                <span class="field-error" id="uEmailErr"></span>
            </div>
            <div class="form-group">
                <label>Password *</label>
                <div class="input-eye-wrap">
                    <input type="password" name="password" id="uPass" class="form-control" required placeholder="Min 8 characters">
                    <button type="button" class="toggle-eye" onclick="togglePass('uPass')">👁</button>
                </div>
                <span class="field-error" id="uPassErr"></span>
            </div>
            <div class="form-row">
                <div class="form-group flex-1">
                    <label>Role *</label>
                    <select name="role" class="form-control">
                        <?php foreach (['user','scout','admin'] as $r): ?>
                            <option value="<?= $r ?>" <?= ($_POST['role'] ?? 'user') === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group flex-1">
                    <label>Verification</label>
                    <div class="form-check mt-1">
                        <label>
                            <input type="checkbox" name="is_verified" value="1"
                                   <?= isset($_POST['is_verified']) ? 'checked' : 'checked' ?>>
                            Mark as Verified
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?= BASE_URL ?>/views/admin/users.php" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Create User</button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePass(id) {
    var inp = document.getElementById(id);
    inp.type = inp.type === 'password' ? 'text' : 'password';
}
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    var valid = true;
    ['uNameErr','uEmailErr','uPassErr'].forEach(function(id) { document.getElementById(id).textContent = ''; });
    if (!document.getElementById('uName').value.trim())  { document.getElementById('uNameErr').textContent  = 'Name required.'; valid = false; }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(document.getElementById('uEmail').value.trim())) { document.getElementById('uEmailErr').textContent = 'Valid email required.'; valid = false; }
    if (document.getElementById('uPass').value.length < 8) { document.getElementById('uPassErr').textContent = 'Min 8 characters.'; valid = false; }
    if (!valid) e.preventDefault();
});
</script>
<?php require_once __DIR__ . '/../shared/footer.php'; ?>
