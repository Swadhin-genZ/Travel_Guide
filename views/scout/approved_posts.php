<?php
require_once '../../config/database.php';

$stmt=mysqli_prepare($conn,
"SELECT * FROM posts WHERE scout_id=? AND status='approved'");

mysqli_stmt_bind_param($stmt,"i",$_SESSION['user_id']);
mysqli_stmt_execute($stmt);

$result=mysqli_stmt_get_result($stmt);
?>

<h2>Approved Posts</h2>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<div>
<h3><?php echo $row['title']; ?></h3>
<p><?php echo $row['country']; ?></p>
</div>

<?php } ?>