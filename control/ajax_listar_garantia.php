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

    require_once "../modelo/clase_garantia.php"; //Llamo a la clase

    $garantia = new Garantia();
    list($resultado, $totalFiltro, $totalRegistros, $columns) = $garantia->listado_garantia($campo, $limit, $pagina, $orderCol, $orderType);

    $num_rows = $resultado->num_rows; 

    //Mostrar resultados
    $output = [];
    $output['totalRegistros'] = $totalRegistros;
    $output['totalFiltro'] = $totalFiltro;
    $output['data'] = '';
    $output['paginacion'] = '';

    $arrayTipo = [

        'trafomonotorre' => 'Transformador monofasico torre',
        'trafomonorack' => 'Transformador monofasico rack',
        'trafotritorre' => 'Transformador trifásico torre',
        'trafotrirack' => 'Transformador trifásico rack',
        'automonorack' => 'Autotransformador monofasico rack',
        'automonotorre' => 'Autotransformador monofasico torre',
        'autotritorre' => 'Autotransformador trifásico torre',
        'autotrirack' => 'Autotransformador trifásico rack',
        'estabimonotorre' => 'Estabilizador con transformador monofasico torre',
        'estabimonorack' => 'Estabilizador con transformador monofasico rack',
        'estabitritorre' => 'Estabilizador con transformador trifásico torre',
        'estabitrirack' => 'Estabilizador con transformador trifásico rack',
        'estabiautomonotorre' => 'Estabilizador con autotransformador monofasico torre',
        'estabiautomonorack' => 'Estabilizador con autotransformador monofasico rack',
        'estabiautotrifatorre' => 'Estabilizador con autotransformador trifásico torre',
        'estabiautotrifarack' => 'Estabilizador con autotransformador trifásico rack',
        'estabiferromono' => 'Estabilizador ferroresonante monofasico',
        'estabiferrotri' => 'Estabilizador ferroresonante trifasico',
        'upsmono' => 'UPS monofasico',
        'upstri' => 'UPS trifasico',
        'upsmonorack' => 'UPS monofasico rack',
        'upstrirack' => 'UPS trifasico rack',

    ];

    $arrayUnipotencia = [
        'Kva' => 'Kva',
        'Va' => 'Va',
        'Kw' => 'Kw',
        'W' => 'W',
    ];

    if ($num_rows > 0 ){//Verificamos que haya algun resultado
        while($row = $resultado->fetch_assoc()){ 
            $output['data'] .= <<<HTML
                <tr>
                    <td>{$row['nombre']}</td>
                    <td>{$row['ruc']}</td>
                    <td>{$row['fecha']}</td>
                    <td>{$row['factura']}</td>
                    <td>{$row['oc']}</td>
                    <td>
                        <div class="d-flex flex-row justify-content-center">
                            <button type="button" class="btn btn-danger" onclick="getGarantia({$row['id_garantia']})" id="{$row['id_garantia']}">
                                <i class="fas fa-file-pdf"></i>
                            </button>

                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarGarantia{$row['id_garantia']}">
                                <i class="fas fa-pen"></i>
                            </button>
                            
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarGarantia{$row['id_garantia']}">
                                <i class="far fa-trash-can"></i>
                            </button>
                        </div>
                    </td>

                <td>

                    <div class="modal fade" id="eliminarGarantia{$row['id_garantia']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <input type="hidden" value="{$row['id_garantia']}">
                                <h4 class="modal-title">¿Estas seguro?</h4>
                                <div class="modal-footer justify-content-center">
                                    <button  type="button" onclick="eliminarGarantia({$row['id_garantia']})" class="btn btn-danger">Ok</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="modal fade" id="editarGarantia{$row['id_garantia']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                            <form method="POST" action="../control/editar_garantia.php" id="formEditGarantia{$row['id_garantia']}">
                            <div class="modal-content">
                            <div class="modal-header text-center">
                                <h4 class="modal-title">EDITAR CERTIFICADO DE GARANTIA</h4>
                                <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="row modal-body mx-4">
                                <div class="md-form mb-2">
                                <i class="grey-text">Nombre</i>
                                <input type="text" class="form-control" name="nombre" value="{$row['nombre']}" placeholder="Nombre" required>
                                </div>
                                <div class="md-form mb-2">
                                <i class="grey-text">RUC</i>
                                <input type="number" class="form-control" name="ruc" value="{$row['ruc']}" placeholder="RUC" required>
                                </div>
                                <div class="md-form mb-2">
                                <i class="grey-text">Fecha</i>
                                <input type="date" class="form-control" name="fecha" value="{$row['fecha']}" placeholder="Fecha" required>
                                </div>
                                <div class="md-form mb-2">
                                <i class="grey-text">N° De factura</i>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        FFF1
                                    </span>
                                    <input type="number" name="factura" value="{$row['factura']}" class="form-control" placeholder="Numero de Factura" required>
                                </div>
                                </div>
                                <div class="md-form mb-2">
                                <i class="grey-text">Orden Compra</i>
                                <input type="text" name="oc" class="form-control" value="{$row['oc']}" placeholder="Orden de compra">
                                </div>
                                <div class="md-form mb-2">
                                <i class="grey-text">Tipo de equipo</i>
                                <select name="tipoequipo" class="form-select" required>
                HTML;
                                    $tipoSelec = $row['tipo'];
                                    foreach ($arrayTipo as $clave => $valor) {
                                        $selected = ($tipoSelec === $clave) ? 'selected' : '';
                $output['data'] .= <<<HTML
                                            <option value="{$clave}" {$selected}>{$valor}</option>
                HTML;
                                        
                                    }
                $output['data'] .= <<<HTML
                                </select>
                                </div>
                                <div class="md-form mb-2">
                                <i class="grey-text">Marca</i>
                                <input type="text" class="form-control" value="{$row['marca']}" name="marca" placeholder="Marca" required>
                                </div>
                                <div class="md-form mb-2">
                                <i class="grey-text">Potencia</i>
                                <div class="input-group">
                                    <input type="number" name="potencia" value="{$row['potencia']}" class="form-control" placeholder="Potencia" required>
                                    <select name="unipotencia" class="form-select col-5">
                HTML;
                $uniSelect = $row['unipotencia'];
                foreach ($arrayUnipotencia as $clave => $valor) {
                    $selected = ($uniSelect === $clave) ? 'selected' : '';
                    $output['data'] .= <<<HTML
                                        <option value="{$clave}" {$selected}>{$valor}</option>
                    HTML;
                                
                }
                $output['data'] .= <<<HTML
                                    </select>
                                </div>
                                </div>
                                <div class="md-form mb-2">
                                <i class="grey-text">Modelo</i>
                                <input type="text" name="modelo" class="form-control" value="{$row['modelo']}" placeholder="Modelo" required>
                                </div>
                                <div class="md-form mb-2">
                                <i class="grey-text">Serie</i>
                                <input type="text" name="serie" class="form-control" value="{$row['serie']}" placeholder="Serie del producto" required>
                                </div>
                                <div class="md-form mb-2">
                                <div class="row">
                                    <div class="input-group col-md-6">
                                    <i class="grey-text">TVSS</i>
                HTML;

                    $checkedTvss = $row['tvss'] == 1 ? 'checked' : '';
                    $checkedManual = $row['manual_v'] == 1 ? 'checked' : '';

                $output['data'] .= <<<HTML
                                        <input type="checkbox" name="tvss" {$checkedTvss} class="col-md-6" required>                    
                                    </div>                   
                                    <div class="input-group col-md-6">
                                    <i class="grey-text">Manual</i>
                                        <input type="checkbox" name="manual_v" {$checkedManual} class=" col-md-6" required>                    
                                    </div>                   
                                </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button  type="button"  onclick="editarGarantia({$row['id_garantia']})" class="btn btn-primary" id="btnEditGarantia{$row['id_garantia']}">Guardar</button>
                            </div>
                            </div>
                            </form>
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