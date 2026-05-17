<?php
//Profile view
require 'views/layouts/header.php';
?>
<div class="form-container">
    <h2>My Profile</h2>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php foreach ($errors as $e): ?>
        <div class="alert alert-error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <?php if ($user['profile_picture']): ?>
        <img src="/travel_guide/<?= htmlspecialchars($user['profile_picture']) ?>" class="avatar" alt="Profile Picture">
    <?php endif; ?>

    <form method="POST" action="index.php?action=profile" enctype="multipart/form-data" id="profileForm" novalidate>
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            <span class="field-error" id="nameError"></span>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            <span class="field-error" id="emailError"></span>
        </div>
        <div class="form-group">
            <label>Profile Picture</label>
            <input type="file" name="profile_picture" accept="image/*">
        </div>
        <hr>
        <h4>Change Password (optional)</h4>
        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" placeholder="Leave blank to keep current">
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" id="new_password" placeholder="Min 8 chars">
            <span class="field-error" id="passError"></span>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
    </form>
</div>
<script src="/travel_guide/public/js/auth.js"></script>
<?php require 'views/layouts/footer.php'; ?>