<?php
global $col;
require "../webpages/details.php";
$id =  $_GET['id'];
if(isset($_SESSION['UID'])){
    try{
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $UID2 = $_SESSION['UID'];
        $sql = "select * from travelimagefavor where ImageID = '$id' and UID = '$UID2'";
        $result = $pdo->query($sql);
        if($result->fetch()){
            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql_delete= "delete from travelimagefavor where ImageID = '$id' and UID='$UID2'";
            $res_delete = $pdo->query($sql_delete);
            echo "<script>window.location.href = ('../webpages/details.php?id=$id');</script>";
        }
        else{
                $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_insert = "insert into travelimagefavor(ImageID,UID) values('$id','$UID2')";
                $res_insert = $pdo->query($sql_insert);
                echo "<script>window.location.href = ('../webpages/details.php?id=$id');</script>";
            }

    $pdo = null;
}catch (PDOException $e) {
    die( $e->getMessage() );
}
}
else{
    echo "<script>
              alert('您还未登录');
              history.go(-1);
</script>";
}

