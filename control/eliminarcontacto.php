<?php

    require_once "../modelo/clase_clientes.php"; //Llamo a la clase cliente

    $id_contacto = $_POST["id_contacto"]  ?? null;

    $cliente = new Clientes();

    $resultado = $cliente->eliminar_contacto($id_contacto); 

    //Mostrar resultados
    $output = [];
    
    $output['data'] = $resultado;
  
    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON
 

?>