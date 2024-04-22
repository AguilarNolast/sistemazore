<?php

    session_start();

    require_once '../modelo/clase_calidad.php';
    
    $nombre = $_POST["nombre"] ?? null;
    $ruc = $_POST["ruc"] ?? null;
    $direccion = $_POST["direccion"] ?? null;
    $tipoequipo = $_POST["tipoequipo"] ?? null;
    $potencia = $_POST["potencia"] ?? null;
    $unipotencia = $_POST["unipotencia"] ?? null;
    $factor = $_POST["factor"] ?? null;
    $marca = $_POST["marca"] ?? null;
    $serie = $_POST["serie"] ?? null;
    $fecha_fab = $_POST["fecha_fab"] ?? null;

    $calidad_obj = new Calidad();

    try {
        $resultado = $calidad_obj->registrar_calidad(
            $nombre,
            $ruc,
            $direccion,
            $tipoequipo,
            $potencia,
            $unipotencia,
            $factor,
            $marca,
            $serie,
            $fecha_fab
        );
    
        // Mostrar resultados
        $output = [];
        $output['data'] = $resultado;

        return $resultado;
    
    } catch (Exception $e) {
        // Manejo de errores
        $errorOutput = ['error' => $e->getMessage()];
        echo json_encode($errorOutput, JSON_UNESCAPED_UNICODE);
    }
?>