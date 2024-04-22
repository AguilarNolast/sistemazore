<?php

    require_once "../modelo/clase_usuario.php"; //Llamo a la clase cliente

    date_default_timezone_set('America/Lima');
    
    $id_usuario = $_POST["id_usuario"]  ?? null;
    $hora_entrada = date("H:i:s");
    $fecha = date("Y/m/d");

    $usuario = new Usuario();

    try {
        $resultado = $usuario->registrar_entrada(
            $id_usuario,
            $hora_entrada,
            $fecha
        );
    
        // Mostrar resultados
        $output = [];
        $output['data'] = $resultado;
    
        echo json_encode($output, JSON_UNESCAPED_UNICODE); // Enviamos los datos encriptados en un JSON
    } catch (Exception $e) {
        // Manejo de errores
        $errorOutput = ['error' => $e->getMessage()];
        echo json_encode($errorOutput, JSON_UNESCAPED_UNICODE);
        // Puedes loguear el error o realizar alguna otra acción de manejo aquí.
        // Evita mostrar mensajes específicos de error al usuario en un entorno de producción.
    }

?>