<?php
    //Uso de la libreria PHPmailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require '../vendor/autoload.php';

    require_once '../modelo/clase_pedidos.php';
    require_once "../modelo/clase_config.php"; //Llamo a la clase

    session_start();

    $fecha_hoy = date('Y-m-d') ?? null;

    //Variables de sesion
    $correouser = $_SESSION["correo_user"];
    $nombre_user = $_SESSION["nombre_user"];

    //Variables recibidas por POST
    $mensaje = $_POST["mensaje"] ?? '';
    $mensaje = nl2br(htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'));//Convertir los caracteres de salto de lines a formato HTML
    $correlativo = $_POST["correlativo"] ?? null;
    $nombrecliente = $_POST["nombrecliente"] ?? null;
    $id_coti = $_POST["id_coti"] ?? null;
    $elementos = $_POST["elementos"] ?? array();
    $id_productos = $_POST["id_productos"] ?? array();

    try {

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

        // Verificar si se enviaron archivos
        if ($_FILES && isset($_FILES['archivos'])) {
        
            $uploadedFiles = $_FILES['archivos'] ?? null;

            // Ruta donde deseas guardar los archivos
            $uploadPath = '../static/files/';

            $url_archivo = [];
            //Extensiones permitidas
            $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'xlsx', 'xls'];

            // Iterar sobre cada archivo recibido
            for ($i = 0; $i < count($uploadedFiles['name']); $i++) {
                $filename = $uploadedFiles['name'][$i];
                $filename = preg_replace("/[^a-zA-Z0-9._-]/", "", $filename);
                $tmpFilePath = $uploadedFiles['tmp_name'][$i];
                $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                // Verificar la extensión del archivo
                if (!in_array($fileExtension, $allowedExtensions)) {
                    $output = array(
                        'tipo' => 'danger',
                        'mensaje' => 'Tipo de archivo no permitido.',
                    );
                    echo json_encode($output, JSON_UNESCAPED_UNICODE);
                    exit;
                }

                $destPath = $uploadPath . $filename;

                // Construir la ruta completa de destino
                array_push($url_archivo, $destPath);

                // Mover el archivo al destino
                move_uploaded_file($tmpFilePath, $destPath);
            }
        }else{
            $url_archivo = [];
        }
        
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
        $mail->setFrom($email, $nombre_user);

        // Si queremos una dirección de respuesta
        $mail->addReplyTo($email, $nombre_user);
    
        // Destinatario
        // Agregar destinatario principal
        $mail->addAddress($correopri, $nombrepri);
        //$mail->addAddress('petteraac@gmail.com', 'Pedro');

        // Agregar destinatarios con copia
        foreach ($correosConNombres as $correo => $nombre) {
            // $correo contendrá el correo y $nombre contendrá el nombre
            $mail->addCC($correo, $nombre);
        }

        // Asunto del correo
        $mail->Subject = 'Orden de Pedido - ' . $nombrecliente;
        
        if($mensaje == ''){
            $mensaje = 'Estimados, por favor proceder con este pedido';
        }

        // Contenido
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Body    = $mensaje;
    
        // Texto alternativo
        $mail->AltBody = 'Orden de Pedido - ' . $nombrecliente;

        if ($_FILES && isset($_FILES['archivos'])) {
            // Agregar algún adjunto
            foreach($url_archivo as $archivo){
                $mail->addAttachment($archivo);
            }        
        }

        //Adjuntar cotizacion
        if ($_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
            $mail->addStringAttachment(file_get_contents($_FILES['pdf']['tmp_name']), $correlativo . '-' . $nombrecliente . '.pdf');
        }
    
        // Enviar el correo
        if (!$mail->send()) {
            throw new Exception($mail->ErrorInfo);
        }
        
        $pedido_obj = new Pedidos();

        $resultado = $pedido_obj->registrar_pedido(
            $id_coti,
            $mensaje,
            $url_archivo,
            $elementos,
            $fecha_hoy
        );
        
    } catch (Exception $e) {
        $output = array(
            'tipo' => 'danger',
            'mensaje' => 'Error al enviar correo de pedido' . $e,
        );
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
    }
?>