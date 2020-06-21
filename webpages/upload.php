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

try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "select ISO,Country_RegionName from geocountries_regions Order By Population desc limit 0,50"; //SQL语句
    $result1 = $pdo->query($sql);
    $array = array();
    $array['Country'] = array('City');

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
$arr = json_encode($array);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=0">
    <title>Upload</title>
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link href="../css/upload.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="../bootstrap-3.3.7-dist/css/bootstrap.css" rel="stylesheet">
    <link href="../css/index.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="../bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
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
    <?php
    if(isset($_GET['ImageID'])){
        $ImageID = $_GET['ImageID'];
        echo '<form  name="form2" enctype="multipart/form-data" method="post" action="../php/uploadSubmit.php?ImageID='.$ImageID.'">';
    }
    else
        echo '<form  name="form2" enctype="multipart/form-data" method="post" action="../php/uploadSubmit.php">';
    ?>
        <div class="upload">
            <div class="img-div">
                <?php
                     if(isset($_GET['ImageID'])){
                         $submitText = "修改";
                         $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                         $ImageID = $_GET['ImageID'];
                         $sql = "select PATH from travelimage where ImageID = '$ImageID' "; //SQL语句
                         $result1 = $pdo->query($sql);
                         while($row = $result1->fetch()){
                             $imagePath = $row['PATH'];
                         }
                         echo '<img src="../travel-images/small/'.$imagePath.'" alt="图片未上传" id="upload-photo" >';

                     }
                     else {
                         $submitText = "上传";
                         echo '<img src="" alt="图片未上传" id="upload-photo" >';
                     }
                ?>
            </div>
            <div class="warp">
                <button class="upload-button">Upload</button>
                <input type="file" id="file" name="file" required>
            </div>
        </div>
        <script type="text/javascript">
            var file = document.getElementById('file');
            var image = document.getElementById('upload-photo');
            if(image.getAttribute("src").length!=0){
                file.removeAttribute("required");
            }
            file.onchange = function() {
                var fileData = this.files[0];//获取到一个FileList对象中的第一个文件( File 对象),是我们上传的文件
                var pettern = /^image/;

                console.info(fileData.type);

                if (!pettern.test(fileData.type)) {
                    alert("图片格式不正确");
                    return;
                }
                var reader = new FileReader();
                reader.readAsDataURL(fileData);//异步读取文件内容，结果用data:url的字符串形式表示
                /*当读取操作成功完成时调用*/
                reader.onload = function(e) {
                    console.log(e); //查看对象
                    console.log(this.result);//要的数据 这里的this指向FileReader（）对象的实例reader
                    image.setAttribute("src", this.result);
                    image.setAttribute("opacity", 1);
                };
            };
        </script>
        <div>

            <h4>图片主题：</h4>
            <select  class="titleFilter" name="titleFilter" placeholder="Filter by Content">
                <option value="Scenery">Scenery</option>
                <option value="City">City</option>
                <option value="Wonder">Wonder</option>
                <option value="People" >People</option>
                <option value="Animal">Animal</option>
                <option value="Building">Building</option>
                <option value="others">Others</option>
            </select>
            <div>
                <h4>拍摄国家:</h4>
                <select id="s1" name="country"></select>
            </div>
            <div>
                <h4>拍摄城市:</h4>
                <select id="s2" name="city"></select>
            </div>
            <br>
        </div>
        <div>
            <h4>图片标题:</h4>
            <input type="text" name="photo-title" class="photo-title" required>
            <h4>图片描述:</h4>
            <input type="text" name="photo-description" class="photo-description" required>
        </div>

        <input type="submit" name="submit" class="submit" value="<?php echo $submitText ?>" id="submit" ">
    </form>
    <script>
        var city =<?php echo $arr; ?>;
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
                  alert('请选择城市');
              }
              return !(select1.options[0].selected);
           }

    </script>

</section>
<footer>
    Copyright © 2019-2021 Web fundamental. All Rights Reserved. 备案号：19302010084
</footer>
</body>
</html>
