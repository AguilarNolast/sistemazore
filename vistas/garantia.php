<?php

  include 'header.php';

?>   
    <div id="resultado"></div>
    <div id="alertaResultado"></div> 
<div class="container-fluid">

 <h3 class="text-center">Listado de Garantias</h3>
 
     
<nav class="navbar navbar-light navbar-dark bg-white">
   <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaGarantia">
          Nueva Garantia
        </button>
    

        <!-- Modal -->
        <div class="modal fade" id="nuevaGarantia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <form method="POST" action="../control/registro_garantia.php" id="formGarantia">
            <div class="modal-content">
              <div class="modal-header text-center">
                <h4 class="modal-title">NUEVA GARANTIA</h4>
                <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="row modal-body mx-4">
                <div class="md-form mb-2">
                   <i class="grey-text">Nombre</i>
                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" required>
                </div>
                <div class="md-form mb-2">
                   <i class="grey-text">RUC</i>
                <input type="number" class="form-control" name="ruc" id="ruc" placeholder="RUC" required>
                </div>
                <div class="md-form mb-2">
                   <i class="grey-text">Fecha</i>
                <input type="date" class="form-control" name="fecha" id="fecha" placeholder="Fecha" required>
                </div>
                <div class="md-form mb-2">
                 <i class="grey-text">N° De factura</i>
                  <div class="input-group mb-3">
                      <span class="input-group-text">
                          FFF1
                      </span>
                      <input type="number" name="factura" id="factura" class="form-control" placeholder="Numero de Factura" required>
                  </div>
                </div>
                <div class="md-form mb-2">
                 <i class="grey-text">Orden de Compra</i>
                  <input type="text" name="oc" id="oc" class="form-control" placeholder="Orden de compra">
                </div>
                <div class="md-form mb-2">
                  <i class="grey-text">Tipo de equipo</i>
                  <select name="tipoequipo" id="tipoequipo" class="form-select" required>
                    <option value="trafomonotorre">Transformador monofasico torre</option>
                    <option value="trafomonorack">Transformador monofasico rack</option>
                    <option value="trafotritorre">Transformador trifásico torre</option>
                    <option value="trafotrirack">Transformador trifásico rack</option>
                    <option value="automonorack">Autotransformador monofasico rack</option>
                    <option value="automonotorre">Autotransformador monofasico torre</option>
                    <option value="autotritorre">Autotransformador trifásico torre</option>
                    <option value="autotrirack">Autotransformador trifásico rack</option>
                    <option value="estabimonotorre">Estabilizador con transformador monofasico torre</option>
                    <option value="estabimonorack">Estabilizador con transformador monofasico rack</option>
                    <option value="estabitritorre">Estabilizador con transformador trifásico torre</option>
                    <option value="estabitrirack">Estabilizador con transformador trifásico rack</option>
                    <option value="estabiautomonotorre">Estabilizador con autotransformador monofasico torre</option>
                    <option value="estabiautomonorack">Estabilizador con autotransformador monofasico rack</option>
                    <option value="estabiautotrifatorre">Estabilizador con autotransformador trifásico torre</option>
                    <option value="estabiautotrifarack">Estabilizador con autotransformador trifásico rack</option>
                    <option value="upsmono">UPS monofasico</option>
                    <option value="upstri">UPS trifasico</option>
                    <option value="upsmonorack">UPS monofasico rack</option>
                    <option value="upstrirack">UPS trifasico rack</option>
                </select>
                </div>
                <div class="md-form mb-2">
                   <i class="grey-text">Marca</i>
                <input type="text" class="form-control" name="marca" id="marca" placeholder="Marca" required>
                </div>
                <div class="md-form mb-2">
                  <i class="grey-text">Potencia</i>
                  <div class="input-group">
                      <input type="number" name="potencia" id="potencia" class="form-control" placeholder="Potencia" required>
                      <select name="unipotencia" id="unipotencia" class="form-select col-5">
                          <option>Kva</option>
                          <option>Va</option>
                          <option>Kw</option>
                          <option>W</option>
                      </select>
                  </div>
                </div>
                <div class="md-form mb-2">
                 <i class="grey-text">Modelo</i>
                  <input type="text" name="modelo" id="modelo" class="form-control" placeholder="Modelo" required>
                </div>
                <div class="md-form mb-2">
                 <i class="grey-text">Serie</i>
                  <input type="text" name="serie" id="serie" class="form-control" placeholder="Serie del producto" required>
                </div>
                <div class="md-form mb-2">
                  <div class="row">
                    <div class="input-group col-md-6">
                      <i class="grey-text">TVSS</i>
                      <input type="checkbox" name="tvss" id="tvss" class=" col-md-6" required>                    
                    </div>                   
                    <div class="input-group col-md-6">
                      <i class="grey-text">Manual</i>
                      <input type="checkbox" name="manual_v" id="manual_v" class=" col-md-6" required>                    
                    </div>                   
                  </div>
                </div>
              </div>
              <div class="modal-footer justify-content-center">
                <button  type="button"  onclick="registrarGarantia()" class="btn btn-primary" id="btnGarantia">Guardar</button>
              </div>
            </div>
            </form>
          </div>
        </div>
        
  <form class="form-inline">
    <input class="form-control mr-sm-2" type="search" placeholder="Buscar" id="campo" aria-label="Search">
  </form>
</nav>
      
  
<div class="table-responsive">
  <table class="table table-striped text-center table-hover table-borderless table-sm">
    <label for="num_registros">Mostrar: </label>

    <select name="num_registros" id="num_registros">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
    </select>

    <label for="num_registros">registros</label>
  <thead>
      <tr>
        <th scope="col">Razon social/DNI</th>
        <th scope="col">RUC</th>
        <th scope="col">Fecha</th>
        <th scope="col">Factura</th>
        <th scope="col">OC</th>
        <th scope="col">Descargar</th>
      </tr>
    </thead>
    <tbody class="table-group-divider" id="contenido">
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
   <script src='../static/js/ajax_listado_garantia.js?v=2.2' async></script>
   
    <?php

        include 'footer.php';

    ?>