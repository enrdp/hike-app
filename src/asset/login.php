<?php 
session_start();
require_once("../connection.php");
if(!empty($_POST)){
    if(isset($_POST["nickname"],$_POST["password"])
    && !empty($_POST["nickname"]) && !empty($_POST["password"])) {

    $nickname = strip_tags($_POST["nickname"]);
    $password  = $_POST['password'];
    

    try{
    $sql = $db->prepare("SELECT * FROM users WHERE nickname = :nickname");
    $sql->bindParam(":nickname", $nickname, PDO::PARAM_STR);
    $sql->execute();

    }catch(Exception $e) {
        echo $e->getMessage();
        exit;
}
$user = $sql->fetch(PDO::FETCH_ASSOC);
if(!$user) {
    die("user doesn't exist &/or password incorrect");
}

// check the password input with the password in db
if(!password_verify($password, $user["password"])){
    die("user doesn't exist &/or password incorrect");
}

}
$_SESSION["user"] = [
    "id" => $user["id"],
    "nickname" => $user["nickname"],
    "email" => $user["email"],
    "admin" => $user["admin"]
];
header("location: ../index.php");
    }


$title = "Haking App Login";
include "../header.php";

?>
    <div class="container-fluid vh-100">
                <div class="rounded d-flex justify-content-center">
                    <div class="col-sm-12 shadow-lg p-5 bg-light">
                        <div class="text-center">
                            <h3 class="text-primary">LogIn</h3>
                        </div>
                        <form action="" method="post">
                            <div class="p-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-person-fill text-white"></i></span>
                                    <input type="text" class="form-control" name="nickname" placeholder="Username">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-lock-fill text-white"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                </div>
                                
                                <button class="btn btn-primary text-center mt-2" type="submit">
                                    Login
                                </button>
                                <p class="text-center mt-5">Don't have an account?
                                    <a href="./register.php" class="text-primary">Sign Up</<a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        
        



    <?php 
include '../footer.php';
?>