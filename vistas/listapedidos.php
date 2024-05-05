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
                    <form id="formFilterPedido" method="POST" action="../control/ajax_filtrar_pedido.php">
                      <ul class="navbar-nav me-auto mb-2 mb-lg-0 justify-content-center">
                        <li class="nav-item">
                          <input type="date" class="form-control" id="dateIn" name="dateIn" require>
                        </li>
                        <li class="nav-item">
                          <input type="date" class="form-control" id="dateOut" name="dateOut" require>
                        </li>
                        <li class="nav-item">
                          <select class="form-select" name="selectUser" id="selectUser" require>
                            
                          </select>
                        </li>
                        <li class="nav-item">
                          <input type="button" onclick="filtrarPedido()" value="Filtrar" class="form-control">
                        </li>
                        <li class="nav-item">
                          <div class="input-group mb-3">
                              <span class="input-group-text" id="basic-addon1"  data-bs-toggle="modal" data-bs-target="#nuevoCli">
                                  S/
                              </span>
                              <input type="text" onclick="" id="solesFil" value="" class="form-control" readonly>
                          </div>
                        </li>
                        <li class="nav-item">
                          <div class="input-group mb-3">
                              <span class="input-group-text" id="basic-addon1"  data-bs-toggle="modal" data-bs-target="#nuevoCli">
                                  $ 
                              </span>
                              <input type="text" onclick="" id="dolarFil" value="" class="form-control" readonly>
                          </div>
                        </li>
                        <li class="nav-item">
                          <button type="button" class="btn btn-info mb-3" onclick="eliminarFiltroPedidos()">
                            Borrar
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
    <script src='../static/js/ajax_listado_pedidos.js?v=1.9' async></script>

    <?php

        include 'footer.php';

    ?>