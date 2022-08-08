<?php
session_start();
require_once('../connection.php');
if(!isset($_SESSION['user'])) {
    header("location: ../index.php");
}


if(empty($_GET['ID'])){
    echo "Error link incorrect";
    exit;
}

$id = $_GET['ID'];


try {

    //prepare() is a PDO method to make sure that our query is not subject to a SQL inject.
    //this returns a PDOStatement object
    $q = $db->prepare("SELECT ID, name, difficulty, distance, duration, elevation_gain,creator   FROM hikes 
    WHERE ID = '$id'");
    

    //To execute the query set into $q (PDOStatement) object
    $q->execute();

} catch(Exception $e) {
    echo $e->getMessage();
    exit;
}

// PDO::FETCH_ASSOC to display only the columns as keys in the array returned
$hikes = $q->fetch(PDO::FETCH_ASSOC);

if($_SESSION['user']['id'] != $hikes['creator'] && $_SESSION["user"]["admin"] == 0 ){
    header('location: ./read.php');
}


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


//Update part
if(!empty($_POST)){
    
    if(isset($_POST['name'],$_POST['difficulty'],
     $_POST['distance'], $_POST['duration'], $_POST['elevation'])){
    $name = htmlspecialchars($_POST['name']);
    $difficulty = $_POST['difficulty'];
    $distance = $_POST['distance'];
    $duration = $_POST['duration'];
    $elevation = $_POST['elevation'];
    
    $dure = explode(':', $duration );
    $total = ($dure[0] * 60) + $dure[1];

    try{
    $sql = $db->prepare("UPDATE hikes SET name = '$name', difficulty = '$difficulty', distance = '$distance',
     duration = '$total', elevation_gain = '$elevation' 
     WHERE ID = '$id';");
    $sql->execute();
    if($sql){
        header('Location: ./read.php');
    }
    //(SELECT * FROM (SELECT name FROM hikes WHERE name = '$name') as hikes) LIMIT 1;")
    //AND NOT EXISTS (SELECT * FROM (SELECT name from hikes WHERE name = '$name') AS data)
    
    }catch(Exception $e) {
        echo $e->getMessage();
        exit;
    
    }
    $hikes = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>

<?php 
    $title = "Haking App Update";
    include '../header.php'; 

?>

<form method="post" action="">

<label for="name">Name of the hike : </label>
<input type="text" name="name" value="<?php echo $hikes['name']; ?>" required>

<br>

<label for="difficulty"> Difficulty of the hike : </label>
<input type="radio" name="difficulty" value="very easy" <?php if($hikes['difficulty'] == "very easy" ){ echo "checked='checked'"; } ?> required>
<label for="very easy">very easy </label>
<input type="radio" name="difficulty" value="easy" <?php if($hikes['difficulty'] == "easy" ){ echo "checked='checked'"; } ?>>
<label for="very easy">easy </label>
<input type="radio" name="difficulty" value="medium"  <?php if($hikes['difficulty'] == "medium" ){ echo "checked='checked'"; } ?>>
<label for="very easy">medium </label>
<input type="radio" name="difficulty" value="hard" <?php if($hikes['difficulty'] == "hard" ){ echo "checked='checked'"; } ?>>
<label for="very easy">hard </label>
<input type="radio" name="difficulty" value="very hard" <?php if($hikes['difficulty'] == "very hard" ){ echo "checked='checked'"; } ?>>
<label for="very easy">very hard </label>

<br>

<label for="distance"> Distance in Km of the hike : </label>
<input type="number" name="distance" step="0.01" value="<?php echo $hikes['distance']; ?>" required>

<br>

<label for="duration"> duration of the hike : </label>
<input type="text" name="duration" required pattern="[0-9]{2}:[0-9]{2}" 
title=" hh:mm " placeholder="hh:mm" value="<?php echo conversion($hikes['duration']);?>" >

<br>

<label for="elevation"> elevation gain during the hike : </label>
<input type="number" name="elevation" value="<?php echo $hikes['elevation_gain']; ?>" required>

<br>

<label for="submit"></label>
<input type="submit" name="submit" value="submit"> 

</form>
<?php 
include '../footer.php';
?>