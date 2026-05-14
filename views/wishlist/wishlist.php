<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - TravelGuide</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; margin: 0; }
        .container { max-width: 900px; margin: 40px auto; padding: 0 24px; }
        h2 { color: #2c3e50; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        th { background: #2c3e50; color: #fff; padding: 12px 16px; text-align: left; }
        td { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; color: #444; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f9f9f9; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .low    { background: #e8f8e8; color: #27ae60; }
        .medium { background: #fef9e7; color: #f39c12; }
        .high   { background: #fde8e8; color: #e74c3c; }
        .btn-remove { background: #e74c3c; color: #fff; border: none; padding: 6px 14px; border-radius: 4px; cursor: pointer; font-size: 13px; }
        .btn-remove:hover { background: #c0392b; }
        .empty { text-align: center; padding: 60px; color: #999; }
        .toast { position: fixed; bottom: 24px; right: 24px; background: #27ae60; color: #fff; padding: 12px 20px; border-radius: 6px; display: none; z-index: 999; }
    </style>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container">
    <h2>❤️ My Wishlist</h2>

    <?php if (empty($items)): ?>
        <div class="empty">
            <p style="font-size:48px;">🗺️</p>
            <p>Your wishlist is empty. Browse destinations and add some!</p>
            <a href="/home" style="color:#2980b9;">← Back to Home</a>
        </div>
    <?php else: ?>
        <table id="wishlistTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Country</th>
                    <th>Genre</th>
                    <th>Cost Level</th>
                    <th>Added</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $i => $item): ?>
                    <tr id="row-<?= $item['post_id'] ?>">
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($item['country'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($item['genre'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><span class="badge <?= $item['cost_level'] ?>"><?= strtoupper($item['cost_level']) ?></span></td>
                        <td><?= date('d M Y', strtotime($item['added_at'])) ?></td>
                        <td>
                            <button class="btn-remove" data-post-id="<?= $item['post_id'] ?>">Remove</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="toast" id="toast"></div>

<script src="/public/js/wishlist.js"></script>
</body>
</html>
