<?php

    require_once "conexionbd.php";
    

    class Usuario extends Conexion{

        public function __construct(){
            
            parent::__construct();
        }

        public function get_login($usuario){
            // Utiliza consultas preparadas para evitar SQL injection
            $consulta = "SELECT * FROM usuarios WHERE usuario = ?";
            $stmt = $this->conexion->prepare($consulta);
        
            // Verifica si la consulta preparada fue exitosa
            if ($stmt) {
                // Utiliza bind_param para asociar los parámetros de manera segura
                $stmt->bind_param("s", $usuario);
        
                // Ejecuta la consulta
                $stmt->execute();
        
                // Obtiene el resultado
                $resultado = $stmt->get_result();
        
                // Cierra la declaración preparada
                $stmt->close();
        
                return $resultado;
            } else {
                // Manejo de error si la preparación de la consulta falla
                $output = array(
                    'tipo' => 'danger',
                    'mensaje' => 'Datos incorrectos',
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            }
        }
        

        public function listado_usuarios($campo, $limit, $pagina, $orderCol, $orderType){
            $columns = ["id_usuario", "usuario", "nombres", "apellidos", "telefono", "correo"];
            $columnsOrder = ["nombres", "apellidos", "usuario", "correo", "telefono"];
            $id = 'id_usuario';
            $columnsWhere = ["usuario", "nombres", "apellidos", "telefono", "correo"];
            $tabla = "usuarios"; 
          
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
        
        //Añadir proteccion con consultas preparadas y hash para las claves
        public function registrar_usuario(
            $usuario,
            $clave,
            $nombre,
            $apellido,
            $telefono,
            $correo
        ) {
            // Inicia una transacción para garantizar la integridad de la base de datos
            $this->conexion->begin_transaction();
        
            try {
                // Verifica si ya existe un usuario con el mismo nombre de usuario
                $stmt_verificar_usuario = $this->conexion->prepare("SELECT id_usuario FROM usuarios WHERE usuario = ?");
                $stmt_verificar_usuario->bind_param("s", $usuario);
                $stmt_verificar_usuario->execute();
                $stmt_verificar_usuario->store_result();

                if ($stmt_verificar_usuario->num_rows > 0) {
                    // Si ya existe un usuario con el mismo nombre de usuario, maneja la situación adecuadamente
                    $stmt_verificar_usuario->close();
                    $this->conexion->rollback();
                    return '
                        <div class="alert alert-warning" id="miAlert" role="alert">
                            Nombre de usuario existente
                        </div>
                    ';
                }

                // Si no existe un usuario con el mismo nombre de usuario, procede con la inserción
                $stmt_verificar_usuario->close();

                $token = "";
                // Inserta el cliente
                $sql_usuario = "INSERT INTO usuarios (usuario, clave, nombres, apellidos, telefono, correo, token_r) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_usuario = $this->conexion->prepare($sql_usuario);
                $stmt_usuario->bind_param("sssssss", $usuario, $clave, $nombre, $apellido, $telefono, $correo, $token);
                $stmt_usuario->execute();
        
                // Confirma la transacción
                $this->conexion->commit();
        
                $validacion = '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Usuario registrado
                    </div>
                
                ';
        
                return $validacion;
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                throw new Exception("Error al registrar el cliente: " . $e->getMessage());
            }
        }

        public function editar_usuario(
            $id_usuario,
            $usuario,
            $clave,
            $nombre,
            $apellido,
            $telefono,
            $correo
        ) {
            // Iniciar una transacción
            $this->conexion->begin_transaction();
        
            try {
                if(empty($clave)){
                    // Actualizar datos del usuario
                    $sql1 = "UPDATE usuarios SET
                        usuario = ?,
                        nombres = ?,
                        apellidos = ?,
                        telefono = ?,
                        correo = ?
                    WHERE id_usuario = ?";
                    $stmt1 = $this->conexion->prepare($sql1);
                    
                    $stmt1->bind_param("sssssi", $usuario, $nombre, $apellido, $telefono, $correo, $id_usuario);
                }else{
                    // Actualizar datos del usuario
                    $sql1 = "UPDATE usuarios SET
                        usuario = ?,
                        clave = ?,
                        nombres = ?,
                        apellidos = ?,
                        telefono = ?,
                        correo = ?
                    WHERE id_usuario = ?";
                    $stmt1 = $this->conexion->prepare($sql1);
                    
                    $stmt1->bind_param("ssssssi", $usuario, $clave, $nombre, $apellido, $telefono, $correo, $id_usuario);
                }
                $stmt1->execute();
                $stmt1->close();
        
                // Confirmar la transacción
                $this->conexion->commit();
        
                return '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Usuario editado
                    </div>
                
                ';
            } catch (Exception $e) {
                // Revertir la transacción en caso de error
                $this->conexion->rollback();
        
                // Devolver un mensaje de error
                return '

                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error al editar usuario '.$e.'
                    </div>
                
                ';
            }
        }

        public function eliminar_usuario($id_usuario) {
            // Inicia una transacción para garantizar la integridad referencial
            $this->conexion->begin_transaction();
        
            try {
                // Query preparada para eliminar el usuario
                $sql_usuario = "DELETE FROM usuarios WHERE id_usuario = ?";
                $stmt_usuario = $this->conexion->prepare($sql_usuario);
                $stmt_usuario->bind_param("s", $id_usuario);
                $stmt_usuario->execute();
        
                // Confirma la transacción
                $this->conexion->commit();
        
                return '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Usuario eliminado correctamente
                    </div>
                
                ';
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                return '

                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error al eliminar el usuario '.$e.'
                    </div>
                
                ';
            } finally {
                // Cierra la conexión
                $this->conexion->close();
            }
        }
        
        public function coti_usuarios($id_usuario, $campo, $limit, $pagina){
            $columns = ["id_usuario", "usuario", "nombres", "apellidos", "telefono", "correo"]; //Array con todas las columnas de la tabla
            $id = 'id_usuario';
            $columnsWhere = ["id_usuario", "usuario", "nombres", "apellidos", "telefono", "correo"]; //Array con todas las columnas donde quiero hacer mi busqueda
            $tabla = "usuarios"; 
        
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
            $sqlOrder = "";
        
            if (isset($_POST['orderCol'])) {
                $orderCol = $_POST['orderCol'];
                $orderType = isset($_POST['orderType']) ? $_POST['orderType'] : 'asc';
        
                $sqlOrder = "ORDER BY " . $columns[intval($orderCol)] . ' ' . $orderType;
            }
        
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

        public function verificarEmail($email){

            // Utiliza consultas preparadas para evitar SQL injection
            $consulta = "SELECT id_usuario, usuario FROM usuarios WHERE correo = ?";
            $stmt = $this->conexion->prepare($consulta);
        
            // Verifica si la consulta preparada fue exitosa
            if ($stmt) {
                // Utiliza bind_param para asociar los parámetros de manera segura
                $stmt->bind_param("s", $email);
        
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

        public function updateToken($token, $id_usuario){
            
            // Utiliza consultas preparadas para evitar SQL injection
            $consulta = "UPDATE usuarios SET token_r = ? WHERE id_usuario = ?";
            $stmt = $this->conexion->prepare($consulta);
        
            // Verifica si la consulta preparada fue exitosa
            if ($stmt) {
                // Utiliza bind_param para asociar los parámetros de manera segura
                $stmt->bind_param("si", $token, $id_usuario);
        
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

        public function verificarToken($token, $id_usuario){
            // Utiliza consultas preparadas para evitar SQL injection
            $consulta = "SELECT * FROM usuarios WHERE token_r = ? AND id_usuario = ?";
            $stmt = $this->conexion->prepare($consulta);
        
            // Verifica si la consulta preparada fue exitosa
            if ($stmt) {
                // Utiliza bind_param para asociar los parámetros de manera segura
                $stmt->bind_param("ss", $token, $id_usuario);
        
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

        public function updatePass($clave, $token, $id_usuario){
            
            // Utiliza consultas preparadas para evitar SQL injection
            $consulta = "UPDATE usuarios SET clave = ?, token_r = ? WHERE id_usuario = ?";
            $stmt = $this->conexion->prepare($consulta);
        
            // Verifica si la consulta preparada fue exitosa
            if ($stmt) {
                // Utiliza bind_param para asociar los parámetros de manera segura
                $stmt->bind_param("ssi", $clave, $token, $id_usuario);
        
                // Ejecuta la consulta
                $stmt->execute();
        
                // Verifica el número de filas afectadas
                $filas_afectadas = $stmt->affected_rows;

                // Cierra la declaración preparada
                $stmt->close();

                // Verifica si al menos una fila fue afectada (actualización exitosa)
                if ($filas_afectadas > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                // Manejo de error si la preparación de la consulta falla
                throw new Exception("Error en la consulta preparada: " . $this->conexion->error);
            }
        }

        public function getUser($id_usuario){
            // Utiliza consultas preparadas para evitar SQL injection
            $consulta = "SELECT id_usuario,usuario,nombres,apellidos,telefono,correo,tipo FROM usuarios WHERE id_usuario = ?";
            $stmt = $this->conexion->prepare($consulta);
        
            // Verifica si la consulta preparada fue exitosa
            if ($stmt) {
                // Utiliza bind_param para asociar los parámetros de manera segura
                $stmt->bind_param("s", $id_usuario);
        
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
        
        public function listado_asistencia($campo, $limit, $pagina, $id_usuario){
            $columns = ["id_usuario", "entrada", "salida", "fecha"];
            $id = 'id_usuario';
            $columnsWhere = ["entrada", "salida", "fecha"];
            $tabla = "asistencia"; 
        
            
            $where = '';
            $sqlParams = [];
        
            if (!empty($campo)) {
                $conditions = array_map(function ($column) use ($campo, &$sqlParams) {
                    $sqlParams[] = "%$campo%";
                    return "$column LIKE ?";
                }, $columnsWhere);
        
                $where = "WHERE " . implode(" OR ", $conditions);
                // Agrega la condición para filtrar por $id_usuario solo cuando no esté vacío
                if (!empty($id_usuario)) {
                    $where .= " AND $id = ?";
                    $sqlParams[] = $id_usuario;
                }
            }else {
                // Agrega la condición para filtrar por $id_usuario solo cuando no esté vacío
                if (!empty($id_usuario)) {
                    $where = "WHERE $id = ?";
                    $sqlParams[] = $id_usuario;
                }
            }

            if($limit != null){
                
                $pagina = max(1, (int) $pagina);
                $inicio = ($pagina - 1) * $limit;
            
                $sqlLimit = "LIMIT ?, ?";
                $sqlParams[] = $inicio;
                $sqlParams[] = $limit;
        
            }else{
                $sqlLimit = '';
            }
        
            // Ordenamiento
            $orderType = 'desc';
    
            $sqlOrder = "ORDER BY fecha " . $orderType;
        
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
                    if($limit != null){
                        $stmt->bind_param(str_repeat('s', count($sqlParams)), ...$sqlParams);
                    }
        
                    $stmt->execute();
                    $resultado = $stmt->get_result();
        
                    // Consulta de cantidad de registros filtrados
                    $resFiltro = $this->conexion->query("SELECT FOUND_ROWS()");
                    $totalFiltro = $resFiltro->fetch_array()[0];
        
                    // Consulta para total de registros filtrados
                    $resTotal = $this->conexion->query("SELECT COUNT($id) FROM $tabla");
                    $totalRegistros = $resTotal->fetch_array()[0];
        
                    return [$resultado, $totalFiltro, $totalRegistros, $columns];
                } else {
                    // Manejo de errores
                    throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
                }
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error en la consulta: " . $e->getMessage();
                return false;
            } finally {
                // Cierra la conexión
                $this->conexion->close();
            }
        }
        
        public function filtro_asistencia($limit, $pagina, $dateIn, $dateOut, $selectUser){

            $columns = ["id_usuario", "entrada", "salida", "fecha"];
            $id = 'id_usuario';
            $columnsWhere = ["entrada", "salida", "fecha"];
            $tabla = "asistencia";         

            if($dateIn == false && $dateOut == false){
                if($selectUser != 'todos'){
                    $where = "WHERE id_usuario = $selectUser";
                }else{
                    $where = "";
                }
            }else{
                if($selectUser != 'todos'){
                    $where = "WHERE fecha BETWEEN '$dateIn' AND '$dateOut' AND id_usuario = $selectUser";
                }else{
                    $where = "WHERE fecha BETWEEN '$dateIn' AND '$dateOut'";
                }
            }

            $sqlParams = [];

            if($limit != null){
                
                $pagina = max(1, (int) $pagina);
                $inicio = ($pagina - 1) * $limit;
            
                $sqlLimit = "LIMIT ?, ?";
                $sqlParams[] = $inicio;
                $sqlParams[] = $limit;
        
            }else{
                $sqlLimit = '';
            }
        
            // Ordenamiento
            $orderType = 'desc';
    
            $sqlOrder = "ORDER BY fecha " . $orderType;
        
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
                    if($limit != null){
                        $stmt->bind_param(str_repeat('s', count($sqlParams)), ...$sqlParams);
                    }
        
                    $stmt->execute();
                    $resultado = $stmt->get_result();
        
                    // Consulta de cantidad de registros filtrados
                    $resFiltro = $this->conexion->query("SELECT FOUND_ROWS()");
                    $totalFiltro = $resFiltro->fetch_array()[0];
        
                    // Consulta para total de registros filtrados
                    $resTotal = $this->conexion->query("SELECT COUNT($id) FROM $tabla");
                    $totalRegistros = $resTotal->fetch_array()[0];
        
                    return [$resultado, $totalFiltro, $totalRegistros, $columns];
                } else {
                    // Manejo de errores
                    throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
                }
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error en la consulta: " . $e->getMessage();
                return false;
            } finally {
                // Cierra la conexión
                $this->conexion->close();
            }
        }

        public function registrar_entrada(
            $id_usuario,
            $hora_entrada,
            $fecha
        ) {
            // Inicia una transacción para garantizar la integridad de la base de datos
            $this->conexion->begin_transaction();
        
            try {
                // Verificar si ya existe un registro para el usuario y la fecha especifica
                $sql_verificar = "SELECT COUNT(*) FROM asistencia WHERE id_usuario = ? AND fecha = ?";
                $stmt_verificar = $this->conexion->prepare($sql_verificar);
                $stmt_verificar->bind_param("ss", $id_usuario, $fecha);
                $stmt_verificar->execute();
                $stmt_verificar->bind_result($count);
                $stmt_verificar->fetch();
                $stmt_verificar->close();
        
                if ($count > 0) {
                    // Ya existe un registro para el usuario y la fecha, no es necesario registrar nuevamente
                    
                    $validacion = '

                        <div class="alert alert-danger" id="miAlert" role="alert">
                            Ya existe un registro para este usuario y fecha
                        </div>
                    
                    ';
        
                    return $validacion;
                }
        
                // No existe un registro previo, proceder con la inserción
                $hora_salida = "";
        
                $sql_usuario = "INSERT INTO asistencia (id_usuario, entrada, salida, fecha) VALUES (?, ?, ?, ?)";
                $stmt_usuario = $this->conexion->prepare($sql_usuario);
                $stmt_usuario->bind_param("ssss", $id_usuario, $hora_entrada, $hora_salida, $fecha);
                $stmt_usuario->execute();
        
                // Confirma la transacción
                $this->conexion->commit();
        
                $validacion = '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Hora de entrada registrada
                    </div>
                
                ';
        
                return $validacion;
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                throw new Exception("Error al registrar: " . $e->getMessage());
            }
        }

        public function registrar_salida(
            $id_usuario,
            $hora_salida,
            $fecha
        ) {
            // Inicia una transacción para garantizar la integridad de la base de datos
            $this->conexion->begin_transaction();
        
            try {
                // Verificar si ya existe un registro para el usuario y la fecha específica con salida igual a "00:00:00"
                $sql_verificar = "SELECT COUNT(*) FROM asistencia WHERE id_usuario = ? AND fecha = ? AND salida = '00:00:00'";
                $stmt_verificar = $this->conexion->prepare($sql_verificar);
                $stmt_verificar->bind_param("ss", $id_usuario, $fecha);
                $stmt_verificar->execute();
                $stmt_verificar->bind_result($count);
                $stmt_verificar->fetch();
                $stmt_verificar->close();
        
                if ($count > 0) {
                    // Ya existe un registro con salida igual a "00:00:00", proceder con la actualización
                    $sql_actualizar = "UPDATE asistencia SET salida = ? WHERE id_usuario = ? AND fecha = ?";
                    $stmt_actualizar = $this->conexion->prepare($sql_actualizar);
                    $stmt_actualizar->bind_param("sss", $hora_salida, $id_usuario, $fecha);
                    $stmt_actualizar->execute();
                    $stmt_actualizar->close();
        
                    // Confirma la transacción
                    $this->conexion->commit();
        
                    $validacion = '

                        <div class="alert alert-success" id="miAlert" role="alert">
                            Salida registrada
                        </div>
                    
                    ';
                    return $validacion;
                } else {
                    // No existe un registro previo con salida igual a "00:00:00", devolver mensaje de error
                    
                    $validacion = '

                        <div class="alert alert-danger" id="miAlert" role="alert">
                            Ya ha registrado su salida
                        </div>
                    
                    ';
                    return $validacion;
                }
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                throw new Exception("Error al registrar: " . $e->getMessage());
            }
        }
        
    }

?>