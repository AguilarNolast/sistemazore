<?php

  include 'header.php';
  if($_SESSION["tipo"] != 'admin'){
    header("location: inicio.php");
  }

?>
<div class="container">
<br>
 <h3 class="text-center">Listado de Usuarios</h3>
 <br>

  <div id="resultado"></div>
  <div id="alertaResultado"></div> 
  
 <nav class="navbar navbar-light navbar-dark bg-white">
    <!-- Button trigger modal -->
         <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoUser">
           Nuevo usuario
         </button>
 
         <!-- Modal -->
         <div class="modal fade" id="nuevoUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
             <div class="modal-dialog" role="document">
             <form method="POST" action="../control/registrar_usuario.php" id="formUser">
             <div class="modal-content">
               <div class="modal-header text-center">
                 <h4 class="modal-title">REGISTRO DE USUARIO</h4>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body mx-4">
                 <div class="md-form mb-4">
                   <i class="grey-text">Nombre</i>
                   <input type="text" class="form-control validate" name="nombre" placeholder="Nombre" required>
                 </div>
                 <div class="md-form mb-4">
                   <i class=" grey-text">Apellido</i>
                   <input type="text" class="form-control validate" name="apellido" placeholder="Apellido" required>
                 </div>
                 <div class="md-form mb-4">
                   <i class="grey-text">Usuario</i>
                   <input type="text" class="form-control validate" name="usuario" placeholder="Usuario" required>
                 </div>
                 <div class="md-form mb-4">
                   <i class="grey-text">Correo electronico</i>
                   <input type="email" name="correo" class="form-control validate" placeholder="Correo electronico" required>
                 </div>
                 <div class="md-form mb-4">
                   <i class="grey-text">Telefono</i>
                   <input type="text" class="form-control validate" name="telefono" placeholder="Telefono" required>
                 </div>
 
                 <div class="input-group md-form mb-4">
                    <span class="input-group-text"><i class="fas fa-lock prefix grey-text"></i></span> 
                    <input type="password" onkeyup="compararClave()" class="form-control validate" name="clave" id="clave" placeholder="Contraseña" required>        
                    <span class="input-group-text" id="clave_span">

                    </span>
                 </div>

                 <div class="input-group mb-3">
                  <span class="input-group-text"><i class="fas fa-lock prefix grey-text"></i></span>
                  <input type="password" onkeyup="compararClave()" class="form-control validate" id="rep_clave" placeholder="Repita contraseña" aria-describedby="basic-addon2" required>        
                  <span class="input-group-text" id="rep_clave_span">
                    
                  </span>
                </div>
               </div>
               <div class="modal-footer justify-content-center">
                 <button  type="button" onclick="registrarUsuario()" class="btn btn-primary">Guardar</button>
               </div>
             </div>
             </form>
           </div>
         </div>
         
   <form class="form-inline">
     <input class="form-control mr-sm-2" type="text" placeholder="Buscar" aria-label="Search"  id="campo">
   </form>
 </nav>

      <br>
  <div class="table-responsive">
    <table class="table table-striped text-center">
      <thead>
      <label for="num_registros">Mostrar: </label>

      <select name="num_registros" id="num_registros">
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
      </select>

      <label for="num_registros">registros</label>
        <tr>
          <th class="sort asc" scope="col" scope="row">Nombre</th>
          <th class="sort asc" scope="col">Apellido</th>
          <th class="sort asc" scope="col">Usuario</th>
          <th class="sort asc" scope="col">Correo</th>
          <th class="sort asc" scope="col">Telefono</th>
          <th scope="col">Opciones</th>
        </tr>
      </thead>
      <tbody id="contenido">
      </tbody>
    </table>
  </div>
    <div>
        <label for="" id="lbl-total"></label>

        <div id="nav-paginacion"></div>

        <input type="hidden" id="pagina" value="1">
        <input type="hidden" id="orderCol" value="0">
        <input type="hidden" id="orderType" value="asc">
        <input type="hidden" id="queListado" value="usuarios">
    </div>
   </div>
   
   <script src='../static/js/ajax_listado_usuario.js?v=1.9' async></script>

<?php

    include 'footer.php';

?>