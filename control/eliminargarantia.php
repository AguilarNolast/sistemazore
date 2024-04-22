<?php

    require_once "../modelo/clase_garantia.php"; //Llamo a la clase

    $id_garantia = $_POST["id_garantia"]  ?? null;

    $garantia = new Garantia();

    $resultado = $garantia->eliminar_garantia($id_garantia); 

    //Mostrar resultados
    $output = [];
    
    $output['data'] = $resultado;
  
    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON
 

?>