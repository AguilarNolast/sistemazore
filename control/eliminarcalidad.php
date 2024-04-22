<?php

    require_once "../modelo/clase_calidad.php"; //Llamo a la clase

    $id_calidad = $_POST["id_calidad"]  ?? null;

    $calidad = new Calidad();

    $resultado = $calidad->eliminar_calidad($id_calidad); 

    //Mostrar resultados
    $output = [];
    
    $output['data'] = $resultado;
  
    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON
 

?>