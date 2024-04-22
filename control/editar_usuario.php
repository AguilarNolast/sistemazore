<?php

    require_once "../modelo/clase_usuario.php"; //Llamo a la clase cliente

    $id_usuario = $_POST["id_usuario"]  ?? null;
    $usuario = $_POST["usuario"]  ?? $_POST["usuarioperfil"];
    $clave = $_POST["clave"]  ?? $_POST["claveperfil"];
    $rep_clave = $_POST["rep_clave"]  ?? $_POST["rep_claveperfil"];
    $nombre = $_POST["nombre"]  ?? $_POST["nombreperfil"];
    $apellido = $_POST["apellido"]  ?? $_POST["apellidoperfil"];
    $telefono = $_POST["telefono"]  ?? $_POST["telefonoperfil"];
    $correo = $_POST["correo"]  ?? $_POST["correoperfil"];

    $hashed_password = password_hash($clave, PASSWORD_DEFAULT);
    
    $usuario_obj = new Usuario();

    $resultado = $usuario_obj->editar_usuario( 
        $id_usuario,
        $usuario,
        $hashed_password,
        $nombre,
        $apellido,
        $telefono,
        $correo
    );

    //Mostrar resultados
    $output = [];
    
    $output['data'] = $resultado;

    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON

?>