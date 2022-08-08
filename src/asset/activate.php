<?php
 
require_once('../connection.php');
 
if(isset($_GET['token'])){
    $token = $_GET['token'];

    try {

        //prepare() is a PDO method to make sure that our query is not subject to a SQL inject.
        //this returns a PDOStatement object
        $q = $db->prepare("UPDATE users SET activate=NULL where activate='$token'");
    
        //To execute the query set into $q (PDOStatement) object
        $q->execute();
    
    } catch(Exception $e) {
        echo $e->getMessage();
        exit;
    }
    if($q){
        header('Refresh: 5; URL= ../index.php');
        exit();
    }
    
}
 
?>