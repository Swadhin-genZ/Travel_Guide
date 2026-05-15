<?php
require_once '../config/database.php';

class PostRequest {

    public static function create($scout_id,$post_data){
        global $conn;
        $status="pending";

        $stmt=mysqli_prepare($conn,
        "INSERT INTO post_requests (scout_id,post_data,status,requested_at)
         VALUES(?,?,?,NOW())");

        mysqli_stmt_bind_param($stmt,"iss",$scout_id,$post_data,$status);
        return mysqli_stmt_execute($stmt);
    }

    public static function getByScout($scout_id){
        global $conn;

        $stmt=mysqli_prepare($conn,
        "SELECT * FROM post_requests WHERE scout_id=?");

        mysqli_stmt_bind_param($stmt,"i",$scout_id);
        mysqli_stmt_execute($stmt);

        return mysqli_stmt_get_result($stmt);
    }

    public static function getById($id){
        global $conn;

        $stmt=mysqli_prepare($conn,
        "SELECT * FROM post_requests WHERE id=?");

        mysqli_stmt_bind_param($stmt,"i",$id);
        mysqli_stmt_execute($stmt);

        return mysqli_stmt_get_result($stmt);
    }

    public static function update($id,$post_data){
        global $conn;

        $stmt=mysqli_prepare($conn,
        "UPDATE post_requests SET post_data=? WHERE id=?");

        mysqli_stmt_bind_param($stmt,"si",$post_data,$id);
        return mysqli_stmt_execute($stmt);
    }

    public static function delete($id){
        global $conn;

        $stmt=mysqli_prepare($conn,
        "DELETE FROM post_requests WHERE id=?");

        mysqli_stmt_bind_param($stmt,"i",$id);
        return mysqli_stmt_execute($stmt);
    }
}
?>