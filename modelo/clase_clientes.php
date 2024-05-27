<?php

    require_once "conexionbd.php";

    class Clientes extends Conexion{

        public function __construct(){

            parent::__construct();
        }

        public function buscar_clientes() {
            $columns = ["id_clientes", "ruc", "razon_social"];
            $tabla = "clientes";
        
            // Consulta SQL 
            $sql = "SELECT " . implode(", ", $columns) . "
                    FROM $tabla ORDER BY razon_social";
        
            try {
                $resultado = $this->conexion->query($sql);
        
                if (!$resultado) {
                    throw new Exception("Error en la consulta: " . $this->conexion->error);
                }

                // Obtenemos los resultados en un array asociativo
                $productos = [];
                while ($row = $resultado->fetch_assoc()) {
                    $productos[] = $row;
                }
        
                // Devuelve los resultados en formato JSON
                header('Content-Type: application/json');
                echo json_encode($productos);
            } catch (Exception $e) {
                // Manejo de errores
                echo $e->getMessage();
                return false;
            }
        }
        

        public function listado_contacto($id_cliente) {
            $columns = ["id_contacto", "nombre", "tlf_contacto", "correo_contacto", "cargo_contacto"];
            $columnsWhere = ["id_cliente"];
            $tabla = "contacto_cliente";
        
            $where = '';
        
            if ($id_cliente != null) {
                $id_cliente = $this->conexion->real_escape_string($id_cliente);
        
                // Construimos la cláusula WHERE
                $where = "WHERE (";
        
                $conditions = array_map(function ($column) use ($id_cliente) {
                    return "$column = '$id_cliente'";
                }, $columnsWhere);
        
                $where .= implode(" OR ", $conditions);
                $where .= ")";
            }
        
            // Consulta SQL 
            $sql = "SELECT " . implode(", ", $columns) . "
                    FROM $tabla 
                    $where";
        
            try {
                $resultado = $this->conexion->query($sql);
        
                if (!$resultado) {
                    throw new Exception("Error en la consulta: " . $this->conexion->error);
                }
        
                return $resultado;
            } catch (Exception $e) {
                // Manejo de errores
                echo $e->getMessage();
                return false;
            }
        }
        

        public function mostrar_contacto($id_contacto) {
            $columns = ["nombre", "tlf_contacto", "correo_contacto"];
            $columnsWhere = ["id_contacto"];
            $tabla = "contacto_cliente"; 
        
            $where = '';
        
            if ($id_contacto != null && !empty($id_contacto)) {
                $id_contacto = $this->conexion->real_escape_string($id_contacto);
                
                // Construimos la cláusula WHERE
                $where = "WHERE (";
        
                $conditions = array_map(function ($column) use ($id_contacto) {
                    return "$column = '$id_contacto'";
                }, $columnsWhere);
        
                $where .= implode(" OR ", $conditions);
                $where .= ")";
            }
        
            // Consulta SQL 
            $sql = "SELECT " . implode(", ", $columns) . "
                    FROM $tabla 
                    $where";
        
            try {
                $resultado = $this->conexion->query($sql);
        
                if (!$resultado) {
                    throw new Exception("Error en la consulta: " . $this->conexion->error);
                }
        
                return $resultado;
            } catch (Exception $e) {
                // Manejo de errores
                echo $e->getMessage();
                return false;
            }
        }
        

        public function listado_clientes($campo, $limit, $pagina, $orderCol, $orderType){

            // Crear la tabla temporal
            $sql_tabla_temporal = "CREATE TEMPORARY TABLE lista_clientes_tmp AS
            SELECT
                cli.id_clientes,
                cli.ruc,
                cli.razon_social,
                cli.direccion,
                cli.distrito,
                cli.departamento,
                cli.id_usuario,
                cli.tipocliente,
                cli.pagocliente,
                usr.nombres,
                usr.apellidos
            FROM
                clientes cli
            JOIN usuarios usr ON usr.id_usuario = cli.id_usuario";

            $tabla_temporal = $this->conexion->query($sql_tabla_temporal);

            $columns = ["id_clientes", "ruc", "razon_social", "direccion", "distrito", "departamento", "id_usuario", "tipocliente", "pagocliente", "nombres", "apellidos"];
            $columnsOrder = ["ruc", "razon_social", "direccion", "id_usuario"];
            $id = 'id_clientes';
            $columnsWhere = ["ruc", "razon_social", "direccion", "distrito", "departamento", "nombres", "apellidos"];
            $tabla = "lista_clientes_tmp";
        
            $where = '';
        
            if (!empty($campo)) {
                $conditions = array_map(function ($column) use ($campo) {
                    return "$column LIKE '%" . $this->conexion->real_escape_string($campo) . "%'";
                }, $columnsWhere);
        
                $where = "WHERE " . implode(" OR ", $conditions);
            }

            //Limite        
            $pagina = max(1, (int)$pagina);
            $inicio = ($pagina - 1) * $limit;
        
            $sqlLimit = "LIMIT $inicio, $limit";

            //Ordenamiento
            $sqlOrder = "ORDER BY " . $columnsOrder[intval($orderCol)] . ' ' . $orderType;
        
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
        
                return [$resultado, $totalFiltro, $totalRegistros, $columns];
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error en la consulta: " . $e->getMessage();
                return false;
            }
        }
        

        public function registrar_cliente(
            $numero, 
            $entidad, 
            $direccion,
            $distrito,
            $departamento,
            $nombres,
            $telefonos,
            $correos,
            $cargos,
            $id_usuario,
            $tipocliente,
            $pagocliente
        ) {
            // Inicia una transacción para garantizar la integridad de la base de datos
            $this->conexion->begin_transaction();
        
            try {
                // Verifica si ya existe un cliente con el mismo número de RUC
                $stmt_verificar_ruc = $this->conexion->prepare("SELECT id_clientes FROM clientes WHERE ruc = ?");
                $stmt_verificar_ruc->bind_param("s", $numero);
                $stmt_verificar_ruc->execute();
                $stmt_verificar_ruc->store_result();

                if ($stmt_verificar_ruc->num_rows > 0) {
                    // Si ya existe un cliente con el mismo RUC, maneja la situación adecuadamente
                    $stmt_verificar_ruc->close();
                    $this->conexion->rollback();
                    return '
                        <div class="alert alert-warning" id="miAlert" role="alert">
                            Ya existe un cliente con el mismo número de RUC.
                        </div>
                    ';
                }

                // Si no existe un cliente con el mismo RUC, procede con la inserción
                $stmt_verificar_ruc->close();

                // Inserta el cliente
                $sql_cliente = "INSERT INTO clientes (ruc, razon_social, direccion, distrito, departamento, id_usuario, tipocliente, pagocliente) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_cliente = $this->conexion->prepare($sql_cliente);
                $stmt_cliente->bind_param("ssssssss", $numero, $entidad, $direccion, $distrito, $departamento, $id_usuario, $tipocliente, $pagocliente);
                $stmt_cliente->execute();
        
                // Obtiene el ID del cliente recién insertado
                $id_cliente = $stmt_cliente->insert_id;
        
                // Inserta los contactos del cliente
                $sql_contacto = "INSERT INTO contacto_cliente (id_cliente, nombre, tlf_contacto, correo_contacto, cargo_contacto) VALUES (?, ?, ?, ?, ?)";
                $stmt_contacto = $this->conexion->prepare($sql_contacto);
                $stmt_contacto->bind_param("issss", $id_cliente, $nombre, $telefono, $correo, $cargo);
        
                $longitud = count($nombres);
        
                for ($i = 0; $i < $longitud; $i++) {
                    // Obtén los valores actuales
                    $nombre = $nombres[$i];
                    $telefono = $telefonos[$i];
                    $correo = $correos[$i];
                    $cargo = $cargos[$i];
        
                    // Ejecuta la inserción
                    $stmt_contacto->execute();
                }
        
                // Confirma la transacción
                $this->conexion->commit();

                return '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Cliente registrado
                    </div>
                
                ';
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                return '

                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error al editar al cliente - '. $e .'
                    </div>
                
                ';
            }
        }
        
        public function registrar_cliente2(
            $numero, 
            $entidad, 
            $direccion,
            $distrito,
            $departamento,
            $nombres,
            $telefonos,
            $correos,
            $cargos,
            $id_usuario,
            $tipocliente,
            $pagocliente
        ) {
            // Inicia una transacción para garantizar la integridad de la base de datos
            $this->conexion->begin_transaction();
        
            try {
                // Verifica si ya existe un cliente con el mismo número de RUC
                $stmt_verificar_ruc = $this->conexion->prepare("SELECT id_clientes FROM clientes WHERE ruc = ?");
                $stmt_verificar_ruc->bind_param("s", $numero);
                $stmt_verificar_ruc->execute();
                $stmt_verificar_ruc->store_result();

                if ($stmt_verificar_ruc->num_rows > 0) {
                    // Si ya existe un cliente con el mismo RUC, maneja la situación adecuadamente
                    $stmt_verificar_ruc->close();
                    $this->conexion->rollback();
                    $mensaje = '
                        <div class="alert alert-warning" id="miAlert" role="alert">
                            Ya existe un cliente con el mismo número de RUC.
                        </div>
                    ';
                    return[$mensaje,'','',''];
                }

                // Si no existe un cliente con el mismo RUC, procede con la inserción
                $stmt_verificar_ruc->close();
                
                // Inserta el cliente
                $sql_cliente = "INSERT INTO clientes (ruc, razon_social, direccion, distrito, departamento, id_usuario, tipocliente, pagocliente) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_cliente = $this->conexion->prepare($sql_cliente);
                $stmt_cliente->bind_param("ssssssss", $numero, $entidad, $direccion, $distrito, $departamento, $id_usuario, $tipocliente, $pagocliente);
                $stmt_cliente->execute();
        
                // Obtiene el ID del cliente recién insertado
                $id_cliente = $stmt_cliente->insert_id;
        
                // Inserta los contactos del cliente
                $sql_contacto = "INSERT INTO contacto_cliente (id_cliente, nombre, tlf_contacto, correo_contacto, cargo_contacto) VALUES (?, ?, ?, ?, ?)";
                $stmt_contacto = $this->conexion->prepare($sql_contacto);
                $stmt_contacto->bind_param("issss", $id_cliente, $nombre, $telefono, $correo, $cargo);
        
                $longitud = count($nombres);
        
                for ($i = 0; $i < $longitud; $i++) {
                    // Obtén los valores actuales
                    $nombre = $nombres[$i];
                    $telefono = $telefonos[$i];
                    $correo = $correos[$i];
                    $cargo = $cargos[$i];
        
                    // Ejecuta la inserción
                    $stmt_contacto->execute();
                }
        
                // Confirma la transacción
                $this->conexion->commit();

                $mensaje = '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Cliente registrado
                    </div>
                
                ';
                return[$mensaje,$id_cliente,$entidad,$numero];
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                return '

                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error al editar al cliente - '. $e .'
                    </div>
                
                ';
            }
        }

        public function editar_cliente(
            $id_cliente, 
            $id_contacto, 
            $numero, 
            $entidad, 
            $direccion,
            $distrito,
            $departamento,
            $nombres,
            $telefonos,
            $correos,
            $cargos,
            $tipocliente,
            $pagocliente,   
            $nombrenuevos,
            $telefononuevos,
            $correonuevos,
            $cargonuevos
        ) {
            // Iniciar una transacción
            $this->conexion->begin_transaction();
        
            try {
                // Actualizar datos del cliente
                $sql1 = "UPDATE clientes SET
                    ruc = ?,
                    razon_social = ?,
                    direccion = ?,
                    distrito = ?,
                    departamento = ?,
                    tipocliente = ?,
                    pagocliente = ?
                WHERE id_clientes = ?";
        
                $stmt1 = $this->conexion->prepare($sql1);
                $stmt1->bind_param("sssssssi", $numero, $entidad, $direccion, $distrito, $departamento, $tipocliente, $pagocliente, $id_cliente);
                $stmt1->execute();
        
                // Actualizar datos de los contactos
                $sql2 = "UPDATE contacto_cliente SET 
                    nombre = ?,
                    tlf_contacto = ?,
                    correo_contacto = ?,
                    cargo_contacto = ?
                WHERE id_contacto = ?";
        
                $stmt2 = $this->conexion->prepare($sql2);
        
                for ($i = 0; $i < count($nombres); $i++) {
                    $stmt2->bind_param("ssssi", $nombres[$i], $telefonos[$i], $correos[$i], $cargos[$i], $id_contacto[$i]);
                    $stmt2->execute();
                }
        
                $stmt2->close();

                // Inserta los nuevos contactos del cliente
                $sql_contacto = "INSERT INTO contacto_cliente (id_cliente, nombre, tlf_contacto, correo_contacto, cargo_contacto) VALUES (?, ?, ?, ?, ?)";
                $stmt_contacto = $this->conexion->prepare($sql_contacto);
                $stmt_contacto->bind_param("issss", $id_cliente, $nombrenuevo, $telefononuevo, $correonuevo, $cargonuevo);
        
                $longitud = count($nombrenuevos);
        
                for ($i = 0; $i < $longitud; $i++) {
                    // Obtén los valores actuales
                    $nombrenuevo = $nombrenuevos[$i];
                    $telefononuevo = $telefononuevos[$i];
                    $correonuevo = $correonuevos[$i];
                    $cargonuevo = $cargonuevos[$i];
        
                    // Ejecuta la inserción
                    $stmt_contacto->execute();
                }
        
                // Confirmar la transacción
                $this->conexion->commit();

                // Cierra la conexión
                $this->conexion->close();
        
                return '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Cliente editado
                    </div>
                
                ';
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                return '

                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error al editar al cliente
                    </div>
                
                ';
            }
        }

        public function eliminar_cliente($id_cliente){
            // Inicia una transacción para garantizar la integridad referencial
            $this->conexion->begin_transaction();

            try {
                // Query para eliminar contactos vinculados al cliente
                $sql_contactos = "DELETE FROM contacto_cliente WHERE id_cliente = '$id_cliente'";
                $this->conexion->query($sql_contactos);

                // Query para eliminar el cliente
                $sql_cliente = "DELETE FROM clientes WHERE id_clientes = '$id_cliente'";
                $this->conexion->query($sql_cliente);

                // Confirma la transacción
                $this->conexion->commit();
                
                return '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Cliente eliminado correctamente
                    </div>
                
                ';
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                return <<<HTML

                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error al eliminar al cliente {$e}
                    </div>
                
                HTML;
            }
        }

        public function getContacto($id_contacto){
            // Utiliza consultas preparadas para evitar SQL injection
            $consulta = "SELECT * FROM contacto_cliente WHERE id_contacto = ?";
            $stmt = $this->conexion->prepare($consulta);
        
            // Verifica si la consulta preparada fue exitosa
            if ($stmt) {
                // Utiliza bind_param para asociar los parámetros de manera segura
                $stmt->bind_param("s", $id_contacto);
        
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

        public function eliminar_contacto($id_contacto){
            // Inicia una transacción para garantizar la integridad referencial
            $this->conexion->begin_transaction();

            try {
                // Query para eliminar contactos vinculados al cliente
                $sql_contactos = "DELETE FROM contacto_cliente WHERE id_contacto = '$id_contacto'";
                $this->conexion->query($sql_contactos);

                // Confirma la transacción
                $this->conexion->commit();
                
                return '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Contacto eliminado correctamente
                    </div>
                
                ';
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                return '

                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error al eliminar al contacto
                    </div>
                
                ';
            }
        }
    }

?>