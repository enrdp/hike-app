<?php
session_start();
require_once('../connection.php');
if(!isset($_SESSION['user'])) {
    header("location: ../index.php");
}

$username = $_SESSION['user']['nickname'];
$email = $_SESSION['user']['email'];

$title = "Profile";
include '../header.php';

?>

<section class="container_profile">
    		<div class="col-md-4">
    		    <div class="card profile-card">
    		        <div class="background-block">
    		            <img src="https://images.pexels.com/photos/459225/pexels-photo-459225.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" alt="profile-sample1" class="background"/>
    		        </div>
    		        <div class="profile-thumb-block">
    		            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png" alt="profile-image" class="profile"/>
    		        </div>
    		        <div class="card-content">
                    <h2><?php echo $username; ?><small><?php echo  $email;  ?> </small></h3>
                    <div class="icon-block"><a href="./updateprofile.php"><i class="bi bi-pencil-square"></i></a></div>
                    </div>
                </div>
    		</div>
    		
    		
    </div>
</section>

<?php 
include '../footer.php';
?>



