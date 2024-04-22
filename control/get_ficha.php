<?php

    require_once "../modelo/clase_ficha.php"; //Llamo a la clase 

    $id_ficha= $_POST['id_ficha'];

    $ficha_obj = new Ficha();

    $resultado_ficha = $ficha_obj->get_ficha($id_ficha);

    $row_ficha = $resultado_ficha->fetch_assoc();

    //Mostrar resultados
    $output = [];

    $output['ficha'] = $row_ficha;

    echo json_encode($output, JSON_UNESCAPED_UNICODE);

?>