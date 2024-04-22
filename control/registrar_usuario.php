<?php

    require_once "../modelo/clase_usuario.php"; //Llamo a la clase cliente

    $usuario = $_POST["usuario"]  ?? null;
    $clave = $_POST["clave"]  ?? null;
    $nombre = $_POST["nombre"]  ?? null;
    $apellido = $_POST["apellido"]  ?? null;
    $telefono = $_POST["telefono"]  ?? null;
    $correo = $_POST["correo"]  ?? null;

    $hashed_password = password_hash($clave, PASSWORD_DEFAULT);

    $usuario_obj = new Usuario();

    try {
        $resultado = $usuario_obj->registrar_usuario(
            $usuario,
            $hashed_password,
            $nombre,
            $apellido,
            $telefono,
            $correo
        );
    
        // Mostrar resultados
        $output = [];
        $output['data'] = $resultado;
    
        echo json_encode($output, JSON_UNESCAPED_UNICODE); // Enviamos los datos encriptados en un JSON
    } catch (Exception $e) {
        // Manejo de errores
        $errorOutput = ['error' => $e->getMessage()];
        echo json_encode($errorOutput, JSON_UNESCAPED_UNICODE);
    }

?>