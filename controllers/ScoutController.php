<?php
require_once '../models/PostRequest.php';

if($_SESSION['role']!='scout'){
    die("Access Denied");
}

if(isset($_POST['create'])){

    $data=json_encode([
        'title'=>$_POST['title'],
        'history'=>$_POST['history'],
        'country'=>$_POST['country'],
        'genre'=>$_POST['genre'],
        'cost'=>$_POST['cost'],
        'travel'=>$_POST['travel']
    ]);

    PostRequest::create($_SESSION['user_id'],$data);

    header("Location: ../views/scout/my_requests.php");
    exit();
}

if(isset($_POST['update'])){

    $data=json_encode([
        'title'=>$_POST['title'],
        'history'=>$_POST['history'],
        'country'=>$_POST['country'],
        'genre'=>$_POST['genre'],
        'cost'=>$_POST['cost'],
        'travel'=>$_POST['travel']
    ]);

    PostRequest::update($_POST['id'],$data);

    header("Location: ../views/scout/my_requests.php");
    exit();
}
?>