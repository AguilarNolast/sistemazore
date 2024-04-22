<?php

    require_once "conexionbd.php";

    class Garantia extends Conexion{

        public function __construct(){

            parent::__construct();
        }

        public function listado_garantia($campo, $limit, $pagina, $orderCol, $orderType) {
            $columns = ["id_garantia","nombre", "ruc", "factura", "oc", "fecha", "tipo", "marca", "potencia", "unipotencia", "tvss", "modelo", "serie", "manual_v"];
            $columnsOrder = ["fecha"];
            $id = 'id_garantia';
            $columnsWhere = ["nombre", "ruc", "factura", "oc"];
            $tabla = "garantia";
        
            $where = '';
            $sqlParams = [];
        
            if (!empty($campo)) {
                $conditions = array_map(function ($column) use ($campo, &$sqlParams) {
                    $sqlParams[] = "%$campo%";
                    return "$column LIKE ?";
                }, $columnsWhere);
        
                $where = "WHERE " . implode(" OR ", $conditions);
            }
        
            $pagina = max(1, (int) $pagina);
            $inicio = ($pagina - 1) * $limit;
        
            $sqlLimit = "LIMIT ?, ?";
            $sqlParams[] = $inicio;
            $sqlParams[] = $limit;
            
            //Ordenamiento
            $sqlOrder = "ORDER BY " . $columnsOrder[intval($orderCol)] . ' DESC' ;
        
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
                    $stmt->bind_param(str_repeat('s', count($sqlParams)), ...$sqlParams);
        
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

        public function registrar_garantia(
            $nombre,
            $ruc,
            $factura,
            $oc,
            $fecha,
            $tipo,
            $marca,
            $potencia,
            $unipotencia,
            $tvss,
            $modelo,
            $serie,
            $manual_v
        ) {
            // Inicia una transacción para garantizar la integridad de la base de datos
            $this->conexion->begin_transaction();
        
            try {
                // Inserta el pedido
                $sql_pedido = "INSERT INTO garantia (nombre,ruc,factura,oc,fecha,tipo,marca,potencia,unipotencia,tvss,modelo,serie,manual_v) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_pedido = $this->conexion->prepare($sql_pedido);
                $stmt_pedido->bind_param("sssssssssssss", $nombre,$ruc,$factura,$oc,$fecha,$tipo,$marca,$potencia,$unipotencia,$tvss,$modelo,$serie,$manual_v);
                $stmt_pedido->execute();
        
                // Confirma la transacción
                $this->conexion->commit();

                $output = array(
                    'tipo' => 'success',
                    'mensaje' => 'Garantia registrada',
                    'redir' => true,
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                $output = array(
                    'tipo' => 'danger',
                    'mensaje' => 'Error al registrar la garantia.' . $e,
                    'redir' => false,
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            }
            // Cierra la conexión
            $this->conexion->close();
        }

        public function eliminar_garantia($id_garantia){
            // Inicia una transacción para garantizar la integridad referencial
            $this->conexion->begin_transaction();

            try {

                // Query para eliminar el usuario
                $sql_garantia = "DELETE FROM garantia WHERE id_garantia = '$id_garantia'";
                $this->conexion->query($sql_garantia);

                // Confirma la transacción
                $this->conexion->commit();
                
                // Cierra la conexión
                $this->conexion->close();

                return '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Garantia eliminada correctamente.
                    </div>
                
                ';
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                return '

                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error al eliminar la Garantia
                    </div>
                
                ';
            }

            // Cierra la conexión
            $this->conexion->close();
        }

        public function get_garantia($id_garantia){
            // Utiliza consultas preparadas para evitar SQL injection
            $consulta = "SELECT * FROM garantia WHERE id_garantia = ?";
            $stmt = $this->conexion->prepare($consulta);
        
            // Verifica si la consulta preparada fue exitosa
            if ($stmt) {
                // Utiliza bind_param para asociar los parámetros de manera segura
                $stmt->bind_param("s", $id_garantia);
        
                // Ejecuta la consulta
                $stmt->execute();
        
                // Obtiene el resultado
                $resultado = $stmt->get_result();
        
                // Cierra la declaración preparada
                $stmt->close();
        
                return $resultado;
            } else {
                // Manejo de error si la preparación de la consulta falla
                throw new Exception("Error en la consulta preparada: " . $this->conexion->error);
            }
        }

        public function editar_garantia(
            $id_garantia,
            $nombre,
            $ruc,
            $factura,
            $oc,
            $fecha,
            $tipo,
            $marca,
            $potencia,
            $unipotencia,
            $tvss,
            $modelo,
            $serie,
            $manual_v
        ) {
            // Iniciar una transacción
            $this->conexion->begin_transaction();
        
            try {
                // Actualizar datos del cliente
                $sql1 = "UPDATE garantia SET
                    nombre = ?,
                    ruc = ?,
                    factura = ?,
                    oc = ?,
                    fecha = ?,
                    tipo = ?,
                    marca = ?,
                    potencia = ?,
                    unipotencia = ?,
                    tvss = ?,
                    modelo = ?,
                    serie = ?,
                    manual_v = ?
                WHERE id_garantia = ?";
        
                $stmt1 = $this->conexion->prepare($sql1);
                $stmt1->bind_param("sssssssssssssi", $nombre, $ruc, $factura, $oc, $fecha, $tipo, $marca, $potencia, $unipotencia, $tvss, $modelo, $serie, $manual_v, $id_garantia);
                $stmt1->execute();
                $stmt1->close();
        
                // Confirmar la transacción
                $this->conexion->commit();

                // Cierra la conexión
                $this->conexion->close();
        
                return '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Garantia editada
                    </div>
                
                ';
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                return '

                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error al editar garantia '.$e.'
                    </div>
                
                ';
            }
        }
    
    }

    