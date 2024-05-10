<?php

    require_once "conexionbd.php";

    class Pedidos extends Conexion{

        public function __construct(){

            parent::__construct();

            /*$this->cantidad = $cantidad;
            $this->idproducto = $idproducto;
            $this->descuento = $descuento;
            $this->descripcion = $descripcion;*/
        }

        public function listado_pedidos($campo, $limit, $pagina, $id_usuario){

            if(empty($id_usuario)){
                // Crear la tabla temporal
                $sql_tabla_temporal = "CREATE TEMPORARY TABLE lista_pedido_tmp AS
                SELECT
                    ped.id_pedidos,
                    ped.estado,
                    usu.nombres,
                    usu.apellidos,
                    cli.ruc,
                    cli.razon_social,
                    cot.fecha,
                    cot.id_coti,
                    cot.moneda
                FROM
                    pedidos ped
                JOIN cotizaciones cot ON cot.id_coti = ped.id_coti
                JOIN usuarios usu ON cot.id_usuario = usu.id_usuario
                JOIN clientes cli ON cot.id_cliente = cli.id_clientes";

                // Crear la tabla temporal de los productos
                $sql_producto_temporal = "CREATE TEMPORARY TABLE lista_producto_tmp AS
                SELECT
                ped.id_pedidos,
                cpro.cantidad,
                cpro.descuento,
                cpro.precio,
                cot.moneda
                FROM
                    pedidos ped
                JOIN items_pedido iped ON iped.id_pedido = ped.id_pedidos
                JOIN coti_producto cpro ON cpro.id_coti_prod = iped.id_item
                JOIN cotizaciones cot ON cot.id_coti = ped.id_coti";
            }else{
                // Crear la tabla temporal
                $sql_tabla_temporal = "CREATE TEMPORARY TABLE lista_pedido_tmp AS
                SELECT
                    ped.id_pedidos,
                    ped.estado,
                    usu.nombres,
                    usu.apellidos,
                    cli.ruc,
                    cli.razon_social,
                    cot.fecha,
                    cot.id_coti,
                    cot.moneda
                FROM
                    pedidos ped
                JOIN cotizaciones cot ON cot.id_coti = ped.id_coti
                JOIN usuarios usu ON cot.id_usuario = usu.id_usuario
                JOIN clientes cli ON cot.id_cliente = cli.id_clientes
                WHERE cot.id_usuario = $id_usuario";
            }

            $tabla_temporal = $this->conexion->query($sql_tabla_temporal);

            $columns = ["nombres", "apellidos", "ruc", "razon_social", "fecha", "id_pedidos","estado", "id_coti", "moneda"];
            $id = 'id_pedidos';
            $columnsWhere = ["nombres", "apellidos", "ruc", "razon_social", "fecha"];
            $tabla = "lista_pedido_tmp";
        
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
    
            $sqlOrder = "ORDER BY id_pedidos " . $orderType;
        
            // Consulta SQL 
            $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . "
                    FROM $tabla 
                    $where 
                    $sqlOrder
                    $sqlLimit";

            //-----Datos de precio de la cotizacion

            if(empty($id_usuario)){

                $prod_temporal = $this->conexion->query($sql_producto_temporal);
    
                $columnsProd = ["id_pedidos","cantidad", "descuento", "precio", "moneda"]; //Array con todas las columnas de la tabla            
                $tabla = "lista_producto_tmp";
    
                // Consulta SQL 
                $sqlProd = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columnsProd) . "
                        FROM $tabla";

            }
        
            try {
                $resultado = $this->conexion->query($sql);
        
                // Consulta de cantidad de registros filtrados
                $resFiltro = $this->conexion->query("SELECT FOUND_ROWS()");
                $totalFiltro = $resFiltro->fetch_array()[0];
        
                // Consulta para total de registros filtrados
                $resTotal = $this->conexion->query("SELECT COUNT($id) FROM $tabla");
                $totalRegistros = $resTotal->fetch_array()[0]; 
                
                $resProd='';
                if(empty($id_usuario)){
                    $resProd = $this->conexion->query($sqlProd);
                } 

                // Cierra la conexión
                $this->conexion->close();
        
                return [$resultado, $resProd, $totalFiltro, $totalRegistros, $columns];
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error en la consulta: " . $e->getMessage();
                return false;
            }
        }

        public function filtro_pedidos($limit, $pagina, $dateIn, $dateOut, $selectUser){

            // Crear la tabla temporal
            $sql_tabla_temporal = "CREATE TEMPORARY TABLE lista_pedido_tmp AS
            SELECT
                ped.id_pedidos,
                ped.estado,
                usu.nombres,
                usu.apellidos,
                cli.id_usuario,
                cli.ruc,
                cli.razon_social,
                cot.fecha,
                cot.id_coti,
                cot.moneda
            FROM
                pedidos ped
            JOIN cotizaciones cot ON cot.id_coti = ped.id_coti
            JOIN usuarios usu ON cot.id_usuario = usu.id_usuario
            JOIN clientes cli ON cot.id_cliente = cli.id_clientes";
            
            $tabla_temporal = $this->conexion->query($sql_tabla_temporal);

            $columns = ["nombres", "apellidos", "ruc", "razon_social", "fecha", "id_pedidos","estado", "id_coti", "moneda"];
            $id = 'id_pedidos';
            $columnsWhere = ["nombres", "apellidos", "ruc", "razon_social", "fecha"];
            $tabla = "lista_pedido_tmp";
        
            if($selectUser != 'todos'){
            
                $where = "WHERE fecha BETWEEN '$dateIn' AND '$dateOut' AND id_usuario = $selectUser";

                $whereProd = "WHERE ped.fecha BETWEEN '$dateIn' AND '$dateOut' AND cot.id_usuario = '$selectUser'";

            }else{
                $where = "WHERE fecha BETWEEN '$dateIn' AND '$dateOut'";
                $whereProd = "WHERE ped.fecha BETWEEN '$dateIn' AND '$dateOut'";
            }
        
            $pagina = max(1, (int)$pagina);
            $inicio = ($pagina - 1) * $limit;
        
            $sqlLimit = "LIMIT $inicio, $limit";
        
            // Ordenamiento
            $orderType = 'desc';
    
            $sqlOrder = "ORDER BY fecha " . $orderType;
        
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
                ped.fecha,
                ped.id_pedidos,
                cpro.cantidad,
                cpro.descuento,
                cpro.precio,
                cot.moneda,
                cot.id_usuario
             FROM
                 pedidos ped
             JOIN items_pedido iped ON iped.id_pedido = ped.id_pedidos
             JOIN coti_producto cpro ON cpro.id_coti_prod = iped.id_item
             JOIN cotizaciones cot ON cot.id_coti = ped.id_coti
             $whereProd";

            $prod_temporal = $this->conexion->query($sql_producto_temporal);

            $columnsProd = ["fecha","id_pedidos", "cantidad", "descuento", "precio", "moneda", "id_usuario"]; //Array con todas las columnas de la tabla            
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

                // Cierra la conexión
                $this->conexion->close();
        
                return [$resultado, $resProd, $totalFiltro, $totalRegistros, $columns];
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error en la consulta: " . $e->getMessage();
                return false;
            }
        }

        public function registrar_pedido(
            $id_coti,
            $mensaje,
            $url_archivos,
            $id_productos,
            $fecha_hoy
        ) {
            // Inicia una transacción para garantizar la integridad de la base de datos
            $this->conexion->begin_transaction();
        
            try {
                // Inserta el pedido
                $sql_pedido = "INSERT INTO pedidos (id_coti, descripcion, fecha) VALUES ( ?, ?, ?)";
                $stmt_pedido = $this->conexion->prepare($sql_pedido);
                $stmt_pedido->bind_param("sss", $id_coti, $mensaje, $fecha_hoy);
                $stmt_pedido->execute();
        
                // Obtiene el ID del pedido recién insertado
                $id_pedido = $stmt_pedido->insert_id;
        
                // Inserta los items del pedido
                $sql_items_pedido = "INSERT INTO items_pedido (id_item, id_pedido) VALUES (?, ?)";
                $stmt_items_pedido = $this->conexion->prepare($sql_items_pedido);
                $stmt_items_pedido->bind_param("ss", $id_producto, $id_pedido);
        
                $long_items_pedido = count($id_productos);
        
                for ($i = 0; $i < $long_items_pedido; $i++) {
                    // Obtén los valores actuales
                    $id_producto = $id_productos[$i];
        
                    // Ejecuta la inserción
                    $stmt_items_pedido->execute();
                }

                // Inserta los archivos del pedido
                $sql_archivo_pedido = "INSERT INTO archivo_pedido (id_pedido, url_archivo) VALUES (?, ?)";
                $stmt_archivo_pedido = $this->conexion->prepare($sql_archivo_pedido);
                $stmt_archivo_pedido->bind_param("is", $id_pedido, $url_archivo);
        
                $long_archivo_pedido = count($url_archivos);
        
                for ($i = 0; $i < $long_archivo_pedido; $i++) {
                    // Obtén los valores actuales
                    $url_archivo = $url_archivos[$i];
        
                    // Ejecuta la inserción
                    $stmt_archivo_pedido->execute();
                }
        
                // Confirma la transacción
                $this->conexion->commit();

                $output = array(
                    'tipo' => 'success',
                    'mensaje' => 'Pedido registrado',
                    'redir' => true,
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                throw new Exception("Error al registrar el pedido: " . $e->getMessage());
            }
            // Cierra la conexión
            $this->conexion->close();
        }

        public function anularPedido($id_pedido){
            // Inicia una transacción para garantizar la integridad referencial
            $this->conexion->begin_transaction();
            $estado = 'anulado';

            try {
                // Actualizar datos del cliente
                $sql1 = "UPDATE pedidos SET
                    estado = ?
                WHERE id_pedidos = ?";
        
                $stmt1 = $this->conexion->prepare($sql1);
                $stmt1->bind_param("si", $estado, $id_pedido);
                $stmt1->execute();
                $stmt1->close();
        
                // Confirmar la transacción
                $this->conexion->commit();

                // Cierra la conexión
                $this->conexion->close();
                
                $output = array(
                    'tipo' => 'success',
                    'mensaje' => 'Pedido anulado',
                    'redir' => true,
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                return '

                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error al editar producto
                    </div>
                
                ';
            }
        }
    
    }

?>