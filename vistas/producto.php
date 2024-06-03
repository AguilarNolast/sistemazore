<?php

  include 'header.php';

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

<div class="container-fluid">

 <h3 class="text-center">Listado de Productos</h3>
 
        
  <div id="resultado"></div>
  <div id="alertaResultado"></div> 
  <div id="edicion"></div>
  <div id="eliminacion"></div>
  
  <nav class="navbar navbar-light navbar-dark bg-white">
   <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoProd">
          Nuevo Producto
        </button>
    

        <!-- Modal -->
        <div class="modal fade" id="nuevoProd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <form>
            <div class="modal-content">
              <div class="modal-header text-center">
                <h4 class="modal-title">NUEVO PRODUCTO</h4>
                <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="row modal-body mx-4">
                <div class="md-form mb-2">
                   <i class="grey-text">Producto</i>
                <input type="text" class="form-control" id="nombre" placeholder="Producto">
                </div>
                <div class="md-form mb-2">
                 <i class="grey-text">Descripción</i>
                  <textarea class="form-control" id="descripcion" rows="4" placeholder="Descripción"></textarea>
                </div>
                <div class="md-form mb-2">
                 <i class="grey-text">Precio</i>
                  <input type="number" id="precio" class="form-control" placeholder="Precio">
                </div>
                <div class="md-form mb-2">
                 <i class="grey-text">Alto</i>
                  <input type="text" id="alto" class="form-control" placeholder="Alto">
                </div>
                <div class="md-form mb-2">
                 <i class="grey-text">Ancho</i>
                  <input type="text" id="ancho" class="form-control" placeholder="Ancho">
                </div>
                <div class="md-form mb-2">
                 <i class="grey-text">Largo</i>
                  <input type="text" id="largo" class="form-control" placeholder="Largo">
                </div>

                <div class="md-form mb-2">
                 <i class="grey-text">Peso</i>
                  <input type="text" id="peso" class="form-control" placeholder="Peso">        
                  </div>
              </div>
              <div class="modal-footer justify-content-center">
                <button  type="submit"  onclick="registrarProducto()" class="btn btn-primary">Guardar</button>
              </div>
            </div>
            </form>
          </div>
        </div>
        
  <form class="form-inline">
    <input class="form-control mr-sm-2" type="search" placeholder="Buscar" id="campo" aria-label="Search">
  </form>
</nav>

<label for="num_registros">Mostrar: </label>

<select name="num_registros" id="num_registros">
    <option value="10">10</option>
    <option value="25">25</option>
    <option value="50">50</option>
</select>

<label for="num_registros">registros</label>

      
  <div class="table-responsive">
    <table class="table table-striped text-center table-hover table-borderless table-sm">
      <thead>
        <tr>
          <th scope="col" scope="row" class="col-2 sort asc">Producto</th>
          <th scope="col" class="sort asc">Descripcion</th>
          <th scope="col" class="col-1 sort asc">Precio</th>
          <th scope="col" class="col-1 sort asc">Peso</th>
          <th scope="col"class="col-1">Medidas</th>
          <th scope="col" class="col-2">Opciones</th>
        </tr>
      </thead>
      <tbody class="table-group-divider" id="contenido">
        <tr>
        <tr>    
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
   <script src='../static/js/ajax_listado_productos.js?v=2.2' async></script>

    <?php

        include 'footer.php';

    ?>