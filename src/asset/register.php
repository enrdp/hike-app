<?php
session_start();
require_once("../connection.php");

if(!empty($_POST)){
$nickname = trim(htmlspecialchars($_POST['nickname']));
$email = trim(htmlspecialchars($_POST['email']));
$password = $_POST['pass'];
$cpassword = $_POST['cpass'];
$token = md5(date("YmdHis") . $email);
$prob = "";


if($password !== $cpassword){
    $prob = "Passwords aren\'t the same";
    
}else{
$hash = password_hash($password, PASSWORD_BCRYPT);
try{

$sql = $db -> prepare("INSERT INTO users( nickname, email, password, activate )
SELECT '$nickname', '$email', '$hash', '$token' FROM DUAL
WHERE NOT EXISTS (SELECT nickname, email FROM users WHERE nickname = '$nickname' OR email = '$email'); ");

$sql -> execute();


$name2 = false;
$email2 = false;
$count = $sql ->rowCount();
    if($count > 0){
        $result = true;
        $msg = "Hi $nickname! Account created here is the activation
         link http://localhost/asset/activate.php?token=$token";
         if (@mail(
            $email, "Confirm your email", $msg,
            implode("\r\n", ["MIME-Version: 1.0", "Content-type: text/html; charset=utf-8"])
        )) { $prob = "Email sent"; }

    }else{
        $sql2 = $db -> prepare("SELECT nickname, email FROM users WHERE (nickname = '$nickname' OR email = '$email');");
        $sql2 -> execute();
        $users2 = $sql2->fetchAll(PDO::FETCH_ASSOC);
            foreach($users2 as $user2){
            if($user2['nickname'] == $nickname){
                $prob = "This Username is already used";
                $name2 = true;
            }if($user2['email'] == $email){
                $prob = "This email is already used";
                $email2 = true;
            }if($name2 == true && $email2 == true){
                $prob = "These Username and email are already used";
            }
          
        $result = false;
            }
    }



}catch(Exception $e) {
    echo $e->getMessage();
    exit;

}
$users = $sql->fetchAll(PDO::FETCH_ASSOC);
}
}
?>

<?php $title = "Haking App Register";
include '../header.php'; 

?>

<div class="container-fluid vh-100">
                <div class="rounded d-flex justify-content-center">
                    <div class="col-sm-12 shadow-lg p-5 bg-light">
                        <div class="text-center">
                            <h3 class="text-primary">Register</h3>
                        </div>
                        <form action="" method="post">
                            <div class="p-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-person-fill text-white"></i></span>
                                    <input type="text" class="form-control" name="nickname" placeholder="Username" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-envelope-fill text-white"></i></span>
                                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-lock-fill text-white"></i></span>
                                    <input type="password" name="pass" class="form-control" placeholder="Password" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-lock-fill text-white"></i></span>
                                    <input type="password" name="cpass" class="form-control" placeholder="Confirm Password" required>
                                </div>
                                
                                <button class="btn btn-primary text-center mt-2" type="submit" value="submit" >
                                    Register
                                </button>
                                <p class="text-center mt-5">Do you have an account?
                                    <a href="./login.php" class="text-primary">Login</<a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

<?php
if(isset($_POST['submit'])){
echo $prob;
}
?>

<?php 
include '../footer.php';
?>