<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Home</title></head>
<body>
<?php require ROOT . '/views/partials/navbar.php'; ?>
<div style="max-width:900px;margin:30px auto;padding:0 20px;">

<?php if (empty($_SESSION['user_id'])): ?>
  <!-- NON REGISTERED USER -->
  <div style="text-align:center;padding:60px 20px;">
    <h1>🌍 Welcome to TravelGuide</h1>
    <p>Discover amazing destinations around the world.</p>
    <a href="index.php?page=register" style="background:#2c3e50;color:#fff;padding:10px 24px;text-decoration:none;border-radius:4px;margin-right:8px;">Register</a>
    <a href="index.php?page=login" style="background:#e67e22;color:#fff;padding:10px 24px;text-decoration:none;border-radius:4px;">Login</a>
  </div>

<?php elseif (empty($_SESSION['is_verified'])): ?>
  <!-- LOGGED IN BUT NOT VERIFIED -->
  <div style="background:#fff3cd;padding:20px;border-radius:6px;text-align:center;">
    <h3>⏳ Pending Admin Approval</h3>
    <p>Your account is not yet verified. Please wait.</p>
  </div>

<?php else: ?>
  <!-- VERIFIED USER -->
  <h2>Welcome, <?= htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8') ?>! 🌍</h2>
  <?php if ($_SESSION['role']==='user'): ?>
    <a href="index.php?page=wishlist">❤️ My Wishlist</a>
  <?php endif; ?>
  <h3>Latest Destinations</h3>
  <?php if (empty($posts)): ?>
    <p>No destinations published yet.</p>
  <?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;margin-top:16px;">
      <?php foreach ($posts as $p): ?>
      <div style="border:1px solid #ddd;padding:16px;border-radius:6px;background:#fff;">
        <h4 style="margin:0 0 8px;"><?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?></h4>
        <p style="margin:0 0 6px;font-size:13px;">📍 <?= htmlspecialchars($p['country'], ENT_QUOTES, 'UTF-8') ?> | <?= htmlspecialchars($p['genre'], ENT_QUOTES, 'UTF-8') ?></p>
        <p style="margin:0 0 8px;font-size:13px;"><?= htmlspecialchars(substr($p['short_history'],0,100), ENT_QUOTES, 'UTF-8') ?>...</p>
        <span style="font-size:12px;font-weight:bold;">Cost: <?= strtoupper($p['cost_level']) ?></span>
        <?php if ($_SESSION['role']==='user'): ?>
          <button class="wishlist-btn" data-id="<?= $p['id'] ?>" style="float:right;background:#e74c3c;color:#fff;border:none;padding:4px 10px;border-radius:4px;cursor:pointer;">❤️ Add</button>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
<?php endif; ?>

</div>
<div id="toast" style="position:fixed;bottom:20px;right:20px;padding:12px 20px;border-radius:6px;color:#fff;display:none;"></div>
<?php if (!empty($_SESSION['role']) && $_SESSION['role']==='user'): ?>
<script src="public/js/wishlist.js"></script>
<?php endif; ?>
</body></html>