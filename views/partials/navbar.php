<?php
$loggedIn = !empty($_SESSION['user_id']);
$role     = $_SESSION['role'] ?? '';
$verified = $_SESSION['is_verified'] ?? 0;
$name     = htmlspecialchars($_SESSION['name'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<nav style="background:#2c3e50;padding:12px 20px;display:flex;justify-content:space-between;align-items:center;">
  <a href="index.php?page=home" style="color:#fff;font-weight:bold;text-decoration:none;font-size:18px;">🌍 TravelGuide</a>
  <div>
    <?php if (!$loggedIn): ?>
      <a href="index.php?page=login"    style="color:#fff;margin-left:16px;text-decoration:none;">Login</a>
      <a href="index.php?page=register" style="color:#fff;margin-left:16px;text-decoration:none;">Register</a>
    <?php else: ?>
      <span style="color:#bdc3c7;font-size:13px;">👤 <?= $name ?> (<?= $role ?>)</span>
      <a href="index.php?page=home"    style="color:#fff;margin-left:16px;text-decoration:none;">Home</a>
      <a href="index.php?page=profile" style="color:#fff;margin-left:16px;text-decoration:none;">Profile</a>
      <?php if ($role==='user' && $verified): ?>
        <a href="index.php?page=wishlist" style="color:#fff;margin-left:16px;text-decoration:none;">❤️ Wishlist</a>
      <?php endif; ?>
      <a href="index.php?page=logout" style="color:#e74c3c;margin-left:16px;text-decoration:none;">Logout</a>
    <?php endif; ?>
  </div>
</nav>