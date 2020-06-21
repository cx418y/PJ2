<?php
require_once "../php/config.php";
session_start();
function displayUser(){
    if(isset($_SESSION['UID'])){
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
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=0">
    <title>Search</title>
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="../bootstrap-3.3.7-dist/css/bootstrap.css" rel="stylesheet">
    <link href="../css/index.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="../bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <link href="../css/Search.css" rel="stylesheet" type="text/css">
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
                <li class="active"><a href="Search.php">Search<span class="sr-only">(current)</span></a></li>
            </ul>

            <ul class="nav navbar-nav nav-pills navbar-right">
                <?php displayUser(); ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<section>
    <header><h3>Search</h3></header>
    <div class="search">
        <form method="post" name="form">
            <input type="radio" name="filter" value="title"  checked>Filter by Title<br>
            <input type="text" class="title-text" name="titleInput" id="titleInput"><br>
            <input type="radio" name="filter" value="description">Filter by Description<br>
            <input type="text" class="description-text" name="descriptionInput" ID="descriptionInput"><br>
            <button type="submit" class="filter-button" >Filter</button>

        </form>
    </div>


    <?php
    require_once "../php/config.php";
    if(isset($_POST['filter'])||(isset($_GET['filterCondition'])&&isset($_GET['filterContent']))) {
    ?>
    <div class="result">
        <header ><h3>Result</h3></header>
        <?php
        if (isset($_POST['filter'])) {
            $currentPage = 1;
            $filterCondition = $_POST['filter'];
            if ($filterCondition == "title") {
                $filterContent = $_POST['titleInput'];
            } else $filterContent = $_POST['descriptionInput'];
        }
        else{
            $filterCondition = $_GET['filterCondition'];
            $filterContent = $_GET['filterContent'];
        }
        if (!$filterContent) {
            echo "<script>alert('您还没有输入任何东西！');history.go(-1);</script>";
        } else {
            try {
                $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                if (mysqli_connect_errno()) {
                    die(mysqli_connect_error());
                }
                if ($filterCondition == "title") {
                    $sql = "select * from travelimage where Title LIKE '%$filterContent%'";
                    $result = mysqli_query($connection, $sql);
                } else {
                    $sql = "select * from travelimage where Description LIKE '%$filterContent%'";
                    $result = mysqli_query($connection, $sql);
                }

                if ($result)
                    $totalCount = $result->num_rows;
                else
                    $totalCount = 0;


                if ($totalCount == 0) {
                    ?>
                    <div class="result_show"><h4>没有搜索到相应的图片，换个关键词重新试试吧！</h4></div>
        <?php

                } else {
                    $pageSize = 5;
                    $totalPage = (int)(($totalCount % $pageSize == 0) ? ($totalCount / $pageSize) : ($totalCount / $pageSize + 1));
                    if ((!isset($_GET['page']))||isset($_POST['filter']))
                        $currentPage = 1;
                    else
                        $currentPage = $_GET['page'];

                    $mark = ($currentPage - 1) * $pageSize;
                    $firstPage = 1;
                    $lastPage = $totalPage;
                    $prePage = ($currentPage > 1) ? $currentPage - 1 : 1;
                    $totalPage = ($totalPage > 5) ? 5:$totalPage;
                    $nextPage = ($totalPage - $currentPage > 0) ? $currentPage + 1 : $totalPage;

                    if ($filterCondition == "title") {
                        $sql2 = "select * from travelimage where Title LIKE '%$filterContent%' limit " . $mark . "," . $pageSize;
                        $result2 = mysqli_query($connection, $sql2);
                    } else {
                        $sql2 = "select * from travelimage where Description LIKE '%$filterContent%' limit " . $mark . "," . $pageSize;
                        $result2 = mysqli_query($connection, $sql2);
                    }

                    for ($j = 0; $j < 5; $j++) {
                        $row = mysqli_fetch_assoc($result2);
                        if (!$row) {
                            break;
                        } else {
                            $ImageID = $row['ImageID'];
                            $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                            if (mysqli_connect_errno()) {
                                die(mysqli_connect_error());
                            }
                            ?>
                            <div class="result_show">
                                <div class="result_photo">
                                    <a href="details.php?id=<?php echo $row['ImageID'] ?>"><img
                                            src="../travel-images/small/<?php echo $row['PATH'] ?>"></a>
                                </div>
                                <div class="result_photo_intro">
                                    <h1><?php echo $row['Title'] ?></h1><br>
                                    <p><?php echo $row['Description'] ?></p>
                                </div>
                            </div>

                            <?php
                        }
                    }
                    ?>
        <div class="page">
            <a href="Search.php?page=<?php echo $prePage; ?>&filterContent=<?php echo $filterContent ?>&filterCondition=<?php echo $filterCondition ?>"><<</a>
            <?php
            for($i=1;$i<=$totalPage&&$i<=5;$i++){
                if($i==$currentPage){
                    echo '<span class="currentPage" style="color: red">'.$currentPage.' </span>';
                }
                else{
                    echo '<span >'.$i.' </span>';
                }
            }
            ?>

            <a href="Search.php?page=<?php echo $nextPage;?>&filterContent=<?php echo $filterContent ?>&filterCondition=<?php echo $filterCondition ?>">>></a>
        </div>
        <?php
                }
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
    }?>
    </div>
</section>

<footer>
    Copyright © 2019-2021 Web fundamental. All Rights Reserved. 备案号：19302010084
</footer>
</body>
</html>

