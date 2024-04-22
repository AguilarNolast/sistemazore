<?php

    require_once "../modelo/clase_cotizacion.php"; //Llamo a la clase cliente

    $id_coti = $_POST["id_coti"]  ?? null;

    $coti_obj = new Cotizacion();

    $resultado = $coti_obj->eliminar_coti($id_coti); 

    //Mostrar resultados
    $output = [];
    
    $output['data'] = $resultado;
  
    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON
 

?>