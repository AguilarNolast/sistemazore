<?php

    //Agregamos el limite

    $limit = isset($_POST['registros']) ? $_POST['registros'] : 10; //Dato que viene de la vista para hacer el limite
    $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 0; 

    $campo = isset($_POST['campo']) ? $_POST['campo'] : null; //Dato que viene de la vista para hacer la busqueda

    session_start();

    if($_SESSION["tipo"] == 'asesor'){
        $id_usuario = $_SESSION["id_usuario"];
    }else{
        $id_usuario = "";
    }

    require_once "../modelo/clase_cotizacion.php"; //Llamo a la clase
    require_once "../modelo/clase_ficha.php"; //Llamo a la clase 
    require_once "../modelo/clase_usuario.php"; //Llamo a la clase 

    $coti = new Cotizacion();
    list($resultado, $resProd, $totalFiltro, $totalRegistros, $columns) = $coti->listado_coti($campo, $limit, $pagina, $id_usuario);

    $num_rows = $resultado->num_rows; 
    $ficha_obj = new Ficha();

    $user = new Usuario();
    list($resUser, $nonuse1, $nonuse2, $nonuse3) = $user->listado_usuarios('', 100, 0, 0, 'desc');

    //Mostrar resultados
    $output = [];
    $output['totalRegistros'] = $totalRegistros;
    $output['totalFiltro'] = $totalFiltro;
    $output['data'] = '';
    $output['paginacion'] = '';
    $output['soles'] = 0;
    $output['dolares'] = 0;
    $output['optionList'] = <<<HTML
        <option value="todos">Todos</option>
    HTML;
    while($rowUser = $resUser->fetch_array()){
        $output['optionList'] .= <<<HTML
            <option value="{$rowUser['id_usuario']}">{$rowUser['nombres']} {$rowUser['apellidos']}</option>
        HTML;
    }

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

    if ($num_rows > 0 ){//Verificamos que haya algun resultado
        while($row = $resultado->fetch_array()){ 
            
            if($row[7] == 'Dolares americanos'){
                $simbolo = '$';
            }else{
                $simbolo = 'S/';
            }

            $subtotal_todo = 0;

            $resultado_prod = $coti->getProdCoti($row[0]);
            while($row_prod = $resultado_prod->fetch_assoc()){
                $subtotal = $row_prod['cantidad'] * $row_prod['precio'];

                $descuento = $row_prod['descuento'] / 100;

                $subtotal_und = $subtotal - ($subtotal * $descuento);

                $subtotal_todo += $subtotal_und;
            }

            $total_todo = $simbolo . '' . number_format($subtotal_todo, 2, ',', '.');

            $fecha = date("d-m-Y", strtotime($row[5]));

            $output['data'] .= <<<HTML
                <tr>
                    <td>{$row[6]}</td>
                    <td>{$row[3]} - {$row[4]}</td>
                    <td>{$fecha}</td>
                    <td>{$row[1]} - {$row[2]}</td>
                    <td>{$total_todo}</td>
                    <td>
                        <input type="hidden" name="id_coti" value="{$row[0]}">
                        <div class="d-flex flex-row justify-content-center">
                        <button type="button" class="btn btn-primary" onclick="linkCoti({$row[0]})">
                            <i class="fas fa-pen"></i>
                        </button>
                        <!--<button type="button" class="btn btn-danger"">
                            <i class="far fa-trash-can"></i>
                        </button>-->
                        <button type="button" class="getpdf btn btn-danger" id="{$row[0]}">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="pedidoCoti({$row[0]})">
                            <i class="fas fa-share-from-square"></i>
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#nuevaFicha{$row[0]}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-check-fill" viewBox="0 0 16 16">
                                <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5"/>
                                <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585q.084.236.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5q.001-.264.085-.5m6.769 6.854-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
                            </svg>
                        </button>

                        <div class="modal fade" id="nuevaFicha{$row[0]}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <form id="formficha{$row[0]}">
                                    <div class="modal-content">
                                        <div class="modal-header text-center">
                                            <h4 class="modal-title">NUEVA FICHA TECNICA</h4>
                                            <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="row modal-body mx-4">
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Tipo de equipo</i>
                                                <select name="" onchange="actConex({$row[0]},this.value)" id="tipoequipo{$row[0]}" class="form-select" required>
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
                                            <div class="col-md-12 col-lg-4">
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Marca</i>
                                                    <input type="text" class="form-control" id="marca{$row[0]}" placeholder="Marca" required>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Potencia</i>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control col-7" id="potencia{$row[0]}" placeholder="Potencia" required>
                                                        <select name="unipotencia" id="unipotencia{$row[0]}" class="form-select col-5">
                                                            <option>Kva</option>
                                                            <option>Va</option>
                                                            <option>Kw</option>
                                                            <option>W</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Fases</i>
                                                    <select name="fases" id="fases{$row[0]}" class="form-select">
                                                        <option>Monofásico</option>
                                                        <option>Bifasico</option>
                                                        <option>Trifásico</option>
                                                    </select>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Tensión primaria</i>
                                                    <div class="input-group">
                                                        <input type="number" id="tensionpri{$row[0]}" class="form-control" placeholder="Tensión primaria" required>
                                                        <span class="input-group-text">
                                                            VAC
                                                        </span>
                                                        <div class="input-group-text" for="flexCheckDefault">
                                                            <input class="form-check-input" type="checkbox" value="" id="neutroent{$row[0]}">
                                                            +N
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">N° de Bornes Primarios</i>
                                                    <input type="number" id="bornespri{$row[0]}" class="form-control" placeholder="N° de Bornes Primarios" required>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Tensión secundaria</i>
                                                    <div class="input-group">
                                                        <input type="number" id="tensionsecun{$row[0]}" class="form-control" placeholder="Tensión secundaria" required>
                                                        <span class="input-group-text">
                                                            VAC
                                                        </span>
                                                        <div class="input-group-text" for="flexCheckDefault">
                                                            <input class="form-check-input" type="checkbox" value="" id="neutrosal{$row[0]}">
                                                            +N
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">N° de Bornes Secundarios</i>
                                                    <input type="number" id="bornessec{$row[0]}" class="form-control" placeholder="N° de Bornes Secundarios" required>
                                                </div>
                                                <div id="regEnt{$row[0]}">
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-4">
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Factor de potencia</i>
                                                    <div class="input-group">
                                                        <input type="number" id="factorpot{$row[0]}" value="0.8" class="form-control" placeholder="Factor de potencia" disabled  required>
                                                        <span class="input-group-text" onclick="editFactor({$row[0]})">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                                                            </svg>
                                                        </span> 
                                                    </div>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Grupo de Conexión</i>
                                                    <input type="text" id="grupocon{$row[0]}" class="form-control" placeholder="Grupo de Conexión" required>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Tipo de factor</i>
                                                    <select name="tipo-factor" id="factor{$row[0]}" class="form-select">
                                                        <option>K1</option>
                                                        <option>K4</option>
                                                        <option>K13</option>
                                                        <option>K20</option>
                                                    </select>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Grado de Protección</i>
                                                    <input type="text" id="gradopro{$row[0]}" class="form-control" placeholder="Grado de Protección" required>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Clase</i>
                                                    <div class="input-group"  id="divclase{$row[0]}">
                                                        <select name="clase" id="clase{$row[0]}" class="form-select">
                                                            <option>F (155°C)</option>
                                                            <option>H (180°C)</option>
                                                        </select>
                                                        <span class="input-group-text" onclick="editClase({$row[0]})">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                                                            </svg>
                                                        </span> 
                                                    </div>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Altitud de Operación</i>
                                                    <div class="input-group">
                                                        <input type="text" id="altitud{$row[0]}" class="form-control" placeholder="Altitud de Operación" required>
                                                        <span class="input-group-text">m.s.n.m</span> 
                                                    </div>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Montaje</i>
                                                    <select name="montaje" id="montaje{$row[0]}" class="form-select" required>
                                                        <option>Interior</option>
                                                        <option>Exterior</option>
                                                        <option>Int/Ext</option>
                                                    </select>
                                                </div>
                                                <div id="regSalDiv{$row[0]}">
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-4">
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Material</i>
                                                    <select name="material" id="material{$row[0]}" class="form-select" required>
                                                        <option>Al - Al</option>
                                                        <option>Cu - Cu</option>
                                                    </select>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Alto</i>
                                                    <div class="input-group">
                                                        <input type="text" id="altoficha{$row[0]}" class="form-control" placeholder="Alto" required>
                                                        <span class="input-group-text">
                                                            Cm
                                                        </span> 
                                                    </div>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Ancho</i>
                                                    <div class="input-group">
                                                        <input type="text" id="anchoficha{$row[0]}" class="form-control" placeholder="Ancho" required>
                                                        <span class="input-group-text">
                                                            Cm
                                                        </span> 
                                                    </div>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Largo</i>
                                                    <div class="input-group">
                                                        <input type="text" id="largoficha{$row[0]}" class="form-control" placeholder="Largo" required>
                                                        <span class="input-group-text">
                                                            Cm
                                                        </span> 
                                                    </div>
                                                </div>
                                                <div class="md-form mb-2">
                                                    <i class="grey-text">Peso</i>
                                                    <div class="input-group">
                                                        <input type="text" id="pesoficha{$row[0]}" class="form-control" placeholder="Peso" required>
                                                        <span class="input-group-text">
                                                            Kg
                                                        </span> 
                                                    </div>
                                                </div>
                                                <div id="divcon{$row[0]}">
                                                </div>
                                            </div>
                                            <div class="accordion accordion-flush" id="listaFicha{$row[0]}">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                                        Fichas generadas
                                                    </button>
                                                    </h2>
                                                    <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#listaFicha{$row[0]}">
                                                    <div class="accordion-body">
                                                        <table class="table table-striped text-center">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col" scope="row">Tipo y potencia</th>
                                                                    <th scope="col">Opciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

            HTML;
                                                    
                                                                $resul_fichas = $ficha_obj->getFichas($row[0]);
                                                                $num_rows = $resul_fichas->num_rows; 
                                                                if($num_rows > 0){
                                                                    while($rowficha = $resul_fichas->fetch_assoc()){
                                    
                                                                        switch ($rowficha['tipoequipo']) {
                                                                            case "trafomonotorre":
                                                                            $tipoequipo = 'Transformador monofasico torre';
                                                                            break;
                                                                        case "trafomonorack":
                                                                            $tipoequipo = 'Transformador monofasico rack';
                                                                            break;
                                                                        case "trafotritorre":
                                                                            $tipoequipo = 'Transformador trifasico torre';
                                                                            break;
                                                                        case "trafotrirack":
                                                                            $tipoequipo = 'Transformador trifasico rack';
                                                                            break;
                                                                        case "automonorack":
                                                                            $tipoequipo = 'Autotransformador monofasico rack';
                                                                            break;
                                                                        case "automonotorre":
                                                                            $tipoequipo = 'Autotransformador monofasico torre';
                                                                            break;
                                                                        case "autotritorre":
                                                                            $tipoequipo = 'Autotransformador trifasico torre';
                                                                            break;
                                                                        case "autotrirack":
                                                                            $tipoequipo = 'Autotransformador trifasico rack';
                                                                            break;
                                                                        case "estabimonotorre":
                                                                            $tipoequipo = 'Estabilizador con transformador monofasico torre';
                                                                            break;
                                                                        case "estabimonorack":
                                                                            $tipoequipo = 'Estabilizador con transformador monofasico rack';
                                                                            break;
                                                                        case "estabitritorre":
                                                                            $tipoequipo = 'Estabilizador con transformador trifasico torre';
                                                                            break;
                                                                        case "estabitrirack":
                                                                            $tipoequipo = 'Estabilizador con transformador trifasico rack';
                                                                            break;
                                                                        case "estabiautomonotorre":
                                                                            $tipoequipo = 'Estabilizador con autotransformador monofasico torre';
                                                                            break;
                                                                        case "estabiautomonorack":
                                                                            $tipoequipo = 'Estabilizador con autotransformador monofasico rack';
                                                                            break;
                                                                        case "estabiautotrifatorre":
                                                                            $tipoequipo = 'Estabilizador con autotransformador trifasico torre';
                                                                            break;
                                                                        case "estabiautotrifarack":
                                                                            $tipoequipo = 'Estabilizador con autotransformador trifasico rack';
                                                                            break;
                                                                        }
            $output['data'] .= <<<HTML
                                                        
                                                                            <tr id="itemficha{$rowficha['id_ficha']}">
                                                                                <td scope="col" scope="row">{$tipoequipo} - {$rowficha['potencia']} {$rowficha['unipotencia']}</td>
                                                                                <td scope="col">
                                                                                    <button type="button" class="btnPdfFicha btn btn-primary" id="{$rowficha['id_ficha']}">
                                                                                        <i class="fas fa-file-pdf"></i>
                                                                                    </button>
                                                                                    <button type="button" class="btn btn-primary" onclick="cargarEdicionFicha({$rowficha['id_ficha']},{$row[0]})">
                                                                                        <i class="fas fa-pen"></i>
                                                                                    </button>
                                                                                    <button type="button" class="btn btn-danger" onclick="eliminarFicha({$rowficha['id_ficha']})">
                                                                                        <i class="far fa-trash-can"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>                        
                                                                        
            HTML;
                                                                    }
                                                                }else{
            $output['data'] .= <<<HTML
                                                        
                                                                            <tr>
                                                                                <th scope="col"colspan="2" scope="row">Sin fichas generadas</th>
                                                                            </tr>                        
                                                                        
            HTML;
                                                                }
                                                
            $output['data'] .= <<<HTML
                                                            </body>  
                                                        </table>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" id="{$row[0]}" class="btnCrearFicha btn btn-primary">Generar ficha</button>
                                        </div>
                                    </div>
                                </form>
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