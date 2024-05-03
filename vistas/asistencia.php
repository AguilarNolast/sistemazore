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
    <form action="../control/excelasistencia.php">
      <button type="submit" target="_blank" class="btn btn-primary">
        Generar Excel
      </button>
    </form>
    <form class="form-inline">
      <input class="form-control mr-sm-2" type="hidden" placeholder="Buscar" id="campo" aria-label="Search">
    </form> 
</nav>
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