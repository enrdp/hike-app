<?php

// open the $_SESSION

session_start();
require_once("../connection.php");
if(!isset($_SESSION['user'])) {
    header("location: ../index.php");
}

try {

    //prepare() is a PDO method to make sure that our query is not subject to a SQL inject.
    //this returns a PDOStatement object
    $q = $db->prepare("SELECT ID, name, difficulty, distance, duration, elevation_gain, created_at, updated_at, creator FROM hikes");
    $Screator = $db->prepare("SELECT nickname,id FROM users;");
    //To execute the query set into $q (PDOStatement) object
    $q->execute();
    $Screator->execute();
} catch(Exception $e) {
    echo $e->getMessage();
    exit;
}

// PDO::FETCH_ASSOC to display only the columns as keys in the array returned
$hikes = $q->fetchAll(PDO::FETCH_ASSOC);
$creat = $Screator->fetchAll(PDO::FETCH_ASSOC);

function conversion($minute){
            $hours = floor($minute / 60);
            $min = $minute - floor($minute /60) * 60;
            if($hours <= 9){
                if($min <= 9){
                    $result = 0 . $hours . ':' . 0 . $min;
                }else{
                    $result = 0 . $hours . ':' .($min);
                }
            }else {
                if($min <= 9){
                    $result = $hours . ':' . 0 . $min;
                }else{
                    $result = $hours . ':' .($min);
                }
            }
            return $result;
}



?>

<?php $title = "Haking App Read";
include '../header.php'; 



?>
<div class="card-group">

 <?php
            //display the datas
            foreach($hikes as $hike) :
                
            ?>
            <?php 
            $create = $hike['created_at'];
            $update = $hike['updated_at'];
            $origin = null;
            if($update == $origin){
                $state = "Created at " . $hike['created_at'];
            }else{
                $state = "Updated at " . $hike['updated_at'];
            }
            $nickname = "";

            foreach($creat as $cre){
            if($cre['id'] == $hike['creator']){
                $nickname = $cre['nickname'];
            }
            }
            

            ?>
               

  <div class="card">
    <div class="card-body">
      <h5 class="card-title"><?php echo $hike["name"]; ?></h5>
      <div class="container-card-info">
      <p><span class="bold">Difficulty: </span><?php echo $hike["difficulty"]; ?> </p>
      <p><span class="bold">Distance: </span><?php echo $hike["distance"] . " Km"; ?></p>
      <p><span class="bold">Duration: </span><?php echo conversion($hike['duration']) . " h";  ?></p>
      <p><span class="bold">Elevation gain: </span><?php echo $hike["elevation_gain"] . " m"; ?></p>
      <p><?php echo "Created by <i>$nickname</i>";  ?></p>
      <?php 
            
            $creator = $_SESSION["user"]["id"];
            $admin = $_SESSION["user"]["admin"];

            if($creator == $hike['creator'] || $admin == 1 ){

            echo "<div class='options'><a href='./update.php?ID=".$hike['ID']."'><i class='bi bi-pencil-square'></i></a>";

            echo "<a href='./delete.php?ID=".$hike['ID']."'><i class='bi bi-trash3-fill'></i></a></div>";
                
            }
            ?>
    </div>
    </div>
    <div class="card-footer">
      <small class="text-muted"><?php echo $state; ?></small>
    </div>
  </div>


<?php

    endforeach;
?>
    </div>


</body>
</html>


<?php 
include '../footer.php';
?>
