<?php

    require_once "conexionbd.php";

    class Ficha extends Conexion{

        public function __construct(){

            parent::__construct();
        }

        public function getFichas($id_coti){
            // Utiliza consultas preparadas para evitar SQL injection
            $consulta = "SELECT id_ficha, tipoequipo, potencia, unipotencia FROM fichas_tec WHERE id_coti = ?";
            $stmt = $this->conexion->prepare($consulta);
        
            // Verifica si la consulta preparada fue exitosa
            if ($stmt) {
                // Utiliza bind_param para asociar los parámetros de manera segura
                $stmt->bind_param("s", $id_coti);
        
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
        
        public function get_ficha($id_ficha){
            // Utiliza consultas preparadas para evitar SQL injection
            $consulta = "SELECT * FROM fichas_tec WHERE id_ficha = ?";
            $stmt = $this->conexion->prepare($consulta);
        
            // Verifica si la consulta preparada fue exitosa
            if ($stmt) {
                // Utiliza bind_param para asociar los parámetros de manera segura
                $stmt->bind_param("s", $id_ficha);
        
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

        public function registrar_ficha(
            $id_coti,
            $tipoequipo,
            $marca,
            $potencia,
            $unipotencia,
            $fases,
            $tensionpri,
            $neutroent,
            $neutrosal,
            $bornespri,
            $tensionsecun,
            $bornessec,
            $factorpot,
            $grupocon,
            $factor,
            $gradopro,
            $clase,
            $altitud,
            $montaje,
            $material,
            $altoficha,
            $anchoficha,
            $largoficha,
            $pesoficha,
            $conpri,
            $consec,
            $regEnt1,
            $regEnt2,
            $regSal
        ) {
            // Inicia una transacción para garantizar la integridad de la base de datos
            $this->conexion->begin_transaction();
        
            try {
                // Inserta el pedido
                $sql_pedido = "INSERT INTO fichas_tec (id_coti,tipoequipo,marca,potencia,unipotencia,fases,tensionpri,neutroent,neutrosal,bornespri,tensionsecun,bornessec,factorpot,grupocon,factor,gradopro,clase,altitud,montaje,material,altoficha,anchoficha,largoficha,pesoficha,conpri,consec,regEnt1,regEnt2,regSal) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_pedido = $this->conexion->prepare($sql_pedido);
                $stmt_pedido->bind_param("sssssssssssssssssssssssssssss", $id_coti,$tipoequipo,$marca,$potencia,$unipotencia,$fases,$tensionpri,$neutroent,$neutrosal,$bornespri,$tensionsecun,$bornessec,$factorpot,$grupocon,$factor,$gradopro,$clase,$altitud,$montaje,$material,$altoficha,$anchoficha,$largoficha,$pesoficha,$conpri,$consec,$regEnt1,$regEnt2,$regSal);
                $stmt_pedido->execute();
        
                // Confirma la transacción
                $this->conexion->commit();

                $output = array(
                    'tipo' => 'success',
                    'mensaje' => 'Ficha registrada',
                    'redir' => true,
                );
                return $output;
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                throw new Exception("Error al registrar ficha: " . $e->getMessage());
            }
            // Cierra la conexión
            $this->conexion->close();
        }

        public function eliminar_ficha($id_ficha){
            // Inicia una transacción para garantizar la integridad referencial
            $this->conexion->begin_transaction();

            try {

                // Query para eliminar el usuario
                $sql_ficha = "DELETE FROM fichas_tec WHERE id_ficha = '$id_ficha'";
                $this->conexion->query($sql_ficha);

                // Confirma la transacción
                $this->conexion->commit();
                
                // Cierra la conexión
                $this->conexion->close();

                return '

                    <div class="alert alert-success" id="miAlert" role="alert">
                        Ficha eliminada correctamente.
                    </div>
                
                ';
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->conexion->rollback();
                return '

                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error al eliminar el ficha
                    </div>
                
                ';
            }

            // Cierra la conexión
            $this->conexion->close();
        }

        //En progreso
        public function editar_ficha($arrayFicha, $id_ficha){
            // Iniciar una transacción
            $this->conexion->begin_transaction();
            
            try {

                $sql1 = "UPDATE fichas_tec SET ";
                $columnas = array_keys($arrayFicha);
                foreach($arrayFicha as $key => $valor){
                    $sql1 .= "$key = ?, ";
                }

                $sql1 = rtrim($sql1, ', ');

                $sql1 .= " WHERE id_ficha = ?";
                
                $stmt1 = $this->conexion->prepare($sql1);
                $stmt1->bind_param("sssssssssssssssssssssssssssi", $arrayFicha['marca'],$arrayFicha['potencia'],$arrayFicha['unipotencia'],$arrayFicha['fases'],$arrayFicha['tensionpri'],$arrayFicha['neutroent'],$arrayFicha['neutrosal'],$arrayFicha['bornespri'],$arrayFicha['tensionsecun'],$arrayFicha['bornessec'],$arrayFicha['factorpot'],$arrayFicha['grupocon'],$arrayFicha['factor'],$arrayFicha['gradopro'],$arrayFicha['clase'],$arrayFicha['altitud'],$arrayFicha['montaje'],$arrayFicha['material'],$arrayFicha['altoficha'],$arrayFicha['anchoficha'],$arrayFicha['largoficha'],$arrayFicha['pesoficha'],$arrayFicha['conpri'],$arrayFicha['consec'],$arrayFicha['regEnt1'],$arrayFicha['regEnt2'],$arrayFicha['regSal'], $id_ficha);
                $stmt1->execute();
                $stmt1->close();

                // Confirmar la transacción
                $this->conexion->commit();

                // Cierra la conexión
                $this->conexion->close();
        
                return $arrayFicha['conpri'];
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

    