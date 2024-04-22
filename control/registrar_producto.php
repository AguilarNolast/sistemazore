<?php

    require_once "../modelo/clase_productos.php"; //Llamo a la clase cliente

    $nombre = $_POST["nombre"]  ?? null;
    $descripcion = $_POST["descripcion"]  ?? null;
    $precio = $_POST["precio"]  ?? null;
    $alto = $_POST["alto"]  ?? null;
    $ancho = $_POST["ancho"]  ?? null;
    $largo = $_POST["largo"]  ?? null;
    $peso = $_POST["peso"]  ?? null;

    $producto = new Productos();

    try {
        $resultado = $producto->registrar_producto(
            $nombre,
            $descripcion,
            $precio,
            $alto,
            $ancho,
            $largo,
            $peso
        );
    
        // Mostrar resultados
        $output = [];
        $output['data'] = $resultado;
    
        echo json_encode($output, JSON_UNESCAPED_UNICODE); // Enviamos los datos encriptados en un JSON
    } catch (Exception $e) {
        // Manejo de errores
        $errorOutput = ['error' => $e->getMessage()];
        echo json_encode($errorOutput, JSON_UNESCAPED_UNICODE);
        // Puedes loguear el error o realizar alguna otra acción de manejo aquí.
        // Evita mostrar mensajes específicos de error al usuario en un entorno de producción.
    }
    // Omitir etiqueta de cierre para evitar problemas con espacios en blanco no deseados.

?>