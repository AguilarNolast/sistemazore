<?php

    require_once "../modelo/clase_clientes.php"; //Llamo a la clase cliente

    session_start();

    $id_usuario = $_SESSION["id_usuario"];

    $numero = $_POST["numero"]  ?? null;
    $entidad = $_POST["entidad"]  ?? null;
    $direccion = $_POST["direccion"]  ?? null;
    $distrito = $_POST["distrito"]  ?? null;
    $departamento = $_POST["departamento"]  ?? null;
    $tipocliente = $_POST["tipocliente"]  ?? null;
    $pagocliente = $_POST["pagocliente"]  ?? null;

    $nombre = json_decode($_POST["nombre"])  ?? null;
    $telefono = json_decode($_POST["telefono"])  ?? null;
    $correo = json_decode($_POST["correo"])  ?? null;
    $cargo = json_decode($_POST["cargo"])  ?? null;

    $cliente = new Clientes();

    try {
        $resultado = $cliente->registrar_cliente(
            $numero,
            $entidad,
            $direccion,
            $distrito,
            $departamento,
            $nombre,
            $telefono,
            $correo,
            $cargo,
            $id_usuario,
            $tipocliente,
            $pagocliente
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