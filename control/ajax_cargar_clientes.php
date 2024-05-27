<?php

    require_once "validatetoken.php";


        require "../modelo/clase_clientes.php"; //Llamo a la clase cliente

        $cliente = new Clientes();

        $resultado = $cliente->buscar_clientes(); 

        echo $resultado;
    

?>