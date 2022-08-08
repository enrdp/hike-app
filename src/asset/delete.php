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
    $q = $db->prepare("SELECT ID, name, difficulty, distance, duration, elevation_gain, created_at, updated_at, creator FROM hikes 
    WHERE ID = '$id'");
    

    //To execute the query set into $q (PDOStatement) object
    $q->execute();

} catch(Exception $e) {
    echo $e->getMessage();
    exit;
}
$hikes = $q->fetch(PDO::FETCH_ASSOC);


if($_SESSION['user']['id'] != $hikes['creator'] || $_SESSION["user"]["admin"] == 0 ){
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

if(!empty($_POST)){
    try {

        //prepare() is a PDO method to make sure that our query is not subject to a SQL inject.
        //this returns a PDOStatement object
        $q = $db->prepare("DELETE FROM hikes WHERE ID = '$id'");
        

        //To execute the query set into $q (PDOStatement) object
        $q->execute();

        if($q){
            header('Location: ./read.php');
        }

    } catch(Exception $e) {
        echo $e->getMessage();
        exit;
    }
    
}


?>

<?php 
    $title = "Haking App Delete";
    include '../header.php'; 


    $create = $hikes['created_at'];
    $update = $hikes['updated_at'];
    $origin = null;
    if($update == $origin){
        $state = "Created at " . $hikes['created_at'];
    }else{
        $state = "Updated at " . $hikes['updated_at'];
    }

 ?>


<table>
    <tr>
        <th>Name</th>
        <th>Difficulty</th>
        <th>Distance</th>
        <th>Duration</th>
        <th>Elevation Gain</th>
    </tr>

    <tr>
        <td><?php echo $hikes["name"]; ?></td>
        
        
        <td><?php echo $hikes["difficulty"]; ?> </td>
        
        
        <td><?php echo $hikes["distance"]; ?> </td>
        
        
        <td><?php echo conversion($hikes['duration']); ?> </td>
        
        
        <td><?php echo $hikes["elevation_gain"]; ?> </td>

        <td><?php echo $state; ?> </td>


    </tr>

</table>

<form method="post" action="">

<input type="submit" name="delete" value="delete"> 

</form>

<?php 
include '../footer.php';
?>
