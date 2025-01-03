<?php

    require_once "clase_usuario.php";

    class Cotizacion extends Conexion{

        public function __construct(){

            parent::__construct();
        }

        public function listado_coti($campo, $limit, $pagina, $id_usuario){

            $campo = $this->conexion->real_escape_string($campo);

            if(empty($id_usuario)){
                // Crear la tabla temporal
                $sql_tabla_temporal = "CREATE TEMPORARY TABLE lista_coti_tmp AS
                SELECT
                    cot.id_coti,
                    cot.id_usuario,
                    usr.nombres AS nombre_usuario,
                    usr.apellidos AS apellido_usuario,
                    cot.id_cliente,
                    cli.ruc AS ruc_cliente,
                    cli.razon_social,
                    cot.id_contacto,
                    cot.fecha,
                    cot.correlativo,
                    cot.moneda,
                    cot.metodo_pago
                FROM
                    cotizaciones cot
                JOIN usuarios usr ON cot.id_usuario = usr.id_usuario
                JOIN clientes cli ON cot.id_cliente = cli.id_clientes";

                // Crear la tabla temporal de los productos
                $sql_producto_temporal = "CREATE TEMPORARY TABLE lista_producto_tmp AS
                SELECT
                    cpro.cantidad,
                    cpro.descuento,
                    cpro.precio,
                    cot.moneda,
                    cot.id_coti
                FROM
                    cotizaciones cot
                JOIN coti_producto cpro ON cot.id_coti = cpro.id_coti";
            }else{
                // Crear la tabla temporal
                $sql_tabla_temporal = "CREATE TEMPORARY TABLE lista_coti_tmp AS
                SELECT
                    cot.id_coti,
                    cot.id_usuario,
                    usr.nombres AS nombre_usuario,
                    usr.apellidos AS apellido_usuario,
                    cot.id_cliente,
                    cli.ruc AS ruc_cliente,
                    cli.razon_social,
                    cot.id_contacto,
                    cot.fecha,
                    cot.correlativo,
                    cot.moneda,
                    cot.metodo_pago
                FROM
                    cotizaciones cot
                JOIN usuarios usr ON cot.id_usuario = usr.id_usuario
                JOIN clientes cli ON cot.id_cliente = cli.id_clientes
                WHERE cot.id_usuario = $id_usuario";
            }

            //-----Datos de la cotizacion

            $tabla_temporal = $this->conexion->query($sql_tabla_temporal);

            $columns = ["id_coti", "nombre_usuario", "apellido_usuario", "ruc_cliente", "razon_social", "fecha", "correlativo", "moneda"]; //Array con todas las columnas de la tabla
            $id = 'id_coti';
            $columnsWhere = ["nombre_usuario", "apellido_usuario", "ruc_cliente", "razon_social", "fecha", "correlativo", "moneda", "metodo_pago"]; //Array con todas las columnas donde quiero hacer mi busqueda
            $tabla = "lista_coti_tmp"; 

            $where = '';

            if (!empty($campo)) {
                $conditions = array_map(function ($column) use ($campo) {
                    return "$column LIKE '%" . $this->conexion->real_escape_string($campo) . "%'";
                }, $columnsWhere);

                $where = "WHERE " . implode(" OR ", $conditions);
            }

            $pagina = max(1, (int)$pagina);
            $inicio = ($pagina - 1) * $limit;

            $sqlLimit = "LIMIT $inicio, $limit";

            // Ordenamiento
            $orderType = 'desc';
    
            $sqlOrder = "ORDER BY id_coti " . $orderType;
        
            // Consulta SQL 
            $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . "
                    FROM $tabla
                    $where 
                    $sqlOrder
                    $sqlLimit";

            try {

                $resultado = $this->conexion->query($sql);
    
                // Consulta de cantidad de registros filtrados
                $resFiltro = $this->conexion->query("SELECT FOUND_ROWS()");
                $totalFiltro = $resFiltro->fetch_array()[0];
    
                // Consulta para total de registros filtrados
                $resTotal = $this->conexion->query("SELECT COUNT($id) FROM $tabla");
                $totalRegistros = $resTotal->fetch_array()[0];
    
                //-----Datos de precio de la cotizacion
    
                if(empty($id_usuario)){
    
                    $prod_temporal = $this->conexion->query($sql_producto_temporal);
        
                    $columnsProd = ["id_coti","cantidad", "descuento", "precio", "moneda"]; //Array con todas las columnas de la tabla            
                    $tabla = "lista_producto_tmp"; 
        
                    // Consulta SQL 
                    $sqlProd = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columnsProd) . "
                            FROM $tabla";
    
                }
                
                $resProd='';
                if(empty($id_usuario)){
                    $resProd = $this->conexion->query($sqlProd);
                }

                return [$resultado, $resProd, $totalFiltro, $totalRegistros, $columns];
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error en la consulta: " . $e->getMessage();
                return false;
            }
        }

        public function filtro_coti($limit, $pagina, $dateIn, $dateOut, $selectUser){

            // Crear la tabla temporal
            $sql_tabla_temporal = "CREATE TEMPORARY TABLE lista_coti_tmp AS
            SELECT
                cot.id_coti,
                cot.id_usuario,
                usr.nombres AS nombre_usuario,
                usr.apellidos AS apellido_usuario,
                cot.id_cliente,
                cli.ruc AS ruc_cliente,
                cli.razon_social,
                cot.id_contacto,
                cot.fecha,
                cot.correlativo,
                cot.moneda,
                cot.metodo_pago
            FROM
                cotizaciones cot
            JOIN usuarios usr ON cot.id_usuario = usr.id_usuario
            JOIN clientes cli ON cot.id_cliente = cli.id_clientes";

            $tabla_temporal = $this->conexion->query($sql_tabla_temporal);

            $columns = ["id_coti", "nombre_usuario", "apellido_usuario", "ruc_cliente", "razon_social", "fecha", "correlativo", "moneda"]; //Array con todas las columnas de la tabla
            $id = 'id_coti';
            $columnsWhere = ["nombre_usuario", "apellido_usuario", "ruc_cliente", "razon_social", "fecha", "correlativo", "moneda", "metodo_pago"]; //Array con todas las columnas donde quiero hacer mi busqueda
            $tabla = "lista_coti_tmp";

            if($selectUser != 'todos'){
            
                $where = "WHERE fecha BETWEEN '$dateIn' AND '$dateOut' AND id_usuario = $selectUser";

                $whereProd = "WHERE fecha BETWEEN '$dateIn' AND '$dateOut' AND cot.id_usuario = '$selectUser'";

            }else{
                $where = "WHERE fecha BETWEEN '$dateIn' AND '$dateOut'";
                $whereProd = "WHERE fecha BETWEEN '$dateIn' AND '$dateOut'";
            }

            $pagina = max(1, (int)$pagina);
            $inicio = ($pagina - 1) * $limit;

            $sqlLimit = "LIMIT $inicio, $limit";

            // Ordenamiento
            $sqlOrder = "";

            if (isset($_POST['orderCol'])) {
                $orderCol = $_POST['orderCol'];
                $orderType = isset($_POST['orderType']) ? $_POST['orderType'] : 'asc';

                $sqlOrder = "ORDER BY id_coti " . ' ' . $orderType;
            }

            // Consulta SQL 
            $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . "
                    FROM $tabla
                    $where 
                    $sqlOrder
                    $sqlLimit";

            //------------- Datos de montos de la cotizacion
            
            // Crear la tabla temporal de los productos
            $sql_producto_temporal = "CREATE TEMPORARY TABLE lista_producto_tmp AS
            SELECT
                cpro.cantidad,
                cpro.descuento,
                cpro.precio,
                cot.moneda,
                cot.fecha,
                cot.id_coti,
                cot.id_usuario
            FROM
                cotizaciones cot
            JOIN coti_producto cpro ON cot.id_coti = cpro.id_coti
            $whereProd";

            $prod_temporal = $this->conexion->query($sql_producto_temporal);

            $columnsProd = ["id_coti","cantidad", "descuento", "precio", "moneda"]; //Array con todas las columnas de la tabla            
            $tablaProd = "lista_producto_tmp"; 

            // Consulta SQL 
            $sqlProd = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columnsProd) . "
                    FROM $tablaProd";

            try {

                $resultado = $this->conexion->query($sql);
                $resProd = $this->conexion->query($sqlProd);

                // Consulta de cantidad de registros filtrados
                $resFiltro = $this->conexion->query("SELECT FOUND_ROWS()");
                $totalFiltro = $resFiltro->fetch_array()[0];

                // Consulta para total de registros filtrados
                $resTotal = $this->conexion->query("SELECT COUNT($id) FROM $tabla");
                $totalRegistros = $resTotal->fetch_array()[0];

                return [$resultado, $resProd, $totalFiltro, $totalRegistros, $columns];
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error en la consulta: " . $e->getMessage();
                return false;
            }
        }
        
        public function registrar_coti(
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
        ) {
            // Inicia una transacción para garantizar la integridad de la base de datos
            $this->conexion->begin_transaction();
        
            try {
                // Inserta el cliente
                $sql_coti = "INSERT INTO cotizaciones (id_usuario, id_cliente, id_contacto, fecha, correlativo, moneda, metodo_pago, tiempo_entrega) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_coti = $this->conexion->prepare($sql_coti);
                $stmt_coti->bind_param("ssssssss", $id_usuario, $idcliente, $input_id_contacto, $fecha_hoy, $correlativo, $moneda, $pago, $tiempo);
                $stmt_coti->execute();
        
                // Obtiene el ID del cliente recién insertado
                $id_coti = $stmt_coti->insert_id;
        
                // Inserta los contactos del cliente
                $sql_prod_coti = "INSERT INTO coti_producto (id_producto, id_coti, cantidad, descuento, descripcion, precio) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_prod_coti = $this->conexion->prepare($sql_prod_coti);
                $stmt_prod_coti->bind_param("isssss", $idproducto, $id_coti, $cantidad, $descuento, $descripcion, $precio);

                $longitud = count($idproductos);

                for ($i = 0; $i < $longitud; $i++) {
                    // Obtén los valores actuales
                    $cantidad = $cantidades[$i];
                    $idproducto = $idproductos[$i];
                    $descuento = $descuentos[$i];
                    $descripcion = $descripciones[$i];
                    $precio = $precios[$i];

                    // Ejecuta la inserción
                    $stmt_prod_coti->execute();
                }
        
                // Confirma la transacción
                $this->conexion->commit();
        
                $output = array(
                    'tipo' => 'success',
                    'mensaje' => 'Cotizacion registrada',
                    'redir' => true,
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                $output = array(
                    'tipo' => 'danger',
                    'mensaje' => 'Error al registrar la cotizacion.' . $e,
                    'redir' => false,
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            }
        }

        public function get_coti($id_coti){

            $id_coti = $this->conexion->real_escape_string($id_coti);

            $this->conexion->query("DROP TABLE IF EXISTS lista_coti_tmp");
        
            // Crear la tabla temporal de la cotizacion
            $sql_coti_temporal = "CREATE TEMPORARY TABLE lista_coti_tmp AS
            SELECT
                cli.id_clientes,
                cli.ruc,
                cli.razon_social,
                con.nombre as nombre_contacto,
                con.tlf_contacto,
                con.correo_contacto,
                usu.nombres,
                usu.apellidos,
                usu.telefono,
                usu.correo,
                cot.id_usuario,
                cot.id_contacto,
                cot.fecha,
                cot.correlativo,
                cot.moneda,
                cot.metodo_pago,
                cot.tiempo_entrega
            FROM
                cotizaciones cot
            JOIN clientes cli ON cot.id_cliente = cli.id_clientes
            LEFT JOIN contacto_cliente con ON cot.id_contacto = con.id_contacto
            JOIN usuarios usu ON cot.id_usuario = usu.id_usuario
            WHERE cot.id_coti = '$id_coti'";

            $coti_temporal = $this->conexion->query($sql_coti_temporal);

            $columns_coti = ["id_clientes","ruc", "razon_social", "id_contacto", "nombre_contacto", "tlf_contacto", "correo_contacto", "nombres", "apellidos", "telefono", "correo", "fecha", "correlativo", "moneda", "metodo_pago", "tiempo_entrega", "id_usuario"]; //Array con todas las columnas de la tabla
            $tabla_coti = "lista_coti_tmp";

            // Consulta SQL 
            $sql_coti = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns_coti) . "
                    FROM $tabla_coti";

            $this->conexion->query("DROP TABLE IF EXISTS lista_producto_tmp");
            
            // Crear la tabla temporal de los productos
            $sql_producto_temporal = "CREATE TEMPORARY TABLE lista_producto_tmp AS
            SELECT
                cpro.cantidad,
                cpro.descuento,
                cpro.descripcion,
                cpro.precio,
                cpro.id_coti_prod,
                pro.id_productos,
                pro.nombre as nombre_producto
            FROM
                cotizaciones cot
            JOIN coti_producto cpro ON cot.id_coti = cpro.id_coti
            JOIN productos pro ON cpro.id_producto = pro.id_productos
            WHERE cot.id_coti = '$id_coti'";

            $producto_temporal = $this->conexion->query($sql_producto_temporal);

            $columns_producto = ["cantidad", "descuento", "descripcion", "precio", "id_coti_prod", "nombre_producto", "id_productos"]; //Array con todas las columnas de la tabla
            $tabla_producto = "lista_producto_tmp";

            // Consulta SQL 
            $sql_prod = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns_producto) . "
                    FROM $tabla_producto";
        
            try {
                $resultado_coti = $this->conexion->query($sql_coti);
                
                $resultado_prod = $this->conexion->query($sql_prod);
                
                return [$resultado_coti, $resultado_prod];
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error en la consulta: " . $e->getMessage();
                return false;
            }
        }
        
        public function getpdfcotipedido($id_coti,$id_pedido){

            $id_coti = $this->conexion->real_escape_string($id_coti);

            $this->conexion->query("DROP TABLE IF EXISTS lista_coti_tmp");
        
            // Crear la tabla temporal de la cotizacion
            $sql_coti_temporal = "CREATE TEMPORARY TABLE lista_coti_tmp AS
            SELECT
                cli.id_clientes,
                cli.ruc,
                cli.razon_social,
                con.nombre as nombre_contacto,
                con.tlf_contacto,
                con.correo_contacto,
                usu.nombres,
                usu.apellidos,
                usu.telefono,
                usu.correo,
                cot.id_usuario,
                cot.id_contacto,
                cot.fecha,
                cot.correlativo,
                cot.moneda,
                cot.metodo_pago,
                cot.tiempo_entrega
            FROM
                cotizaciones cot
            JOIN clientes cli ON cot.id_cliente = cli.id_clientes
            JOIN contacto_cliente con ON cot.id_contacto = con.id_contacto
            JOIN usuarios usu ON cot.id_usuario = usu.id_usuario
            WHERE cot.id_coti = '$id_coti'";

            $coti_temporal = $this->conexion->query($sql_coti_temporal);

            $columns_coti = ["id_clientes","ruc", "razon_social", "id_contacto", "nombre_contacto", "tlf_contacto", "correo_contacto", "nombres", "apellidos", "telefono", "correo", "fecha", "correlativo", "moneda", "metodo_pago", "tiempo_entrega", "id_usuario"]; //Array con todas las columnas de la tabla
            $tabla_coti = "lista_coti_tmp";

            // Consulta SQL 
            $sql_coti = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns_coti) . "
                    FROM $tabla_coti";

            $this->conexion->query("DROP TABLE IF EXISTS lista_producto_tmp");
            
            // Crear la tabla temporal de los productos
            $sql_producto_temporal = "CREATE TEMPORARY TABLE lista_producto_tmp AS
            SELECT
                cpro.cantidad,
                cpro.descuento,
                cpro.descripcion,
                cpro.precio,
                cpro.id_coti_prod,
                pro.id_productos,
                pro.nombre as nombre_producto,
                item.id_item
            FROM
                pedidos ped
            JOIN cotizaciones cot ON ped.id_coti = cot.id_coti
            JOIN items_pedido item ON item.id_pedido = ped.id_pedidos
            JOIN coti_producto cpro ON item.id_item = cpro.id_coti_prod
            JOIN productos pro ON pro.id_productos = cpro.id_producto
            WHERE ped.id_pedidos = $id_pedido";

            $producto_temporal = $this->conexion->query($sql_producto_temporal);

            $columns_producto = ["cantidad", "descuento", "descripcion", "precio", "id_coti_prod", "nombre_producto", "id_productos"]; //Array con todas las columnas de la tabla
            $tabla_producto = "lista_producto_tmp";

            // Consulta SQL 
            $sql_prod = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns_producto) . "
                    FROM $tabla_producto";
        

            try {
                $resultado_coti = $this->conexion->query($sql_coti);
                
                $resultado_prod = $this->conexion->query($sql_prod);
                
                return [$resultado_coti, $resultado_prod];
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error en la consulta: " . $e->getMessage();
                return false;
            }
        }

        public function prox_id() {
            $añoActual = date('Y');
        
            // Inicia la transacción
            $this->conexion->begin_transaction();
        
            try {
                // Verifica si el año actual ya está registrado en la tabla `correlativos`
                $stmt = $this->conexion->prepare("SELECT ultimo_id FROM correlativos WHERE año = ?");
                $stmt->bind_param("i", $añoActual);
                $stmt->execute();
                $resultado = $stmt->get_result();
        
                if ($resultado->num_rows > 0) {
                    // Si existe, incrementa el último ID
                    $row = $resultado->fetch_assoc();
                    $proxID = $row['ultimo_id'] + 1;
        
                    // Actualiza el último ID en la tabla
                    $stmtUpdate = $this->conexion->prepare("UPDATE correlativos SET ultimo_id = ? WHERE año = ?");
                    $stmtUpdate->bind_param("ii", $proxID, $añoActual);
                    $stmtUpdate->execute();
                } else {
                    // Si no existe, crea un registro para el año actual
                    $proxID = 1;
        
                    $stmtInsert = $this->conexion->prepare("INSERT INTO correlativos (año, ultimo_id) VALUES (?, ?)");
                    $stmtInsert->bind_param("ii", $añoActual, $proxID);
                    $stmtInsert->execute();
                }
        
                // Confirma la transacción
                $this->conexion->commit();
        
                return $proxID;
            } catch (Exception $e) {
                // Revierte la transacción en caso de error
                $this->conexion->rollback();
                throw new Exception("Error al generar el próximo ID: " . $e->getMessage());
            }
        }
        

        public function editar_coti(
            $idcoti,
            $id_contacto,
            $moneda,
            $pago,
            $tiempo,
            $cantidades,
            $idproductos,
            $descuentos,
            $descripciones,
            $precios
        ) {
            // Inicia una transacción para garantizar la integridad de la base de datos
            $this->conexion->begin_transaction();
        
            try {
                // Actualiza la cotización
                $sql_coti_update = "UPDATE cotizaciones SET moneda = ?, metodo_pago = ?, tiempo_entrega = ?, id_contacto = ? WHERE id_coti = ?";
                $stmt_coti_update = $this->conexion->prepare($sql_coti_update);
                $stmt_coti_update->bind_param("ssssi", $moneda, $pago, $tiempo, $id_contacto, $idcoti);
                $stmt_coti_update->execute();
            
                // Elimina los productos relacionados con la cotización actual
                $sql_prod_coti_delete = "DELETE FROM coti_producto WHERE id_coti = ?";
                $stmt_prod_coti_delete = $this->conexion->prepare($sql_prod_coti_delete);
                $stmt_prod_coti_delete->bind_param("s", $idcoti);
                $stmt_prod_coti_delete->execute();
            
                // Inserta los nuevos productos
                $sql_prod_coti_insert = "INSERT INTO coti_producto (id_producto, id_coti, cantidad, descuento, descripcion, precio) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_prod_coti_insert = $this->conexion->prepare($sql_prod_coti_insert);
                $stmt_prod_coti_insert->bind_param("isssss", $idproducto, $idcoti, $cantidad, $descuento, $descripcion, $precio);
            
                $longitud = count($idproductos);
            
                for ($i = 0; $i < $longitud; $i++) {
                    // Obtén los valores actuales
                    $cantidad = $cantidades[$i];
                    $idproducto = $idproductos[$i];
                    $descuento = $descuentos[$i];
                    $descripcion = $descripciones[$i];
                    $precio = $precios[$i];
            
                    // Ejecuta la inserción
                    $stmt_prod_coti_insert->execute();
                }
            
                // Confirma la transacción
                $this->conexion->commit();
            
                $output = array(
                    'tipo' => 'success',
                    'mensaje' => 'Cotizacion actualizada',
                    'redir' => true,
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                $output = array(
                    'tipo' => 'danger',
                    'mensaje' => 'Error al actualizar la cotización.' . $e,
                    'redir' => false,
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            }
        
        }

        public function get_coti_pedido($id_coti, $itemsMarcados){

            $id_coti = $this->conexion->real_escape_string($id_coti);

            $this->conexion->query("DROP TABLE IF EXISTS lista_coti_tmp");
        
            // Crear la tabla temporal de la cotizacion
            $sql_coti_temporal = "CREATE TEMPORARY TABLE lista_coti_tmp AS
            SELECT
                cli.id_clientes,
                cli.ruc,
                cli.razon_social,
                con.nombre as nombre_contacto,
                con.tlf_contacto,
                con.correo_contacto,
                usu.nombres,
                usu.apellidos,
                usu.telefono,
                usu.correo,
                cot.id_usuario,
                cot.id_contacto,
                cot.fecha,
                cot.correlativo,
                cot.moneda,
                cot.metodo_pago,
                cot.tiempo_entrega
            FROM
                cotizaciones cot
            JOIN clientes cli ON cot.id_cliente = cli.id_clientes
            JOIN contacto_cliente con ON cot.id_contacto = con.id_contacto
            JOIN usuarios usu ON cot.id_usuario = usu.id_usuario
            WHERE cot.id_coti = '$id_coti'";

            $coti_temporal = $this->conexion->query($sql_coti_temporal);

            $columns_coti = ["id_clientes","ruc", "razon_social", "id_contacto", "nombre_contacto", "tlf_contacto", "correo_contacto", "nombres", "apellidos", "telefono", "correo", "fecha", "correlativo", "moneda", "metodo_pago", "tiempo_entrega", "id_usuario"]; //Array con todas las columnas de la tabla
            $tabla_coti = "lista_coti_tmp";

            // Consulta SQL 
            $sql_coti = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns_coti) . "
                    FROM $tabla_coti";

            $this->conexion->query("DROP TABLE IF EXISTS lista_producto_tmp");
            
            // Crear la tabla temporal de los productos
            $sql_producto_temporal = "CREATE TEMPORARY TABLE lista_producto_tmp AS
            SELECT
                cpro.cantidad,
                cpro.descuento,
                cpro.descripcion,
                cpro.precio,
                cpro.id_coti_prod,
                pro.id_productos,
                pro.nombre as nombre_producto
            FROM
                cotizaciones cot
            JOIN coti_producto cpro ON cot.id_coti = cpro.id_coti
            JOIN productos pro ON cpro.id_producto = pro.id_productos
            WHERE cot.id_coti = '$id_coti'";

            $producto_temporal = $this->conexion->query($sql_producto_temporal);

            $columns_producto = ["cantidad", "descuento", "descripcion", "precio", "id_coti_prod", "nombre_producto", "id_productos"]; //Array con todas las columnas de la tabla
            $tabla_producto = "lista_producto_tmp";

            // Construir la condición WHERE dinámicamente
            $condiciones = [];
            foreach ($itemsMarcados as $idProducto_coti) {
                $condiciones[] = "id_coti_prod = '$idProducto_coti'";
            }

            // Unir las condiciones con OR
            $condicionWhere = implode(" OR ", $condiciones);

            // Consulta SQL final
            $sql_prod = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns_producto) . "
                FROM $tabla_producto
                WHERE $condicionWhere";
        

            try {
                $resultado_coti = $this->conexion->query($sql_coti);
                
                $resultado_prod = $this->conexion->query($sql_prod);
                
                return [$resultado_coti, $resultado_prod];
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error en la consulta: " . $e->getMessage();
                return false;
            }
        }

        public function getProdCoti($id_coti){
            //Busqueda de los productos de la cotizacion
            $this->conexion->query("DROP TABLE IF EXISTS lista_producto_tmp");
            
            // Crear la tabla temporal de los productos
            $sql_producto_temporal = "CREATE TEMPORARY TABLE lista_producto_tmp AS
            SELECT
                cpro.cantidad,
                cpro.descuento,
                cpro.descripcion,
                cpro.precio,
                cpro.id_coti_prod,
                pro.id_productos,
                pro.nombre as nombre_producto
            FROM
                cotizaciones cot
            JOIN coti_producto cpro ON cot.id_coti = cpro.id_coti
            JOIN productos pro ON cpro.id_producto = pro.id_productos
            WHERE cot.id_coti = '$id_coti'";

            $producto_temporal = $this->conexion->query($sql_producto_temporal);

            $columns_producto = ["cantidad", "descuento", "descripcion", "precio", "id_coti_prod", "nombre_producto", "id_productos"]; //Array con todas las columnas de la tabla
            $tabla_producto = "lista_producto_tmp";

            // Consulta SQL 
            $sql_prod = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns_producto) . "
                    FROM $tabla_producto";

            try {

                $resultado_prod = $this->conexion->query($sql_prod);
        
                return $resultado_prod;
                
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error en la consulta: " . $e->getMessage();
                return false;
            }
        }

        /* public function eliminar_coti($id_coti){
            // Inicia una transacción para garantizar la integridad referencial
            $this->conexion->begin_transaction();

            try {
                // Query preparada para actualizar el estado del usuario a 'inactivo'
                $sql_coti = "UPDATE cotis SET estado = 'inactivo' WHERE id_cotis = ?";
                $stmt_coti = $this->conexion->prepare($sql_coti);
                $stmt_coti->bind_param("s", $id_coti);
                $stmt_coti->execute();

                // Confirma la transacción
                $this->conexion->commit();

                return '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Producto eliminado correctamente.
                    </div>
                
                ';
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                return '

                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error al eliminar el producto
                    </div>
                
                ';
            } finally {
                // Cierra la conexión
                $this->conexion->close();
            }

            // Cierra la conexión
            $this->conexion->close();
        } */

        public function eliminar_coti($id_coti) {
            // Inicia una transacción para garantizar la integridad referencial
            $this->conexion->begin_transaction();
        
            try {
                // Verifica si existen pedidos con la cotización
                $sql_verificar_pedidos = "SELECT COUNT(*) AS count FROM pedidos WHERE id_coti = ?";
                $stmt_verificar = $this->conexion->prepare($sql_verificar_pedidos);
                $stmt_verificar->bind_param("s", $id_coti);
                $stmt_verificar->execute();
                $resultado = $stmt_verificar->get_result();
                $row = $resultado->fetch_assoc();
        
                if ($row['count'] > 0) {
                    // Si existen pedidos, no eliminar la cotización y devolver un mensaje de error
                    $this->conexion->rollback();
                    return '
                        <div class="alert alert-danger" id="miAlert" role="alert">
                            No se puede eliminar la cotización porque tiene pedidos asociados.
                        </div>
                    ';
                }
        
                // Query para eliminar productos de la cotización
                $sql_coti_prod = "DELETE FROM coti_producto WHERE id_coti = ?";
                $stmt_coti_prod = $this->conexion->prepare($sql_coti_prod);
                $stmt_coti_prod->bind_param("s", $id_coti);
                $stmt_coti_prod->execute();
        
                // Query para eliminar cotización
                $sql_coti = "DELETE FROM cotizaciones WHERE id_coti = ?";
                $stmt_coti = $this->conexion->prepare($sql_coti);
                $stmt_coti->bind_param("s", $id_coti);
                $stmt_coti->execute();
        
                // Confirma la transacción
                $this->conexion->commit();
                
                return '
                    <div class="alert alert-success" id="miAlert" role="alert">
                        Cotización eliminada correctamente.
                    </div>
                ';
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                return '
                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error al eliminar la cotización: ' . $e->getMessage() . '
                    </div>
                ';
            }
        }
        

    }

?>