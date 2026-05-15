<?php
require_once '../../models/PostRequest.php';

PostRequest::delete($_GET['id']);

header("Location: ../../views/scout/my_requests.php");
exit();
?>