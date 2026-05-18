<?php
// [TASK 2] Scout Dashboard
require 'views/layouts/header.php';
?>
<h2>Scout Dashboard</h2>
<div class="stats-row">
    <div class="stat-card">
        <h3><?= count($requests) ?></h3>
        <p>Total Requests</p>
    </div>
    <div class="stat-card">
        <h3><?= count(array_filter($requests, fn($r) => $r['status'] === 'pending')) ?></h3>
        <p>Pending</p>
    </div>
    <div class="stat-card">
        <h3><?= count(array_filter($requests, fn($r) => $r['status'] === 'approved')) ?></h3>
        <p>Approved</p>
    </div>
</div>
<div class="quick-actions">
    <a href="index.php?action=scout_create" class="btn btn-primary">+ New Request</a>
    <a href="index.php?action=scout_my_requests" class="btn btn-outline">My Requests</a>
</div>
<?php require 'views/layouts/footer.php'; ?>