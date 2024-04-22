<?php

    require_once "../modelo/clase_usuario.php"; //Llamo a la clase cliente

    $id_usuario = $_POST["id_usuario"]  ?? null;

    $usuario = new Usuario();

    $resultado = $usuario->eliminar_usuario($id_usuario); 

    //Mostrar resultados
    $output = [];
    
    $output['data'] = $resultado;
  
    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON
 

?>