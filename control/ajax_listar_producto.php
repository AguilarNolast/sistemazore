<?php

    //Agregamos el limite

    $limit = isset($_POST['registros']) ? $_POST['registros'] : 10; //Dato que viene de la vista para hacer el limite
    $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 0; 

    $campo = isset($_POST['campo']) ? $_POST['campo'] : null; //Dato que viene de la vista para hacer la busqueda

    // Ordenamiento
    if (isset($_POST['orderCol'])) {
        $orderCol = $_POST['orderCol'];
        $orderType = isset($_POST['orderType']) ? $_POST['orderType'] : 'asc';
    }else{
        $orderCol = '';
        $orderType = '';
    }

    require_once "../modelo/clase_productos.php"; //Llamo a la clase

    $producto = new Productos();
    list($resultado, $totalFiltro, $totalRegistros, $columns) = $producto->listado_productos($campo, $limit, $pagina, $orderCol, $orderType);

    $num_rows = $resultado->num_rows; 

    //Mostrar resultados
    $output = [];
    $output['totalRegistros'] = $totalRegistros;
    $output['totalFiltro'] = $totalFiltro;
    $output['data'] = '';
    $output['paginacion'] = '';

    if ($num_rows > 0 ){//Verificamos que haya algun resultado
        while($row = $resultado->fetch_array()){ 
            $output['data'] .= <<<HTML
                <tr>
                    <td>{$row[1]}</td>
                    <td>{$row[2]}</td>
                    <td>{$row[3]}</td>
                    <td>{$row[7]}</td>
                    <td>{$row[4]} x {$row[5]} x {$row[6]}</td>
                    <td>
                        <div class="d-flex flex-row justify-content-center">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarProducto{$row[0]}"><i class="fas fa-pen"></i></button>
                            
                            <button type="button" class="btn btn-danger" style="margin-left: 10px" data-bs-toggle="modal" data-bs-target="#eliminarProducto{$row[0]}"><i class="far fa-trash-can"></i></button>
                        </div>
                    </td>
                    <td>    
                        <div class="modal fade" id="editarProducto{$row[0]}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form>
                                    <div class="modal-content">
                                        <div class="modal-header text-justify">
                                            <h4 class="modal-title">EDITAR PRODUCTO</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="row modal-body mx-4 text-justify">
                                            <div class="md-form mb-2">
                                            <i class="grey-text">Producto</i>
                                                <input type="text" class="form-control" id="nombre{$row[0]}" value="{$row[1]}" placeholder="Producto" required>
                                            </div>
                                            <div class="md-form mb-2">
                                            <i class="grey-text">Descripción</i>
                                            <textarea class="form-control" id="descripcion{$row[0]}" value="" rows="4" placeholder="Descripción" required>{$row[2]}</textarea>
                                            </div>
                                            <div class="md-form mb-2">
                                            <i class="grey-text">Precio</i>
                                            <input type="number" id="precio{$row[0]}" value="{$row[3]}" class="form-control" placeholder="Precio" required>
                                            </div>
                                            <div class="md-form mb-2">
                                            <i class="grey-text">Alto</i>
                                            <input type="text" id="alto{$row[0]}" value="{$row[4]}" class="form-control" placeholder="Alto" required>
                                            </div>
                                            <div class="md-form mb-2">
                                            <i class="grey-text">Ancho</i>
                                            <input type="text" id="ancho{$row[0]}" value="{$row[5]}" class="form-control" placeholder="Ancho" required>
                                            </div>
                                            <div class="md-form mb-2">
                                            <i class="grey-text">Largo</i>
                                            <input type="text" id="largo{$row[0]}" value="{$row[6]}" class="form-control" placeholder="Largo" required>
                                            </div>

                                            <div class="md-form mb-2">
                                            <i class="grey-text">Peso</i>
                                            <input type="text" id="peso{$row[0]}" value="{$row[7]}" class="form-control" placeholder="Peso" required>        
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button  type="submit"  onclick="editarProducto({$row[0]})" class="btn btn-primary">Guardar cambios</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="modal fade" id="eliminarProducto{$row[0]}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-header text-center">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <input type="hidden" value="{$row[0]}">
                                    <h4 class="modal-title">¿Estas seguro?</h4>
                                    <div class="modal-footer justify-content-center">
                                        <button  type="button" onclick="eliminarProducto({$row[0]})" class="btn btn-danger">Ok</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            HTML;
                
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
                $output['paginacion'] .= '<li class="page-item"><a class="page-link" href="#" onclick="nextPage(' . $i . ')">' . $i . '</a></li>'; 
            }
        }

        $output['paginacion'] .= '</ul>';
        $output['paginacion'] .= '</nav>';
    }

    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON

?>