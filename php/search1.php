<?php
require_once "config.php";

$filterCondition = $_POST['filter'];
if($filterCondition=="title"){
    $filterContent = $_POST['titleInput'];
}
else $filterContent = $_POST['descriptionInput'];
if(!$filterContent){
    echo "<script>alert('您还没有输入任何东西！');history.go(-1);</script>";
}
else{
    try{
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        if ( mysqli_connect_errno() ) {
            die( mysqli_connect_error() );
        }
        if($filterCondition=="title") {
            $sql = "select * from travelimage where Title LIKE %{$filterContent}%";
            $result = mysqli_query($connection, $sql);
        }

        else{
            $sql = "select * from travelimage where Description LIKE %{$filterContent}%";
            $result = mysqli_query($connection, $sql);
        }

        if($result)
            $totalCount = $result->num_rows;
        else
            $totalCount = 0;

        if($totalCount==0){}
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
            $nextPage = ($totalPage - $currentPage > 0) ? $currentPage + 1 : $totalPage;

            $sql2 = "select * from travelimage where Description LIKE %{$filterContent}% limit " . $mark . "," . $pageSize;
            $result2 = mysqli_query($connection, $sql2);
            function displayResult(){
                global $totalCount,$result,$pageSize;
                if ($totalCount == 0) {
                    echo '<div class="result_show"><h4>没有搜索到相应的图片，换个关键词重新试试吧！</h4></div>';
                }
                for ($j = 0; $j < 5; $j++) {
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
                        <div class="result_photo">
                            <div class="photo">
                                <a href="details.php?id=<?php echo $row['ImageID'] ?>"><img
                                        src="../travel-images/small/<?php echo $row['PATH'] ?>"></a>
                            </div>
                            <div class=" my_photo_intro">
                                <h1><?php echo $row['Title'] ?></h1><br>
                                <p><?php echo $row['Description'] ?></p>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
        }
        echo "<script>window.location.href=('../webpages/Search.php')</script>";
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }
}
