<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Error</title>
    <link rel="stylesheet" href='../static/css/error.css' type="text/css">
</head>
<body>
<?php
    // En error.php
    $mensaje = isset($_GET['mensaje']) ? urldecode($_GET['mensaje']) : "Error desconocido";
    $submensaje = isset($_GET['submensaje']) ? urldecode($_GET['submensaje']) : "";
?>
    <div class="error-container">
        <div class="error-code"><?php echo $mensaje; ?></div>
        <div class="error-message"><?php echo $submensaje; ?></div>
        <a href="/" class="back-to-home">Volver a la página principal</a>
    </div>
</body>
</html>
