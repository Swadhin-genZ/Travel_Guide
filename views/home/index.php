<?php
//Home page 
require 'views/layouts/header.php';
?>
<?php if (!isset($_SESSION['user_id'])): ?>
    <section class="hero">
        <h1>Discover the World</h1>
        <p>Your ultimate travel companion for finding amazing destinations worldwide.</p>
        <div class="hero-btns">
            <a href="index.php?action=register" class="btn btn-primary">Get Started</a>
            <a href="index.php?action=login" class="btn btn-outline">Login</a>
        </div>
    </section>
<?php elseif (!$_SESSION['verified']): ?>
    <div class="alert alert-warning">
        <strong>Pending Approval</strong> — Your account is pending admin approval. You'll get full access once verified.
    </div>
<?php else: ?>
    <h2>Latest Destinations</h2>
    <div class="post-grid">
        <?php foreach ($posts as $post): ?>
            <div class="card">
                <?php if ($post['image_path']): ?>
                    <img src="<?= htmlspecialchars($post['image_path']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                <?php endif; ?>
                <div class="card-body">
                    <span class="badge"><?= htmlspecialchars($post['genre']) ?></span>
                    <h3><?= htmlspecialchars($post['title']) ?></h3>
                    <p class="meta">📍 <?= htmlspecialchars($post['country']) ?> &bull; 💰 <?= htmlspecialchars($post['cost_level']) ?></p>
                    <p><?= htmlspecialchars(substr($post['short_history'], 0, 120)) ?>...</p>
                    <a href="index.php?action=post_detail&id=<?= $post['id'] ?>" class="btn btn-sm">Read More</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ($_SESSION['role'] === 'user'): ?>
        <a href="index.php?action=browse" class="btn btn-primary" style="margin-top:1rem;">Browse All Destinations</a>
    <?php endif; ?>
<?php endif; ?>
<?php require 'views/layouts/footer.php'; ?>