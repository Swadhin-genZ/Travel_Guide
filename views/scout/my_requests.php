<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>My Requests</title></head>
<body>
<div style="max-width:860px;margin:30px auto;padding:0 20px;">
  <h2>My Requests</h2>
  <a href="index.php?page=scout_create" style="background:#2c3e50;color:#fff;padding:8px 16px;text-decoration:none;border-radius:4px;">+ New Request</a>
  <br><br>

  <?php if (empty($requests)): ?>
    <p>No requests yet.</p>
  <?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%;border-collapse:collapse;">
      <tr style="background:#2c3e50;color:#fff;">
        <th>#</th><th>Title</th><th>Country</th><th>Genre</th><th>Cost</th><th>Status</th><th>Actions</th>
      </tr>
      <?php foreach ($requests as $i => $r):
        $d = json_decode($r['post_data'], true);
      ?>
      <tr id="row-<?= $r['id'] ?>">
        <td><?= $i+1 ?></td>
        <td><?= htmlspecialchars($d['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($d['country'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($d['genre'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= strtoupper($d['cost'] ?? '') ?></td>
        <td><?= htmlspecialchars($r['status'], ENT_QUOTES, 'UTF-8') ?></td>
        <td>
          <?php if ($r['status'] === 'pending'): ?>
            <a href="index.php?page=scout_edit&id=<?= $r['id'] ?>">Edit</a> |
            <button class="btn-delete" data-id="<?= $r['id'] ?>" style="background:#e74c3c;color:#fff;border:none;padding:4px 10px;cursor:pointer;">Delete</button>
          <?php else: ?>
            <span style="color:#999;">—</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>
<div id="toast" style="position:fixed;bottom:20px;right:20px;padding:12px 20px;border-radius:6px;color:#fff;display:none;"></div>
<script src="public/js/scout.js"></script>
</body></html>