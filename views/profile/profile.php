<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - TravelGuide</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; margin: 0; }
        .container { max-width: 560px; margin: 40px auto; background: #fff; padding: 32px; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; margin-bottom: 24px; }
        label { display: block; margin-bottom: 4px; font-weight: bold; color: #555; }
        input[type=text], input[type=email], input[type=password], input[type=file] {
            width: 100%; padding: 10px; margin-bottom: 16px; border: 1px solid #ccc;
            border-radius: 4px; box-sizing: border-box; font-size: 14px;
        }
        button { width: 100%; padding: 12px; background: #2c3e50; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background: #34495e; }
        .alert-error   { background: #fde8e8; color: #c0392b; padding: 10px; border-radius: 4px; margin-bottom: 16px; }
        .alert-success { background: #e8f8e8; color: #27ae60; padding: 10px; border-radius: 4px; margin-bottom: 16px; }
        .avatar { text-align: center; margin-bottom: 20px; }
        .avatar img { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #2c3e50; }
        hr { margin: 24px 0; border: none; border-top: 1px solid #eee; }
        .error-msg { color: #e74c3c; font-size: 12px; margin-top: -12px; margin-bottom: 8px; display: none; }
    </style>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container">
    <h2>My Profile</h2>

    <?php if ($error): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <!-- Avatar -->
    <div class="avatar">
        <?php if (!empty($user['profile_picture'])): ?>
            <img src="/<?= htmlspecialchars($user['profile_picture'], ENT_QUOTES, 'UTF-8') ?>" alt="Profile Picture">
        <?php else: ?>
            <div style="width:100px;height:100px;border-radius:50%;background:#dfe6e9;display:flex;align-items:center;justify-content:center;font-size:40px;margin:auto;">👤</div>
        <?php endif; ?>
        <p style="color:#666;font-size:13px;">Role: <strong><?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?></strong> &nbsp;|&nbsp;
        Status: <strong><?= $user['is_verified'] ? '✅ Verified' : '⏳ Pending' ?></strong></p>
    </div>

    <form id="profileForm" method="POST" action="/profile" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>">
        <span class="error-msg" id="nameError">Name is required.</span>

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>">
        <span class="error-msg" id="emailError">Enter a valid email.</span>

        <label for="profile_picture">Profile Picture (JPEG/PNG/GIF/WEBP, max 2MB)</label>
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*">

        <hr>
        <h3 style="color:#2c3e50;margin-bottom:16px;">Change Password <small style="font-weight:normal;font-size:13px;color:#999;">(leave blank to keep current)</small></h3>

        <label for="current_password">Current Password</label>
        <input type="password" id="current_password" name="current_password" placeholder="Enter current password">

        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password" placeholder="Minimum 8 characters">
        <span class="error-msg" id="newPwError">New password must be at least 8 characters.</span>

        <label for="confirm_password">Confirm New Password</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat new password">
        <span class="error-msg" id="confirmError">Passwords do not match.</span>

        <button type="submit">Save Changes</button>
    </form>
</div>

<script src="/public/js/profile.js"></script>
</body>
</html>
