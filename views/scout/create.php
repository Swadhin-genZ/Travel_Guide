<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Create Request</title></head>
<body>
<div style="max-width:500px;margin:40px auto;padding:24px;border:1px solid #ccc;border-radius:6px;">
  <h2>New Post Request</h2>
  <?php if ($error):   ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <?php if ($success): ?><p style="color:green;"><?= htmlspecialchars($success) ?></p><?php endif; ?>

  <form id="createForm" method="POST" action="index.php?page=scout_create" enctype="multipart/form-data" novalidate>
    <p><label>Title<br><input type="text" name="title" id="c_title" style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p style="color:red;display:none;" id="err_title">Title is required.</p>

    <p><label>Short History<br><textarea name="history" id="c_history" rows="4" style="width:100%;padding:8px;box-sizing:border-box;"></textarea></label></p>
    <p style="color:red;display:none;" id="err_history">History is required.</p>

    <p><label>Country<br><input type="text" name="country" id="c_country" style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p style="color:red;display:none;" id="err_country">Country is required.</p>

    <p><label>Genre<br>
      <select name="genre" style="width:100%;padding:8px;">
        <option value="beach">Beach</option>
        <option value="mountain">Mountain</option>
        <option value="city">City</option>
        <option value="historical">Historical</option>
        <option value="other">Other</option>
      </select>
    </label></p>

    <p><label>Cost Level<br>
      <select name="cost" style="width:100%;padding:8px;">
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
      </select>
    </label></p>

    <p><label>Travel Medium<br><input type="text" name="travel" id="c_travel" placeholder="e.g. flight, train" style="width:100%;padding:8px;box-sizing:border-box;"></label></p>
    <p style="color:red;display:none;" id="err_travel">Travel medium is required.</p>

    <p><label>Image (optional, max 2MB)<br><input type="file" name="image" accept="image/*"></label></p>

    <button type="submit" style="width:100%;padding:10px;background:#2c3e50;color:#fff;border:none;cursor:pointer;">Submit Request</button>
  </form>
</div>
<script src="public/js/scout.js"></script>
</body></html>