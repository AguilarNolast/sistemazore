<?php

    require_once "../modelo/clase_pruebas.php"; //Llamo a la clase

    $id_pruebas = $_POST["id_pruebas"]  ?? null;

    $pruebas = new Pruebas();

    $resultado = $pruebas->eliminar_pruebas($id_pruebas); 

    //Mostrar resultados
    $output = [];
    
    $output['data'] = $resultado;
  
    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON
 

?>