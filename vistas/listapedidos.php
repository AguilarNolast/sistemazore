<?php

  include 'header.php';

?>
    <div id="resultado"></div>
    <div id="alertaResultado"></div> 
<div class="container">
<br>
 <h3 class="text-center">Listado de Pedidos</h3>
 <br>
     
       <nav class="navbar navbar-light navbar-dark bg-white">

          <form class="form-inline">
            <input class="form-control mr-sm-2" type="text" placeholder="Buscar" aria-label="Search"  name="campo" id="campo">
          </form>
        </nav>
      <br>
  <div class="table-responsive">
    <table class="table table-striped text-center">
      <label for="num_registros">Mostrar: </label>

      <select name="num_registros" id="num_registros">
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
      </select>

      <label for="num_registros">registros</label>
      <thead>
        <tr>
          <th class="sort asc" scope="col" scope="row">N° Pedido</th>
          <th class="sort asc" scope="col">Cliente</th>
          <th class="sort asc" scope="col">Asesor</th>
          <th class="sort asc" scope="col">Fecha</th>
          <th class="" scope="col">Monto(Sin IGV)</th>
          <th class="sort asc" scope="col">Opciones</th>
          <!--<th class="sort asc" scope="col">Monto</th>-->
        </tr>
      </thead>
      <tbody id="contenido">
        <tr>
        </tr>
      </tbody>
    </table>
  </div>


    <div>
        <label for="" id="lbl-total"></label>

        <div id="nav-paginacion"></div>

        <input type="hidden" id="pagina" value="1">
        <input type="hidden" id="orderCol" value="0">
        <input type="hidden" id="orderType" value="asc">
        <input type="hidden" id="queListado" value="productos">
    </div>
   </div>
    <script src='../static/js/ajax_listado_pedidos.js?v=1.0' async></script>

    <?php

        include 'footer.php';

    ?>