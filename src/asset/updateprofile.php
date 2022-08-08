<?php
session_start();
require_once('../connection.php');
if(!isset($_SESSION['user'])) {
    header("location: ../index.php");
}

$username = $_SESSION['user']['nickname'];
$email = $_SESSION['user']['email'];
$id = $_SESSION['user']['id'];
$prob = "";

if(!empty($_POST)){
    $nickname = trim(htmlspecialchars($_POST['nickname']));
    $password = $_POST['pass'];
    $cpassword = $_POST['cpass'];
    
    
    
    
if($password !== $cpassword){
    $prob = "Passwords aren\'t the same";
    
}else{
    
$hash = password_hash($password, PASSWORD_BCRYPT);

try{
    $q = $db->prepare("SELECT nickname FROM users WHERE nickname = '$nickname'");
    $q->execute();

    $count = $q ->rowCount();
    if($count > 0){
        $prob = "Nickname is already in the database";
    }else{
        $sql = $db->prepare("UPDATE users SET nickname = '$nickname', password = '$hash' WHERE id = '$id';"); 
        $sql->execute();

        session_destroy();
        header("location: ../index.php");
    }

}catch(Exception $e) {
    echo $e->getMessage();
    exit;

}
}
}


?>


<?php $title = "Haking App Update profile";
include '../header.php'; 

?>

<div class="container-fluid vh-100">
                <div class="rounded d-flex justify-content-center">
                    <div class="col-sm-12 shadow-lg p-5 bg-light">
                        <div class="text-center">
                            <h3 class="text-primary">Update Profile</h3>
                        </div>
                        <form action="" method="post">
                            <div class="p-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-person-fill text-white"></i></span>
                                    <input type="text" class="form-control" name="nickname" value="<?php echo $username; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-envelope-fill text-white"></i></span>
                                            <span class="edit_profile"><?php echo $email;  ?></span>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-lock-fill text-white"></i></span>
                                    <input type="password" name="pass" class="form-control" placeholder="Password">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-lock-fill text-white"></i></span>
                                    <input type="password" name="cpass" class="form-control" placeholder="Confirm Password">
                                </div>
                                
                                <button class="btn btn-primary text-center mt-2" type="submit" value="submit">
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        

<?php

echo $prob;

?>

<?php 
include '../footer.php';
?>