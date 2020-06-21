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
//在mysql数据可找到城市和国家并组成数组
try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "select ISO,Country_RegionName from geocountries_regions Order By Population desc limit 0,50"; //SQL语句
    $result1 = $pdo->query($sql);
    $array = array();
    $array['Filter by Country'] = array('Filter by City');

    while ($row1 = $result1->fetch()) {
        $countryCode = $row1['ISO'];
        $countryName = $row1['Country_RegionName'];
        $sql2 = "select AsciiName,GeoNameID from geocities where Country_RegionCodeISO= '$countryCode' Order By Population desc limit 0,50";
        $result2 = $pdo->query($sql2);
        $array1 = array();
        $j = 0;

        while($row2=$result2->fetch()){
            $array1[$j] = $row2['AsciiName'];
            $j++;
        }
        if($array1!=null) {
            $array[$countryName] = $array1;
        }
    }
} catch (PDOException $e) {
    die($e->getMessage());
}
$arr0 = json_encode($array);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=0">
    <title>Browser</title>
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="../bootstrap-3.3.7-dist/css/bootstrap.css" rel="stylesheet">
    <link href="../css/index.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="../bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <link href="../css/Browser.css" rel="stylesheet" type="text/css">
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
                <li class="active"><a href="Browser.php">Browse<span class="sr-only">(current)</span></a></li>
                <li><a href="Search.php">Search</a></li>
            </ul>

            <ul class="nav navbar-nav nav-pills navbar-right">
                <?php displayUser(); ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<aside>
    <!--通过标题搜索-->
    <div class="search-by-title">
        <form method="post" name="form1">
        <div class="title1">Search by Title </div>
        <input type="text" class="search-text" name="searchByTitleOnly" id="searchByTitleOnly" placeholder="请输入标题：">
        <button type="submit"  class="search-button" id="searchByTitleButton" >Search</button>
        </form>
        <script>
            var submit1 = document.getElementById("searchByTitleButton");
            var titleInput = document.getElementById("searchByTitleOnly");
            submit1.onclick=function () {
                if(titleInput.value==""){
                    alert("您还没有输入任何东西！");
                    return false;
                }
                else return true;
            }
        </script>

    </div>
    <!--侧边栏点击跳转 -->
    <?php
    try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql1 = "select Country_RegionCodeISO from travelimage order by RAND() ";
    $sql2 = "select CityCode from travelimage order by RAND() ";
    $sql3 = "select * from travelimage order by Content ";
    $result1=$pdo->query($sql1);
    $result2=$pdo->query($sql2);
    $result3=$pdo->query($sql3);
    ?>
    <div class="hot">
        <table>
            <tr>
                <th>Hot Content</th>
            </tr>
            <?php
                $arr=array();
                $n=1;
                while($row3=$result3->fetch()) {
                    if (!(array_key_exists($row3['Content'],$arr))) {
                        $contentHot = $row3['Content'];
                        $arr[$row3['Content']]=1;
                        $n++;
                        echo '<tr><td><a href="../php/browser_hotContent.php?hotContent=' . $contentHot . '"><input type="button" value="' . $contentHot . '"></a></td></tr>';
                    }
                    if($n>5) break;
                }
            ?>
        </table>
    </div>
    <div class="hot">
        <table>
            <tr>
                <th>Hot Countries</th>
            </tr>
            <?php
            $arr = array();
            $n = 1;
            while($row1=$result1->fetch()) {
                $ISO = $row1['Country_RegionCodeISO'];
                $sql = "select Country_RegionName from geocountries_regions where ISO = '$ISO' ";
                $result = $pdo->query($sql);
                while ($a = $result->fetch()) {
                    $countryHot = $a['Country_RegionName'];
                    if (!(array_key_exists($countryHot, $arr))) {
                        $arr[$countryHot] = 1;
                        $n++;
                        echo '<tr><td><a href="../php/browser_hotCountry.php?hotCountry=' . $countryHot . '"><input type="button" value="' . $countryHot . '"></a></td></tr>';
                    }
                }
                if($n>5) break;
            }
            ?>
        </table>
    </div>
    <div class="hot">
        <table>
            <tr>
                <th>Hot Cities</th>
            </tr>
            <?php
            $arr = array();
            $n = 1;
            while($row2=$result2->fetch()) {
                $co = $row2['CityCode'];
                $sql = "select AsciiName from geocities where GeoNameID='$co'";
                $result = $pdo->query($sql);
                while ($a = $result->fetch()) {
                    if (!(array_key_exists($a['AsciiName'], $arr))) {
                        $arr[$a['AsciiName']] =1;
                        $n++;
                        $cityHot = $a['AsciiName'];
                        echo '<tr><td><a href="../php/browser_hotCity.php?hotCity=' . $cityHot . '"><input type="button" value="' . $cityHot . '"></a></td></tr>';
                    }
                }
                if($n>5) break;
            }
            ?>
        </table>
    </div>

    <?php
    } catch (PDOException $e) {
        die($e->getMessage());
    }?>
