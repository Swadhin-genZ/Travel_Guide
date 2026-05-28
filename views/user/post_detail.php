<?php
// [TASK 4] Post Detail view
require 'views/layouts/header.php';
?>
<article class="post-detail">
    <?php if ($post['image_path']): ?>
        <img src="/travel_guide/<?= htmlspecialchars($post['image_path']) ?>" class="detail-img" alt="">
    <?php endif; ?>
    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <div class="meta-row">
        <span class="badge"><?= htmlspecialchars($post['genre']) ?></span>
        <span>📍 <?= htmlspecialchars($post['country']) ?></span>
        <span>💰 <?= ucfirst($post['cost_level']) ?></span>
        <span>🧭 By <?= htmlspecialchars($post['scout_name']) ?></span>
    </div>
    <div class="content">
        <h3>About this Place</h3>
        <p><?= nl2br(htmlspecialchars($post['short_history'])) ?></p>
        <?php if ($post['travel_medium_info']): ?>
            <h3>How to Get There</h3>
            <p><?= nl2br(htmlspecialchars($post['travel_medium_info'])) ?></p>
        <?php endif; ?>
    </div>

    <!-- Cost Calculator [TASK 4] -->
    <div class="cost-calculator">
        <h3>💵 Cost Estimator</h3>
        <p>Base cost: <strong>$<span id="baseCost"><?= $baseCost ?></span></strong></p>
        <div class="calc-row">
            <label>Travelers: <input type="number" id="travelers" value="1" min="1" max="10"></label>
            <label>Days: <input type="number" id="days" value="7" min="1" max="365"></label>
            <button class="btn btn-sm btn-primary" onclick="calculateCost()">Calculate</button>
        </div>
        <p id="calcResult" class="calc-result"></p>
        <span class="field-error" id="calcError"></span>
    </div>

    <!-- Wishlist [TASK 1] -->
    <?php if ($_SESSION['role'] === 'user'): ?>
    <div class="wishlist-action">
        <?php if ($inWishlist): ?>
            <button id="wlBtn" class="btn btn-outline btn-danger" onclick="removeWishlist(<?= $post['id'] ?>)">❤ Remove from Wishlist</button>
        <?php else: ?>
            <button id="wlBtn" class="btn btn-outline" onclick="addWishlist(<?= $post['id'] ?>)">🤍 Add to Wishlist</button>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</article>

<!-- Comments [TASK 4] -->
<section class="comments-section">
    <h3>Comments (<?= count($comments) ?>)</h3>

    <?php if ($_SESSION['role'] === 'user'): ?>
    <div class="comment-form">
        <textarea id="commentContent" placeholder="Share your thoughts..." maxlength="1000"></textarea>
        <span class="field-error" id="commentError"></span>
        <button class="btn btn-primary" onclick="submitComment(<?= $post['id'] ?>)">Post Comment</button>
    </div>
    <?php endif; ?>

    <div id="commentsList">
        <?php foreach ($comments as $c): ?>
        <div class="comment" id="comment-<?= $c['id'] ?>">
            <div class="comment-header">
                <strong><?= htmlspecialchars($c['user_name']) ?></strong>
                <span class="muted"><?= date('M d, Y H:i', strtotime($c['created_at'])) ?></span>
                <?php if ($c['user_id'] == $_SESSION['user_id'] || $_SESSION['role'] === 'admin'): ?>
                    <button class="btn btn-sm btn-danger" onclick="deleteComment(<?= $c['id'] ?>)">Delete</button>
                <?php endif; ?>
            </div>
            <p><?= nl2br(htmlspecialchars($c['content'])) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<script src="/travel_guide/public/js/wishlist.js"></script>
<script src="/travel_guide/public/js/user.js"></script>
<?php require 'views/layouts/footer.php'; ?>