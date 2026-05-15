<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Profile</title></head>
<body>
<?php require ROOT . '/views/partials/navbar.php'; ?>
<div style="max-width:480px;margin:40px auto;padding:24px;border:1px solid #ccc;border-radius:6px;">
  <h2>My Profile</h2>
  <?php if ($error):   ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <?php if ($success): ?><p style="color:green;"><?= htmlspecialchars($success) ?></p><?php endif; ?>

  <?php if (!empty($user['profile_picture'])): ?>
    <img src="public/uploads/<?= htmlspecialchars($user['profile_picture'], ENT_QUOTES, 'UTF-8') ?>" style="width:80px;height:80px;border-radius:50%;object-fit:cover;">
  <?php endif; ?>
  <p style="font-size:13px;">Role: <b><?= $user['role'] ?></b> | Status: <b><?= $user['is_verified'] ? '✅ Verified' : '⏳ Pending' ?></b></p>

  <form id="profileForm" method="POST" action="index.php?page=profile" enctype="multipart/form-data" novalidate>
    <p><label>Name<br><input type="text" name="name" id="pro_name" value="<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>" style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p style="color:red;display:none;" id="err_pro_name">Name is required.</p>
    <p><label>Email<br><input type="email" name="email" id="pro_email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>" style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p style="color:red;display:none;" id="err_pro_email">Enter valid email.</p>
    <p><label>Profile Picture<br><input type="file" name="profile_picture" accept="image/*"></label></p>
    <hr>
    <p><b>Change Password</b> <small>(leave blank to keep current)</small></p>
    <p><label>Current Password<br><input type="password" name="current_password" style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p><label>New Password<br><input type="password" name="new_password" id="pro_newpw" style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p style="color:red;display:none;" id="err_pro_newpw">Min 8 characters.</p>
    <p><label>Confirm New Password<br><input type="password" name="confirm_password" id="pro_confirm" style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p style="color:red;display:none;" id="err_pro_confirm">Passwords do not match.</p>
    <button type="submit" style="width:100%;padding:10px;background:#2c3e50;color:#fff;border:none;cursor:pointer;">Save Changes</button>
  </form>
</div>
<script src="public/js/validation.js"></script>
</body></html>