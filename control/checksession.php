<?php
    session_start();

    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['usuario']) || time() > $_SESSION['expire_time']) {
        $output = array(
            'redir' => true,
        );
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
    }else{
        $output = array(
            'redir' => false,
        );
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
    }
?>