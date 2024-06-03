<?php

    require_once "../modelo/clase_clientes.php"; //Llamo a la clase cliente

    $id_cliente = $_POST["id_cliente_edit"]  ?? null;

    $numero = $_POST["numeroedit"]  ?? null;
    $entidad = $_POST["entidadedit"]  ?? null;
    $direccion = $_POST["direccionedit"]  ?? null;
    $distrito = $_POST["distritoedit"]  ?? null;
    $departamento = $_POST["departamentoedit"]  ?? null;
    $tipocliente = $_POST["tipoclienteedit"]  ?? null;
    $pagocliente = $_POST["pagoclienteedit"]  ?? null;
    $usercliente = $_POST["userclienteedit"]  ?? null;

    $id_contacto = json_decode($_POST["id_contactoedit"])  ?? null;
    $nombre = json_decode($_POST["nombreedit"])  ?? null;
    $telefono = json_decode($_POST["telefonoedit"])  ?? null;
    $correo = json_decode($_POST["correoedit"])  ?? null;
    $cargo = json_decode($_POST["cargoedit"])  ?? null;


    $nombrenuevo = json_decode($_POST["nombrenuevo"])  ?? null;
    $telefononuevo = json_decode($_POST["telefononuevo"])  ?? null;
    $correonuevo = json_decode($_POST["correonuevo"])  ?? null;
    $cargonuevo = json_decode($_POST["cargonuevo"])  ?? null;


    $cliente = new Clientes();

    $resultado = $cliente->editar_cliente( 
        $id_cliente, 
        $id_contacto,
        $numero, 
        $entidad, 
        $direccion,
        $distrito,
        $departamento,
        $nombre,
        $telefono,
        $correo,
        $cargo,
        $tipocliente,
        $pagocliente,
        $usercliente,
        $nombrenuevo,
        $telefononuevo,
        $correonuevo,
        $cargonuevo
    ); 

     //Mostrar resultados
    $output = [];
    
    $output['data'] = $resultado;
  

    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON

?>