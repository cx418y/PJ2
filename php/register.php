<?php
require_once "config.php";
try {

    $user = $_POST["username"];
    $psw = $_POST["password"];
    $psw_confirm = $_POST["confirm"];
    $email = $_POST["email"];
    if($psw == $psw_confirm)
    {
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from traveluser where UserName = '$_POST[username]'"; //SQL语句
        $result = $pdo->query($sql);    //执行SQL语句
        $sql2 = "select * from traveluser where Email = '$_POST[email]'"; //SQL语句
        $result2 = $pdo->query($sql2);
        if($result->fetch())    //如果已经存在该用户
        {
            echo "<script>alert('用户名已存在！'); history.go(-1);</script>";
        }
        else if($result2->fetch()){
            echo "<script>alert('该邮箱已经被注册过！'); history.go(-1);</script>";
        }
        else    //不存在当前注册用户名称
        {
            $sql_insert = "insert into traveluser(UserName,Pass,Email) values('$_POST[username]','$_POST[password]','$_POST[email]')";
            $res_insert = $pdo->query($sql_insert);
            if($res_insert)
            {
                echo "<script>alert('注册成功！');window.location.href = ('../webpages/login.html');</script>";
            }
            else
            {
                echo "<script>alert('系统繁忙，请稍候！'); history.go(-1);</script>";
            }
        }
    }
    else
    {
        echo "<script>alert('两次输入的密码不一致！'); history.go(-1);</script>";
    }
    $pdo = null;
}catch (PDOException $e) {
    die( $e->getMessage() );
}
?>