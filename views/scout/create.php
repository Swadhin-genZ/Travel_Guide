<?php require_once '../../config/database.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Create Post Request</title>
<style>
body{font-family:Arial;background:#f4f4f4;padding:30px}
.box{background:white;padding:20px;width:500px;margin:auto}
input,textarea,select,button{width:100%;padding:10px;margin:8px 0}
</style>
</head>
<body>

<div class="box">
<h2>Create Travel Post Request</h2>

<form method="POST" action="../../controllers/ScoutController.php">

<input name="title" placeholder="Title" required>

<textarea name="history" placeholder="Short History"></textarea>

<input name="country" placeholder="Country">

<select name="genre">
<option>Beach</option>
<option>Mountain</option>
<option>Historical</option>
<option>City</option>
</select>

<select name="cost">
<option>low</option>
<option>medium</option>
<option>high</option>
</select>

<input name="travel" placeholder="Travel Medium">

<button name="create">Submit</button>

</form>
</div>

</body>
</html>