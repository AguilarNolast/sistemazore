<?php

    require_once "../modelo/clase_productos.php"; //Llamo a la clase cliente

    $id_producto = $_POST["id_producto"]  ?? null;

    $nombre = $_POST["nombre"]  ?? null;
    $descripcion = $_POST["descripcion"]  ?? null;
    $precio = $_POST["precio"]  ?? null;
    $alto = $_POST["alto"]  ?? null;
    $ancho = $_POST["ancho"]  ?? null;
    $largo = $_POST["largo"]  ?? null;
    $peso = $_POST["peso"]  ?? null;

    $producto_obj = new Productos();

    $resultado = $producto_obj->editar_producto( 
        $id_producto,
        $nombre,
        $descripcion,
        $precio,
        $alto,
        $ancho,
        $largo,
        $peso
    ); 

     //Mostrar resultados
    $output = [];
    
    $output['data'] = $resultado;
  

    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON
 

?>