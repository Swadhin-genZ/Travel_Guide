<?php
$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/PostModel.php';
require_once __DIR__ . '/../../models/PostRequestModel.php';
require_once __DIR__ . '/../../models/OtherModels.php';
require_once __DIR__ . '/../../controllers/AdminController.php';
startSession();
requireRole('admin');

$ctrl  = new AdminController();
$stats = $ctrl->getDashboardStats();

require_once __DIR__ . '/../shared/header.php';
?>
<div class="container">
    <div class="page-header">
        <h1>Admin Dashboard</h1>
        <p>Welcome back, <?= e($_SESSION['name']) ?>! Here's your site overview.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-blue">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <div class="stat-num"><?= array_sum($stats['user_counts']) ?></div>
                <div class="stat-label">Total Users</div>
                <div class="stat-sub"><?= $stats['user_counts']['scout'] ?> scouts · <?= $stats['user_counts']['user'] ?> travelers</div>
            </div>
        </div>
        <div class="stat-card stat-yellow">
            <div class="stat-icon">📬</div>
            <div class="stat-info">
                <div class="stat-num"><?= $stats['pending_reqs'] ?></div>
                <div class="stat-label">Pending Requests</div>
            </div>
        </div>
        <div class="stat-card stat-green">
            <div class="stat-icon">🗺️</div>
            <div class="stat-info">
                <div class="stat-num"><?= $stats['total_posts'] ?></div>
                <div class="stat-label">Published Posts</div>
            </div>
        </div>
        <div class="stat-card stat-purple">
            <div class="stat-icon">💬</div>
            <div class="stat-info">
                <div class="stat-num"><?= $stats['total_comments'] ?></div>
                <div class="stat-label">Total Comments</div>
            </div>
        </div>
    </div>

    <div class="admin-quick-links">
        <a href="<?= BASE_URL ?>/views/admin/users.php" class="quick-link-card">
            <span class="ql-icon">👥</span>
            <span>Manage Users</span>
        </a>
        <a href="<?= BASE_URL ?>/views/admin/requests.php" class="quick-link-card">
            <span class="ql-icon">📋</span>
            <span>Review Requests</span>
            <?php if ($stats['pending_reqs'] > 0): ?>
                <span class="badge-count"><?= $stats['pending_reqs'] ?></span>
            <?php endif; ?>
        </a>
        <a href="<?= BASE_URL ?>/views/admin/posts.php" class="quick-link-card">
            <span class="ql-icon">🗺️</span>
            <span>Manage Posts</span>
        </a>
        <a href="<?= BASE_URL ?>/views/admin/comments.php" class="quick-link-card">
            <span class="ql-icon">💬</span>
            <span>Moderate Comments</span>
        </a>
        <a href="<?= BASE_URL ?>/views/admin/add_user.php" class="quick-link-card">
            <span class="ql-icon">➕</span>
            <span>Add New User</span>
        </a>
    </div>
</div>
<?php require_once __DIR__ . '/../shared/footer.php'; ?>
