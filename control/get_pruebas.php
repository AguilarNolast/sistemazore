<?php

    require_once "../modelo/clase_pruebas.php"; //Llamo a la clase 

    $id_pruebas= $_POST['id_pruebas'];

    $pruebas_obj = new Pruebas();

    $resultado_pruebas = $pruebas_obj->get_pruebas($id_pruebas);

    $row_pruebas = $resultado_pruebas->fetch_assoc();

    //Mostrar resultados
    $output = [];

    $output['pruebas'] = $row_pruebas;

    echo json_encode($output, JSON_UNESCAPED_UNICODE);

?>