<?php

    require "../modelo/clase_cotizacion.php"; //Llamo a la clase
    require "../modelo/clase_clientes.php"; //Llamo a la clase

    $id_coti= $_POST['id_coti'];
    $coti_obj = new Cotizacion();

    list($resultado_coti, $resultado_prod) = $coti_obj->get_coti($id_coti);

    $row_coti = $resultado_coti->fetch_assoc();

    $cliente_obj = new Clientes();

    if(isset($row_coti['id_contacto'])){

        $resultado_contacto = $cliente_obj->getContacto($row_coti['id_contacto']);
        $row_contacto = $resultado_contacto->fetch_assoc();
    
    }else{
        $row_contacto = [];
        $row_contacto['nombre'] = '';
        $row_contacto['tlf_contacto'] = '';
        $row_contacto['correo_contacto'] = '';
        

    }

    $user_obj = new Usuario();

    $resultado_user = $user_obj->getUser($row_coti['id_usuario']);

    $row_user = $resultado_user->fetch_assoc();

    //Mostrar resultados
    $output = [];

    $output['resCoti'] = $row_coti;
    $output['resCont'] = $row_contacto;
    $output['resProd'] = [];
    $output['resUser'] = $row_user;

    while($row_prod = $resultado_prod->fetch_assoc()){
        $output['resProd'][]  = array(
            'cantidad' => $row_prod['cantidad'],
            'descuento' => $row_prod['descuento'],
            'descripcion' => $row_prod['descripcion'],
            'precio' => $row_prod['precio'],
            'nombre_producto' => $row_prod['nombre_producto'],
            'id_productos' => $row_prod['id_productos']
        );
    }

    echo json_encode($output, JSON_UNESCAPED_UNICODE);

?>