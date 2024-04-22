<?php

    require_once "conexionbd.php";

    class Calidad extends Conexion{

        public function __construct(){

            parent::__construct();
        }

        public function listado_calidad($campo, $limit, $pagina, $orderCol, $orderType) {
            $columns = ["id_calidad","nombre","ruc", "marca", "potencia", "unipotencia", "factor", "direccion", "tipo", "serie", "fecha_fab"];
            $columnsOrder = ["nombre", "ruc", "tipo", "serie"];
            $id = 'id_calidad';
            $columnsWhere = ["nombre", "ruc", "tipo", "serie"];
            $tabla = "calidad";
        
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

        public function registrar_calidad(
            $nombre,
            $ruc,
            $direccion,
            $tipoequipo,
            $potencia,
            $unipotencia,
            $factor,
            $marca,
            $serie,
            $fecha_fab
        ) {
            // Inicia una transacción para garantizar la integridad de la base de datos
            $this->conexion->begin_transaction();
        
            try {
                // Inserta el pedido
                $sql_pedido = "INSERT INTO calidad (nombre,ruc,marca,potencia,unipotencia,factor,direccion,tipo,serie,fecha_fab) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_pedido = $this->conexion->prepare($sql_pedido);
                $stmt_pedido->bind_param("ssssssssss", $nombre,$ruc,$marca,$potencia,$unipotencia,$factor,$direccion,$tipoequipo,$serie,$fecha_fab);
                $stmt_pedido->execute();
        
                // Confirma la transacción
                $this->conexion->commit();

                $output = array(
                    'tipo' => 'success',
                    'mensaje' => 'Calidad registrada',
                    'redir' => true,
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                $output = array(
                    'tipo' => 'danger',
                    'mensaje' => 'Error al registrar la calidad.' . $e,
                    'redir' => false,
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            }
            // Cierra la conexión
            $this->conexion->close();
        }

        public function eliminar_calidad($id_calidad){
            // Inicia una transacción para garantizar la integridad referencial
            $this->conexion->begin_transaction();

            try {

                // Query para eliminar el usuario
                $sql_calidad = "DELETE FROM calidad WHERE id_calidad = '$id_calidad'";
                $this->conexion->query($sql_calidad);

                // Confirma la transacción
                $this->conexion->commit();
                
                // Cierra la conexión
                $this->conexion->close();

                return '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Certificado eliminado correctamente.
                    </div>
                
                ';
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                return '

                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error al eliminar certificado
                    </div>
                
                ';
            }

            // Cierra la conexión
            $this->conexion->close();
        }

        public function get_calidad($id_calidad){
            // Utiliza consultas preparadas para evitar SQL injection
            $consulta = "SELECT * FROM calidad WHERE id_calidad = ?";
            $stmt = $this->conexion->prepare($consulta);
        
            // Verifica si la consulta preparada fue exitosa
            if ($stmt) {
                // Utiliza bind_param para asociar los parámetros de manera segura
                $stmt->bind_param("s", $id_calidad);
        
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

        public function editar_calidad($arrayCalidad,$id_calidad) {
            // Iniciar una transacción
            $this->conexion->begin_transaction();
        
            try {
                $sql1 = "UPDATE calidad SET ";
                $columnas = array_keys($arrayCalidad);
                foreach($arrayCalidad as $key => $valor){
                    $sql1 .= "$key = ?, ";
                }
                $sql1 = rtrim($sql1, ', ');

                $sql1 .= " WHERE id_calidad = ?";
                /*$sql1 = "UPDATE calidad SET
                    nombre = ?,
                    ruc = ?,
                    direccion = ?,
                    tipo = ?,
                    potencia = ?,
                    unipotencia = ?,
                    factor = ?,
                    marca = ?,
                    serie = ?,
                    fecha_fab = ?
                WHERE id_calidad = ?";*/
        
                $stmt1 = $this->conexion->prepare($sql1);
                $stmt1->bind_param("ssssssssssi", $arrayCalidad['nombre'], $arrayCalidad['ruc'], $arrayCalidad['marca'], $arrayCalidad['potencia'], $arrayCalidad['unipotencia'], $arrayCalidad['factor'], $arrayCalidad['direccion'], $arrayCalidad['tipo'], $arrayCalidad['serie'], $arrayCalidad['fecha_fab'], $id_calidad);
                $stmt1->execute();
                $stmt1->close();
        
                // Confirmar la transacción
                $this->conexion->commit();

                // Cierra la conexión
                $this->conexion->close();
        
                return '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Certificado editado
                    </div>
                
                ';
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                return '

                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error al editar certificado '.$e.'
                    </div>
                
                ';
            }
        }
    
    }

    