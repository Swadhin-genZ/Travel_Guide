<?php
// [TASK 3] Admin Users Management
require 'views/layouts/header.php';
?>
<h2>User Management</h2>

<details class="collapsible">
    <summary class="btn btn-primary">+ Add New User</summary>
    <div class="form-container" style="padding:1rem;">
        <form method="POST" action="index.php?action=admin_add_user">
            <div class="form-row">
                <div class="form-group">
                    <label>Name</label><input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email</label><input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label><input type="password" name="password" required minlength="8">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role">
                        <option value="user">User</option>
                        <option value="scout">Scout</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create User</button>
        </form>
    </div>
</details>

<div class="table-wrap" style="margin-top:1.5rem;">
    <table class="data-table">
        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Verified</th><th>Joined</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr id="user-<?= $u['id'] ?>">
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><span class="badge"><?= $u['role'] ?></span></td>
                <td>
                    <span id="vstatus-<?= $u['id'] ?>" class="status-badge status-<?= $u['is_verified'] ? 'approved' : 'pending' ?>">
                        <?= $u['is_verified'] ? 'Verified' : 'Pending' ?>
                    </span>
                </td>
                <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                <td>
                    <?php if ($u['is_verified']): ?>
                        <button class="btn btn-sm btn-outline" onclick="toggleVerify(<?= $u['id'] ?>, 0)">Unverify</button>
                    <?php else: ?>
                        <button class="btn btn-sm btn-success" onclick="toggleVerify(<?= $u['id'] ?>, 1)">Verify</button>
                    <?php endif; ?>
                    <form method="POST" action="index.php?action=admin_delete_user" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $u['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="/travel_guide/public/js/admin.js"></script>
<?php require 'views/layouts/footer.php'; ?>