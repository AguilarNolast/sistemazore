<?php

    require_once "../modelo/clase_config.php"; //Llamo a la clase cliente

    $correos = $_POST["correos"] ?? null;
    $nombres = $_POST["nombres"] ?? null;
    $id_correos = $_POST["id_correos"] ?? null;

    $servidor = $_POST["servidor"] ?? null;
    $clave = $_POST["clave"] ?? null;
    
    $config_obj = new Config();

    try {
        $resultado = $config_obj->updateConfig(
            $correos,
            $nombres,
            $id_correos,
            $servidor,
            $clave
        );

        if($resultado == true){
            $output  = array(
                'tipo' => 'success',
                'mensaje' => 'Configuracion guardada',
            );
        }
    
        echo json_encode($output, JSON_UNESCAPED_UNICODE); // Enviamos los datos encriptados en un JSON
    } catch (Exception $e) {
        // Manejo de errores
        $output['data']  = array(
            'tipo' => 'danger',
            'mensaje' => 'Error' . $e,
        );
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
    }

?>