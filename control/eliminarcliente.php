<?php

    require_once "../modelo/clase_clientes.php"; //Llamo a la clase cliente

    $id_cliente = $_POST["id_cliente"]  ?? null;

    $cliente = new Clientes();

    $resultado = $cliente->eliminar_cliente($id_cliente); 

    //Mostrar resultados
    $output = [];
    
    $output['data'] = $resultado;
  
    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON
 

?>