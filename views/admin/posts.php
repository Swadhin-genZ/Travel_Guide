<?php
// [TASK 3] Admin Posts Moderation
require 'views/layouts/header.php';
?>
<h2>Post Moderation</h2>
<div class="table-wrap">
    <table class="data-table">
        <thead><tr><th>Title</th><th>Scout</th><th>Country</th><th>Genre</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= htmlspecialchars($post['title']) ?></td>
                <td><?= htmlspecialchars($post['scout_name']) ?></td>
                <td><?= htmlspecialchars($post['country']) ?></td>
                <td><?= htmlspecialchars($post['genre']) ?></td>
                <td><span class="status-badge status-<?= $post['status'] ?>"><?= $post['status'] ?></span></td>
                <td>
                    <a href="index.php?action=admin_edit_post&id=<?= $post['id'] ?>" class="btn btn-sm">Edit</a>
                    <form method="POST" action="index.php?action=admin_delete_post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $post['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete post?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require 'views/layouts/footer.php'; ?>