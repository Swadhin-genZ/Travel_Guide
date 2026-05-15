<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Register</title></head>
<body>
<?php require ROOT . '/views/partials/navbar.php'; ?>
<div style="max-width:420px;margin:50px auto;padding:24px;border:1px solid #ccc;border-radius:6px;">
  <h2>Register</h2>
  <?php if ($error):   ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <?php if ($success): ?><p style="color:green;"><?= htmlspecialchars($success) ?></p><?php endif; ?>
  <form id="regForm" method="POST" action="index.php?page=register" novalidate>
    <p><label>Name<br><input type="text" name="name" id="reg_name" style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p style="color:red;display:none;" id="err_name">Name is required.</p>
    <p><label>Email<br><input type="email" name="email" id="reg_email" style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p style="color:red;display:none;" id="err_email">Enter valid email.</p>
    <p><label>Role<br>
      <select name="role" id="reg_role" style="width:100%;padding:8px;">
        <option value="">-- Select --</option>
        <option value="user">General User</option>
        <option value="scout">Scout</option>
        <option value="admin">Admin</option>
      </select>
    </label></p>
    <p style="color:red;display:none;" id="err_role">Select a role.</p>
    <p><label>Password<br><input type="password" name="password" id="reg_pw" style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p style="color:red;display:none;" id="err_pw">Min 8 characters.</p>
    <p><label>Confirm Password<br><input type="password" name="confirm" id="reg_confirm" style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p style="color:red;display:none;" id="err_confirm">Passwords do not match.</p>
    <button type="submit" style="width:100%;padding:10px;background:#2c3e50;color:#fff;border:none;cursor:pointer;">Register</button>
  </form>
  <p>Already have account? <a href="index.php?page=login">Login</a></p>
</div>
<script src="public/js/validation.js"></script>
</body></html>