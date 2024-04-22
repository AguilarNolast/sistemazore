<?php

    require_once "../modelo/clase_usuario.php"; //Llamo a la clase cliente
    require "../modelo/clase_config.php"; //Llamo a la clase

    //Uso de la libreria PHPmailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require '../vendor/autoload.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];

        $usuario_obj = new Usuario();

        try {
            //Verificar que el Email exista en la base de datos
            $resultado = $usuario_obj->verificarEmail(
                $email
            );
            
            $num_rows = $resultado->num_rows; 

            //Obtener datos de configuracion
            $config_obj = new Config();

            list($resultado_correo, $resultado_servidor) = $config_obj->getConfig(); 

            $rowServidor = $resultado_servidor->fetch_assoc();

            while($rowCorreo = $resultado_correo->fetch_assoc()){
                if($rowCorreo['tipo'] == 'sistema'){
                    $emisor = $rowCorreo['correo'];
                }
            }

            if ($num_rows > 0) {
                
                $row = $resultado->fetch_assoc();

                $id_usuario = $row['id_usuario'];

                // Genera un token único para la recuperación de contraseña
                $token = bin2hex(random_bytes(32));

                $resultado = $usuario_obj->updateToken(
                    $token,
                    $id_usuario
                );

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
                $mail->Username     = $emisor;
                $mail->Password     = $rowServidor['clave'];
            
                // Quien envía este mensaje
                $mail->setFrom($emisor, 'Sistema Zore');
        
                // Si queremos una dirección de respuesta
                $mail->addReplyTo($emisor, 'Sistema Zore');
            
                // Destinatario
                $mail->addAddress($email, 'Usuario');
            
                // Asunto del correo
                $mail->Subject = 'Recuperar clave - Sistema Zore';
        
                // Contenido
                $mail->IsHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->ContentType = 'text/html';
                $mail->Body = <<<HTML
                    <html>
                        <head>
                            <style>
                                /* Estilos opcionales para mejorar la apariencia del correo */
                                body {
                                    font-family: 'Arial', sans-serif;
                                    background-color: #f4f4f4;
                                    margin: 0;
                                    padding: 20px;
                                }
                                .container {
                                    max-width: 600px;
                                    margin: 0 auto;
                                    background-color: #fff;
                                    padding: 20px;
                                    border-radius: 10px;
                                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                                }
                                .button {
                                    display: inline-block;
                                    padding: 10px 20px;
                                    background-color: #007bff;
                                    color: #ffffff;
                                    text-decoration: none;
                                    border-radius: 5px;
                                }
                            </style>
                        </head>
                        <body>
                            <div class='container'>
                                <p>Hola,</p>
                                <p>Recibiste este correo porque has solicitado restablecer tu contraseña.</p>
                                <p>Para completar el proceso, haz clic en el siguiente botón:</p>
                                <!--<a class='button' href='http://localhost:3000/vistas/restorepass.php?x=$token&y=$id_usuario' target='_blank'>Restablecer Contraseña</a>-->
                                <a class='button' href='https://grupozore.com/sistemazore/vistas/restorepass.php?x=$token&y=$id_usuario' target='_blank'>Restablecer Contraseña</a>
                                <p>Si no solicitaste restablecer tu contraseña, ignora este correo.</p>
                            </div>
                        </body>
                    </html>
                HTML;
                
                // Texto alternativo
                $mail->AltBody = 'Recuperar clave - Sistema Zore';     
            
                // Enviar el correo
                if (!$mail->send()) {
                    throw new Exception($mail->ErrorInfo);
                }

                $output = array(
                    'tipo' => 'success',
                    'mensaje' => 'Correo enviado, revise su bandeja',
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                
            } else {
                $output = array(
                    'tipo' => 'danger',
                    'mensaje' => 'No se encontró ninguna cuenta asociada a ese correo electrónico.',
                );
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
            }
                
        } catch (Exception $e) {
            $output = array(
                'tipo' => 'danger',
                'mensaje' => 'error' + $e->getMessage(),
            );
            echo json_encode($output, JSON_UNESCAPED_UNICODE);
        }
    } else {
        // Redirige a la página de recuperación de contraseña si se accede directamente a este script sin enviar datos POST
        header("Location: ../vistas/recuperarclave.php");
        exit();
    }
?>