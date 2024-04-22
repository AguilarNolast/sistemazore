<?php

    require_once "validatetoken.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        /*$csrf_token = $_POST['csrf_token'];

        // Validar el token CSRF
        if (!validateCSRFToken($csrf_token)) {
            $output['data'] = 'Error CSRF: Token no válido.';
            die(json_encode($output, JSON_UNESCAPED_UNICODE));
        }*/

        require "../modelo/clase_clientes.php"; //Llamo a la clase cliente

        $input_cliente = isset($_POST['input_cliente']) ? $_POST['input_cliente'] : null; //Dato que viene de la vista para hacer la busqueda

        $cliente = new Clientes();

        $resultado = $cliente->buscar_clientes($input_cliente); 

        $num_rows = $resultado->num_rows; 

        //Mostrar resultados
        $output = [];
        $output['data'] = '';

        //Armando respuesta del JSON
        if(!empty($input_cliente)){
            if ($num_rows > 0){//Verificamos que haya algun resultado
                while($row = $resultado->fetch_assoc()){ 
                    $output['data'] .= '<div class="lista-item" onclick="getContacto('. $row['id_clientes'] .', \''. $row['razon_social'] .'\', \''. $row['ruc'] .'\')">' . $row['ruc'] . ' - ' . $row['razon_social'] . '</div>';
                }
            }else{
                $output['data'] = '<div class="lista-item">Sin resultados</div>';
            }
        }else{
            $output['data'] = '';
        }
        
        echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON
    
    }

?>