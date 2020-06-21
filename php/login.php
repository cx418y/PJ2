<?php
require_once "config.php";
try {
    $user = $_POST["username"];
    $psw = $_POST["password"];
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "select * from traveluser where UserName = '$user'and Pass = '$psw'"; //SQL语句
    $result1 = $pdo->query($sql);    //执行SQL语句
        if($row=$result1->fetch())
        {
            session_start();
            $_SESSION['UID']=$row['UID'];
            echo "<script>window.location.href = ('../index.php');</script>";
        }
        else
        {
            $sql = "select * from traveluser where Email = '$user'and Pass = '$psw'";
            $result2 = $pdo->query($sql);
            if($row=$result2->fetch()){
                session_start();
                $_SESSION['UID']=$row['UID'];
                echo "<script>window.location.href = ('../index.php');</script>";
            }
            else{echo "<script>alert('用户名或密码输入错误'); history.go(-1);</script>";}
        }
    $pdo = null;
}catch (PDOException $e) {
    die( $e->getMessage() );
}
?>