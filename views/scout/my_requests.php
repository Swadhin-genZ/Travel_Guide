<?php
require_once '../../models/PostRequest.php';

$result=PostRequest::getByScout($_SESSION['user_id']);
?>

<h2>My Requests</h2>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<div>
<p><?php echo $row['status']; ?></p>

<a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>

<a href="../../api/scout/delete.php?id=<?php echo $row['id']; ?>">Delete</a>
</div>

<?php } ?>