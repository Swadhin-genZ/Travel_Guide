<?php
require_once '../../models/PostRequest.php';

$result=PostRequest::getById($_GET['id']);
$row=mysqli_fetch_assoc($result);

$data=json_decode($row['post_data'],true);
?>

<form method="POST" action="../../controllers/ScoutController.php">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<input name="title" value="<?php echo $data['title']; ?>">

<textarea name="history"><?php echo $data['history']; ?></textarea>

<input name="country" value="<?php echo $data['country']; ?>">

<input name="travel" value="<?php echo $data['travel']; ?>">

<button name="update">Update</button>

</form>