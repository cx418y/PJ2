<?php
require_once "../webpages/favor.php";
require_once "config.php";
try{
    $UID = $_GET['UID'];
    $ImageID = $_GET['ImageID'];

    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql_delete= "delete from travelimagefavor where ImageID = '$ImageID' and UID ='$UID'";
    $res_delete = $pdo->query($sql_delete);
    echo isset($res_delete);
    echo "<script>window.location.href = ('../webpages/favor.php?UID=$UID');</script>";
} catch (PDOException $e) {
    die($e->getMessage());
}
