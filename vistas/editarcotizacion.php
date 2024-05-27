<?php

  include 'header.php';

?> 
    <link rel="stylesheet" href='../static/css/coti.css' type="text/css">
    <a class="btn btn-primary" href="listacotizacion.php" style="margin-left: 100px">Volver</a>
<div class="container mt-5">
    <h2 class="text-center mb-4">Editar Cotización</h2>
    <div id="resultado"></div>
    <div id="alertaResultado"></div> 

    <?php

        require "../modelo/clase_cotizacion.php"; //Llamo a la clase
        require "../modelo/clase_clientes.php"; //Llamo a la clase

        $id_coti= $_POST['id_coti'];

        $coti_obj = new Cotizacion();

        list($resultado_coti, $resultado_prod) = $coti_obj->get_coti($id_coti);

        $row = $resultado_coti->fetch_assoc();

        $cliente_obj = new Clientes();

        $resultado_contacto = $cliente_obj->listado_contacto($row['id_clientes']);

    ?>
    
    <form action="../control/editar_coti.php" id="formCoti" method="post">
        <?php

            echo <<<HTML
                <input type="hidden" value="{$id_coti}" name="idcoti" id="idcoti">
            HTML;

        ?>
        
        <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo generateCSRFToken(); ?>">
        
        <div class="row">
            <!-- Información del Cliente (izquierda) -->
            <div class="col-md-6">
                <h4>Información del Cliente</h4>
                <div class="form-group">
                    <label for="ruc">RUC:</label>
                    <input type="text" value="<?php echo $row['ruc']; ?>" class="form-control" id="ruc" placeholder="" required readonly>
                </div>

                <div class="form-group">
                    <label for="razonSocial">Razón Social:</label>
                    <input type="text" class="form-control" id="razonSocial" placeholder="" value="<?php echo $row['razon_social']; ?>" required readonly>
                </div>
                
                <div class="form-group">
                    <label for="contacto">Persona de Contacto:</label>
                    <select onchange="mostrarContacto(this.value)" class="form-select" name="id_contacto" id="id_contacto">
                        <?php
                            while($row_contacto = $resultado_contacto->fetch_assoc()){ 

                                echo '<option ';
                                
                                if($row_contacto['id_contacto'] == $row['id_contacto']){
                                    echo 'selected';
                                }
                                
                                echo <<<HTML
                                    value="{$row_contacto['id_contacto']}">{$row_contacto['nombre']}</option>
                                HTML;
                                
                            }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="telefonoCliente">Teléfono del Cliente:</label>
                    <input type="tel" id="telefono" readonly class="form-control" placeholder="" value="<?php echo $row['tlf_contacto']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="correoCliente">Correo del Cliente:</label>
                    <input type="email" id="correo" readonly class="form-control" placeholder="" value="<?php echo $row['correo_contacto']; ?>" required>
                </div>

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
                            <option <?php if($row['moneda'] == 'Dolares americano'){echo 'selected';} ?>>Dolares americanos</option>
                            <option <?php if($row['moneda'] == 'Soles'){echo 'selected';} ?>>Soles</option>
                        </select>
                </div>

                <div class="form-group" id="divPago">
                    <label>Metodo de pago</label>
                    <div class="input-group mb-3">
                        <?php
                            $arraypago = [
                                'Contado' => 'Contado',
                                'Credito 30 dias' => 'Credito 30 dias',
                                'Credito 45 dias' => 'Credito 45 dias',
                                'Credito 60 dias' => 'Credito 60 dias',
                                'Credito 90 dias' => 'Credito 90 dias',
                                'Cheque 30 dias' => 'Cheque 30 dias',
                            ];
                            $pagoSelect = $row['metodo_pago'];
                            $isSelected = false;
                            foreach ($arraypago as $clave => $valor) {
                                $isSelected = ($pagoSelect === $clave) ? true : $isSelected;
                            }

                            if(!$isSelected){
                                echo <<<HTML
                                    <input type="text" class="form-control" value="{$row['metodo_pago']}" name="pago" id="pago" placeholder="Ingrese el metodo de pago" maxlength="50">
                                    <span class="input-group-text" id="" onclick="selectPago()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                        </svg>
                                    </span>
                                HTML;
                            }else{
                                echo <<<HTML
                                    <select name="pago" id="pago" class="form-select">
                                HTML;

                                foreach ($arraypago as $clave => $valor) {
                                    $selected2 = ($pagoSelect === $clave) ? 'selected' : '';
                                    echo <<<HTML
                                        <option value="{$clave}" {$selected2}>{$valor}</option>
                                    HTML;
                                                
                                }

                                echo <<<HTML
                                    </select>
                                    <span class="input-group-text" id="" onclick="tipingPago()" data-bs-toggle="modal" data-bs-target="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                        </svg>
                                    </span>
                                HTML;
                            }
                        ?>
                    </div>
                </div>
                
                <div class="form-group" id="divEntrega">
                    <label for="">Tiempo de entrega</label>
                    <div class="input-group mb-3">
                        <select name="tiempo" id="tiempo" class="form-select">
                            <option <?php if($row['tiempo_entrega'] == 'Stock'){echo 'selected';} ?>>Stock</option>
                            <option <?php if($row['tiempo_entrega'] == '3-5 dias'){echo 'selected';} ?>>3-5 dias utiles</option>
                            <option <?php if($row['tiempo_entrega'] == '5-7 dias'){echo 'selected';} ?>>5-7 dias utiles</option>
                            <option <?php if($row['tiempo_entrega'] == '7-9 dias'){echo 'selected';} ?>>7-9 dias utiles</option>
                            <option <?php if($row['tiempo_entrega'] == '10-12 dias'){echo 'selected';} ?>>10-12 dias utiles</option>
                            <option <?php if($row['tiempo_entrega'] == '12-15 dias'){echo 'selected';} ?>>12-15 dias utiles</option>
                            <option <?php if($row['tiempo_entrega'] == '15-20 dias'){echo 'selected';} ?>>15-20 dias utiles</option>
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
            <!--<div class="form-group col-md-">
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

        <?php

            $items = 1;

            echo '<div  id="cont_items">';

            while($row2 = $resultado_prod->fetch_assoc()){
                echo <<<HTML

                    <div class="form-row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-1">
                            <input type="number" min="1" class="cantidad form-control" onkeyup="totalP(this)" value="{$row2['cantidad']}" name="cantidad[]" placeholder="Cant" required>
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-3">
                            <select class="form-control product-list">
                                <option>Selecciona un producto</option>
                            </select> 
                            <input type="hidden" value="{$row2['id_productos']}" class="idproducto" name="idproducto[]">
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-4">
                            <textarea class="descripcion form-control" name="descripcion[]" rows="4" placeholder="Describa el producto" required>{$row2['descripcion']}</textarea>
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-1">
                            <input type="number" class="precio form-control" onkeyup="totalP(this)" value="{$row2['precio']}" name="precio[]" placeholder="Precio unitario" required>
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-1">
                            <input type="number" class="descuento form-control" onkeyup="totalP(this)" value="{$row2['descuento']}" name="descuento[]" placeholder="Descuento">
                        </div>
                        
                HTML;
                        $total_todo = 0;

                        $subtotal = $row2['cantidad'] * $row2['precio'];

                        $porcentaje = $row2['descuento'] / 100;

                        $total = $subtotal - ($subtotal * $porcentaje);

                        $total_todo += $total;

                echo <<<HTML

                        <div class="form-group col-sm-12 col-md-12 col-lg-1">
                            <input type="text" class="total_producto form-control" value="{$total}" readonly placeholder="total" required>
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-1">
                            <button type="button" class="btn btn-primary btn-block" onclick="eliminar_Item(this.id)">
                                X
                            </button>
                        </div>
                    </div>

                HTML;
                $items++;
            }

            $item_show = $items - 1;
            
            echo <<<HTML
                </div>
                <input type="hidden" value="$item_show" id="sig_item">
            HTML;

        ?>
        
        <button type="button" onclick="addItem()" class="btn btn-primary">
            Añadir
        </button>

        <!-- Sumatoria General -->
        <h4 class="mt-4 mb-3" style="">Sumatoria General</h4>

        <div class="form-group" style="">
          <table class="table">
            <tr>
              <th scope="col"><label for="sumatoria">Sub Total:</label></th>
              <th scope="col"><input type="text" class="form-control col-sm-11" value="" id="subtotal" placeholder="Sub Total" readonly></th>
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
        <button type="button" id="btnEnviar" onclick="editarCoti()" class="btn btn-primary">Editar Cotización</button>
        </div>
    </form>
    <br>
</div>
<script src='../static/js/createCoti.js?v=2.1' async></script>
<script>
    $(document).ready(function() {
        $('.product-list').each(function() {
            var listaProd = $(this);
            iniciarSelect2Edit(listaProd);
        });
    });
</script>
<?php

    include 'footer.php';

?>