</aside>
<section>
    <header>Filter</header>
    <!-- 三级筛选 -->
    <div class="filter">
        <form name="form2" method="post">
            <select  class="content" name="content" placeholder="Filter by Content">
                <option value="Scenery">Scenery</option>
                <option value="City">City</option>
                <option value="Wonder">Wonder</option>
                <option value="People" >People</option>
                <option value="Animal">Animal</option>
                <option value="Building">Building</option>
                <option value="others">others</option>
            </select>
            <select id="s1" name="country"></select>
            <select id="s2" name="city"></select>
            <button class="filter-button" type="submit" id="submit">Filter</button>
        </form>
            <script>
            var city =<?php echo $arr0; ?>;

            var select1 = document.getElementById("s1");
            var select2 = document.getElementById("s2");

            window.onload = function() {
                //*初始化国家下拉框
                for (let i in city) {
                    select1.add(new Option(i, i), null);
                }
                //*初始化城市下拉框
                select2.add(new Option(city[select1.value][0], city[select1.value][0]), null);

                //*给国家下拉框添加事件
                select1.addEventListener('change', function(){
                    select2.length = 0; //每次都先清空一下城市菜单
                    for (let i in city[select1.value]) {
                        select2.add(new Option(city[select1.value][i], city[select1.value][i]), null);
                    }
                });

            };
            var submit = document.getElementById("submit");
            submit.onclick=function () {
                if(select1.options[0].selected){
                    alert('请选择国家');
                }
                else{
                    window3.location.href=('../php/threeConditionsSearch.php')
                }
                return !(select1.options[0].selected);
            }
            </script>

    </div>
    <!--图片部分-->
    <div class="photo" id="photoDiv">
        <?php
        try {
            $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from travelimage"; //SQL语句
            $result = mysqli_query($connection, $sql);
            for ($j = 0; $j < 12; $j++) {
                $row = mysqli_fetch_assoc($result);
                if (!$row) {
                    break;
                } else {
                    $ImageID = $row['ImageID'];
                    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                    if (mysqli_connect_errno()) {
                        die(mysqli_connect_error());
                    }
                    ?>
                    <a href="details.php?id=<?php echo $row['ImageID'] ?>"><img
                                src="../travel-images/small/<?php echo $row['PATH'] ?>"></a>
                    <?php
                }
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        ?>


    <?php

if(isset($_POST['searchByTitleOnly'])||isset($_GET['searchByTitleOnly'])||isset($_GET['content'])||isset($_POST['content'])||isset($_GET['hotContent'])||isset($_GET['hotCity'])||isset($_GET['hotCountry'])||isset($_GET['hotContent1'])||isset($_GET['hotCity1'])||isset($_GET['hotCountry1'])) {

        //标题搜索
        if ((isset($_POST['searchByTitleOnly']) || isset($_GET['searchByTitleOnly']))&&(!isset($_POST['content']))){
            if (isset($_POST['searchByTitleOnly'])) {
                $currentPage = 1;
                $searchByTitleOnly = $_POST['searchByTitleOnly'];
            } else {
            $searchByTitleOnly = $_GET['searchByTitleOnly'];
            }
            try {
                $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                if (mysqli_connect_errno()) {
                    die(mysqli_connect_error());
                }
                    $sql = "select * from travelimage where Title LIKE '%$searchByTitleOnly%'";
                    $result = mysqli_query($connection, $sql);

                if ($result)
                    $totalCount = $result->num_rows;
                else
                    $totalCount = 0;

                if ($totalCount == 0) {
                    ?>
                    <script>alert("没有搜索到相应的图片，换个关键词重新试试吧！")</script>
                    <?php

                } else {
                    ?>
                    <script>
                        var photoDiv = document.getElementById("photoDiv");
                        photoDiv.innerHTML="";
                    </script>
                    <header><h3>Result</h3></header>
                    <?php
                    $pageSize = 12;
                    $totalPage = (int)(($totalCount % $pageSize == 0) ? ($totalCount / $pageSize) : ($totalCount / $pageSize + 1));
                    if ((!isset($_GET['page'])) || isset($_POST['searchByTitleOnly']))
                        $currentPage = 1;
                    else
                        $currentPage = $_GET['page'];

                    $mark = ($currentPage - 1) * $pageSize;
                    $firstPage = 1;
                    $lastPage = $totalPage;
                    $prePage = ($currentPage > 1) ? $currentPage - 1 : 1;
                    $nextPage = ($totalPage - $currentPage > 0) ? $currentPage + 1 : $totalPage;
                    $nextPage = ($currentPage == 5) ? 5 : $currentPage + 1;

                    $sql2 = "select * from travelimage where Title LIKE '%$searchByTitleOnly%' limit " . $mark . "," . $pageSize;
                    $result2 = mysqli_query($connection, $sql2);
                    for ($j = 0; $j < 12; $j++) {
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
                                    <a href="details.php?id=<?php echo $row['ImageID'] ?>"><img
                                                src="../travel-images/small/<?php echo $row['PATH'] ?>"></a>
                            <?php
                        }
                    }
                    ?>
                    <div class="page">
                        <a href="Browser.php?page=<?php echo $prePage; ?>&searchByTitleOnly=<?php echo $searchByTitleOnly ?>"><<</a>
                        <?php
                        for ($i = 1; $i <= $totalPage && $i <= 5; $i++) {
                            if ($i == $currentPage) {
                                echo '<span class="currentPage" style="color: red">' . $currentPage . ' </span>';
                            } else {
                                echo '<span >' . $i . ' </span>';
                            }
                        }
                        ?>
                        <a href="Browser.php?page=<?php echo $nextPage; ?>&searchByTitleOnly=<?php echo $searchByTitleOnly ?>">>></a>
                    </div>
                    <?php
                }
            } catch (PDOException $e) {
                die($e->getMessage());
            }
            //三级搜索
        }else if ((isset($_GET['content'])||isset($_POST['content']))){
                    if (isset($_POST['content'])) {
                        $currentPage = 1;
                        $content = $_POST['content'];
                        $city = $_POST['city'];
                        $country = $_POST['country'];
                    } else {
                        $content = $_GET['content'];
                        $city = $_GET['city'];
                        $country = $_GET['country'];
                    }

                    try {
                    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql1 = "select GeoNameID from geocities where AsciiName = '$city'";
                    $result1 = $pdo->query($sql1);
                    if($row = $result1->fetch()){
                        $cityCode = $row['GeoNameID'];
                    }
                    else{ $cityCode = Null;}

                    $sql2 = "select ISO from geocountries_regions where Country_RegionName = '$country'";
                    $result2 = $pdo->query($sql2);
                    if($row = $result2->fetch()){
                        $countryCode = $row['ISO'];
                    }
                    else{ $countryCode = Null;}

                    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                    if (mysqli_connect_errno()) {
                        die(mysqli_connect_error());
                    }
                    $sql = "select * from travelimage where Country_RegionCodeISO='$countryCode' and Content='$content' and CityCode='$cityCode'";
                    $result = mysqli_query($connection, $sql);

                    if ($result)
                        $totalCount = $result->num_rows;
                    else
                        $totalCount = 0;

                    if ($totalCount == 0) {
                    ?>
            <script>alert("没有搜索到相应的图片，换个选项重新试试吧！")</script>
        <?php

        } else {
        ?>
            <script>
                var photoDiv = document.getElementById("photoDiv");
                photoDiv.innerHTML="";
            </script>
            <header><h3>Result</h3></header>
        <?php
        $pageSize = 12;
        $totalPage = (int)(($totalCount % $pageSize == 0) ? ($totalCount / $pageSize) : ($totalCount / $pageSize + 1));
        if ((!isset($_GET['page1'])) || isset($_POST['content']))
            $currentPage = 1;
        else
            $currentPage = $_GET['page1'];

        $mark = ($currentPage - 1) * $pageSize;
        $firstPage = 1;
        $lastPage = $totalPage;
        $prePage = ($currentPage > 1) ? $currentPage - 1 : 1;
        $totalPage = ($totalPage > 5) ? 5:$totalPage;
        $nextPage = ($totalPage - $currentPage > 0) ? $currentPage + 1 : $totalPage;

        $sql2 = "select * from travelimage where Country_RegionCodeISO='$countryCode' and Content='$content' and CityCode='$cityCode' limit " . $mark . "," . $pageSize;
        $result2 = mysqli_query($connection, $sql2);
        for ($j = 0; $j < 12; $j++) {
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
            <a href="details.php?id=<?php echo $row['ImageID'] ?>"><img
                        src="../travel-images/small/<?php echo $row['PATH'] ?>"></a>
        <?php
        }
        }
        ?>
            <div class="page">
                <a href="Browser.php?page1=<?php echo $prePage; ?>&content=<?php echo $content ?>&city=<?php echo $city?>&country=<?php echo $country?>"><<</a>
                <?php
                for ($i = 1; $i <= $totalPage && $i <= 5; $i++) {
                    if ($i == $currentPage) {
                        echo '<span class="currentPage" style="color: red">' . $currentPage . ' </span>';
                    } else {
                        echo '<span >' . $i . ' </span>';
                    }
                }
                ?>
                <a href="Browser.php?page1=<?php echo $nextPage; ?>&content=<?php echo $content ?>&city=<?php echo $city?>&country=<?php echo $country?>">>></a>
            </div>
            <?php
        }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
                    //点击搜索
        }else{
            try {
            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
            if (isset($_GET['hotContent'])||isset($_GET['hotContent1'])) {
                if(isset($_GET['hotContent'])) {$currentPage=1;$hotContent = $_GET['hotContent'];}
                else {
                    $currentPage=$_GET['page2'];
                $hotContent = $_GET['hotContent1'];
                }
                $getContent="hotContent1=".$hotContent;
                $sql = "select * from travelimage where Content = '$hotContent'";
                $result = mysqli_query($connection, $sql);

            } else if(isset($_GET['hotCountry'])||isset($_GET['hotCountry1'])){
                if(isset($_GET['hotCountry'])) {$currentPage=1;$hotCountry = $_GET['hotCountry'];}
                else {
                    $currentPage = $_GET['page2'];
                    $hotCountry = $_GET['hotCountry1'];
                }
                $getContent="hotCountry1=".$hotCountry;
                $sql2 = "select ISO from geocountries_regions where Country_RegionName = '$hotCountry'";
                $result2 = $pdo->query($sql2);
                while($row = $result2->fetch()) {
                    $countryCode = $row['ISO'];
                }
                $sql = "select * from travelimage where Country_RegionCodeISO='$countryCode'";
                $result = mysqli_query($connection, $sql);

            }else{
                if(isset($_GET['hotCity'])) {$currentPage=1;$hotCity = $_GET['hotCity'];}
                else {
                    $currentPage = $_GET['page2'];
                    $hotCity = $_GET['hotCity1'];
                }
                $getContent="hotCity1=".$hotCity;
                $sql1 = "select GeoNameID from geocities where AsciiName = '$hotCity'";
                $result1 = $pdo->query($sql1);
                while($row = $result1->fetch()){
                    $cityCode = $row['GeoNameID'];
                }
                $sql = "select * from travelimage where CityCode='$cityCode'";
                $result = mysqli_query($connection, $sql);;
            }


            if ($result)
                $totalCount = $result->num_rows;
            else
                $totalCount = 0;

            if ($totalCount == 0) {
            ?>
            <script>alert("没有搜索到相应的图片，换个选项重新试试吧！")</script>
        <?php

        } else {
        ?>
            <script>
                var photoDiv = document.getElementById("photoDiv");
                photoDiv.innerHTML="";
            </script>
            <header><h3>Result</h3></header>
        <?php
        $pageSize = 12;
        $totalPage = (int)(($totalCount % $pageSize == 0) ? ($totalCount / $pageSize) : ($totalCount / $pageSize + 1));
        if ((!isset($_GET['page2'])))
            $currentPage = 1;
        else
            $currentPage = $_GET['page2'];

        $mark = ($currentPage - 1) * $pageSize;
        $firstPage = 1;
        $lastPage = $totalPage;
        $prePage = ($currentPage > 1) ? $currentPage - 1 : 1;
        $totalPage = ($totalPage > 5) ? 5:$totalPage;
        $nextPage = ($totalPage - $currentPage > 0) ? $currentPage + 1 : $totalPage;

        if (isset($_GET['hotContent'])||isset($_GET['hotContent1'])) {
            $sql2 = "select * from travelimage where Content = '$hotContent' limit " . $mark . "," . $pageSize;
            $result2 =$pdo->query($sql2);

        } else if(isset($_GET['hotCountry'])||isset($_GET['hotCountry1'])){
            $sql2 = "select * from travelimage where Country_RegionCodeISO = '$countryCode' limit " . $mark . "," .$pageSize;
            $result2 =$pdo->query($sql2);

        }else{
            $sql2 = "select * from travelimage where CityCode = '$cityCode' limit " . $mark . "," . $pageSize;
            $result2 =$pdo->query($sql2);
        }

        while($row=$result2->fetch()){
        $ImageID = $row['ImageID'];
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        if (mysqli_connect_errno()) {
            die(mysqli_connect_error());
        }
        ?>
            <a href="details.php?id=<?php echo $row['ImageID'] ?>"><img
                        src="../travel-images/small/<?php echo $row['PATH'] ?>"></a>
        <?php
        }

        ?>
            <div class="page">

                <a href="Browser.php?page2=<?php echo $prePage; ?>&<?php echo $getContent ?>"><<</a>
                <?php
                for ($i = 1; $i <= $totalPage && $i <= 5; $i++) {
                    if ($i == $currentPage) {
                        echo '<span class="currentPage" style="color: red">' . $currentPage . ' </span>';
                    } else {
                        echo '<span >' . $i . ' </span>';
                    }
                }
                ?>
                <a href="Browser.php?page2=<?php echo $nextPage; ?>&<?php echo $getContent ?>">>></a>
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


