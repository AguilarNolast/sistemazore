<?php

    require_once "../modelo/clase_ficha.php"; //Llamo a la clase

    $id_ficha = $_POST["id_ficha"]  ?? null;

    $ficha = new Ficha();

    $resultado = $ficha->get_ficha($id_ficha);

    $num_rows = $resultado->num_rows; 

    $output['data'] = '';

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

    ];

    $arrayUni = [
        'Kva' => 'Kva',
        'Va' => 'Va',
        'Kw' => 'Kw',
        'W' => 'W',
    ];

    $arrayFases = [
        'Monofásico' => 'Monofásico',
        'Bifasico' => 'Bifasico',
        'Trifásico' => 'Trifásico',
    ];

    $arrayFactor = [
        'K1' => 'K1',
        'K4' => 'K4',
        'K13' => 'K13',
        'K20' => 'K20',
    ];

    $arrayClase = [
        'F (155°C)' => 'F (155°C)',
        'H (180°C)' => 'H (180°C)',
    ];

    $arrayMontaje = [
        'Interior' => 'Interior',
        'Exterior' => 'Exterior',
        'Int/Ext' => 'Int/Ext',
    ];

    $arrayMaterial = [
        'Al - Al' => 'Al - Al',
        'Cu - Cu' => 'Cu - Cu',
    ];

    $arrayCone = [
        'Delta' => 'Delta',
        'Estrella' => 'Estrella',
        'Delta-Estrella' => 'Delta-Estrella',
    ];
    
    while($rowFicha = $resultado->fetch_array()){

        $output['data'] .= <<<HTML
            <div class="modal fade" id="editFicha{$id_ficha}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <form id="formeditficha{$id_ficha}">
                        <div class="modal-content">
                            <div class="modal-header text-center">
                                <h4 class="modal-title">EDITAR FICHA TECNICA</h4>
                                <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="row modal-body mx-4">
                                <div class="md-form mb-2">
                                    <i class="grey-text">Tipo de equipo</i>
                                    <select name="" onchange="actConex({$id_ficha},this.value)" id="tipoequipo{$id_ficha}" class="form-select" required disabled>
        HTML;
                                    $tipoSelec = $rowFicha['tipoequipo'];
                                    foreach ($arrayTipo as $clave => $valor) {
                                        $selected = ($tipoSelec === $clave) ? 'selected' : '';
        $output['data'] .= <<<HTML
                                        <option value="{$clave}" {$selected}>{$valor}</option>
        HTML;
                                        
                                    }
        $output['data'] .= <<<HTML
                                    </select>
                                </div>
                                <div class="col-md-12 col-lg-4">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Marca</i>
                                        <input type="text" class="form-control" id="marcaEdit{$id_ficha}" value="{$rowFicha['marca']}" placeholder="Marca" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Potencia</i>
                                        <div class="input-group">
                                            <input type="number" class="form-control col-7" value="{$rowFicha['potencia']}" id="potenciaEdit{$id_ficha}" placeholder="Potencia" required>
                                            <select name="unipotencia" id="unipotenciaEdit{$id_ficha}" class="form-select col-5">
        HTML;
                                                $uniSelect = $rowFicha['unipotencia'];
                                                foreach ($arrayUni as $clave => $valor) {
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
                                        <i class="grey-text">Fases</i>
                                        <select name="fases" id="fasesEdit{$id_ficha}" value="{$rowFicha['fases']}" class="form-select">
        HTML;
                                            $faseSelect = $rowFicha['fases'];
                                            foreach ($arrayFases as $clave => $valor) {
                                                $selected = ($faseSelect === $clave) ? 'selected' : '';
        $output['data'] .= <<<HTML
                                                <option value="{$clave}" {$selected}>{$valor}</option>
        HTML;
                                                
                                            }
        $output['data'] .= <<<HTML
                                        </select>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Tensión primaria</i>
                                        <div class="input-group">
                                            <input type="number" id="tensionpriEdit{$id_ficha}" value="{$rowFicha['tensionpri']}" class="form-control" placeholder="Tensión primaria" required>
                                            <span class="input-group-text">
                                                VAC
                                            </span>
                                            <div class="input-group-text" for="flexCheckDefault">
        HTML;
                                                if($rowFicha['neutroent'] == 'true'){
                                                    $checked = 'checked';
                                                }else{
                                                    $checked = '';
                                                }
        $output['data'] .= <<<HTML
                                                <input class="form-check-input" type="checkbox" {$checked} id="neutroentEdit{$id_ficha}">
                                                +N
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">N° de Bornes Primarios</i>
                                        <input type="number" id="bornespriEdit{$id_ficha}" value="{$rowFicha['bornespri']}" class="form-control" placeholder="N° de Bornes Primarios" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Tensión secundaria</i>
                                        <div class="input-group">
                                            <input type="number" id="tensionsecunEdit{$id_ficha}" value="{$rowFicha['tensionsecun']}" class="form-control" placeholder="Tensión secundaria" required>
                                            <span class="input-group-text">
                                                VAC
                                            </span>
                                            <div class="input-group-text" for="flexCheckDefault">
        HTML;
                                                if($rowFicha['neutrosal'] == 'true'){
                                                    $checked = 'checked';
                                                }else{
                                                    $checked = '';
                                                }
        $output['data'] .= <<<HTML
                                                <input class="form-check-input" type="checkbox" {$checked} id="neutrosalEdit{$id_ficha}">
                                                +N
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">N° de Bornes Secundarios</i>
                                        <input type="number" id="bornessecEdit{$id_ficha}" value="{$rowFicha['bornessec']}" class="form-control" placeholder="N° de Bornes Secundarios" required>
                                    </div>
        HTML;
                                    if($rowFicha['regEnt1'] != 0){
        $output['data'] .= <<<HTML
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Regulacion de entrada</i>
                                        <div class="input-group">
                                            <span class="input-group-text">-</span>
                                            <input type="number" id="regEnt1Edit{$id_ficha}" value="{$rowFicha['regEnt1']}" class="form-control" placeholder="" required>
                                            <span class="input-group-text">%</span> 
                                            <span class="input-group-text">+</span>
                                            <input type="number" id="regEnt2Edit{$id_ficha}" value="{$rowFicha['regEnt2']}" class="form-control" placeholder="" required>
                                            <span class="input-group-text">%</span> 
                                        </div>
                                    </div>
        HTML;                            
                                    }
        $output['data'] .= <<<HTML
                                </div>
                                <div class="col-md-12 col-lg-4">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Factor de potencia</i>
                                        <div class="input-group">
                                            <input type="number" id="factorpotEdit{$id_ficha}" value="{$rowFicha['factorpot']}" class="form-control" placeholder="Factor de potencia" disabled  required>
                                            <span class="input-group-text" onclick="editFactorFicha({$id_ficha})">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                                                </svg>
                                            </span> 
                                        </div>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Grupo de Conexión</i>
                                        <input type="text" id="grupoconEdit{$id_ficha}" value="{$rowFicha['grupocon']}" class="form-control" placeholder="Grupo de Conexión" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Tipo de factor</i>
                                        <select name="tipo-factor" id="factorEdit{$id_ficha}" class="form-select">
        HTML;
                                            $factorSelect = $rowFicha['factor'];
                                            foreach ($arrayFactor as $clave => $valor) {
                                                $selected = ($factorSelect === $clave) ? 'selected' : '';
        $output['data'] .= <<<HTML
                                            <option value="{$clave}" {$selected}>{$valor}</option>
        HTML;
                    
                                            }
        $output['data'] .= <<<HTML
                                        </select>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Grado de Protección</i>
                                        <input type="text" id="gradoproEdit{$id_ficha}" value="{$rowFicha['gradopro']}" class="form-control" placeholder="Grado de Protección" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Clase</i>
                                        <div class="input-group"  id="divclase{$id_ficha}">
        HTML;
                                                
                            $claseSelect = $rowFicha['clase'];
                            $isSelected = false;
                            foreach ($arrayClase as $clave => $valor) {
                                $isSelected = ($claseSelect === $clave) ? true : $isSelected;
                            }

                            if(!$isSelected){
        $output['data'] .= <<<HTML
                                    <input type="text" class="form-control" value="{$rowFicha['clase']}" name="clase" id="claseEdit{$id_ficha}" placeholder="Clase">
                                    <span class="input-group-text" id="" onclick="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                        </svg>
                                    </span>
        HTML;
                            }else{
        $output['data'] .= <<<HTML
                                    <select name="clase" id="claseEdit{$id_ficha}" class="form-select">
        HTML;

                                foreach ($arrayClase as $clave => $valor) {
                                    $selected2 = ($claseSelect === $clave) ? 'selected' : '';
        $output['data'] .= <<<HTML
                                        <option value="{$clave}" {$selected2}>{$valor}</option>
        HTML;    
                                }

        $output['data'] .= <<<HTML
                                    </select>
                                    <span class="input-group-text" id="" onclick="" data-bs-toggle="modal" data-bs-target="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                        </svg>
                                    </span>
        HTML;
                            }
        $output['data'] .= <<<HTML
                                        </div>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Altitud de Operación</i>
                                        <div class="input-group">
                                            <input type="text" id="altitudEdit{$id_ficha}" value="{$rowFicha['altitud']}" class="form-control" placeholder="Altitud de Operación" required>
                                            <span class="input-group-text">m.s.n.m</span> 
                                        </div>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Montaje</i>
                                        <select name="montaje" id="montajeEdit{$id_ficha}" class="form-select" required>
            HTML;
                                            $montajeSelect = $rowFicha['montaje'];
                                            foreach ($arrayMontaje as $clave => $valor) {
                                                $selected = ($montajeSelect === $clave) ? 'selected' : '';
            $output['data'] .= <<<HTML
                                            <option value="{$clave}" {$selected}>{$valor}</option>
            HTML;
                    
                                            }
            $output['data'] .= <<<HTML
                                        </select>
                                    </div>
            HTML;
                                    if($rowFicha['regSal'] != 0){
            $output['data'] .= <<<HTML
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Regulacion de salida</i>
                                        <div class="input-group">
                                            <span class="input-group-text">±</span>
                                            <input type="number" id="regSalEdit{$id_ficha}" value="{$rowFicha['regSal']}" class="form-control" placeholder="" required>
                                            <span class="input-group-text">%</span> 
                                        </div>
                                    </div>
            HTML;                            
                                    }
            $output['data'] .= <<<HTML
                                </div>
                                <div class="col-md-12 col-lg-4">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Material</i>
                                        <select name="material" id="materialEdit{$id_ficha}" class="form-select" required>
            HTML;
                                            $materialSelect = $rowFicha['material'];
                                            foreach ($arrayMaterial as $clave => $valor) {
                                                $selected = ($materialSelect === $clave) ? 'selected' : '';
            $output['data'] .= <<<HTML
                                            <option value="{$clave}" {$selected}>{$valor}</option>
            HTML;
                    
                                            }
            $output['data'] .= <<<HTML
                                        </select>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Alto</i>
                                        <div class="input-group">
                                            <input type="text" id="altofichaEdit{$id_ficha}" value="{$rowFicha['altoficha']}" class="form-control" placeholder="Alto" required>
                                            <span class="input-group-text">
                                                Cm
                                            </span> 
                                        </div>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Ancho</i>
                                        <div class="input-group">
                                            <input type="text" id="anchofichaEdit{$id_ficha}" value="{$rowFicha['anchoficha']}" class="form-control" placeholder="Ancho" required>
                                            <span class="input-group-text">
                                                Cm
                                            </span> 
                                        </div>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Largo</i>
                                        <div class="input-group">
                                            <input type="text" id="largofichaEdit{$id_ficha}" value="{$rowFicha['largoficha']}" class="form-control" placeholder="Largo" required>
                                            <span class="input-group-text">
                                                Cm
                                            </span> 
                                        </div>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Peso</i>
                                        <div class="input-group">
                                            <input type="text" id="pesofichaEdit{$id_ficha}" value="{$rowFicha['pesoficha']}" class="form-control" placeholder="Peso" required>
                                            <span class="input-group-text">
                                                Kg
                                            </span> 
                                        </div>
                                    </div>
    HTML;
                                    if($rowFicha['conpri'] != ''){
                                        $output['data'] .= <<<HTML
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Conexión Primario</i>
                                                <select name="" id="conpriEdit{$id_ficha}" class="form-select">
            HTML;
                                            $coneSelect = $rowFicha['conpri'];
                                            foreach ($arrayCone as $clave => $valor) {
                                                $selected = ($coneSelect === $clave) ? 'selected' : '';
            $output['data'] .= <<<HTML
                                                    <option value="{$clave}" {$selected}>{$valor}</option>
            HTML;
                    
                                            }
            $output['data'] .= <<<HTML
                                                </select>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Conexión Secundario</i>
                                                <select name="" id="consecEdit{$id_ficha}" class="form-select">
            HTML;                               
                                            $coneSelect = $rowFicha['consec'];
                                            foreach ($arrayCone as $clave => $valor) {
                                                $selected = ($coneSelect === $clave) ? 'selected' : '';
            $output['data'] .= <<<HTML
                                                    <option value="{$clave}" {$selected}>{$valor}</option>
            HTML;

                                            }
            $output['data'] .= <<<HTML
                                                </select>
                                            </div>
                                        HTML;
                                    }
                                                
                                    
    $output['data'] .= <<<HTML
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" onclick="editFicha({$id_ficha})" class="btn btn-primary">Editar ficha</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        HTML;

    }
  
    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON
 

?>