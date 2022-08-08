<?php
require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

define("HOST",$_ENV['DB_HOST']);
define("DB",$_ENV['DB_NAME']);
define("PORT", "3306");
define("LOGIN",$_ENV['DB_USER']);
define("PASSWORD",$_ENV['DB_PASS']);



try {

    // We create a new instance of the class PDO
    $db = new PDO("mysql:host=".HOST.";dbname=".DB.";port=".PORT, LOGIN, PASSWORD);

    //We want any issues to throw an exception with details, instead of a silence or a simple warning
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(Exception $e) {
    // We intantiate an Exception object in $e so we can use methods within this object to display errors nicely
    echo $e->getMessage();
    exit;
}


