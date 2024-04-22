<?php

    require_once "../modelo/clase_productos.php"; //Llamo a la clase

    $id_producto = $_POST["id_producto"]  ?? null;

    $producto = new Productos();

    $resultado = $producto->eliminar_producto($id_producto); 

    //Mostrar resultados
    $output = [];
    
    $output['data'] = $resultado;
  
    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON
 

?>