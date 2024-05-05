<?php

    //Agregamos el limite

    $limit = isset($_POST['registros']) ? $_POST['registros'] : 10; //Dato que viene de la vista para hacer el limite
    $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 0; 

    $dateIn = $_POST["dateIn"] ?? null;
    $dateOut = $_POST["dateOut"] ?? null;
    $selectUser = $_POST["selectUser"] ?? null;

    require_once "../modelo/clase_pedidos.php"; //Llamo a la clase
    require_once "../modelo/clase_cotizacion.php"; //Llamo a la clase

    $pedido = new Pedidos();
    list($resultado, $resProd, $totalFiltro, $totalRegistros, $columns) = $pedido->filtro_pedidos($limit, $pagina, $dateIn, $dateOut, $selectUser);

    $num_rows = $resultado->num_rows; 

    //Mostrar resultados
    $output = [];
    $output['totalRegistros'] = $totalRegistros;
    $output['totalFiltro'] = $totalFiltro;
    $output['data'] = '';
    $output['paginacion'] = '';
    $output['soles'] = 0;
    $output['dolares'] = 0;

    if(!empty($resProd)){
        while($rowProd = $resProd->fetch_array()){
            if($rowProd['moneda'] == 'Soles'){
                $subtotal = $rowProd['cantidad'] * $rowProd['precio'];
    
                $descuento = $rowProd['descuento'] / 100;
    
                $subtotal_und = $subtotal - ($subtotal * $descuento);
    
                $output['soles'] += $subtotal_und;
            }else{
                $subtotal = $rowProd['cantidad'] * $rowProd['precio'];
    
                $descuento = $rowProd['descuento'] / 100;
    
                $subtotal_und = $subtotal - ($subtotal * $descuento);
    
                $output['dolares'] += $subtotal_und;
            }
        }
    }

    $coti_obj = new Cotizacion();

    if ($num_rows > 0 ){//Verificamos que haya algun resultado
        while($row = $resultado->fetch_assoc()){ 

            list($resultado_coti, $resultado_prod) = $coti_obj->getpdfcotipedido($row['id_coti'], $row['id_pedidos']);

            $row_coti = $resultado_coti->fetch_assoc();

            if($row_coti['moneda'] == 'Dolares americanos'){
                $simbolo = '$';
            }else{
                $simbolo = 'S/';
            }

            $fecha = date("d-m-Y", strtotime($row['fecha']));
        
            $total_todo = 0;

            while($row_prod = $resultado_prod->fetch_assoc()){

                $subtotal = $row_prod['cantidad'] * $row_prod['precio'];

                $porcentaje = $row_prod['descuento'] / 100;

                $total = $subtotal - ($subtotal * $porcentaje);

                $total_todo += $total;
            
            }

            $total_monto = $simbolo . ''. number_format($total_todo, 2, ',', '.');

            $output['data'] .= <<<HTML
                <tr>
                    <td>{$row['id_pedidos']}</td>
                    <td>{$row['razon_social']}</td>
                    <td>{$row['nombres']} {$row['apellidos']}</td>
                    <td>{$fecha}</td>
                    <td>{$total_monto}</td>
            HTML;

            if($row['estado'] == 'activo'){
                $output['data'] .= <<<HTML
                        <td>
                            <button type="button" class="btn btn-danger" style="margin-left: 10px" data-bs-toggle="modal" data-bs-target="#anularPedido{$row['id_pedidos']}">Anular</button>

                            <button type="button" class="btnpdfpedido btn btn-success" id="{$row['id_pedidos']},{$row['id_coti']}" style="margin-left: 10px">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                        </td>
                        <td>
                            <div class="modal fade" id="anularPedido{$row['id_pedidos']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <form id="formanular{$row['id_pedidos']}" method="POST" action="../control/anularpedido.php">
                                            <div class="modal-header text-center">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <h4 class="modal-title">¿Estas seguro?</h4>
                                            <textarea class="form-control" name="motivo" id="{$row['id_pedidos']}" rows="5" placeholder="Motivo" required></textarea>
                                            <div class="modal-footer justify-content-center">
                                            <button id="{$row['id_pedidos']},{$row['id_coti']}"  type="button" class="anularpedido btn btn-danger">Aceptar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                
                HTML;
            }else{
                $output['data'] .= <<<HTML
                        <td>
                            <button type="button" class="btn btn-secondary" style="margin-left: 10px">Anulado</button>

                            <button type="button" class="btnpdfpedido btn btn-success" id="{$row['id_pedidos']},{$row['id_coti']}" style="margin-left: 10px">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                        </td> 
                    </tr>               
                HTML;
            }
                
        }
    }else{
        $output['data'] .= <<<HTML
                <tr>
                    <td colspan="6">Sin resultados</td>
                </tr>
            HTML;
    }

    if($output['totalRegistros'] > 0){
        $totalPaginas = ceil($output['totalFiltro'] / $limit); //Calculamos la cantidad de paginas que tendriamos
        //Armamos el HTML de nuestra paginacion
        $output['paginacion'] .= '<nav>';
        $output['paginacion'] .= '<ul class="pagination">';

        $numeroInicio = 1;

        if(($pagina - 4) > 1){
            $numeroInicio = $pagina - 4;
        }

        $numeroFin = $numeroInicio + 9;

        if($numeroFin > $totalPaginas){
            $numeroFin = $totalPaginas;
        }

        for($i = $numeroInicio; $i <= $numeroFin; $i++){
            if($pagina == $i){
                $output['paginacion'] .= '<li class="page-item active"><a class="page-link" href="#" </a></li>'; 
            }else{
                $output['paginacion'] .= '<li class="page-item"><a class="page-link" href="#" onclick="nextPageFilterPedido(' . $i . ')">' . $i . '</a></li>'; 
            }
        }

        $output['paginacion'] .= '</ul>';
        $output['paginacion'] .= '</nav>';
    }

    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON

?>