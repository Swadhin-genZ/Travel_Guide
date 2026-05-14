<?php
$loggedIn   = !empty($_SESSION['user_id']);
$role       = $_SESSION['role'] ?? '';
$verified   = $_SESSION['is_verified'] ?? 0;
$name       = htmlspecialchars($_SESSION['name'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<nav style="background:#2c3e50;padding:12px 24px;display:flex;justify-content:space-between;align-items:center;color:#fff;">
    <a href="/home" style="color:#fff;font-size:20px;font-weight:bold;text-decoration:none;">🌍 TravelGuide</a>
    <div style="display:flex;gap:16px;align-items:center;">
        <?php if (!$loggedIn): ?>
            <a href="/login"    style="color:#ecf0f1;text-decoration:none;">Login</a>
            <a href="/register" style="color:#ecf0f1;text-decoration:none;">Register</a>
        <?php else: ?>
            <span style="color:#bdc3c7;">Hi, <?= $name ?> (<?= $role ?>)</span>
            <a href="/home"    style="color:#ecf0f1;text-decoration:none;">Home</a>
            <a href="/profile" style="color:#ecf0f1;text-decoration:none;">Profile</a>
            <?php if ($role === 'user' && $verified): ?>
                <a href="/wishlist" style="color:#ecf0f1;text-decoration:none;">Wishlist</a>
            <?php endif; ?>
            <?php if ($role === 'admin'): ?>
                <a href="/admin" style="color:#ecf0f1;text-decoration:none;">Admin Panel</a>
            <?php endif; ?>
            <?php if ($role === 'scout' && $verified): ?>
                <a href="/scout" style="color:#ecf0f1;text-decoration:none;">Scout Panel</a>
            <?php endif; ?>
            <a href="/logout" style="color:#e74c3c;text-decoration:none;">Logout</a>
        <?php endif; ?>
    </div>
</nav>
