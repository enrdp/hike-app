<?php
session_start();
require_once("../connection.php");
if(!isset($_SESSION['user'])) {
    header("location: ../index.php");
}

$creator = $_SESSION["user"]["id"];

if(!empty($_POST)){
    
    if(isset($_POST['name'],$_POST['difficulty'],
     $_POST['distance'], $_POST['duration'], $_POST['elevation'])){
    $name = trim(htmlspecialchars($_POST['name']));
    $difficulty = $_POST['difficulty'];
    $distance = $_POST['distance'];
    $duration = $_POST['duration'];
    $elevation = $_POST['elevation'];
    

    $dure = explode(':', $duration );
    $total = ($dure[0] * 60) + $dure[1];

    try{
    $sql = $db->prepare("INSERT INTO hikes (name, difficulty, distance, duration, elevation_gain, creator)
    SELECT '$name', '$difficulty', '$distance', '$total', '$elevation', '$creator' FROM DUAL
    WHERE NOT EXISTS (SELECT name FROM hikes WHERE name = '$name') LIMIT 1;");
    $sql->execute();

    $count = $sql ->rowCount();
    if($count > 0){
        $result = true;
    }else{
        $result = false;
    }
    
    }catch(Exception $e) {
        echo $e->getMessage();
        exit;
    
    }
    $hikes = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>

<?php $title = "Haking App Create";
include '../header.php'; 

 ?>

<div class="container-fluid container-create vh-100">
                <div class="rounded d-flex justify-content-center">
                    <div class="col-sm-12 shadow-lg p-5 bg-light">
                        <div class="text-center">
                            <h3 class="text-primary text-primary-create">Haking App Create</h3>
                        </div>
                        <form action="" method="post">
                            <div class="p-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text-create bg-primary"></span>
                                    <input type="text" class="form-control" name="name" placeholder="Name of the hike">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text-create bg-primary"></span>
                                            <select id="difficulty" name="difficulty">
                                            <option name="difficulty" value="very easy" required>Very easy</option>
                                            <option name="difficulty" value="easy">Easy</option>
                                            <option name="difficulty" value="medium">Medium</option>
                                            <option name="difficulty" value="hard">Hard</option>
                                            <option name="difficulty" value="very hard">Very hard</option>
                                            </select>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text-create bg-primary"></span>
                                    <input type="number" name="distance" step="0.01" required class="form-control" placeholder="Distance in Km of the hike">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text-create bg-primary"></span>
                                    <input type="text" name="duration" pattern="[0-9]{2}:[0-9]{2}" title=" hh:mm " placeholder="hh:mm" class="form-control" required>
                                </div>
                                
                                <div class="input-group mb-3">
                                    <span class="input-group-text-create bg-primary"></span>
                                    <input type="number" name="elevation" class="form-control" placeholder="Elevation gain during the hike" required>
                                </div>

                                <button class="btn btn-create btn-primary text-center mt-2" type="submit" name="submit" value="submit">
                                    Submit
                                </button>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>






<?php
if(isset($_POST['submit'])){
if($result == true){
    echo "<p> succesfully add the new hike. </p>";
}else{
    echo "<p> This hike already exist. </p>";
}
}

?>

<?php 
include '../footer.php';
?>