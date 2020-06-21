<?php
session_start();
require_once "config.php";
try{
    $title = $_POST['photo-title'];
    $description = $_POST['photo-description'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $content = $_POST['titleFilter'];
    $UID = $_SESSION['UID'];

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

    //修改(未改变图片)
        if ($_FILES['file']['name'] == null) {
            $ImageID = $_GET['ImageID'];
            $sql3 = "Update travelimage Set Title='$title', Description='$description',Content='$content',CityCode='$cityCode',Country_RegionCodeISO='$countryCode' where ImageID = '$ImageID'";
            $result3 = $pdo->query($sql3);
            if (isset($result3)) {
                echo "<script>alert('修改成功！');window.location.href('../webpages/my_photo.php')</script>";
            }
        }


    //上传和改变图片的修改
    else {
        $filename = $_FILES['file']['name'];
        $type=$_FILES['file']['type'];
        $tmp_name=$_FILES['file']['tmp_name'];
        $size = $_FILES['file']['size'];
        $error =$_FILES['file']['error'];

        //保存文件到文件夹中
        move_uploaded_file($tmp_name,"../travel-images/small/uploadFiles/".$filename);
        $a = "../travel-images/small/uploadFiles/".$filename;
        $filePath = array();
        function traverse($path = '.'){
            global $filePath;
            $current_dir = opendir($path);
            while($file = readdir($current_dir)!==false){
                $sub_dir = $path . DIRECTORY_SEPARATOR . $file;
                if($file=='.'||$file=='..') {
                    continue;
                }else if (is_dir($sub_dir)){
                    echo 'Directory ' . $file . ':';
                    traverse($sub_dir);
                }else{
                    echo '../'.$file.'<br/>';
                    $filePath[$path.'/'.$file]='../travel-images/small/uploadFiles/'.$file;
                }
            }
            return $filePath;
        }
        $array = traverse("../travel-images/small/uploadFiles");

        $sql = "select * From travelimage order by ImageID";
        $result = $pdo->query($sql);
        $path = "uploadFiles/".$filename;

        //修改
        if(isset($_GET['ImageID'])){
            $ImageID = $_GET['ImageID'];
            while($row=$result->fetch()){
                if($row['PATH']==="uploadFiles/".$filename) {
                    $sql5 = "select * From travelimage where ImageID = '$ImageID'";
                    $result5 = $pdo->query($sql5);
                    $row5 = $result5->fetch();
                    if ($row5['PATH'] == $path) {
                        $repeat = false;
                    } else {
                        $repeat  =true;
                        echo "<script>alert('您已经上传过该图片');window.location.href=('../webpages/upload.php');</script>";
                    }
                }
            }
            if(!$repeat) {
                $sql3 = "Update travelimage Set Title='$title', Description='$description',Content='$content',CityCode='$cityCode',Country_RegionCodeISO='$countryCode',PATH='$path' where ImageID = '$ImageID'";
                $result3 = $pdo->query($sql3);
                if (isset($result3)) {
                    echo "<script>alert('修改成功！');window.location.href=('../webpages/my_photo.php');</script>";
                }
            }
        }

        //上传
        else {
            $imgID = 1;
            $repeat = false;
            while($row=$result->fetch()){
                if($row['PATH']==="uploadFiles/".$filename){
                    $repeat = true;
                    echo "<script>alert('您已经上传过该图片');window.location.href=('../webpages/upload.php');</script>";
                }
                if($row['ImageID']==$imgID){
                    $imgID++;
                }
            }
            if(!$repeat) {
                $sql_insert = "insert into travelimage(ImageID,Title,Description,CityCode,Country_RegionCodeISO,UID,PATH,Content) values('$imgID','$title','$description','$cityCode','$countryCode','$UID','$path','$content')";
                $rel_insert = $pdo->query($sql_insert);
                if ($rel_insert) {
                    echo "<script>alert('上传成功！');window.location.href = ('../webpages/my_photo.php');</script>";
                }
            }
        }
    }
}catch (PDOException $e) {
    die($e->getMessage());
}


