<?php

    require_once "../modelo/clase_usuario.php"; //Llamo a la clase cliente

    // Verificar si se recibieron las variables por POST
    if (isset($_POST['clave'], $_POST['rep_clave'], $_POST['token'])) {
        
        // Obtener las variables por POST
        $clave = $_POST['clave'];
        $rep_clave = $_POST['rep_clave'];
        $token = "";
        $id_usuario = $_POST['id_usuario'];

        // Verificar si las claves son iguales
        if ($clave === $rep_clave) {

            $usuario_obj = new Usuario();

            $hashed_password = password_hash($clave, PASSWORD_DEFAULT);

            $resultado = $usuario_obj->updatePass(
                            $hashed_password,
                            $token,
                            $id_usuario
                        );

            if ($resultado == true) {
                $output = array(
                    'tipo' => 'success',
                    'mensaje' => 'Clave actualizada con éxito.',
                    'redir' => true,
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            } else {
                $output = array(
                    'tipo' => 'danger',
                    'mensaje' => 'Error al actualizar la clave: ',
                    'redir' => false,
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            }

        } else {
            $output = array(
                'tipo' => 'danger',
                'mensaje' => 'Las claves no coinciden.',
            );
            echo json_encode($output, JSON_UNESCAPED_UNICODE);
        }

    } else {
        $output = array(
            'tipo' => 'danger',
            'mensaje' => 'Error: No se recibieron todas las variables necesarias por POST.',
        );
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
    }

?>