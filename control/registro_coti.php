<?php

    session_start();

    include_once '../modelo/clase_cotizacion.php';
    include_once '../modelo/clase_productos.php';
    
    $idcliente = $_POST["idcliente"] ?? null;
    $input_id_contacto = $_POST["input_id_contacto"] ?? null;
    $moneda = $_POST["moneda"] ?? null;
    $pago = $_POST["pago"] ?? null;
    $tiempo = $_POST["tiempo"] ?? null;
    $id_usuario = $_SESSION["id_usuario"] ?? null;
    $fecha_hoy = date('Y-m-d') ?? null;

    $cantidades = $_POST['cantidad'] ?? array();
    $idproductos = $_POST['idproducto'] ?? array();
    $descuentos = $_POST['descuento'] ?? array();
    $descripciones = $_POST['descripcion'] ?? array();
    $precios = $_POST['precio'] ?? array();

    $longitud = count($idproductos);
    $canCreate = true;

    for ($i = 0; $i < $longitud; $i++) {

        if(empty($idproductos[$i])){

            $canCreate = false;         

        }
        
    }

    if($canCreate){
    
        $coti_obj = new Cotizacion();

        $resultado = $coti_obj->prox_id();

        while ($id = $resultado->fetch_array()){
            $prox_id = $id[0];
        }

        function obtenerIniciales($frase) {
            // Dividir la frase en palabras
            $palabras = explode(' ', $frase);
        
            // Inicializar el string para almacenar las iniciales
            $iniciales = '';
        
            // Iterar sobre cada palabra y agregar la inicial al string
            foreach ($palabras as $palabra) {
                // Verificar si la palabra no está vacía
                if (!empty($palabra)) {
                    // Obtener la inicial y concatenarla al string de iniciales
                    $iniciales .= strtoupper($palabra[0]);
                }
            }
        
            return $iniciales;
        }
        
        // Ejemplo de uso
        $frase = $_SESSION["nombre_user"];
        $iniciales = obtenerIniciales($frase);

        $correlativo = $prox_id . "-" . date('my') . "-" . $iniciales;

        try {
            $resultado = $coti_obj->registrar_coti(
                $idcliente,
                $input_id_contacto,
                $moneda,
                $pago,
                $tiempo,
                $id_usuario,
                $fecha_hoy,
                $correlativo,
                $cantidades,
                $idproductos,
                $descuentos,
                $descripciones,
                $precios
            );
        
            // Mostrar resultados
            $output = [];
            $output['data'] = $resultado;

            return $output;
        
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