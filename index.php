<?php
    session_start();

    require_once("control/comprueba_index.php");

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
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href='static/css/index.css' type="text/css">
    <link rel="stylesheet" href='static/css/general.css' type="text/css">
</head>
<body>

<div id="alertaResultado"></div>

<div class="container login-container">
    <h2>Iniciar Sesión</h2>
    <form class="login-form" id="formLogIn" action="control/comprueba_usuario.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
        <div class="mb-3">
            <label for="username" class="form-label">Usuario:</label>
            <input type="text" id="username" class="form-control" name="usuario" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" id="password" class="form-control" name="clave" required>
        </div>
        <button type="button" onclick="logIn()" id="btnEnviar" class="btn btn-primary">Iniciar Sesión</button>
        
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="mantenerSesion" name="mantenerSesion">
            <label class="form-check-label" for="mantenerSesion">Mantener sesión iniciada</label>
        </div>
    </form>
    

    <a href="vistas/recuperarclave.php" class="">Recuperar clave</a>
</div>

<footer style="position: fixed; bottom: 0; right: 0; margin: 10px;">
    <p>Versión 1.16.19</p>
</footer>

<!-- Scripts de Bootstrap y Popper.js (asegúrate de incluir Popper.js antes de Bootstrap) -->
<script src='static/js/general.js?v=1.7' async></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
