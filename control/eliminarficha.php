<?php

    require_once "../modelo/clase_ficha.php"; //Llamo a la clase

    $id_ficha = $_POST["id_ficha"]  ?? null;

    $ficha = new Ficha();

    $resultado = $ficha->eliminar_ficha($id_ficha); 

    //Mostrar resultados
    $output = [];
    
    $output['data'] = $resultado;
  
    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON
 

?>