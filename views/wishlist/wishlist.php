<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Wishlist</title></head>
<body>
<?php require ROOT . '/views/partials/navbar.php'; ?>
<div style="max-width:860px;margin:30px auto;padding:0 20px;">
  <h2>❤️ My Wishlist</h2>
  <?php if (empty($items)): ?>
    <p>Your wishlist is empty. <a href="index.php?page=home">Browse destinations</a></p>
  <?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%;border-collapse:collapse;">
      <tr style="background:#2c3e50;color:#fff;">
        <th>#</th><th>Title</th><th>Country</th><th>Genre</th><th>Cost</th><th>Added</th><th>Action</th>
      </tr>
      <?php foreach ($items as $i => $item): ?>
      <tr id="row-<?= $item['post_id'] ?>">
        <td><?= $i+1 ?></td>
        <td><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($item['country'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($item['genre'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= strtoupper($item['cost_level']) ?></td>
        <td><?= date('d M Y', strtotime($item['added_at'])) ?></td>
        <td><button class="btn-remove" data-id="<?= $item['post_id'] ?>" style="background:#e74c3c;color:#fff;border:none;padding:5px 10px;cursor:pointer;">Remove</button></td>
      </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>
<div id="toast" style="position:fixed;bottom:20px;right:20px;padding:12px 20px;border-radius:6px;color:#fff;display:none;"></div>
<script src="public/js/wishlist.js"></script>
</body></html>