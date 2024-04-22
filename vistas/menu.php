<?php
  
  include '../control/comprueba_sesion.php';
  require "../modelo/clase_usuario.php"; //Llamo a la clase usuario
  require "../modelo/clase_config.php"; //Llamo a la clase

  $id_usuario = $_SESSION["id_usuario"];

  $usuario_obj = new Usuario();

  $resultado = $usuario_obj->getUser( 
      $id_usuario
  ); 

  $rowUser = $resultado->fetch_assoc();

  $config_obj = new Config();

  list($resultado_correo, $resultado_servidor) = $config_obj->getConfig(); 

  $rowServidor = $resultado_servidor->fetch_assoc();

  // Lógica para mostrar el tiempo restante en la sesión
  $remainingTime = $_SESSION['expire_time'] - time();
?>
<nav class="navbar navbar-expand-lg  navbar-dark bg-primary">
  <div class="container-fluid">
    <a href="inicio.php"> <img src="img/logo-grupozore.png" alt="logo"> </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 justify-content-center">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="listacotizacion.php">Cotizacion</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="listapedidos.php">Pedidos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="producto.php">Producto</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="cliente.php">Cliente</a>
        </li>
        <?php
          if($_SESSION["tipo"] == 'admin'){
            echo '
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="listausuario.php">Usuario</a>
              </li>
            ';
          }
        ?>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="asistencia.php">Asistencia</a>
        </li>
        <?php
          if($_SESSION["tipo"] == 'admin'){
            echo <<<HTML
              <li class="nav-item dropdown">
                <a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  PostVenta
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="garantia.php">Certificado de Garantia</a></li>
                  <li><a class="dropdown-item" href="calidad.php">Certificado de Calidad</a></li>
                  <li><a class="dropdown-item" href="pruebas.php">Protocolo de Pruebas</a></li>
                </ul>
              </li>
            HTML;
          }
        ?>
        
      </ul>

      <?php
          if($_SESSION["tipo"] == 'admin'){
            echo '
              <button type="button" class="btn btn-primary d-flex" data-bs-toggle="modal" data-bs-target="#config">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                  <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                  <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                </svg>
              </button>

              <div class="modal fade" id="config" tabindex="-1" aria-labelledby="configModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <form action="../control/updateconfig.php" id="formConfig" method="POST">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="configModalLabel">Configuración de Correos y Servidor</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                          ';
                                $numero = 1;
                                while($rowCorreo = $resultado_correo->fetch_assoc()){
                                  if($rowCorreo['tipo'] == 'principal'){
                                    echo '
                                      <div class="row g-3 align-items-center">
                                        <div class="col-4">
                                          <label class="col-form-label">Correo principal:</label>
                                        </div>
                                        <div class="col-8">
                                          <input type="email" class="form-control" name="correos[]" value="'. $rowCorreo['correo'] .'" aria-describedby="">
                                          <input type="hidden" class="form-control" name="id_correos[]" value="'. $rowCorreo['id_correo'] .'">
                                        </div>
                                      </div>
                                      <div class="row g-3 align-items-center">
                                        <div class="col-4">
                                          <label class="col-form-label">Nombre:</label>
                                        </div>
                                        <div class="col-8">
                                          <input type="text" class="form-control" name="nombres[]" value="'. $rowCorreo['nombre'] .'" aria-describedby="">
                                        </div>
                                      </div>
                                      <br>
                                    ';
                                  }elseif($rowCorreo['tipo'] == 'copia'){
                                    echo '
                                      <div class="row g-3 align-items-center">
                                        <div class="col-4">
                                          <label class="col-form-label">Correo copia '. $numero .':</label>
                                        </div>
                                        <div class="col-8">
                                          <input type="email" class="form-control" name="correos[]" value="'. $rowCorreo['correo'] .'" aria-describedby="">
                                          <input type="hidden" class="form-control" name="id_correos[]" value="'. $rowCorreo['id_correo'] .'">
                                        </div>
                                      </div>
                                      <div class="row g-3 align-items-center">
                                        <div class="col-4">
                                          <label class="col-form-label">Nombre:</label>
                                        </div>
                                        <div class="col-8">
                                          <input type="text" class="form-control" name="nombres[]" value="'. $rowCorreo['nombre'] .'" aria-describedby="">
                                        </div>
                                      </div>
                                      <br>
                                    ';
                                    $numero++;
                                  }else{
                                    echo '
                                      <div class="row g-3 align-items-center">
                                        <div class="col-4">
                                          <label class="col-form-label">Correo recuperacion</label>
                                        </div>
                                        <div class="col-8">
                                          <input type="email" class="form-control" name="correos[]" value="'. $rowCorreo['correo'] .'" aria-describedby="">
                                          <input type="hidden" class="form-control" name="id_correos[]" value="'. $rowCorreo['id_correo'] .'">
                                        </div>
                                      </div>
                                      <div class="row g-3 align-items-center">
                                        <div class="col-4">
                                          <label class="col-form-label">Nombre:</label>
                                        </div>
                                        <div class="col-8">
                                          <input type="text" class="form-control" name="nombres[]" value="'. $rowCorreo['nombre'] .'" aria-describedby="">
                                        </div>
                                      </div>
                                      <br>
                                    ';
                                  }
                                }
                 
                          echo '    
                              <div class="row g-3 align-items-center">
                                <div class="col-4">
                                  <label class="col-form-label">Servidor:</label>
                                </div>
                                <div class="col-8">
                                  <input type="text" class="form-control" name="servidor" value="'. $rowServidor['servidor'] .'" aria-describedby="">
                                </div>
                              </div>
                              <div class="row g-3 align-items-center">
                                <div class="col-4">
                                  <label class="col-form-label">Clave:</label>
                                </div>
                                <div class="col-8">
                                  <input type="text" class="form-control" name="clave" value="'. $rowServidor['clave'] .'" aria-describedby="">
                                </div>
                              </div>
      
                          </div>
                          <div class="modal-footer">
                              <button type="button" id="btnConfig" onclick="updateConfig()" class="btn btn-primary">Guardar Configuración</button>
                          </div>
                      </div>
                    </form>
                  </div>
              </div>
            ';
          }
      ?>
      
       <button type="button" class="btn btn-primary d-flex" data-bs-toggle="modal" data-bs-target="#perfil"><i class="fas fa-user prefix grey-text"></i></button>
        <!-- Modal -->

        <div class="modal fade" id="perfil" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <form  method="POST" action="../control/editar_usuario.php" id="formPerfil">
              <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title">EDITAR USUARIO</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mx-4">
                 <div class="md-form mb-4">
                   <i class="grey-text">Nombre</i>
                   <input type="text" class="form-control validate" name="nombreperfil" id="nombreperfil" value="<?php echo $rowUser['nombres']; ?>" placeholder="Nombre" required>
                 </div>
                 <div class="md-form mb-4">
                   <i class=" grey-text">Apellido</i>
                   <input type="text" class="form-control validate" name="apellidoperfil" id="apellidoperfil" value="<?php echo $rowUser['apellidos']; ?>" placeholder="apellido" required>
                 </div>
                 <div class="md-form mb-4">
                   <i class="grey-text">Usuario</i>
                   <input type="text" class="form-control validate" name="usuarioperfil" id="usuarioperfil" value="<?php echo $rowUser['usuario']; ?>" placeholder="Usuario" required>
                 </div>
                 <div class="md-form mb-4">
                   <i class="grey-text">Correo electronico</i>
                   <input type="email" name="correoperfil" id="correoperfil" class="form-control validate" value="<?php echo $rowUser['correo']; ?>" placeholder="Correo electronico" required>
                 </div>
                 <div class="md-form mb-4">
                   <i class="grey-text">Telefono</i>
                   <input type="text" class="form-control validate" name="telefonoperfil" id="telefonoperfil" value="<?php echo $rowUser['telefono']; ?>" placeholder="Telefono" required>
                 </div>
 
                 <div class="input-group md-form mb-4">
                      <span class="input-group-text"><i class="fas fa-lock prefix grey-text"></i></span> 
                      <input type="password" onkeyup="compararClave3()" class="form-control validate" name="claveperfil" id="claveperfil" placeholder="Contraseña" required>        
                      <span class="input-group-text" id="clave_spanperfil">

                      </span>
                  </div>

                  <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-lock prefix grey-text"></i></span>
                    <input type="password" onkeyup="compararClave3()" class="form-control validate" name="rep_claveperfil" id="rep_claveperfil" placeholder="Repita contraseña" aria-describedby="basic-addon2" required>        
                    <span class="input-group-text" id="rep_clave_spanperfil">
                        
                    </span>
                  </div>
               </div>
               <div class="modal-footer justify-content-center">
                    <button  type="button" onclick="editarPerfil(<?php echo $id_usuario; ?>)" class="btn btn-primary">Guardar</button>
                </div>
              </div>
            </form>
            </div>
        </div>

      <div class="d-flex" role="Cerrar">
        <a href="../control/cerrar_sesion.php"  class="form-control btn">Cerrar sesion</a>
      </div>
    </div>
  </div>
</nav>