<?php

require_once "../modelo/clase_productos.php"; //Llamo a la clase cliente

    $id_producto = isset($_POST['producto']) ? $_POST['producto'] : null; //Dato que viene de la vista para hacer la busqueda

    $producto = new Productos();

    $resultado = $producto->mostrar_producto($id_producto); 

    $num_rows = $resultado->num_rows; 

    //Mostrar resultados
    $output = [];
    $output['descripcion'] = '';
    $output['precio'] = '';
    $output['nombre'] = '';

    if(!empty($id_producto)){
        if ($num_rows > 0){//Verificamos que haya algun resultado
            $row = $resultado->fetch_assoc();
            $output['descripcion'] = $row['descripcion'];
            $output['precio'] = $row['precio'];
            $output['nombre'] = $row['nombre'];
        }
    }else{
        $output['descripcion'] = '';
        $output['precio'] = '';
    }


    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON

?>