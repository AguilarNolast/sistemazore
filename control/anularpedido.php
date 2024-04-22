<?php

    require_once "../modelo/clase_cotizacion.php"; //Llamo a la clase
    require_once "../modelo/clase_pedidos.php"; //Llamo a la clase
    require_once "../modelo/clase_config.php"; //Llamo a la clase

    //Uso de la libreria PHPmailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require '../vendor/autoload.php';

    session_start();

    //Variables de sesion
    $correouser = $_SESSION["correo_user"];
    
    //Variables recibidas por POST
    $motivo = $_POST["motivo"] ?? '';
    $id_pedido = $_POST["id_pedido"]  ?? null;
    $nombrecliente = $_POST["nombrecliente"]  ?? null;
    $correlativo = $_POST["correlativo"]  ?? null;

    $pedido = new Pedidos();

    try{
        
        //Obtener datos de configuracion y envio de correos
        $config_obj = new Config();

        list($resultado_correo, $resultado_servidor) = $config_obj->getConfig(); 

        $rowServidor = $resultado_servidor->fetch_assoc();

        $correosCopia = [];

        while($rowCorreo = $resultado_correo->fetch_assoc()){
            if($rowCorreo['tipo'] == 'principal'){
                $correopri = $rowCorreo['correo'];
                $nombrepri = $rowCorreo['nombre'];
            }elseif($rowCorreo['tipo'] == 'copia'){
                $correosCopia[] = $rowCorreo['correo'];
                $nombresCopia[] = $rowCorreo['nombre'];
            }
        }
        $correosConNombres = array_combine($correosCopia, $nombresCopia);

        // Liberar recursos después de usarlos
        $resultado_correo->free_result();

        //Instancia de la clase PHPmailer
        $mail = new PHPMailer(true);

        // Intancia de PHPMailer
        $mail                = new PHPMailer();
    
        // Es necesario para poder usar un servidor SMTP como gmail
        $mail->isSMTP();
    
        // Si estamos en desarrollo podemos utilizar esta propiedad para ver mensajes de error
        //SMTP::DEBUG_OFF    = off (for production use) 0
        //SMTP::DEBUG_CLIENT = client messages 1 
        //SMTP::DEBUG_SERVER = client and server messages 2
        $mail->SMTPDebug     = 0;
    
        //Set the hostname of the mail server
        $mail->Host          = $rowServidor['servidor'];
        $mail->Port          = 465; // o 587
    
        // Propiedad para establecer la seguridad de encripción de la comunicación
        $mail->SMTPSecure    = PHPMailer::ENCRYPTION_SMTPS; // tls o ssl para gmail obligado
    
        // Para activar la autenticación smtp del servidor
        $mail->SMTPAuth      = true;

        // Credenciales de la cuenta  
        $email = $correouser;
        $mail->Username     = $email;
        $mail->Password     = $rowServidor['clave'];
    
        // Quien envía este mensaje
        $mail->setFrom($email, $correouser);

        // Si queremos una dirección de respuesta
        $mail->addReplyTo($email, $correouser);
    
        // Destinatario
        // Agregar destinatario principal
        //$mail->addAddress('petteraac@gmail.com', 'Pedro Aguilera');
        /*$mail->addAddress($correopri, $nombrepri);

        // Agregar destinatarios con copia
        foreach ($correosConNombres as $correo => $nombre) {
            // $correo contendrá el correo y $nombre contendrá el nombre
            $mail->addCC($correo, $nombre);
        }*/

        // Asunto del correo
        $mail->Subject = 'Pedido anulado - ' . $nombrecliente;
        
        if($motivo == ''){
            $motivo = 'Estimados, este pedido ha sido anulado';
        }

        // Contenido
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Body    = $motivo;
    
        // Texto alternativo
        $mail->AltBody = 'Pedido anulado - ' . $nombrecliente;

        //Adjuntar cotizacion
        if(isset($_FILES['pdf'])){
            if ($_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
                $mail->addStringAttachment(file_get_contents($_FILES['pdf']['tmp_name']), $correlativo . '-' . $nombrecliente . '.pdf');
            }
        }

        // Enviar el correo
        if (!$mail->send()) {
            throw new Exception($mail->ErrorInfo);
        }

        //Proceso de anulacion de pedido

        $resultado = $pedido->anularPedido($id_pedido); 
    
        return $resultado;
    
    } catch (Exception $e) {
        $output = array(
            'tipo' => 'danger',
            'mensaje' => 'Error al anular pedido ' . $e,
        );
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
    }

?>