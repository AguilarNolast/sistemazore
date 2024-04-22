<?php

    require_once "../modelo/clase_clientes.php"; //Llamo a la clase cliente

    $id_contacto = isset($_POST['id_contacto']) ? $_POST['id_contacto'] : null; //Dato que viene de la vista para hacer la busqueda

    $output = [];
    $output['telefono'] = '';
    $output['correo'] = '';

    if (empty($id_contacto)) {
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit; // Terminar el script si $id_contacto está vacía
    }

    $cliente = new Clientes();

    $resultado = $cliente->mostrar_contacto($id_contacto); 

    $num_rows = $resultado->num_rows; 

    //Armando respuesta del JSON
    if ($num_rows > 0){//Verificamos que haya algun resultado

        $row = $resultado->fetch_assoc();

        $output['telefono'] .= $row['tlf_contacto'];
        $output['correo'] .= $row['correo_contacto'];
        
    }

    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON


?>