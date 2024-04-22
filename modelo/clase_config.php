<?php

    require_once "conexionbd.php";

    class Config extends Conexion{

        public function __construct(){

            parent::__construct();
        }

        public function getConfig(){
            // Utiliza consultas preparadas para evitar SQL injection

            $consulta = "SELECT * FROM correos; SELECT * FROM servidor_correo";
            $stmt = $this->conexion->multi_query($consulta);

            if ($stmt) {
                // Obtiene los resultados
                $resultado_correo = $this->conexion->store_result();
                $this->conexion->next_result();  // Avanza al siguiente conjunto de resultados
                $resultado_servidor = $this->conexion->store_result();

                return [$resultado_correo, $resultado_servidor];
            } else {
                // Manejo de error si la preparación de la consulta falla
                throw new Exception("Error en la consulta preparada: " . $this->conexion->error);
            }
        }

        public function updateConfig(
            $correos,
            $nombres,
            $id_correos,
            $servidor,
            $clave
        ){
            $this->conexion->begin_transaction();
        
            try {
                // Actualizar datos de configuracion
                $sql = "UPDATE correos SET
                    correo = ?,
                    nombre = ?
                WHERE id_correo = ?";
        
                $stmt = $this->conexion->prepare($sql);
                $stmt->bind_param("ssi", $correo, $nombre, $id_correo);

                $longitud = count($id_correos);

                for ($i = 0; $i < $longitud; $i++) {
                    // Obtén los valores actuales
                    $correo = $correos[$i];
                    $nombre = $nombres[$i];
                    $id_correo = $id_correos[$i];

                    // Ejecuta la inserción
                    $stmt->execute();
                }

                $sql2 = "UPDATE servidor_correo SET
                    servidor = ?,
                    clave = ?
                WHERE id_servidor = 1";
        
                $stmt2 = $this->conexion->prepare($sql2);
                $stmt2->bind_param("ss", $servidor, $clave);

                // Ejecuta la inserción
                $stmt2->execute();
                
                $stmt->close();
                $stmt2->close();
        
                // Confirmar la transacción
                $this->conexion->commit();

                $output = true;
                
                return $output;
            } catch (Exception $e) {
                // Revertir la transacción en caso de error
                $this->conexion->rollback();
        
                // Devolver un mensaje de error
                $output = array(
                    'tipo' => 'danger',
                    'mensaje' => 'Fallo al guardar configuracion',
                );
                return $output;
            }
        }
    }

?>