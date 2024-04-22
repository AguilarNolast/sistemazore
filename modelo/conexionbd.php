<?php

    Class Conexion{

        protected $conexion;

        public function __construct(){
            $this->conexion = new mysqli('localhost', 'root', '', 'zore');
            //$this->conexion = new mysqli('127.0.0.1:3306', 'u514925568_adzore', 'tXvEIj@8', 'u514925568_siszore');
            
            if($this->conexion->connect_errno){
                echo "Fallo al conectar a Mysql: " . $this->conexion->connect_error;

                return;
            }

            $this->conexion->set_charset("utf8");
        }

    }

?>