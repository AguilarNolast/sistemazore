
    <?php

        require_once "validatetoken.php";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $csrf_token = $_POST['csrf_token'];

            // Validar el token CSRF
            if (!validateCSRFToken($csrf_token)) {
                $output = array(
                    'tipo' => 'danger',
                    'mensaje' => 'Error CSRF: Token no válido.',
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            }

            require "../modelo/clase_usuario.php"; //Llamo a la clase usuario

            $usuario = $_POST["usuario"] ?? null;
    
            $clave = $_POST["clave"] ?? null;

            $mantenerSesion = $_POST["mantenerSesion"] ?? null;
            
            $user_class = new Usuario();
    
            $resultado = $user_class->get_login($usuario); 
    
            //$numero_registro = $resultado->num_rows;
            
            $usuario_res = $resultado->fetch_array();

            $hashed_password = $usuario_res['clave'];

            if(password_verify($clave, $hashed_password)){

                session_destroy();
    
                if (isset($_POST['mantenerSesion']) && $_POST['mantenerSesion'] == 'on') {
                    $tiempo_vida = 3600 * 24 * 30;
                } else {
                    $tiempo_vida = 3600 * 24;
                }

                session_start();
    
                $_SESSION["usuario"]=$usuario_res['usuario'];
                $_SESSION["id_usuario"]=$usuario_res['id_usuario'];
                $_SESSION["nombre_user"]=$usuario_res['nombres'] . " " . $usuario_res['apellidos'];
                $_SESSION["tlf_user"]=$usuario_res['telefono'];
                $_SESSION["correo_user"]=$usuario_res['correo'];
                $_SESSION["tipo"]=$usuario_res['tipo'];
                $_SESSION['expire_time'] = time() + $tiempo_vida;
    
                generateCSRFToken();            
                
                $output = array(
                    'tipo' => 'success',
                    'mensaje' => 'Sesion iniciada',
                    'redir' => true,
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            }else{
                $output = array(
                    'tipo' => 'danger',
                    'mensaje' => 'Datos incorrectos',
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            }
        }

    ?>