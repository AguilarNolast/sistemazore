<?php

    require_once '../modelo/clase_calidad.php';

    $id_calidad = $_POST["id_calidad"]  ?? null;

    $arrayCalidad = array(
        'nombre' => $_POST["nombre"] ?? null,
        'ruc' => $_POST["ruc"] ?? null,
        'marca' => $_POST["marca"] ?? null,
        'potencia' => $_POST["potencia"] ?? null,
        'unipotencia' => $_POST["unipotencia"] ?? null,
        'factor' => $_POST["factor"] ?? null,
        'direccion' => $_POST["direccion"] ?? null,
        'tipo' => $_POST["tipoequipo"] ?? null,
        'serie' => $_POST["serie"] ?? null,
        'fecha_fab' => $_POST["fecha_fab"] ?? null
    );

    $calidad_obj = new Calidad();

    $resultado = $calidad_obj->editar_calidad($arrayCalidad,$id_calidad); 

    //Mostrar resultados
    $output = [];
    
    $output['data'] = $resultado;
  
    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON
 

?>