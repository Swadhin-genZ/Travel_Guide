<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Guide</title>
    <link rel="stylesheet" href="/travel_guide/public/css/style.css">
</head>
<body>
<nav class="navbar">
    <a class="brand" href="index.php?action=home">✈ TravelGuide</a>
    <div class="nav-links">
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="index.php?action=admin_dashboard">Dashboard</a>
                <a href="index.php?action=admin_users">Users</a>
                <a href="index.php?action=admin_posts">Posts</a>
                <a href="index.php?action=admin_comments">Comments</a>
            <?php elseif ($_SESSION['role'] === 'scout' && $_SESSION['verified']): ?>
                <a href="index.php?action=scout_dashboard">Dashboard</a>
                <a href="index.php?action=scout_create">New Request</a>
                <a href="index.php?action=scout_my_requests">My Requests</a>
            <?php elseif ($_SESSION['role'] === 'user' && $_SESSION['verified']): ?>
                <a href="index.php?action=browse">Browse</a>
                <a href="index.php?action=wishlist">Wishlist</a>
            <?php endif; ?>
            <a href="index.php?action=profile">Profile</a>
            <a href="index.php?action=logout">Logout</a>
        <?php else: ?>
            <a href="index.php?action=login">Login</a>
            <a href="index.php?action=register">Register</a>
        <?php endif; ?>
    </div>
</nav>
<main class="container">
<?php if (!empty($_SESSION['flash'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash']) ?></div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>