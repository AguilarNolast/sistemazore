<?php

    require_once "../modelo/clase_garantia.php"; //Llamo a la clase 

    $id_garantia= $_POST['id_garantia'];

    $garantia_obj = new Garantia();

    $resultado_garantia = $garantia_obj->get_garantia($id_garantia);

    $row_garantia = $resultado_garantia->fetch_assoc();

    //Mostrar resultados
    $output = [];

    $output['garantia'] = $row_garantia;

    echo json_encode($output, JSON_UNESCAPED_UNICODE);

?>