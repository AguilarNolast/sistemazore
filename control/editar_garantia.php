<?php

    require_once '../modelo/clase_garantia.php';

    $id_garantia = $_POST["id_garantia"]  ?? null;

    $nombre = $_POST["nombre"] ?? null;
    $ruc = $_POST["ruc"] ?? null;
    $fecha = $_POST["fecha"] ?? null;
    $factura = $_POST["factura"] ?? null;
    $oc = $_POST["oc"] ?? null;
    $marca = $_POST["marca"] ?? null;
    $modelo = $_POST["modelo"] ?? null;
    $potencia = $_POST["potencia"] ?? null;
    $unipotencia = $_POST["unipotencia"] ?? null;
    $serie = $_POST["serie"] ?? null;
    $tipo = $_POST["tipoequipo"] ?? null;

    if(isset($_POST["tvss"])){
        $tvss = 1 ?? null;
    }else{
        $tvss = 0 ?? null;
    }

    if(isset($_POST["manual_v"])){
        $manual_v = 1 ?? null;
    }else{
        $manual_v = 0 ?? null;
    }

    $garantia_obj = new Garantia();

    $resultado = $garantia_obj->editar_garantia( 
        $id_garantia,
        $nombre,
        $ruc,
        $factura,
        $oc,
        $fecha,
        $tipo,
        $marca,
        $potencia,
        $unipotencia,
        $tvss,
        $modelo,
        $serie,
        $manual_v
    ); 

    //Mostrar resultados
    $output = [];
    
    $output['data'] = $resultado;
  
    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON
 

?>