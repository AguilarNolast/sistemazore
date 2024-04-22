<?php
    session_start();

    function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href='../static/css/index.css' type="text/css">
</head>
<body>
    <div id="resultado"></div>
    <div id="alertaResultado"></div> 
<?php
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Verifica si se proporcionó un token válido en la URL
        if (isset($_GET['x'])) {

            require_once "../modelo/clase_usuario.php"; //Llamo a la clase cliente

            $token = $_GET['x'];

            $id_usuario = $_GET['y'];

            $usuario_obj = new Usuario();

            $resultado = $usuario_obj->verificarToken(
                            $token,
                            $id_usuario
                        );

            $row = $resultado->fetch_array();

            $num_rows = $resultado->num_rows;

            if($num_rows > 0){
                // Muestra el formulario para restablecer la contraseña
                echo '
                    <div class="container login-container">
                    <h2>Recuperar clave</h2>
                    <form class="login-form" id="formReset" action="../control/resetpass.php" method="post">
                    <input type="hidden" name="token" value="'. $token .'">
                    <input type="hidden" name="id_usuario" value="'. $id_usuario .'">
                        <div class="mb-3">
                            <label for="username" class="form-label">Contraseña:</label>
                            <input type="password" id="clave" class="form-control" name="clave" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Repita contraseña:</label>
                            <input type="password" id="rep_clave" class="form-control" name="rep_clave" required>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="enviarFormulario()" id="btnEnviar">Modificar</button>
                    </form>
                    </div>
                ';
            }else{
                $mensaje = "Token invalido.";
                header("Location: error.php?mensaje=" . urlencode($mensaje));
                exit();  
            }
        } else {
            $mensaje = "Token invalido.";
            header("Location: error.php?mensaje=" . urlencode($mensaje));
            exit();            
        }
    }else{
        header("Location: ../index.php");
        exit();
    }
?>

<!-- Scripts de Bootstrap y Popper.js (asegúrate de incluir Popper.js antes de Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<?php

    include 'footer.php';

?>
