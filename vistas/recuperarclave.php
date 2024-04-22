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
    <title>Recuperar clave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href='../static/css/index.css' type="text/css">
</head>
<body>

    <div id="resultado"></div>
    <div id="alertaResultado"></div> 

<div class="container login-container">
    <h2>Recuperar clave</h2>
    <form class="login-form" id="miFormulario" action="../control/recuperarclave.php" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
        <div class="mb-3">
            <label for="username" class="form-label">Ingrese su correo:</label>
            <input type="email" id="email" class="form-control" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary" id="btnEnviar">Enviar</button>
    </form>

    <a href="vistas/recuperarclave.php"></a>
</div>

<!-- Scripts de Bootstrap y Popper.js (asegúrate de incluir Popper.js antes de Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<?php

    include 'footer.php';

?>
