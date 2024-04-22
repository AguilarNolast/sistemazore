<?php

    require_once "conexionbd.php";

    class Pruebas extends Conexion{

        public function __construct(){

            parent::__construct();
        }

        public function listado_pruebas($campo, $limit, $pagina, $orderCol, $orderType) {
            $columns = ["id_pruebas","cliente","datos_t","fecha","potencia","unipotencia","v1","v2","l1","l2","fases","frecuencia","conexion","grupo","altitud","marca","serie","fabricacion","norma","uv1","uv2","uv3","tensionu_v","tensionv_w","tensionw_u","intensidadu_v","intensidadv_w","intensidadw_u","at_bt","at_m","bt_m","at_bt_und","at_m_und","bt_m_und","minimo","resultado","int_lectura","int_valor","ten_lectura","at_tension_u_v","at_tension_v_w","at_tension_w_u","at_intensidad_u_v","at_intensidad_v_w","at_intensidad_w_u","at_resistencia_u_v","at_resistencia_v_w","at_resistencia_w_u","bt_tension_u_v","bt_tension_v_w","bt_tension_w_u","bt_intensidad_u_v","bt_intensidad_v_w","bt_intensidad_w_u","bt_resistencia_u_v","bt_resistencia_v_w","bt_resistencia_w_u","checkresis","medido","rela_teo"];
            $columnsOrder = ["cliente", "fecha"];
            $id = 'id_pruebas';
            $columnsWhere = ["cliente", "fecha"];
            $tabla = "pruebas";
        
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

        public function registrar_pruebas($arrayPruebas) {
            // Inicia una transacción para garantizar la integridad de la base de datos
            $this->conexion->begin_transaction();
        
            try {
            
                // Inicializar la consulta SQL
                $sql_pedido = "INSERT INTO pruebas (";

                // Obtener los nombres de las columnas del array
                $columnas = array_keys($arrayPruebas);

                // Construir la lista de columnas en la consulta SQL
                $sql_pedido .= implode(", ", $columnas);

                // Añadir los valores de los campos
                $sql_pedido .= ") VALUES (";

                // Construir la lista de marcadores de posición para los valores
                $sql_pedido .= implode(", ", array_fill(0, count($columnas), '?'));

                // Cerrar la consulta SQL
                $sql_pedido .= ")";
                
                /*$sql_pedido = "INSERT INTO pruebas (cliente,datos_t,fecha,potencia,unipotencia,v1,v2,l1,l2,fases,frecuencia,conexion,grupo,altitud,marca,serie,fabricacion,norma,uv1,uv2,uv3,tensionu_v,tensionv_w,tensionw_u,intensidadu_v,intensidadv_w,intensidadw_u,at_bt,at_m,bt_m,at_bt_und,at_m_und,bt_m_und,minimo,resultado,conmutador,int_lectura,int_valor,ten_lectura,ten_valor,at_tension_u_v,at_tension_v_w,at_tension_w_u,at_intensidad_u_v,at_intensidad_v_w,at_intensidad_w_u,at_resistencia_u_v,at_resistencia_v_w,at_resistencia_w_u,bt_tension_u_v,bt_tension_v_w,bt_tension_w_u,bt_intensidad_u_v,bt_intensidad_v_w,bt_intensidad_w_u,bt_resistencia_u_v,bt_resistencia_v_w,bt_resistencia_w_u,checkresis,medido,int_k,val_k,wat_k) VALUES (";
                $sql_pedido .= rtrim(str_repeat('?, ', count($arrayPruebas)), ', ');
                $sql_pedido .= ")";*/
                $stmt_pedido = $this->conexion->prepare($sql_pedido);

                // Obtener los valores del array asociativo
                $valores = array_values($arrayPruebas);

                // Crear el string de tipos para bind_param
                $tipos = str_repeat('s', count($valores));

                // Hacer el bind_param con los valores del array
                $stmt_pedido->bind_param($tipos, ...$valores);

                $stmt_pedido->execute();

        
                // Confirma la transacción
                $this->conexion->commit();

                $output = array(
                    'tipo' => 'success',
                    'mensaje' => 'Certificado registrado',
                    'redir' => true,
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                $output = array(
                    'tipo' => 'danger',
                    'mensaje' => 'Error al registrar el certificado.' . $e,
                    'redir' => false,
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            }
            // Cierra la conexión
            $this->conexion->close();
        }

        public function eliminar_pruebas($id_pruebas){
            // Inicia una transacción para garantizar la integridad referencial
            $this->conexion->begin_transaction();

            try {

                // Query para eliminar el usuario
                $sql_pruebas = "DELETE FROM pruebas WHERE id_pruebas = '$id_pruebas'";
                $this->conexion->query($sql_pruebas);

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

        public function get_pruebas($id_pruebas){
            // Utiliza consultas preparadas para evitar SQL injection
            $consulta = "SELECT * FROM pruebas WHERE id_pruebas = ?";
            $stmt = $this->conexion->prepare($consulta);
        
            // Verifica si la consulta preparada fue exitosa
            if ($stmt) {
                // Utiliza bind_param para asociar los parámetros de manera segura
                $stmt->bind_param("s", $id_pruebas);
        
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

        public function editar_pruebas($arrayPruebas,$id_pruebas) {
            // Iniciar una transacción
            $this->conexion->begin_transaction();
            
            try {

                $sql1 = "UPDATE pruebas SET ";
                $columnas = array_keys($arrayPruebas);
                foreach($arrayPruebas as $key => $valor){
                    $sql1 .= "$key = ?, ";
                }

                $sql1 = rtrim($sql1, ', ');

                $sql1 .= " WHERE id_pruebas = ?";
                
                $stmt1 = $this->conexion->prepare($sql1);
                $stmt1->bind_param("sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssi", $arrayPruebas['cliente'], $arrayPruebas['datos_t'], $arrayPruebas['fecha'], $arrayPruebas['potencia'], $arrayPruebas['unipotencia'], $arrayPruebas['v1'], $arrayPruebas['v2'], $arrayPruebas['l1'], $arrayPruebas['l2'], $arrayPruebas['fases'], $arrayPruebas['frecuencia'], $arrayPruebas['conexion'], $arrayPruebas['grupo'], $arrayPruebas['altitud'], $arrayPruebas['marca'], $arrayPruebas['serie'], $arrayPruebas['fabricacion'], $arrayPruebas['norma'], $arrayPruebas['uv1'], $arrayPruebas['uv2'], $arrayPruebas['uv3'], $arrayPruebas['tensionu_v'], $arrayPruebas['tensionv_w'], $arrayPruebas['tensionw_u'], $arrayPruebas['intensidadu_v'], $arrayPruebas['intensidadv_w'], $arrayPruebas['intensidadw_u'], $arrayPruebas['at_bt'], $arrayPruebas['at_m'], $arrayPruebas['bt_m'], $arrayPruebas['at_bt_und'], $arrayPruebas['at_m_und'], $arrayPruebas['bt_m_und'], $arrayPruebas['minimo'], $arrayPruebas['resultado'], $arrayPruebas['int_lectura'], $arrayPruebas['int_valor'], $arrayPruebas['ten_lectura'], $arrayPruebas['at_tension_u_v'], $arrayPruebas['at_tension_v_w'], $arrayPruebas['at_tension_w_u'], $arrayPruebas['at_intensidad_u_v'], $arrayPruebas['at_intensidad_v_w'], $arrayPruebas['at_intensidad_w_u'], $arrayPruebas['at_resistencia_u_v'], $arrayPruebas['at_resistencia_v_w'], $arrayPruebas['at_resistencia_w_u'], $arrayPruebas['bt_tension_u_v'], $arrayPruebas['bt_tension_v_w'], $arrayPruebas['bt_tension_w_u'], $arrayPruebas['bt_intensidad_u_v'], $arrayPruebas['bt_intensidad_v_w'], $arrayPruebas['bt_intensidad_w_u'], $arrayPruebas['bt_resistencia_u_v'], $arrayPruebas['bt_resistencia_v_w'], $arrayPruebas['bt_resistencia_w_u'], $arrayPruebas['checkresis'], $arrayPruebas['medido'], $arrayPruebas['rela_teo'], $id_pruebas);
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

    