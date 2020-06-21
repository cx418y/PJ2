<?php
require_once('../php/config.php');
session_start();
/*
 Displays a list of genres
*/
try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'select ImageID,Title,Description,CityCode, Country_RegionCodeISO, UID, Content,PATH from travelimage where ImageId=:id';
    global $id ;
    $id =  $_GET['id'];
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_ASSOC);

    $CityID = $row['CityCode'];
    $sql = "select AsciiName from geocities where GeoNameId = '$CityID' ";
    $statement1 = $pdo->prepare($sql);
    $statement1->execute();
    $row1 = $statement1->fetch(PDO::FETCH_ASSOC);

    $CountryID = $row['Country_RegionCodeISO'];
    $sql = "select Country_RegionName from geocountries_regions where ISO = '$CountryID' ";
    $statement2 = $pdo->prepare($sql);
    $statement2->execute();
    $row2 = $statement2->fetch(PDO::FETCH_ASSOC);

    $UID = $row['UID'];
    $sql = "select UserName from traveluser where UID = '$UID' ";
    $statement3 = $pdo->prepare($sql);
    $statement3->execute();
    $row3 = $statement3->fetch(PDO::FETCH_ASSOC);

    $sql = "select * from travelimagefavor where ImageID = '$id' ";
    $result = $pdo->query($sql);
    $n=0;
    while($row4=$result->fetch()){
        $n++;
    }

    $cot = null;
    if(isset($_SESSION['UID'])) {
        $UID2 = $_SESSION['UID'];
        $sql = "select * from travelimagefavor where ImageID = '$id' and UID = '$UID2'";
        $result = $pdo->query($sql);
        if($result->fetch()){
            $cot = "取消收藏";
        }
        else{
            $cot="收藏";
        }
    }
    else{$cot="收藏";}
    function constructGenreLink1()
    {
        global $id;
        global $cot;
        $link = '<a href="../php/collect.php?id=' . $id. '"><button class="collection">❤'.$cot.'</button></a>';
        return $link;
    }


    $pdo = null;

}
catch (PDOException $e) {
    die( $e->getMessage() );
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=0">
    <title>Details</title>
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="../bootstrap-3.3.7-dist/css/bootstrap.css" rel="stylesheet">
    <link href="../css/index.css" rel="stylesheet">
    <link href="../css/details.css" rel="stylesheet" type="text/css">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="../bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <?php require_once "../php/index1.php"; ?>
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
            <?php
            require_once "../php/config.php";
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
            ?>
            <ul class="nav navbar-nav nav-pills navbar-right">
                <?php displayUser(); ?>
            </ul>
        </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
</nav>
<section>
    <?php
    require_once "../php/config.php";
    ?>
    <header><h3>Details</h3></header>
    <div class="details_title"><h2><?php echo $row['Title']; ?></h2><small>by <?php echo $row3['UserName'] ?></small><br></div>
    <div class="details_photo">
        <?php
        displayImage_Details($row);
        ?>
    </div>
    <div class="details">
        <table class="like_number">
            <tr><td class="like_number_title">Like Number</td></tr>
            <tr><td class="number"><h1><?php echo $n ?></h1></td></tr>
        </table>
        <table class="image_details">
            <tr><td class="image_details_title">Image Details</td></tr>
            <tr><td>Content: <?php echo $row['Content']; ?></td></tr>
            <tr><td>Country: <?php echo $row2['Country_RegionName'] ?></td></tr>
            <tr><td>City: <?php
                    if($row1)
                    echo $row1['AsciiName'];
                    else{echo 'Null';}?></td></tr>
        </table>
        <?php
        echo constructGenreLink1();
        ?>

    </div>
    <p class="introduction">
        <?php
        if($row['Description']) {
            echo $row['Description'];
        }
        else{ echo 'There is no description.';}
        ?>
    </p>
</section>
<footer class="otherFooter">
    Copyright © 2019-2021 Web fundamental. All Rights Reserved. 备案号：19302010084
</footer>
</body>
</html>