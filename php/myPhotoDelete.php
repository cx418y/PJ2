<?php
require_once "../webpages/my_photo.php";
require_once "config.php";
try{
    $UID = $_GET['UID'];
    $ImageID = $_GET['ImageID'];

    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql_delete= "delete from travelimage where ImageID = '$ImageID' and UID ='$UID'";
    $res_delete = $pdo->query($sql_delete);
    echo "<script>window.location.href = ('../webpages/my_Photo.php?UID=$UID');</script>";
} catch (PDOException $e) {
    die($e->getMessage());
}
