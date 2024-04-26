
<?php

    require_once "../modelo/clase_productos.php"; //Llamo a la clase

    $input_producto = isset($_POST['producto']) ? $_POST['producto'] : null; //Dato que viene de la vista para hacer la busqueda
    //$iditem = $_POST['idproducto'];
    //Funcion real_escape para evitar inyeccion de codigo HTML

    $producto = new Productos();

    $resultado = $producto->buscar_productos($input_producto); 

    $num_rows = $resultado->num_rows; 

    //Mostrar resultados
    $output = [];
    $output['data'] = '';

    if(!empty($input_producto)){
        if ($num_rows > 0){//Verificamos que haya algun resultado
            while($row = $resultado->fetch_assoc()){ 
                $output['data'] .= '<div class="lista-item" onclick="mostrarProducto('. $row['id_productos'] .', this)">' . $row['nombre'] . '</div>';
            }
        }else{
            $output['data'] .= '<div class="lista-item">Sin resultados</div>';
        }
    }else{
        $output['data'] = '';
    }


    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON


?>