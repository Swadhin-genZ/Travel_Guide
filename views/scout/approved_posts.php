<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Approved Posts</title></head>
<body>
<div style="max-width:860px;margin:30px auto;padding:0 20px;">
  <h2>My Approved Posts</h2>

  <?php if (!empty($_SESSION['error'])): ?>
    <p style="color:red;"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>
  <?php if (!empty($_SESSION['success'])): ?>
    <p style="color:green;"><?= htmlspecialchars($_SESSION['success']) ?></p>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <?php if (empty($posts)): ?>
    <p>No approved posts yet.</p>
  <?php else: ?>
    <?php foreach ($posts as $p): ?>
    <div style="border:1px solid #ddd;padding:16px;border-radius:6px;margin-bottom:16px;">
      <h3 style="margin:0 0 8px;"><?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?></h3>
      <p style="margin:0 0 6px;font-size:13px;">📍 <?= htmlspecialchars($p['country'], ENT_QUOTES, 'UTF-8') ?> | <?= $p['genre'] ?> | Cost: <?= strtoupper($p['cost_level']) ?></p>
      <p style="margin:0 0 10px;font-size:13px;"><?= htmlspecialchars(substr($p['short_history'], 0, 150), ENT_QUOTES, 'UTF-8') ?>...</p>

      <details>
        <summary style="cursor:pointer;color:#2980b9;">Request Change</summary>
        <form method="POST" action="index.php?page=scout_approved" style="margin-top:10px;">
          <input type="hidden" name="post_id" value="<?= $p['id'] ?>">
          <p><label>Title<br><input type="text" name="title" value="<?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?>" style="width:100%;padding:6px;box-sizing:border-box;"></label></p>
          <p><label>History<br><textarea name="history" rows="3" style="width:100%;padding:6px;box-sizing:border-box;"><?= htmlspecialchars($p['short_history'], ENT_QUOTES, 'UTF-8') ?></textarea></label></p>
          <p><label>Country<br><input type="text" name="country" value="<?= htmlspecialchars($p['country'], ENT_QUOTES, 'UTF-8') ?>" style="width:100%;padding:6px;box-sizing:border-box;"></label></p>
          <p><label>Genre<br>
            <select name="genre" style="width:100%;padding:6px;">
              <?php foreach (['beach','mountain','city','historical','other'] as $g): ?>
                <option value="<?= $g ?>" <?= $p['genre'] === $g ? 'selected' : '' ?>><?= ucfirst($g) ?></option>
              <?php endforeach; ?>
            </select>
          </label></p>
          <p><label>Cost<br>
            <select name="cost" style="width:100%;padding:6px;">
              <?php foreach (['low','medium','high'] as $c): ?>
                <option value="<?= $c ?>" <?= $p['cost_level'] === $c ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
              <?php endforeach; ?>
            </select>
          </label></p>
          <p><label>Travel Medium<br><input type="text" name="travel" value="<?= htmlspecialchars($p['travel_medium_info'], ENT_QUOTES, 'UTF-8') ?>" style="width:100%;padding:6px;box-sizing:border-box;"></label></p>
          <button type="submit" style="padding:8px 16px;background:#2c3e50;color:#fff;border:none;cursor:pointer;">Submit Change Request</button>
        </form>
      </details>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
</body></html>