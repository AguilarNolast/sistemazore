<?php

    require_once "conexionbd.php";

    class Productos extends Conexion{
    
        public function __construct(){

            parent::__construct();
        }

        public function getOneProducto() {
            $columns = ["id_productos", "nombre"]; // Array con todas las columnas de la tabla
            $columnsWhere = ["id_productos"]; // Array con todas las columnas donde quiero hacer mi búsqueda
            $tabla = "productos";
        
            $where = '';
        
            if ($input_producto !== null) {
                // Armamos la cláusula WHERE
                $where = "WHERE (";
        
                $cont = count($columnsWhere); // Contamos cuántas columnas hay
                for ($i = 0; $i < $cont; $i++) {
                    // Concatenamos las diferentes columnas a la consulta WHERE
                    $where .= $columnsWhere[$i] . " LIKE ? OR ";
                }
                $where = substr_replace($where, "", -3); // Eliminamos el último OR de la cadena ya que no lo necesitamos
                $where .= ")";
            }
        
            // Consulta SQL
            $sql = "SELECT " . implode(", ", $columns) . "
                    FROM $tabla 
                    $where"; // Construcción de la consulta
        
            try {
                $stmt = $this->conexion->prepare($sql);
        
                if ($stmt) {
                    if ($input_producto !== null) {
                        // Asignamos valores a los marcadores de posición en la consulta preparada
                        $input_producto_like = '%' . $input_producto . '%';
                        $stmt->bind_param(str_repeat('s', $cont), ...array_fill(0, $cont, $input_producto_like));
                    }
        
                    $stmt->execute();
                    $resultado = $stmt->get_result();
        
                    return $resultado; // Devuelve el resultado
                } else {
                    throw new Exception("Error al preparar la consulta SQL.");
                }
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error: " . $e->getMessage();
                return false;
            }
        }

        public function buscar_productos($input_producto) {
            $columns = ["id_productos", "nombre", "descripcion"]; // Array con todas las columnas de la tabla
            $columnsWhere = ["id_productos", "nombre", "descripcion"]; // Array con todas las columnas donde quiero hacer mi búsqueda
            $tabla = "productos";
        
            $where = 'WHERE estado = "activo"'; // Condición para filtrar productos activos
            
            if ($input_producto !== null) {
                // Armamos la cláusula WHERE
                $where .= " AND ("; // Agregar "AND" para concatenar con la condición de estado
                
                $cont = count($columnsWhere); // Contamos cuántas columnas hay
                for ($i = 0; $i < $cont; $i++) {
                    // Concatenamos las diferentes columnas a la consulta WHERE
                    $where .= $columnsWhere[$i] . " LIKE ? OR ";
                }
                $where = substr_replace($where, "", -3); // Eliminamos el último OR de la cadena ya que no lo necesitamos
                $where .= ")";
            }
        
            // Consulta SQL
            $sql = "SELECT " . implode(", ", $columns) . "
                    FROM $tabla 
                    $where
                    ORDER BY nombre"; // Construcción de la consulta
        
            try {
                $stmt = $this->conexion->prepare($sql);
        
                if ($stmt) {
                    if ($input_producto !== null) {
                        // Asignamos valores a los marcadores de posición en la consulta preparada
                        $input_producto_like = '%' . $input_producto . '%';
                        $stmt->bind_param(str_repeat('s', $cont), ...array_fill(0, $cont, $input_producto_like));
                    }
        
                    $stmt->execute();
                    $resultado = $stmt->get_result();
        
                    // Obtenemos los resultados en un array asociativo
                    $productos = [];
                    while ($row = $resultado->fetch_assoc()) {
                        $productos[] = $row;
                    }
        
                    // Cierra la conexión
                    $this->conexion->close();
        
                    // Devuelve los resultados en formato JSON
                    header('Content-Type: application/json');
                    echo json_encode($productos);
                } else {
                    throw new Exception("Error al preparar la consulta SQL.");
                }
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error: " . $e->getMessage();
                return false;
            }
        }
        
        
        public function mostrar_producto($id_producto) {
            $columns = ["id_productos", "nombre", "descripcion", "precio"]; // Array con todas las columnas de la tabla
            $columnsWhere = ["id_productos"]; // Array con todas las columnas donde quiero hacer mi búsqueda
            $tabla = "productos";
        
            $where = '';
        
            if ($id_producto !== null) {
                // Armamos la cláusula WHERE
                $where = "WHERE (";
        
                $cont = count($columnsWhere); // Contamos cuántas columnas hay
                for ($i = 0; $i < $cont; $i++) {
                    // Concatenamos las diferentes columnas a la consulta WHERE
                    $where .= $columnsWhere[$i] . " = ? OR ";
                }
                $where = substr_replace($where, "", -3); // Eliminamos el último OR de la cadena ya que no lo necesitamos
                $where .= ")";
            }
        
            // Consulta SQL
            $sql = "SELECT " . implode(", ", $columns) . "
                    FROM $tabla 
                    $where"; // Construcción de la consulta
        
            try {
                $stmt = $this->conexion->prepare($sql);
        
                if ($stmt) {
                    if ($id_producto !== null) {
                        // Asignamos valores a los marcadores de posición en la consulta preparada
                        $stmt->bind_param(str_repeat('s', $cont), $id_producto);
                    }
        
                    $stmt->execute();
                    $resultado = $stmt->get_result();

                    // Cierra la conexión
                    $this->conexion->close();
        
                    return $resultado; // Devuelve el resultado
                } else {
                    throw new Exception("Error al preparar la consulta SQL.");
                }
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error: " . $e->getMessage();
                return false;
            }
        }

        public function listado_productos($campo, $limit, $pagina, $orderCol, $orderType) {
            $columns = ["id_productos", "nombre", "descripcion", "precio", "alto", "ancho", "largo", "peso"];
            $columnsOrder = ["nombre", "descripcion", "precio", "peso"];
            $id = 'id_productos';
            $columnsWhere = ["nombre", "descripcion", "precio", "alto", "ancho", "largo", "peso"];
            $tabla = "productos";
        
            $where = ' WHERE estado = "activo"';
            $sqlParams = [];
        
            if (!empty($campo)) {
                $conditions = array_map(function ($column) use ($campo, &$sqlParams) {
                    $sqlParams[] = "%$campo%";
                    return "$column LIKE ?";
                }, $columnsWhere);
        
                $where .= " AND (" . implode(" OR ", $conditions) . ")";
            }
        
            $pagina = max(1, (int) $pagina);
            $inicio = ($pagina - 1) * $limit;
        
            $sqlLimit = "LIMIT ?, ?";
            $sqlParams[] = $inicio;
            $sqlParams[] = $limit;
            
            //Ordenamiento
            $sqlOrder = "ORDER BY " . $columnsOrder[intval($orderCol)] . ' ' . $orderType;
        
            // Consulta SQL 
            $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . "
                    FROM $tabla 
                    $where 
                    $sqlOrder
                    $sqlLimit";
        
            try {
                $stmt = $this->conexion->prepare($sql);
        
                if ($stmt) {
                    // Asignar los parámetros necesarios
                    $types = str_repeat('s', count($sqlParams) - 2) . 'ii';
                    $stmt->bind_param($types, ...$sqlParams);
                    
                    $stmt->execute();
                    $resultado = $stmt->get_result();
        
                    // Consulta de cantidad de registros filtrados
                    $resFiltro = $this->conexion->query("SELECT FOUND_ROWS()");
                    $totalFiltro = $resFiltro->fetch_array()[0];
        
                    // Consulta para total de registros filtrados
                    $resTotal = $this->conexion->query("SELECT COUNT($id) FROM $tabla");
                    $totalRegistros = $resTotal->fetch_array()[0];

                    // Cierra la conexión
                    $this->conexion->close();
        
                    return [$resultado, $totalFiltro, $totalRegistros, $columns];
                } else {
                    throw new Exception("Error al preparar la consulta SQL.");
                }
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error en la consulta: " . $e->getMessage();
                return false;
            }
        }
        
        public function registrar_producto(
            $nombre,
            $descripcion,
            $precio,
            $alto,
            $ancho,
            $largo,
            $peso
        ) {
            // Inicia una transacción para garantizar la integridad de la base de datos
            $this->conexion->begin_transaction();
        
            try {
                $precio = floatval($precio);
                $alto = floatval($alto);
                $ancho = floatval($ancho);
                $largo = floatval($largo);
                $peso = floatval($peso);
                // Inserta el cliente
                $sql_producto = "INSERT INTO productos (nombre, descripcion, precio, alto, ancho, largo, peso) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_producto = $this->conexion->prepare($sql_producto);
                $stmt_producto->bind_param("sssssss", $nombre, $descripcion, $precio, $alto, $ancho, $largo, $peso);
                $stmt_producto->execute();
        
                // Confirma la transacción
                $this->conexion->commit();

                // Cierra la conexión
                $this->conexion->close();
        
                return '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Producto registrado
                    </div>
                
                ';
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

        public function editar_producto(
            $id_producto,
            $nombre,
            $descripcion,
            $precio,
            $alto,
            $ancho,
            $largo,
            $peso
        ) {
            // Iniciar una transacción
            $this->conexion->begin_transaction();
        
            try {
                // Actualizar datos del cliente
                $sql1 = "UPDATE productos SET
                    nombre = ?,
                    descripcion = ?,
                    precio = ?,
                    alto = ?,
                    ancho = ?,
                    largo = ?,
                    peso = ?
                WHERE id_productos = ?";
        
                $stmt1 = $this->conexion->prepare($sql1);
                $stmt1->bind_param("sssssssi", $nombre, $descripcion, $precio, $alto, $ancho, $largo, $peso, $id_producto);
                $stmt1->execute();
                $stmt1->close();
        
                // Confirmar la transacción
                $this->conexion->commit();

                // Cierra la conexión
                $this->conexion->close();
        
                return '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Producto editado
                    </div>
                
                ';
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

        public function eliminar_producto($id_producto){
            // Inicia una transacción para garantizar la integridad referencial
            $this->conexion->begin_transaction();

            try {
                // Query preparada para actualizar el estado del usuario a 'inactivo'
                $sql_producto = "UPDATE productos SET estado = 'inactivo' WHERE id_productos = ?";
                $stmt_producto = $this->conexion->prepare($sql_producto);
                $stmt_producto->bind_param("s", $id_producto);
                $stmt_producto->execute();

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
        }

    }

?>