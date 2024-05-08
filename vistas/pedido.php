<?php

  include 'header.php';

?> 
    <div id="resultado"></div>
    <div id="alertaResultado"></div> 

<?php

    $id_coti = $_POST["id_coti"] ?? null;
    
    include '../modelo/clase_cotizacion.php';

    $coti_obj = new Cotizacion();

    try {
        list($resultado_coti, $resultado_prod) = $coti_obj->get_coti(
            $id_coti
        );

    } catch (Exception $e) {
        // Manejo de errores
        $errorOutput = ['error' => $e->getMessage()];
        echo json_encode($errorOutput, JSON_UNESCAPED_UNICODE);
        // Puedes loguear el error o realizar alguna otra acción de manejo aquí.
        // Evita mostrar mensajes específicos de error al usuario en un entorno de producción.
    }
    // Omitir etiqueta de cierre para evitar problemas con espacios en blanco no deseados.

    $row_coti = $resultado_coti->fetch_assoc();

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

    <a class="btn btn-primary" href="listacotizacion.php" style="margin-left: 100px">Volver</a>
<div class="container mt-5">
    <form id="formCoti" method="post" action="../control/registro_pedido.php" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="text-center mb-4">Generar Pedido</h2>
                    <!-- Lista de productos con checkbox -->
                    <div class="mb-3">
                        <h4>Productos</h4>
                        <?php
                            echo '<input type="hidden" value="'. $id_coti .'" name="id_coti"> ';
                            $num_rows = $resultado_prod->num_rows;
                            $item = 1;
                            if($row_coti['moneda'] == 'Dolares americanos'){
                                $simbolo = '$';                                
                            }else{
                                $simbolo = 'S/';
                            }
                            if ($num_rows > 0 ){//Verificamos que haya algun resultado
                                while($row_prod = $resultado_prod->fetch_assoc()){

                                    $total_todo = 0;

                                    $subtotal = $row_prod['cantidad'] * $row_prod['precio'];

                                    $porcentaje = $row_prod['descuento'] / 100;

                                    $total = $subtotal - ($subtotal * $porcentaje);

                                    $total_todo += $total;
                                    echo '

                                        <input type="hidden" value="'. $row_prod['id_coti_prod'] .'" name="id_productos[]">
                                        
                                        <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="'. $row_prod['id_coti_prod'] .'" id="item'. $row_prod['id_coti_prod'] .''.$item.'" name="elementos[]"> 
                                            <label class="form-check-label" for="item'. $row_prod['id_coti_prod'] .''.$item.'">
                                                '. $row_prod['cantidad'] .' und(s) - '. $row_prod['nombre_producto'] .' - '. $row_prod['descripcion'] .' - '. $simbolo .''. $total .'
                                            </label>
                                        </div>
                                        

                                    '; 
                                    $item++; 
                                } 
                            }
                        ?>
                        <!-- Agrega más productos según sea necesario -->
                    </div>

                    <div class="form-group">
                        <label for="orden">Orden de pedido:</label>
                        <input type="text" class="form-control readonly-input" readonly value="<?php echo $row_coti['razon_social']; ?>" id="nombrecliente" name="nombrecliente">                        
                    </div>

                    <!-- Cuadro de texto para el mensaje -->
                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="4"></textarea>
                    </div>

                    <!-- Campo para cargar el archivo PDF de la cotización -->
                    
                    <div class="mb-3">
                        
                        <h2>Adjuntar y Mostrar Archivos</h2>

                        <!-- Sección de Adjuntar Archivos -->
                        <div class="file-input-wrapper">
                            <input type="file" id="archivos" class="form-control" value="null" name="archivos[]" accept=".pdf, .jpg, .jpeg, .png" multiple>
                            <span>Seleccionar archivos</span>
                        </div>

                        <!-- Sección de Mostrar Archivos -->
                        <div class="selected-files">
                            <h3>Archivos Seleccionados:</h3>
                            <ul id="listaArchivos"></ul>
                        </div>
                    </div>

                    <!-- Botón de enviar -->
                    <button type="button" id="<?php echo $id_coti; ?>" class="btnEnviarPedido btn btn-primary" style="margin-left: 300px">Enviar pedidos</button>
                
            </div>
            
        </div>
    </form>
</div>

<script src='../static/js/createPedido.js?v=1.9' async></script>
<?php

    include 'footer.php';

?>