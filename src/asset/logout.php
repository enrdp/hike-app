<?php

session_start();

// delete session variable (unset($_SESSION["user"]);)

session_destroy();

header("location: ../index.php");