<?php

    require_once "../modelo/clase_calidad.php"; //Llamo a la clase 

    $id_calidad= $_POST['id_calidad'];

    $calidad_obj = new Calidad();

    $resultado_calidad = $calidad_obj->get_calidad($id_calidad);

    $row_calidad = $resultado_calidad->fetch_assoc();

    //Mostrar resultados
    $output = [];

    $output['calidad'] = $row_calidad;

    echo json_encode($output, JSON_UNESCAPED_UNICODE);

?>