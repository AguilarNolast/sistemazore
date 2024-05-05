<?php

  include 'header.php';

?>
<div class="container">
<br>
 <h3 class="text-center">Control de asistencia</h3>
 <br>

 <div id="resultado"></div>
    <div id="alertaResultado"></div> 
  
<nav class="navbar navbar-light navbar-dark bg-white">
    <button type="button" onclick="registrarEntrada(<?php echo $_SESSION['id_usuario']; ?>)" class="btn btn-success" data-bs-toggle="modal" data-bs-target="">
      Registrar entrada
    </button>
    <button type="button" onclick="registrarSalida(<?php echo $_SESSION['id_usuario']; ?>)" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="">
      Registrar salida
    </button>
    <form class="form-inline">
      <input class="form-control mr-sm-2" type="hidden" placeholder="Buscar" id="campo" aria-label="Search">
    </form> 
</nav>
    <?php
      if($_SESSION["tipo"] == 'admin'){
        echo <<<HTML
          <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
              <a class="navbar-brand" href="#">Filtrar</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarFilter" aria-controls="navbarFilter" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarFilter">
                <form id="formFilterUsuario" method="POST" action="../control/ajax_filtrar_asistencia.php">
                  <ul class="navbar-nav me-auto mb-2 mb-lg-0 justify-content-center">
                    <li class="nav-item">
                      <input type="date" class="form-control" id="dateIn" name="dateIn" disabled require>
                    </li>
                    <li class="nav-item">
                      <input type="date" class="form-control" id="dateOut" name="dateOut" disabled require>
                    </li>
                    <li class="nav-item">
                      <select class="form-select" name="selectUser" id="selectUser" require>
                        
                      </select>
                    </li>
                    <li class="nav-item">
                      <input type="button" onclick="filtrarAsistencia()" value="Filtrar" class="form-control">
                    </li>
                    <li class="nav-item">
                      <div class="form-check-inline form-switch"> 
                        <input class="form-check-input" type="checkbox" role="switch" id="checkDateAsistencia" checked>
                        <label class="form-check-label" for="checkDateAsistencia">Todas las fechas</label>
                      </div>
                    </li>
                    <li class="nav-item">
                      <button type="button" class="btn btn-info mb-3" onclick="eliminarFiltroAsistencia()">
                        Borrar
                      </button>
                    </li>
                    </li>
                    <li class="nav-item">
                      <button type="button" id="excelAsist" class="btn btn-primary">
                        Generar Excel
                      </button>
                    </li>
                  </ul>
                </form>
              </div>
            </div>
          </nav>
        HTML;
      }
    ?>
<br>
  <label for="num_registros">Mostrar: </label>

  <select name="num_registros" id="num_registros">
      <option value="10">10</option>
      <option value="25">25</option>
      <option value="50">50</option>
  </select>

  <label for="num_registros">registros</label>

      <br>
  <div class="table-responsive">
    <table class="table table-striped text-center">
      <thead>
        <tr>
          <th scope="col" scope="row">Nombre y apellido</th>
          <th scope="col">Usuario</th>
          <th scope="col">Fecha</th>
          <th scope="col">Hora entrada</th>
          <th scope="col">Hora salida</th>
          <th scope="col">Tiempo de trabajo</th>
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
        <input type="hidden" id="queListado" value="clientes">
    </div>
   </div>
   
   <script src='../static/js/ajax_listado_asistencia.js?v=1.9' async></script>
<?php

  include 'footer.php';

?>