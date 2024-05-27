
<?php

    require_once "../modelo/clase_productos.php"; //Llamo a la clase

    $producto = new Productos();

    $resultado = $producto->buscar_productos(); 

    echo $resultado;

?>