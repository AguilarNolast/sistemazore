<?php

  include 'header.php';

?> 
<link rel="stylesheet" href='../static/css/coti.css' type="text/css">

    <div id="resultado"></div>
    <div id="alertaResultado"></div> 

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
              <button  type="button" onclick="registrarCliente2()" class="btn btn-primary">Guardar</button>
            </div>
          </div>
          </form>
        </div>
      </div>
    
    <a class="btn btn-primary" href="listacotizacion.php" style="margin-left: 100px">Volver</a>
<div class="container mt-5">
    <h2 class="text-center mb-4">Formulario de Cotización</h2>
    <div id="alertaResultado"></div>
    
    <form action="../control/registro_coti.php" id="formCoti" method="post">
        <input type="hidden" name="idcliente" id="idcliente">
        
        <div class="row">
            <!-- Información del Cliente (izquierda) -->
            <div class="col-md-6">
                <h4>Información del Cliente</h4>
                <div class="form-group">
                    <label for="ruc">RUC:</label>
                    <div class="input-group mb-3">
                        <input type="text" id="input_cliente" class="form-control" id="ruc" placeholder="Ingrese el RUC o DNI del cliente" autocomplete="off" required>
                        <span class="input-group-text" id="basic-addon1"  data-bs-toggle="modal" data-bs-target="#nuevoCli">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                            </svg>
                        </span>
                    </div>
                    <div class="contenedor">
                        <div class="lista-overlay" id="lista-overlay">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="razonSocial">Razón Social:</label>
                    <input type="text" class="form-control" id="razonSocial" placeholder="Ingrese la razón social del cliente" required readonly>
                </div>

                <div class="form-group">
                    <label for="contacto">Persona de Contacto:</label>
                    <select onchange="mostrarContacto(this.value)" class="form-select" name="input_id_contacto" id="contacto">
                        <option>---</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="telefonoCliente">Teléfono del Cliente:</label>
                    <input type="tel" id="telefono" readonly class="form-control" placeholder="">
                </div>

                <div class="form-group">
                    <label for="correoCliente">Correo del Cliente:</label>
                    <input type="email" id="correo" readonly class="form-control" placeholder="">
                </div>

                <!--<div class="form-group">
                    <label for="tipocambio">Tipo de cambio:</label>
                    <div class="input-group mb-3">
                        <input type="number" id="tipocambio" readonly class="form-control" placeholder="" maxlength="4"> 
                        <span class="input-group-text" id="spantc"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                            </svg>
                        </span>
                    </div>      
                </div>-->
            </div>

            <!-- Información del Asesor (derecha) -->
            <div class="col-md-6">
                <h4>Información del Asesor</h4>
                <div class="form-group">
                    <label for="asesor">Asesor:</label>
                    <input type="text" class="form-control" value="<?php echo $_SESSION['nombre_user']; ?>" id="asesor" placeholder="Nombre del asesor" readonly>
                </div>
                <div class="form-group">
                    <label for="telefonoAsesor">Teléfono del Asesor:</label>
                    <input type="tel" class="form-control" id="telefonoAsesor" value="<?php echo $_SESSION['tlf_user']; ?>" placeholder="Ingrese el número de teléfono del asesor" readonly>
                </div>

                <div class="form-group">
                    <label for="correoAsesor">Correo del Asesor:</label>
                    <input type="email" class="form-control" id="correoAsesor" value="<?php echo $_SESSION['correo_user']; ?>" placeholder="Ingrese el correo del asesor" readonly>
                </div>

                <div class="form-group">
                    <label for="">Moneda</label>
                    <select name="moneda" id="moneda" class="form-select">
                        <option>Dolares americanos</option>
                        <option>Soles</option>
                    </select>
                </div>

                <div class="form-group" id="divPago">
                    <label>Metodo de pago</label>
                    <div class="input-group mb-3">
                        <select name="pago" id="pago" class="form-select">
                            <option>Contado</option>
                            <option>Credito 30 dias</option>
                            <option>Credito 45 dias</option>
                            <option>Credito 60 dias</option>
                            <option>Credito 90 dias</option>
                            <option>Cheque 30 dias</option>
                            <option>Adelanto 50%, Saldo al finalizar</option>
                        </select>
                        <span class="input-group-text" id="" onclick="tipingPago()" data-bs-toggle="modal" data-bs-target="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="form-group" id="divEntrega">
                    <label for="">Tiempo de entrega</label>
                    <div class="input-group mb-3">
                        <select name="tiempo" id="tiempo" class="form-select">
                            <option>Stock</option>
                            <option>3-5 dias utiles</option>
                            <option>5-7 dias utiles</option>
                            <option>7-9 dias utiles</option>
                            <option>10-12 dias utiles</option>
                            <option>12-15 dias utiles</option>
                            <option>15-20 dias utiles</option>
                        </select>
                        <span class="input-group-text" id="" onclick="tipingEntrega()" data-bs-toggle="modal" data-bs-target="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalles de la Cotización -->
        <h4 class="mt-4 mb-3">Detalles de la Cotización</h4>

        <div class="form-row">
            <!--<div class="form-group d-none d-md-table-cell col-md-">
                <label for="item">Item:</label>
            </div>-->
            <div class="form-group d-none d-lg-table-cell col-md-1">
                <label for="cantidad">Cantidad:</label>
            </div>
            <div class="form-group d-none d-lg-table-cell col-md-3">
                <label for="precio">Producto:</label>
            </div>
            <div class="form-group d-none d-lg-table-cell col-md-4">
                <label for="descripcion">Descripción:</label>
            </div>
            <div class="form-group d-none d-lg-table-cell col-md-1">
                <label for="precio">Precio:</label>
            </div>
            <div class="form-group d-none d-lg-table-cell col-md-1">
                <label for="precio">Descuento:</label>
            </div>
            <div class="form-group d-none d-lg-table-cell col-md-1">
                <label for="precio">Total:</label>
            </div>
        </div>

        <input type="hidden" value="1" id="sig_item">

        <div  id="cont_items">
            <div class="form-row" id="item1">
                <!--<div class="form-group col-md-1">
                    <label  class="form-control num_item" for="">1</label>
                </div>-->
                <div class="form-group col-sm-12 col-md-12 col-lg-1">
                    <input type="number" min="1" class="form-control" oninput="formatearNumero(this)" onkeyup="total_producto(this.id)" value="1" id="cantidad1" name="cantidad[]" placeholder="Cant" required>
                    
                </div>
                <div class="form-group col-sm-12 col-md-12 col-lg-3">
                    <input type="text" class="prod form-control" onkeyup="getProducto(this.id)" onchange="getProducto(this.id)" id="pro1" placeholder="Producto" autocomplete="off" required>
                    <input type="hidden" name="idproducto[]" id="idproducto1">
                    <div class="contenedor row">
                        <div class="lista-overlayPro" id="producto_lista1">
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-12 col-md-12 col-lg-4">
                    <textarea class="form-control" id="descripcion1" name="descripcion[]" rows="4" placeholder="Describa el producto" required></textarea>
                </div>
                <div class="form-group col-sm-12 col-md-12 col-lg-1">
                    <input type="number" class="form-control" id="precio1"name="precio[]" onkeyup="total_producto(this.id)" placeholder="Precio unitario" required>
                </div>
                <div class="form-group col-sm-12 col-md-12 col-lg-1">
                    <input type="number" class="form-control" onkeyup="total_producto(this.id)" value="0" id="descuento1" name="descuento[]" placeholder="Descuento">
                </div>
                <div class="form-group col-sm-12 col-md-12 col-lg-1">
                    <input type="number" class="form-control" id="total_producto1" readonly placeholder="Total">
                </div>
                <div class="form-group col-sm-12 col-md-12 col-lg-1">
                    <button type="button" class="btn btn-primary btn-block" onclick="eliminar_Item(this.id)" id="btnitem1">
                        X
                    </button>
                </div>
            </div>
        </div>
        
        <button type="button" onclick="addItem()" class="btn btn-primary">
            Añadir
        </button>

        <!-- Sumatoria General -->
        <h4 class="mt-4 mb-3" style="">Sumatoria General</h4>

        <div class="form-group" style="">
          <table class="table">
            <tr>
              <th scope="col"><label for="sumatoria">Sub Total:</label></th>
              <th scope="col"><input type="text" class="form-control col-sm-11" id="subtotal" placeholder="Sub Total" readonly></th>
            </tr>
            <tr>
              <th scope="col"><label for="sumatoria">IGV:</label></th>
              <th scope="col"><input type="text" class="form-control col-sm-11" id="igv" placeholder="IGV" readonly></th>
            </tr>
            <tr>
              <th scope="col"><label for="sumatoria">Total General:</label></th>
              <th scope="col"><input type="text" class="form-control col-sm-11" id="totalgeneral" placeholder="Total general" readonly></th>
            </tr>
        </table>
        </div>
        <div class="justify-content-center">
        <button type="button" id="btnEnviar" onclick="registrarCoti()" class="btn btn-primary">Generar Cotización</button>
        </div>
    </form>
    <br>
</div>
<script src='../static/js/createCoti.js?v=1.0' async></script>
<?php

    include 'footer.php';

?>
