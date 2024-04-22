<?php

    session_start();

    include_once '../modelo/clase_cotizacion.php';
    include_once '../modelo/clase_productos.php';
    
    $idcoti = $_POST["idcoti"] ?? null;
    $id_contacto = $_POST["id_contacto"] ?? null;
    $moneda = $_POST["moneda"] ?? null;
    $pago = $_POST["pago"] ?? null;
    $tiempo = $_POST["tiempo"] ?? null; 

    $cantidades = $_POST['cantidad'] ?? array();
    $idproductos = $_POST['idproducto'] ?? array();
    $descuentos = $_POST['descuento'] ?? array();
    $descripciones = $_POST['descripcion'] ?? array();
    $precio = $_POST['precio'] ?? array();

    $pro_obj = new Productos();// Crea una instancia de la clase Productos

    foreach($idproductos as $valor){//Itera el array
        $resultado = $pro_obj->getOneProducto($valor); //Busca cada id de producto en la base de datos

        $num_rows = $resultado->num_rows; //Verifica si hay resultados en la base de datos

        if($num_rows == 0){
            break;
        }
    }

    if($num_rows != 0){
        $coti_obj = new Cotizacion();

        try {
            $resultado = $coti_obj->editar_coti(
                $idcoti,
                $id_contacto,
                $moneda,
                $pago,
                $tiempo,
                $cantidades,
                $idproductos,
                $descuentos,
                $descripciones,
                $precio
            );
        
            // Mostrar resultados
            $output = [];
            $output['data'] = $resultado;

            return $resultado;

        } catch (Exception $e) {
            // Manejo de errores
            $errorOutput = ['error' => $e->getMessage()];
            echo json_encode($errorOutput, JSON_UNESCAPED_UNICODE);
        }

    }else{
        $output = array(
            'tipo' => 'danger',
            'mensaje' => 'Debe registrar los productos',
        );
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
    }
?>