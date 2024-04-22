<?php

    require_once '../modelo/clase_pruebas.php';

    
    $id_pruebas = $_POST["id_pruebas"] ?? null;

    $arrayPruebas = array(
        'cliente' => $_POST["cliente"] ?? null,
        'datos_t' => $_POST["datos"] ?? null,
        'fecha' => $_POST["fecha"] ?? null,
        'potencia' => $_POST["potencia"] ?? null,
        'unipotencia' => $_POST["unipotencia"] ?? null,
        'v1' => $_POST["v1"] ?? null,
        'v2' => $_POST["v2"] ?? null,
        'l1' => $_POST["l1"] ?? null,
        'l2' => $_POST["l2"] ?? null,
        'fases' => $_POST["fases"] ?? null,
        'frecuencia' => $_POST["frecuencia"] ?? null,
        'conexion' => $_POST["conexion"] ?? null,
        'grupo' => $_POST["grupo"] ?? null,
        'altitud' => $_POST["altitud"] ?? null,
        'marca' => $_POST["marca"] ?? null,
        'serie' => $_POST["serie"] ?? null,
        'fabricacion' => $_POST["fabricacion"] ?? null,
        'norma' => $_POST["norma"] ?? null,
        'uv1' => $_POST["uv1"] ?? null,
        'uv2' => $_POST["uv2"] ?? null,
        'uv3' => $_POST["uv3"] ?? null,
        'tensionu_v' => $_POST["tensionu_v"] ?? null,
        'tensionv_w' => $_POST["tensionv_w"] ?? null,
        'tensionw_u' => $_POST["tensionw_u"] ?? null,
        'intensidadu_v' => $_POST["intensidadu_v"] ?? null,
        'intensidadv_w' => $_POST["intensidadv_w"] ?? null,
        'intensidadw_u' => $_POST["intensidadw_u"] ?? null,
        'at_bt' => $_POST["at_bt"] ?? null,
        'at_m' => $_POST["at_m"] ?? null,
        'bt_m' => $_POST["bt_m"] ?? null,
        'at_bt_und' => $_POST["at_bt_und"] ?? null,
        'at_m_und' => $_POST["at_m_und"] ?? null,
        'bt_m_und' => $_POST["bt_m_und"] ?? null,
        'minimo' => $_POST["minimo"] ?? null,
        'resultado' => $_POST["resultado"] ?? null,
        'int_lectura' => $_POST["int_lectura"] ?? null,
        'int_valor' => $_POST["int_valor"] ?? null,
        'ten_lectura' => $_POST["ten_lectura"] ?? null,
        'at_tension_u_v' => $_POST["at_tension_u_v"] ?? null,
        'at_tension_v_w' => $_POST["at_tension_v_w"] ?? null,
        'at_tension_w_u' => $_POST["at_tension_w_u"] ?? null,
        'at_intensidad_u_v' => $_POST["at_intensidad_u_v"] ?? null,
        'at_intensidad_v_w' => $_POST["at_intensidad_v_w"] ?? null,
        'at_intensidad_w_u' => $_POST["at_intensidad_w_u"] ?? null,
        'at_resistencia_u_v' => $_POST["at_resistencia_u_v"] ?? null,
        'at_resistencia_v_w' => $_POST["at_resistencia_v_w"] ?? null,
        'at_resistencia_w_u' => $_POST["at_resistencia_w_u"] ?? null,
        'bt_tension_u_v' => $_POST["bt_tension_u_v"] ?? null,
        'bt_tension_v_w' => $_POST["bt_tension_v_w"] ?? null,
        'bt_tension_w_u' => $_POST["bt_tension_w_u"] ?? null,
        'bt_intensidad_u_v' => $_POST["bt_intensidad_u_v"] ?? null,
        'bt_intensidad_v_w' => $_POST["bt_intensidad_v_w"] ?? null,
        'bt_intensidad_w_u' => $_POST["bt_intensidad_w_u"] ?? null,
        'bt_resistencia_u_v' => $_POST["bt_resistencia_u_v"] ?? null,
        'bt_resistencia_v_w' => $_POST["bt_resistencia_v_w"] ?? null,
        'bt_resistencia_w_u' => $_POST["bt_resistencia_w_u"] ?? null,
        'checkresis' => $checkresis ?? null,
        'medido' => $_POST["medido"] ?? null,
        'rela_teo' => $_POST["rela_teo"] ?? null
    );

    $pruebas_obj = new Pruebas();

    $resultado = $pruebas_obj->editar_pruebas($arrayPruebas,$id_pruebas); 

    //Mostrar resultados
    $output = [];
    
    $output['data'] = $resultado;
  
    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON
 

?>