<?php
// Wishlist view
require 'views/layouts/header.php';
?>
<h2>My Wishlist</h2>
<?php if (empty($items)): ?>
    <p>Your wishlist is empty. <a href="index.php?action=browse">Browse destinations</a></p>
<?php else: ?>
    <div class="post-grid" id="wishlistGrid">
        <?php foreach ($items as $item): ?>
            <div class="card" id="wl-<?= $item['post_id'] ?>">
                <div class="card-body">
                    <span class="badge"><?= htmlspecialchars($item['genre']) ?></span>
                    <h3><?= htmlspecialchars($item['title']) ?></h3>
                    <p>📍 <?= htmlspecialchars($item['country']) ?> &bull; 💰 <?= htmlspecialchars($item['cost_level']) ?></p>
                    <a href="index.php?action=post_detail&id=<?= $item['post_id'] ?>" class="btn btn-sm">View</a>
                    <button class="btn btn-sm btn-danger" onclick="removeWishlist(<?= $item['post_id'] ?>)">Remove</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<script src="/travel_guide/public/js/wishlist.js"></script>
<?php require 'views/layouts/footer.php'; ?>