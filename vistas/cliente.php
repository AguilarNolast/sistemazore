<?php

  include 'header.php';

?>
<div class="container">
<br>
 <h3 class="text-center">Listado de clientes</h3>
 <br>
        
  <div id="resultado"></div>
  <div id="alertaResultado"></div> 
  
  <nav class="navbar navbar-light navbar-dark bg-white">
   <!-- Button trigger modal -->
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoCli">
        Nuevo cliente
      </button>

      <!-- Modal -->
      <div class="modal fade" id="nuevoCli" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
          <form  method="post" action="../control/registrar_cliente.php">
          <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo generateCSRFToken(); ?>">
          <div class="modal-content">
            <div class="modal-header text-center">
              <h4 class="modal-title">REGISTRO DE CLIENTE</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body mx-4">
              <div class="row justify-content-center">
              <div class="md-form mb-2">
               <i class="grey-text">RUC/DNI</i>
                <input type="text" id="numero" class="form-control"  placeholder="RUC / DNI" required>
              </div>
              <div class="md-form mb-2">
               <i class="grey-text">Razon social / Nombre</i>
                <input type="text" id="entidad" class="form-control" placeholder="Razon social / Nombre y apellido" required>
              </div><br><br>
              <div class="md-form mb-2 row">
                <i class="grey-text col">Dirección</i>
                <i class="grey-text col">Distrito</i>
                <i class="grey-text col">Departamento</i>
              </div>
              <div class="md-form mb-2 row">
                <input type="text" id="direccion" class="form-control col-4" placeholder="Dirección" required="">
                <input type="text" id="distrito" class="form-control col-4" placeholder="Distrito" required="">
                <input type="text" id="departamento" class="form-control col-4" placeholder="Departamento" required="">
              </div>
              <div class="md-form mb-2">
               <i class="grey-text">Tipo de cliente</i>
                  <select name="tipocliente" id="tipocliente" class="form-select">
                      <option>Distribuidor</option>
                      <option>Cliente final</option>
                  </select>
              </div><br><br>
              <div class="md-form mb-2">
               <i class="grey-text">Tipo de pago</i>
                <div class="input-group" id="inputpago"  onselectstart="return false;">
                  <select name="pagocliente" id="pagocliente" class="form-select">
                      <option>Contado</option>
                      <option>Credito 30 dias</option>
                      <option>Credito 45 dias</option>
                      <option>Credito 60 dias</option>
                      <option>Credito 90 dias</option>
                      <option>Cheque 30 dias</option>
                      <option>Adelanto 50%, Saldo al finalizar</option>
                  </select>
                  <span class="input-group-text" onclick="tipearPago()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                      <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                      <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>
                  </span> 
                </div>
              </div>
            </div>
                Contacto
                <div id="cont_contactos">
                  <div class="cont" id="cont1">
                    <div class="row justify-content-center">
                      <div class="md-form mb-2">
                       <i class="grey-text">Nombre</i>
                        <input type="text" class="form-control nombre" name="nombre[]" id="nombre1" placeholder="Nombre y apellido" required>
                      </div>
                      <div class="md-form mb-2">
                       <i class="grey-text">Telefono</i>
                        <input type="text" class="form-control telefono" name="telefono[]" id="telefono1" placeholder="Telefono" required>
                      </div>
                      <div class="md-form mb-2">
                       <i class="grey-text">Correo electronico</i>
                        <input type="text" class="form-control correo" name="correo[]" id="correo1" placeholder="Correo electronico">
                      </div>
                      <div class="md-form mb-2">
                       <i class="grey-text">Cargo</i>
                        <input type="text" class="form-control cargo" name="cargo[]" id="cargo1" placeholder="Cargo" required>
                      </div>
                      <div class="md-form mb-2">
                        <button type="button" class="btn btn-primary" onclick="removeContacto(1)" id="removecontacto">x</button>
                      </div>
                    </div>
                  </div>
                </div>
              <br>
              <div class=" justify-content-center md-form">
                <button  type="button"  onclick="añadirContacto()" id="addcontacto"  class="btn btn-primary">Agregar</button>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button  type="button" onclick="registrarCliente()" class="btn btn-primary">Guardar</button>
            </div>
          </div>
          </form>
        </div>
      </div>
        
  <form class="form-inline">
    <input class="form-control mr-sm-2" type="text" placeholder="Buscar" aria-label="Search" id="campo">
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
  <div class="table-responsive">
    <table class="table table-striped text-center">
      <thead>
        <tr>
          <th class="sort asc" scope="col" scope="row">RUC / DNI</th>
          <th class="sort asc" scope="col">Razon social</th>
          <th class="sort asc" scope="col">Dirección</th>
          <th class="sort asc" scope="col">Asesor</th>
          <th class="sort asc" scope="col">Contacto</th>
          <th class="sort asc" scope="col">Opciones</th>
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

  <script src='../static/js/ajax_listado_cliente.js?v=1.9' async></script>
<?php

  include 'footer.php';

?>