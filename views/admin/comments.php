<?php
// [TASK 3] Admin Comment Moderation
require 'views/layouts/header.php';
?>
<h2>Comment Moderation</h2>
<div class="table-wrap">
    <table class="data-table">
        <thead><tr><th>Post</th><th>User</th><th>Comment</th><th>Date</th><th>Action</th></tr></thead>
        <tbody id="commentsTable">
            <?php foreach ($comments as $c): ?>
            <tr id="cmt-<?= $c['id'] ?>">
                <td><?= htmlspecialchars($c['post_title']) ?></td>
                <td><?= htmlspecialchars($c['user_name']) ?></td>
                <td><?= htmlspecialchars(substr($c['content'], 0, 80)) ?>...</td>
                <td><?= date('M d, Y', strtotime($c['created_at'])) ?></td>
                <td><button class="btn btn-sm btn-danger" onclick="adminDeleteComment(<?= $c['id'] ?>)">Delete</button></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="/travel_guide/public/js/admin.js"></script>
<?php require 'views/layouts/footer.php'; ?>