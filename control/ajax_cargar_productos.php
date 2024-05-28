
<?php

    require_once "../modelo/clase_productos.php"; //Llamo a la clase
    
    $input_producto = isset($_GET['input_producto']) ? $_GET['input_producto'] : '';;

    $producto = new Productos();

    $resultado = $producto->buscar_productos($input_producto); 

    echo $resultado;

?>