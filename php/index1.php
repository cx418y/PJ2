<?php
require_once "config.php";

//找到收藏最多的图片的ID
function findBannerID()
{
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from travelimagefavor Order By ImageID"; //SQL语句
        $result = $pdo->query($sql);
        $item = array();
        $max = 0;
        $bID = null;
        while ($row = $result->fetch()) {
            if (array_key_exists($row['ImageID'], $item)) {
                $item[$row['ImageID']] += 1;
            } else {
                $item[$row['ImageID']] = 1;
            }
        }
        foreach ($item as $key => $value) {
            if ($value > $max) {
                $max = $value;
                $bID = $key;
            }
        }
        outputBannerID($bID);
        $pdo = null;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

//输出热图的路径
function outputBannerID($bID)
{
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from travelimage where ImageID = '$bID'"; //SQL语句
        $result = $pdo->query($sql);
        if ($row = $result->fetch()) {
            echo $row['PATH'];
        }
        $pdo = null;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

require_once "config.php";
function outputImage() {
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from travelimage Order By RAND()"; //SQL语句
        $result = $pdo->query($sql);
        $n=1;
        while ($row = $result->fetch()) {
            if($n<=6) {
                // echo $row['PATH'];
                outputSingleImage($row);
                $n++;
            }
            else{ break;}
        }
        $pdo = null;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}
function outputSingleImage($row){
    echo '<div class="home_photo_overview">
        <div class="home_photo">';
    $img = '<img src="travel-images/small/'.$row["PATH"].' ">';
    echo constructGenreLink($row['ImageID'], $img);
    echo '</div>';
    echo '<div>';
    echo' <h3>';
    echo  $row['Title'];
    echo '</h3>';
    if(isset($row['Description'])) {
       echo'<small>' . $row['Description'] . '</small>
        </div>
    </div>';
    }
    else{
        echo'<small>There is no description.</small>
        </div>
    </div>';
    }
    outputDetails($row);
}
function outputDetails($row){
    return $row;
}
function constructGenreLink($id, $label) {
    $link = '<a href="webpages/details.php?id=' . $id . '">';
    $link .= $label;
    $link .= '</a>';
    return $link;
}
function displayImage_Details($row){
    echo '<img src="../travel-images/small/'.$row['PATH'].'">';
}






