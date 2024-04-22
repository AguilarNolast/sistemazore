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

    require_once "../modelo/clase_calidad.php"; //Llamo a la clase

    $calidad = new Calidad();
    list($resultado, $totalFiltro, $totalRegistros, $columns) = $calidad->listado_calidad($campo, $limit, $pagina, $orderCol, $orderType);

    $num_rows = $resultado->num_rows; 

    //Mostrar resultados
    $output = [];
    $output['totalRegistros'] = $totalRegistros;
    $output['totalFiltro'] = $totalFiltro;
    $output['data'] = '';
    $output['paginacion'] = '';

        
    $arrayTipo = [

        'trafomonotorre' => 'Transformador monofásico torre',
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

    ];

    $arrayunipotencia = [

        'Kva' => 'Kva',
        'Va' => 'Va',
        'Kw' => 'Kw',
        'W' => 'W',

    ];

    $arrayfactor = [

        'K1' => 'K1',
        'K4' => 'K4',
        'K13' => 'K13',
        'K20' => 'K20',

    ];

    if ($num_rows > 0 ){//Verificamos que haya algun resultado
        while($row = $resultado->fetch_assoc()){ 

            switch ($row['tipo']) {
                case "trafomonotorre":
                    $tipoequipo = 'Transformador monofasico torre';
                    break;
                case "trafomonorack":
                    $tipoequipo = 'Transformador monofasico rack';
                    break;
                case "trafotritorre":
                    $tipoequipo = 'Transformador trifásico torre';
                    break;
                case "trafotrirack":
                    $tipoequipo = 'Transformador trifásico rack';
                    break;
                case "automonorack":
                    $tipoequipo = 'Autotransformador monofasico rack';
                    break;
                case "automonotorre":
                    $tipoequipo = 'Autotransformador monofasico torre';
                    break;
                case "autotritorre":
                    $tipoequipo = 'Autotransformador trifásico torre';
                    break;
                case "autotrirack":
                    $tipoequipo = 'Autotransformador trifásico rack';
                    break;
                case "estabimonotorre":
                    $tipoequipo = 'Estabilizador con transformador monofasico torre';
                    break;
                case "estabimonorack":
                    $tipoequipo = 'Estabilizador con transformador monofasico rack';
                    break;
                case "estabitritorre":
                    $tipoequipo = 'Estabilizador con transformador trifásico torre';
                    break;
                case "estabitrirack":
                    $tipoequipo = 'Estabilizador con transformador trifásico rack';
                    break;
                case "estabiautomonotorre":
                    $tipoequipo = 'Estabilizador con autotransformador monofasico torre';
                    break;
                case "estabiautomonorack":
                    $tipoequipo = 'Estabilizador con autotransformador monofasico rack';
                    break;
                case "estabiautotrifatorre":
                    $tipoequipo = 'Estabilizador con autotransformador trifásico torre';
                    break;
                case "estabiautotrifarack":
                    $tipoequipo = 'Estabilizador con autotransformador trifásico rack';
                    break;
            }      

            $output['data'] .= <<<HTML
                <tr>
                    <td>{$row['nombre']}</td>
                    <td>{$row['ruc']}</td>
                    <td>{$tipoequipo}</td>
                    <td>{$row['serie']}</td>
                    <td>

                        <button type="button" class="btn btn-danger" onclick="getCalidad({$row['id_calidad']})" id="{$row['id_calidad']}">
                            <i class="fas fa-file-pdf"></i>
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarCalidad{$row['id_calidad']}"><i class="fas fa-pen"></i></button>

                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarCalidad{$row['id_calidad']}"><i class="far fa-trash-can"></i></button>

                    </td>
                    <td>

                <div class="modal fade" id="eliminarCalidad{$row['id_calidad']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header text-center">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <input type="hidden" value="{$row['id_calidad']}">
                            <h4 class="modal-title">¿Estas seguro?</h4>
                            <div class="modal-footer justify-content-center">
                                <button  type="button" onclick="eliminarCalidad({$row['id_calidad']})" class="btn btn-danger">Ok</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editarCalidad{$row['id_calidad']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form method="POST" action="../control/editar_calidad.php" id="formEditCalidad{$row['id_calidad']}">
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <h4 class="modal-title">EDITAR CERTIFICADO DE CALIDAD</h4>
                                    <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="row modal-body mx-4">
                                    <div class="md-form mb-2">
                                    <i class="grey-text">Razon Social</i>
                                    <input type="text" class="form-control" name="nombre" value="{$row['nombre']}" placeholder="Razon Social" required>
                                    </div>
                                    <div class="md-form mb-2">
                                    <i class="grey-text">RUC</i>
                                    <input type="text" class="form-control" name="ruc" value="{$row['ruc']}" placeholder="RUC" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Direccion</i>
                                        <input type="text" class="form-control" name="direccion" value="{$row['direccion']}" placeholder="Direccion" required>
                                    </div>
                                    <div class="md-form mb-2">
                                    <i class="grey-text">Fecha de Fabricacion</i>
                                    <input type="date" name="fecha_fab" value="{$row['fecha_fab']}" class="form-control" placeholder="Fecha de Fabricacion" required>
                                    </div>
                                    <div class="md-form mb-2">
                                    <i class="grey-text">Tipos de Equipos</i>
                                    <select name="tipoequipo" class="form-select" required>
                HTML;
                $tipoSelect = $row['tipo'];
                foreach ($arrayTipo as $clave => $valor) {
                    $selected = ($tipoSelect === $clave) ? 'selected' : '';
                    $output['data'] .= <<<HTML
                                        <option value="{$clave}" {$selected}>{$valor}</option>
                    HTML;
                                
                }
                $output['data'] .= <<<HTML
                                        </select>
                                    </div>
                                    <div class="md-form mb-2">
                                    <i class="grey-text">Potencia</i>
                                    <div class="input-group">
                                        <input type="number" value="{$row['potencia']}" name="potencia" class="form-control" placeholder="Potencia" required>
                                        <select name="unipotencia" class="form-select col-5">
                HTML;
                $uniSelect = $row['tipo'];
                foreach ($arrayunipotencia as $clave => $valor) {
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
                                    <i class="grey-text">Factor</i>
                                        <select name="factor" class="form-select" required>
                HTML;
                $factorSelect = $row['tipo'];
                foreach ($arrayfactor as $clave => $valor) {
                    $selected = ($factorSelect === $clave) ? 'selected' : '';
                    $output['data'] .= <<<HTML
                                        <option value="{$clave}" {$selected}>{$valor}</option>
                    HTML;
                                
                }
                $output['data'] .= <<<HTML
                                        </select>
                                    </div>
                                    <div class="md-form mb-2">
                                    <i class="grey-text">Marca</i>
                                    <input type="text" name="marca" value="{$row['marca']}" class="form-control" placeholder="Marca" required>
                                    </div>
                                    <div class="md-form mb-2">
                                    <i class="grey-text">Serie</i>
                                    <input type="text" name="serie" value="{$row['serie']}" class="form-control" placeholder="Serie del producto" required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button  type="button"  onclick="editarCalidad({$row['id_calidad']})" id="btnEditCalidad{$row['id_calidad']}" class="btn btn-primary">Guardar</button>
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