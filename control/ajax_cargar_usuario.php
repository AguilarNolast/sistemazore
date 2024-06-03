<?php

    require_once "validatetoken.php";


        require "../modelo/clase_usuario.php"; //Llamo a la clase cliente

        $usuario = new Usuario();

        list($resultado, $totalFiltro, $totalRegistros, $columns) = $usuario->listado_usuarios(null, 10, 0, '', '');

        while($row = $resultado->fetch_assoc()){
            echo $row['nombres'];
        }
    

?>