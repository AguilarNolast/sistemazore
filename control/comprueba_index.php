<?php

    if(isset($_SESSION["usuario"])){
        header("location:../vistas/inicio.php");
        //header("location:https://grupozore.com/sistemazore/vistas/inicio.php");
        exit();
    }
?>