<?php
// [TASK 3] Admin Dashboard
require 'views/layouts/header.php';
?>
<h2>Admin Dashboard</h2>
<div class="stats-row">
    <div class="stat-card"><h3><?= $userCounts['user'] ?? 0 ?></h3><p>Users</p></div>
    <div class="stat-card"><h3><?= $userCounts['scout'] ?? 0 ?></h3><p>Scouts</p></div>
    <div class="stat-card"><h3><?= $totalPosts ?></h3><p>Posts</p></div>
    <div class="stat-card"><h3><?= $pendingReqs ?></h3><p>Pending Requests</p></div>
    <div class="stat-card"><h3><?= $totalComments ?></h3><p>Comments</p></div>
</div>

<h3>Pending Post Requests</h3>
<?php if (empty($pendingRequests)): ?>
    <p>No pending requests.</p>
<?php else: ?>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>Scout</th><th>Title</th><th>Country</th><th>Submitted</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($pendingRequests as $req):
                    $d = json_decode($req['post_data'], true);
                ?>
                <tr id="preq-<?= $req['id'] ?>">
                    <td><?= htmlspecialchars($req['scout_name']) ?></td>
                    <td><?= htmlspecialchars($d['title'] ?? '') ?></td>
                    <td><?= htmlspecialchars($d['country'] ?? '') ?></td>
                    <td><?= date('M d, Y', strtotime($req['requested_at'])) ?></td>
                    <td>
                        <button class="btn btn-sm btn-success" onclick="approvePost(<?= $req['id'] ?>)">Approve</button>
                        <button class="btn btn-sm btn-danger" onclick="rejectPost(<?= $req['id'] ?>)">Reject</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<script src="/travel_guide/public/js/admin.js"></script>
<?php require 'views/layouts/footer.php'; ?>