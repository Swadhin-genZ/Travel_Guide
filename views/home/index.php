<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - TravelGuide</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; margin: 0; }
        .hero { background: #2c3e50; color: #fff; text-align: center; padding: 80px 24px; }
        .hero h1 { font-size: 42px; margin-bottom: 12px; }
        .hero p  { font-size: 18px; margin-bottom: 24px; color: #bdc3c7; }
        .btn { display: inline-block; padding: 12px 28px; background: #e67e22; color: #fff; text-decoration: none; border-radius: 4px; font-size: 16px; margin: 4px; }
        .btn:hover { background: #d35400; }
        .section { max-width: 1100px; margin: 40px auto; padding: 0 24px; }
        .section h2 { color: #2c3e50; margin-bottom: 24px; }
        .cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .card { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; }
        .card img { width: 100%; height: 180px; object-fit: cover; }
        .card-body { padding: 16px; }
        .card-body h3 { margin: 0 0 8px; color: #2c3e50; }
        .card-body p  { margin: 0 0 8px; color: #666; font-size: 14px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .low    { background: #e8f8e8; color: #27ae60; }
        .medium { background: #fef9e7; color: #f39c12; }
        .high   { background: #fde8e8; color: #e74c3c; }
        .notice { background: #fff3cd; border: 1px solid #ffc107; color: #856404; padding: 20px; border-radius: 8px; text-align: center; margin: 40px auto; max-width: 600px; }
    </style>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<?php
$loggedIn = !empty($_SESSION['user_id']);
$verified = $_SESSION['is_verified'] ?? 0;
?>

<?php if (!$loggedIn): ?>
    <!-- Guest View -->
    <div class="hero">
        <h1>🌍 Discover the World</h1>
        <p>Your ultimate travel companion. Explore destinations, plan trips, and build your wishlist.</p>
        <a href="/register" class="btn">Get Started</a>
        <a href="/login"    class="btn" style="background:#27ae60;">Login</a>
    </div>

<?php elseif (!$verified): ?>
    <!-- Logged in but not verified -->
    <div class="notice">
        <h2>⏳ Account Pending Approval</h2>
        <p>Your account is currently awaiting admin approval. You will be notified once verified.</p>
    </div>

<?php else: ?>
    <!-- Verified user: show latest posts -->
    <div class="hero" style="padding:40px 24px;">
        <h1>Welcome back, <?= htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8') ?>! 👋</h1>
        <p>Explore the latest travel destinations below.</p>
        <?php if ($_SESSION['role'] === 'user'): ?>
            <a href="/wishlist" class="btn">My Wishlist ❤️</a>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>Latest Destinations</h2>
        <?php if (empty($posts)): ?>
            <p style="color:#666;">No approved destinations yet. Check back soon!</p>
        <?php else: ?>
            <div class="cards">
                <?php foreach ($posts as $post): ?>
                    <div class="card">
                        <?php if (!empty($post['image_path'])): ?>
                            <img src="/<?= htmlspecialchars($post['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>">
                        <?php else: ?>
                            <div style="height:180px;background:#dfe6e9;display:flex;align-items:center;justify-content:center;color:#999;font-size:40px;">🗺️</div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h3><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p>📍 <?= htmlspecialchars($post['country'], ENT_QUOTES, 'UTF-8') ?> &nbsp;|&nbsp; 🏷️ <?= htmlspecialchars($post['genre'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p><?= htmlspecialchars(substr($post['short_history'], 0, 100), ENT_QUOTES, 'UTF-8') ?>...</p>
                            <span class="badge <?= $post['cost_level'] ?>"><?= strtoupper($post['cost_level']) ?> COST</span>

                            <?php if ($_SESSION['role'] === 'user'): ?>
                                <button
                                    class="wishlist-btn"
                                    data-post-id="<?= $post['id'] ?>"
                                    style="float:right;background:#e74c3c;color:#fff;border:none;padding:4px 12px;border-radius:4px;cursor:pointer;">
                                    ❤️ Wishlist
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($_SESSION['role'] === 'user'): ?>
        <script src="/public/js/wishlist.js"></script>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>
