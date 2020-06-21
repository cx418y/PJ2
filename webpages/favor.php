<?php
require_once "../php/config.php";
session_start();
function displayUser(){
    if(isset($_SESSION['UID'])){
        global $UID;
        $UID = $_SESSION['UID'];
        echo '<ul class="nav navbar-nav nav-pills navbar-right">';
        echo '<li role="presentation" class="dropdown">';
        echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true"><span class="glyphicon glyphicon-user"></span>My account <span class="caret"></span></a>';
        echo '<ul class="dropdown-menu">';
        echo '<li><a href="upload.php"><i class="fa fa-upload"></i><span class="glyphicon glyphicon-import"></span> Upload</a></li>';
        echo '<li><a href="my_photo.php?UID='.$_SESSION['UID'].'"><i class="fa fa-photo"></i><span class="glyphicon glyphicon-picture"></span> My Photo</a></li>';
        echo '<li><a href="favor.php?UID='.$_SESSION['UID'].'"><i class="fa fa-heart"></i><span class="glyphicon glyphicon-heart-empty"></span> My Favorite</a></li>';
        echo '<li><a href="../php/logout.php"><i class="fa fa-sign-in"></i><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>';
        echo '</ul>';
        echo '</li>';
        echo '</ul>';
    }
    else{
        echo '<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">';
        echo '<ul class="nav navbar-nav navbar-right">';
        echo '<li><a href="login.html" role="button" aria-haspopup="true" aria-expanded="true"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
        echo '</ul>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=0">
    <title>My Favorite</title>
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="../bootstrap-3.3.7-dist/css/bootstrap.css" rel="stylesheet">
    <link href="../css/index.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="../bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <link href="../css/my_photo.css" rel="stylesheet" type="text/css">
</head>
<body>
<nav class="navbar navbar-inverse" id="top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><img alt="Brand" src="../images/logo.jpg" width="60px" height="40px"></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="../index.php">Home</a></li>
                <li><a href="Browser.php">Browse</a></li>
                <li><a href="Search.php">Search</a></li>
            </ul>

            <ul class="nav navbar-nav nav-pills navbar-right">
                <?php displayUser(); ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<section>
    <header><h3>My  Favorite</h3></header>
    <?php
    global $id ;
    $id =  $_SESSION['UID'];
    try{
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        if ( mysqli_connect_errno() ) {
            die( mysqli_connect_error() );
        }
        $sql = "select * from travelimagefavor where UID = '$id'";
        $result = mysqli_query($connection, $sql);

        if($result)
            $totalCount = $result->num_rows;
        else
            $totalCount = 0;

        if ($totalCount==0){
            echo'<div class="my_photo"><h4>您还没有收藏图片呢，快去收藏一些你喜欢的图片吧！</h4></div>';
        }
        else {
            $pageSize = 5;
            $totalPage = (int)(($totalCount % $pageSize == 0) ? ($totalCount / $pageSize) : ($totalCount / $pageSize + 1));

            if (!isset($_GET['page']))
                $currentPage = 1;
            else
                $currentPage = $_GET['page'];

            $mark = ($currentPage - 1) * $pageSize;
            $firstPage = 1;
            $lastPage = $totalPage;
            $prePage = ($currentPage > 1) ? $currentPage - 1 : 1;
            $totalPage = $totalPage>5? 5:$totalPage;
            $nextPage = ($totalPage - $currentPage > 0) ? $currentPage + 1 : $totalPage;

            $sql2 = "select * from travelimagefavor where UID = '$id' limit " . $mark . "," . $pageSize;
            $result2 = mysqli_query($connection, $sql2);
        for($j=0;$j<5;$j++) {
            $row = mysqli_fetch_assoc($result2);
            if (!$row) {
                break;
            } else {
                $ImageID = $row['ImageID'];
                $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                if (mysqli_connect_errno()) {
                    die(mysqli_connect_error());
                }
                $sql3 = "select * from travelimage where ImageID = '$ImageID'";
                $result3 = mysqli_query($connection, $sql3);
                $row3 = mysqli_fetch_assoc($result3);
                ?>
                <div class="my_photo">
                    <div class="photo">
                        <a href="details.php?id=<?php echo $row3['ImageID'] ?>"><img
                                src="../travel-images/small/<?php echo $row3['PATH'] ?>"></a>
                    </div>
                    <div class=" my_photo_intro">
                        <h1><?php echo $row3['Title'] ?></h1><br>
                        <p><?php echo $row3['Description'] ?></p>
                        <a href="../php/favor_delete.php?ImageID=<?php echo $ImageID ?>&UID=<?php echo $id ?>">
                            <button class="delete button">Delete</button>
                        </a>
                    </div>
                </div>
                <?php
            }
        }?>
        <div class="page">
    <a href="favor.php?page=<?php echo $prePage; ?>&UID=<?php echo $id; ?>"><<</a>
    <?php
    for($i=1;$i<=$totalPage&&$i<=5;$i++){
        if($i==$currentPage){
            echo '<span class="currentPage">'.$currentPage.' </span>';
        }
        else{
            echo '<span >'.$i.'</span>';
        }
    }
    ?>
    <a href="favor.php?page=<?php echo $nextPage; ?>&UID=<?php echo $id; ?>">>></a>
</div>
    <?php
    }
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }
    ?>
</section>

<footer>
    Copyright © 2019-2021 Web fundamental. All Rights Reserved. 备案号：19302010084
</footer>
</body>
</html>