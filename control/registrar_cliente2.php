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
        list($mensaje,$id_cliente,$nombre_entidad,$ruc) = $cliente->registrar_cliente2(
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
        $output['data'] = $mensaje;
        $output['id_cliente'] = $id_cliente;
        $output['nombre_entidad'] = $nombre_entidad;
        $output['ruc'] = $ruc;
    
        echo json_encode($output, JSON_UNESCAPED_UNICODE); // Enviamos los datos encriptados en un JSON
    } catch (Exception $e) {
        // Manejo de errores
        $errorOutput = ['error' => $e->getMessage()];
        echo json_encode($errorOutput, JSON_UNESCAPED_UNICODE);
    }
?>