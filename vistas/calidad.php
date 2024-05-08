<?php

  include 'header.php';

?>
   <script>
  

//export default generatePDFPedro
</script>
   
    <div id="resultado"></div>
    <div id="alertaResultado"></div> 
<div class="container-fluid">

 <h3 class="text-center">Listado de Certificado de Calidad</h3>
 
     
<nav class="navbar navbar-light navbar-dark bg-white">
   <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaCalidad">
          Nuevo Certificado
        </button>
    

        <!-- Modal -->
        <div class="modal fade" id="nuevaCalidad" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <form method="POST" action="../control/registro_calidad.php" id="formCalidad">
            <div class="modal-content">
              <div class="modal-header text-center">
                <h4 class="modal-title">NUEVO CERTIFICADO DE CALIDAD</h4>
                <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="row modal-body mx-4">
                <div class="md-form mb-2">
                   <i class="grey-text">Razon Social</i>
                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Razon Social" required>
                </div>
                <div class="md-form mb-2">
                   <i class="grey-text">RUC</i>
                <input type="text" class="form-control" name="ruc" id="ruc" placeholder="RUC" required>
                </div>
                <div class="md-form mb-2">
                    <i class="grey-text">Direccion</i>
                    <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Direccion" required>
                </div>
                <div class="md-form mb-2">
                  <i class="grey-text">Fecha de Fabricacion</i>
                  <input type="date" id="fecha_fab" name="fecha_fab" class="form-control" placeholder="Fecha de Fabricacion" required>
                </div>
                <div class="md-form mb-2">
                  <i class="grey-text">Tipos de Equipos</i>
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
                    </select>
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
                   <i class="grey-text">Factor</i>
                    <select name="factor" id="factor" class="form-select" required>
                        <option>K1</option>
                        <option>K4</option>
                        <option>K13</option>
                        <option>K20</option>
                    </select>
                </div>
                <div class="md-form mb-2">
                 <i class="grey-text">Marca</i>
                  <input type="text" id="marca" name="marca" class="form-control" placeholder="Marca" required>
                </div>
                <div class="md-form mb-2">
                 <i class="grey-text">Serie</i>
                  <input type="text" id="serie" name="serie" class="form-control" placeholder="Serie del producto" required>
                </div>
              </div>
              <div class="modal-footer justify-content-center">
                <button  type="button"  onclick="registrarCalidad()" id="btnCalidad" class="btn btn-primary">Guardar</button>
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
        <th scope="col">Razon social</th>
        <th scope="col">RUC</th>
        <th scope="col">Tipo</th>
        <th scope="col">Serie</th>
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
   <script src='../static/js/ajax_listado_calidad.js?v=1.9' async></script>
   
    <?php

        include 'footer.php';

    ?>