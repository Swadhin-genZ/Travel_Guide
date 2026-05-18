<?php
// [TASK 2] My Requests view
require 'views/layouts/header.php';
?>
<h2>My Requests</h2>
<a href="index.php?action=scout_create" class="btn btn-primary" style="margin-bottom:1rem;">+ New Request</a>

<?php if (empty($requests)): ?>
    <p>No requests yet.</p>
<?php else: ?>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>Title</th><th>Country</th><th>Genre</th><th>Status</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $req):
                    $data = json_decode($req['post_data'], true);
                ?>
                <tr id="req-<?= $req['id'] ?>">
                    <td><?= htmlspecialchars($data['title'] ?? '') ?></td>
                    <td><?= htmlspecialchars($data['country'] ?? '') ?></td>
                    <td><?= htmlspecialchars($data['genre'] ?? '') ?></td>
                    <td><span class="status-badge status-<?= $req['status'] ?>"><?= $req['status'] ?></span></td>
                    <td><?= date('M d, Y', strtotime($req['requested_at'])) ?></td>
                    <td>
                        <?php if ($req['status'] === 'pending'): ?>
                            <a href="index.php?action=scout_edit&id=<?= $req['id'] ?>" class="btn btn-sm">Edit</a>
                            <button class="btn btn-sm btn-danger" onclick="deleteRequest(<?= $req['id'] ?>)">Delete</button>
                        <?php else: ?>
                            <span class="muted">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php if (!empty($approvedPosts)): ?>
    <h3 style="margin-top:2rem;">My Published Posts</h3>
    <div class="post-grid">
        <?php foreach ($approvedPosts as $post): ?>
            <div class="card">
                <div class="card-body">
                    <h3><?= htmlspecialchars($post['title']) ?></h3>
                    <p>📍 <?= htmlspecialchars($post['country']) ?></p>
                    <a href="index.php?action=scout_create&original=<?= $post['id'] ?>" class="btn btn-sm btn-outline">Request Changes</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script src="/travel_guide/public/js/scout.js"></script>
<?php require 'views/layouts/footer.php'; ?>