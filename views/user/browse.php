<?php
// [TASK 4] Browse Posts view
require 'views/layouts/header.php';
?>
<h2>Browse Destinations</h2>

<div class="filter-bar">
    <input type="text" id="searchBox" placeholder="Search by title or country..." class="search-input">
    <select id="filterCountry">
        <option value="">All Countries</option>
        <?php foreach ($countries as $c): ?>
            <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
        <?php endforeach; ?>
    </select>
    <select id="filterGenre">
        <option value="">All Genres</option>
        <?php foreach (['beach','mountain','city','historical','other'] as $g): ?>
            <option value="<?= $g ?>"><?= ucfirst($g) ?></option>
        <?php endforeach; ?>
    </select>
    <select id="filterCost">
        <option value="">All Costs</option>
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
    </select>
</div>

<div id="postGrid" class="post-grid">
    <?php foreach ($posts as $post): ?>
    <div class="card" data-id="<?= $post['id'] ?>">
        <?php if ($post['image_path']): ?>
            <img src="/travel_guide/<?= htmlspecialchars($post['image_path']) ?>" alt="">
        <?php endif; ?>
        <div class="card-body">
            <span class="badge"><?= htmlspecialchars($post['genre']) ?></span>
            <h3><?= htmlspecialchars($post['title']) ?></h3>
            <p class="meta">📍 <?= htmlspecialchars($post['country']) ?> &bull; 💰 <?= htmlspecialchars($post['cost_level']) ?></p>
            <p><?= htmlspecialchars(substr($post['short_history'], 0, 100)) ?>...</p>
            <a href="index.php?action=post_detail&id=<?= $post['id'] ?>" class="btn btn-sm">Read More</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<script src="/travel_guide/public/js/user.js"></script>
<?php require 'views/layouts/footer.php'; ?>