<?php
require_once "php/config.php";
session_start();
function displayUser(){
    if(isset($_SESSION['UID'])){
        $UID=$_SESSION['UID'];
        echo '<ul class="nav navbar-nav nav-pills navbar-right">';
        echo '<li role="presentation" class="dropdown">';
        echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true"><span class="glyphicon glyphicon-user"></span>My account <span class="caret"></span></a>';
        echo '<ul class="dropdown-menu">';
        echo '<li><a href="webpages/upload.php"><i class="fa fa-upload"></i><span class="glyphicon glyphicon-import"></span> Upload</a></li>';
        echo '<li><a href="webpages/my_photo.php?UID='.$UID.'"><i class="fa fa-photo"></i><span class="glyphicon glyphicon-picture"></span> My Photo</a></li>';
        echo '<li><a href="webpages/favor.php?UID='.$UID.'"><i class="fa fa-heart"></i><span class="glyphicon glyphicon-heart-empty"></span> My Favorite</a></li>';
        echo '<li><a href="php/logout.php"><i class="fa fa-sign-in"></i><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>';
        echo '</ul>';
        echo '</li>';
        echo '</ul>';
    }
    else{
        echo '<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">';
        echo '<ul class="nav navbar-nav navbar-right">';
        echo '<li><a href="webpages/login.html" role="button" aria-haspopup="true" aria-expanded="true"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
        echo '</ul>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="bootstrap-3.3.7-dist/css/bootstrap.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <?php require_once "php/index1.php"; ?>
    <title>Index</title>
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
            <a class="navbar-brand" href="#"><img alt="Brand" src="images/logo.jpg" width="60px" height="40px"></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Home <span class="sr-only">(current)</span></a></li>
                <li><a href="webpages/Browser.php">Browse</a></li>
                <li><a href="webpages/Search.php">Search</a></li>
            </ul>


            <ul class="nav navbar-nav nav-pills navbar-right">
                <?php displayUser(); ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="website_banner">
    <img src="travel-images/small/<?php findBannerID(); ?>"
</div>

<section class="home_section" id="home_section">
    <?php
    outputImage();
    ?>
</section>

<footer class="index_footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <p><a>使用条款</a></p>
                <p><a>隐私保护</a></p>
                <p><a>Cookie</a></p>
            </div>
            <div class="col-md-3">
                <p><a>关于</a></p>
                <p><a>联系我们</a></p>
            </div>
            <div class="col-md-3">
                <div>
                    <img src="images/footer/WeChat.jpg" width="40px" />
                    <img src="images/footer/ins.jpg" width="40px" />
                </div>
                <div>
                    <img src="images/footer/qq.jpg" width="40px" />
                    <img src="images/footer/github.jpg" width="40px" />
                </div>
            </div>

            <div class="col-md-3">
                <img src="images/footer/wechat2DCode.jpg" width="60px"/>
            </div>
        </div>
    </div>

    <div id="copyrightRow">
        <div class="container">
            <div class="row">
                <p class="copyright">Copyright © 2019-2021 Web fundamental. All Rights Reserved. 备案号：沪BILIBILI备12138号-1</p>
            </div>
        </div>
    </div>
</footer>

<button type="button" class="btn btn-default back-to-top" aria-label="Left Align" >
    <a href="#top">
        <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
    </a>
</button>
<button type="button" class="btn btn-default refresh" aria-label="Left Align" onclick="refresh()">
    <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
</button>

<script>
    function refresh() {
        var homeSection = document.getElementById("home_section");
        homeSection.innerHTML="";
        function AjaxCaller(){
            var xmlhttp=false;
            try{
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            }catch(e){
                try{
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }catch(E){
                    xmlhttp = false;
                }
            }

            if(!xmlhttp && typeof XMLHttpRequest!='undefined'){
                xmlhttp = new XMLHttpRequest();
            }
            return xmlhttp;
        }
        function callPage(url, div){
            ajax=AjaxCaller();
            ajax.open("GET", url, true);
            ajax.onreadystatechange=function(){
                if(ajax.readyState==4){
                    if(ajax.status==200){
                        div.innerHTML = ajax.responseText;
                    }
                }
            }
            ajax.send(null);
        }
        callPage("php/refresh.php",document.getElementById("home_section"));
    }
</script>
</body>
</html>

