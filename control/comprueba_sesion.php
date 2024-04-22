<?php
    
    require_once "validatetoken.php";

    generateCSRFToken();

    if(!isset($_SESSION["usuario"]) || time() > $_SESSION['expire_time']){
        header("location:../index.php");
        exit();
    }
?>