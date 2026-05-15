<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Login</title></head>
<body>
<?php require ROOT . '/views/partials/navbar.php'; ?>
<div style="max-width:400px;margin:60px auto;padding:24px;border:1px solid #ccc;border-radius:6px;">
  <h2>Login</h2>
  <?php if ($error):   ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <?php if ($success): ?><p style="color:green;"><?= htmlspecialchars($success) ?></p><?php endif; ?>
  <form method="POST" action="index.php?page=login">
    <p><label>Email<br><input type="email" name="email" required style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p><label>Password<br><input type="password" name="password" required style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p><label><input type="checkbox" name="remember"> Remember Me (30 days)</label></p>
    <button type="submit" style="width:100%;padding:10px;background:#2c3e50;color:#fff;border:none;cursor:pointer;">Login</button>
  </form>
  <p>No account? <a href="index.php?page=register">Register</a></p>
</div>
</body></html>