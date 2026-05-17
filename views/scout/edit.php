<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Edit Request</title></head>
<body>
<div style="max-width:500px;margin:40px auto;padding:24px;border:1px solid #ccc;border-radius:6px;">
  <h2>Edit Request</h2>
  <?php if ($error):   ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <?php if ($success): ?><p style="color:green;"><?= htmlspecialchars($success) ?></p><?php endif; ?>

  <form id="editForm" method="POST" action="index.php?page=scout_edit" novalidate>
    <input type="hidden" name="id" value="<?= $request['id'] ?>">

    <p><label>Title<br><input type="text" name="title" id="e_title" value="<?= htmlspecialchars($postData['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p style="color:red;display:none;" id="err_e_title">Title is required.</p>

    <p><label>Short History<br><textarea name="history" id="e_history" rows="4" style="width:100%;padding:8px;box-sizing:border-box;"><?= htmlspecialchars($postData['history'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></label></p>
    <p style="color:red;display:none;" id="err_e_history">History is required.</p>

    <p><label>Country<br><input type="text" name="country" id="e_country" value="<?= htmlspecialchars($postData['country'] ?? '', ENT_QUOTES, 'UTF-8') ?>" style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p style="color:red;display:none;" id="err_e_country">Country is required.</p>

    <p><label>Genre<br>
      <select name="genre" style="width:100%;padding:8px;">
        <?php foreach (['beach','mountain','city','historical','other'] as $g): ?>
          <option value="<?= $g ?>" <?= ($postData['genre'] ?? '') === $g ? 'selected' : '' ?>><?= ucfirst($g) ?></option>
        <?php endforeach; ?>
      </select>
    </label></p>

    <p><label>Cost Level<br>
      <select name="cost" style="width:100%;padding:8px;">
        <?php foreach (['low','medium','high'] as $c): ?>
          <option value="<?= $c ?>" <?= ($postData['cost'] ?? '') === $c ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
        <?php endforeach; ?>
      </select>
    </label></p>

    <p><label>Travel Medium<br><input type="text" name="travel" id="e_travel" value="<?= htmlspecialchars($postData['travel'] ?? '', ENT_QUOTES, 'UTF-8') ?>" style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p style="color:red;display:none;" id="err_e_travel">Travel medium is required.</p>

    <button type="submit" style="width:100%;padding:10px;background:#2c3e50;color:#fff;border:none;cursor:pointer;">Update Request</button>
  </form>
  <br>
  <a href="index.php?page=scout_requests">← Back</a>
</div>
<script src="public/js/scout.js"></script>
</body></html>