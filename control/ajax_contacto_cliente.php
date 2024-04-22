<?php

    require_once "../modelo/clase_clientes.php"; //Llamo a la clase cliente

    $id_cliente = isset($_POST['id_cliente']) ? $_POST['id_cliente'] : null; //Dato que viene de la vista para hacer la busqueda

    $cliente = new Clientes();

    $resultado = $cliente->listado_contacto($id_cliente); 

    $num_rows = $resultado->num_rows; 

    //Mostrar resultados
    $output = [];
    $output['data'] = '';
    $output['id_contacto'] = '';
    $selected = "selected";

    //Armando respuesta del JSON
        if ($num_rows > 0){//Verificamos que haya algun resultado
            while($row = $resultado->fetch_assoc()){ 
                $output['data'] .= '<option '. $selected .' value="'. $row['id_contacto'] .'">'. $row['nombre'] .'</option>';
                if($selected != ""){
                    $output['id_contacto'] = $row['id_contacto'];
                }
                $selected = "";
            }
        }
   
    
    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON


?>