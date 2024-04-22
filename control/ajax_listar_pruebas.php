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

    require_once "../modelo/clase_pruebas.php"; //Llamo a la clase

    $pruebas = new Pruebas();
    list($resultado, $totalFiltro, $totalRegistros, $columns) = $pruebas->listado_pruebas($campo, $limit, $pagina, $orderCol, $orderType);

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
                    <td>{$row['cliente']}</td>
                    <td>{$row['fecha']}</td>
                    <td>
                        <button type="button" class="btn btn-danger" onclick="getPruebas({$row['id_pruebas']})" id="{$row['id_pruebas']}"">
                            <i class="fas fa-file-pdf"></i>
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarPruebas{$row['id_pruebas']}"><i class="fas fa-pen"></i></button>
                        
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarPruebas{$row['id_pruebas']}"><i class="far fa-trash-can"></i></button>
                    </td>
                    <td>
                    
                    <div class="modal fade" id="eliminarPruebas{$row['id_pruebas']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <input type="hidden" value="{$row['id_pruebas']}">
                                <h4 class="modal-title">¿Estas seguro?</h4>
                                <div class="modal-footer justify-content-center">
                                    <button  type="button" onclick="eliminarPruebas({$row['id_pruebas']})" class="btn btn-danger">Ok</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editarPruebas{$row['id_pruebas']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                        <form method="POST" action="../control/editar_pruebas.php" id="formEditPruebas{$row['id_pruebas']}">
                        <div class="modal-content">
                        <div class="modal-header text-center">
                            <h4 class="modal-title">NUEVO PROTOCOLO DE PRUEBA</h4>
                            <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                            <div class="row modal-body mx-4">
                            <div class="col-md-4">
                                <div class="md-form mb-2">
                                        <i class="grey-text">Cliente:</i>
                                        <input type="text" name="cliente" id="cliente{$row['id_pruebas']}" class="form-control" value="{$row['cliente']}" placeholder="Cliente" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Datos Tecnicos</i>
                                        <input type="text" class="form-control" name="datos_t" id="datos_t{$row['id_pruebas']}" value="{$row['datos_t']}" placeholder="Datos Tecnicos" required>
                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Fecha</i>
                                        <input type="date" class="form-control" name="fecha" id="fecha{$row['id_pruebas']}" value="{$row['fecha']}" placeholder="Marca" required>
                                    </div>
                                    </div>
                            </div>
                            <div class="row modal-body mx-4">
                                <div class="col-md-12 col-lg-4">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Potencia:</i>
                                        <div class="input-group">
                                            <input type="number" class="form-control" value="{$row['potencia']}" name="potencia" id="potencia{$row['id_pruebas']}" placeholder="Potencia" required>
                                            <select name="unipotencia" id="unipotencia{$row['id_pruebas']}" class="form-select col-5">
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
                                        <i class="grey-text">V1:</i>
                                        <div class="input-group">
                                            <input type="number" class="form-control" value="{$row['v1']}" name="v1" id="v1{$row['id_pruebas']}" placeholder="V1" required>
                                            <span class="input-group-text">
                                                V.
                                            </span>
                                        </div>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">V2:</i>
                                        <div class="input-group">
                                            <input type="number" class="form-control" value="{$row['v2']}" name="v2" id="v2{$row['id_pruebas']}" placeholder="V2" required>
                                            <span class="input-group-text">
                                                V.
                                            </span>
                                        </div>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">L1:</i>
                                        <div class="input-group">
                                            <input type="number" class="form-control" value="{$row['l1']}" name="l1" id="l1{$row['id_pruebas']}" placeholder="L1" required>
                                            <span class="input-group-text">
                                                A.
                                            </span>
                                        </div>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">I2:</i>
                                        <div class="input-group">
                                            <input type="number" class="form-control" value="{$row['l2']}" name="l2" id="l2{$row['id_pruebas']}" placeholder="I2" required>
                                            <span class="input-group-text">
                                                A.
                                            </span>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-12 col-lg-4">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Fases:</i>
                                        <input type="number" class="form-control" name="fases" id="fases{$row['id_pruebas']}" value="{$row['fases']}" placeholder="Fases" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Frecuencia:</i>
                                        <div class="input-group">
                                            <input type="number" class="form-control" value="{$row['frecuencia']}" placeholder="Frecuencia" name="frecuencia" id="frecuencia{$row['id_pruebas']}" required>
                                            <span class="input-group-text">
                                                HZ
                                            </span>
                                        </div>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Conexión:</i>
                                        <input type="text" class="form-control" name="conexion" id="conexion{$row['id_pruebas']}" value="{$row['conexion']}" placeholder="Conexion" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Grupo:</i>
                                        <input type="text" class="form-control" name="grupo" id="grupo{$row['id_pruebas']}" value="{$row['grupo']}" placeholder="Grupo" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Altitud:</i>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="altitud" id="altitud{$row['id_pruebas']}" value="{$row['altitud']}" placeholder="Altitud" required>
                                            <span class="input-group-text">
                                                m.s.n.m
                                            </span>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-12 col-lg-4">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Marca:</i>
                                        <input type="text" class="form-control" name="marca" id="marca{$row['id_pruebas']}" value="{$row['marca']}" placeholder="Marca" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Numero de Serie:</i>
                                        <input type="text" class="form-control" name="serie" id="serie{$row['id_pruebas']}" value="{$row['serie']}" placeholder="numero de serie" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Año de Fabricación:</i>
                                        <select name="fabricacion" id="fabricacion{$row['id_pruebas']}" class="form-select" required>
                HTML;
                                                $anio_actual = date("Y");
                                                for ($i = 2020; $i <= $anio_actual + 100; $i++) {
                                                    $selected = $row['fabricacion'] == $i ? 'selected' : '';
                                                    $output['data'] .= <<<HTML
                                                        <option {$selected}>{$i}</option>
                                                    HTML;
                                                }

                $output['data'] .= <<<HTML
                                        </select>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Norma:</i>
                                        <input type="text" class="form-control" name="norma" id="norma{$row['id_pruebas']}" value="{$row['norma']}" placeholder="Norma" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row modal-body mx-4">
                                <div class="col-md-12">
                                    PRUEBA DE RELACION DE TRANSFORMACION:
                                </div>
                            </div>
                            <div class="row modal-body mx-4">
                                <div class="col-md-12 col-lg-3">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Relacion teorica:</i>
                                        <input type="number" class="form-control" name="rela_teo" id="rela_teo{$row['id_pruebas']}" value="{$row['rela_teo']}" placeholder="Relacion teorica" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-3">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">U - V:</i>
                                        <input type="text" class="form-control" name="uv1" id="uv1{$row['id_pruebas']}" value="{$row['uv1']}" placeholder="U - V" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-3">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">U - V:</i>
                                        <input type="text" class="form-control" name="uv2" id="uv2{$row['id_pruebas']}" value="{$row['uv2']}" placeholder="U - V" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-3">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">U - V:</i>
                                        <input type="text" class="form-control" name="uv3" id="uv3{$row['id_pruebas']}" value="{$row['uv3']}" placeholder="U - V" required>
                                    </div>
                                </div>
                            </div>


                            <div class="row modal-body mx-4">
                                <div class="col-md-12">
                                    PRUEBA DE VACIO:
                                </div>
                            </div>
                            <div class="row modal-body mx-4">
                                <div class="col-md-12 col-lg-6">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">TENSION u-v:</i>
                                        <input type="number" class="form-control" name="tensionu_v" id="tensionu_v{$row['id_pruebas']}" value="{$row['tensionu_v']}" placeholder="TENSION u-v" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">TENSION v-w:</i>
                                        <input type="number" class="form-control" name="tensionv_w" id="tensionv_w{$row['id_pruebas']}" value="{$row['tensionv_w']}" placeholder="TENSION v-w" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">TENSION w-u:</i>
                                        <input type="number" class="form-control" name="tensionw_u" id="tensionw_u{$row['id_pruebas']}" value="{$row['tensionw_u']}" placeholder="TENSION w-u" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">INTENSIDAD u-v:</i>
                                        <input type="number" class="form-control" placeholder="INTENSIDAD u-v" name="intensidadu_v" id="intensidadu_v{$row['id_pruebas']}" value="{$row['intensidadu_v']}" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">INTENSIDAD v-w:</i>
                                        <input type="number" class="form-control" placeholder="INTENSIDAD v-w" name="intensidadv_w" id="intensidadv_w{$row['id_pruebas']}" value="{$row['intensidadv_w']}" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">INTENSIDAD w-u:</i>
                                        <input type="number" class="form-control" placeholder="INTENSIDAD w-u" name="intensidadw_u" id="intensidadw_u{$row['id_pruebas']}" value="{$row['intensidadw_u']}" required>
                                    </div>
                                </div>
                            </div>


                            <div class="row modal-body mx-4">
                                <div class="col-md-12">
                                    MEDIDA DE LA RESISTENCIA DE AISLAMIENTO:
                                </div>
                            </div>
                            <div class="row modal-body mx-4">
                                <div class="col-md-12 col-lg-4">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">AT-BT:</i>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="at_bt" id="at_bt{$row['id_pruebas']}" value="{$row['at_bt']}" placeholder="AT-BT:" required>
                                            <select name="at_bt_und" id="at_bt_und{$row['id_pruebas']}" class="form-select col-5">
                                                <option>G-ohm</option>
                                                <option>M-ohm</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-4">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">AT-M:</i>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="at_m" id="at_m{$row['id_pruebas']}" value="{$row['at_m']}" placeholder="AT-M:" required>
                                            <select name="at_m_und" id="at_m_und{$row['id_pruebas']}" class="form-select col-5">
                                                <option>G-ohm</option>
                                                <option>M-ohm</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-4">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">BT-M:</i>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="bt_m" id="bt_m{$row['id_pruebas']}" value="{$row['bt_m']}" placeholder="BT-M:" required>
                                            <select name="bt_m_und" id="bt_m_und{$row['id_pruebas']}" class="form-select col-5">
                                                <option>G-ohm</option>
                                                <option>M-ohm</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row modal-body mx-4">
                                <div class="col-md-12">
                                    MEDIDA DEL ESPESOR DE PINTURA:
                                </div>
                            </div>
                            <div class="row modal-body mx-4">
                                <div class="col-md-12 col-lg-4">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Valor minimo:</i>
                                        <input type="number" class="form-control" name="minimo" id="minimo{$row['id_pruebas']}" value="{$row['minimo']}" placeholder="Valor minimo" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-4">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Valor medido:</i>
                                        <input type="number" class="form-control" name="medido" id="medido{$row['id_pruebas']}" value="{$row['medido']}" placeholder="Valor medido" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-4">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Resultado:</i>
                                        <input type="text" class="form-control" name="resultado" id="resultado{$row['id_pruebas']}" value="{$row['resultado']}" placeholder="Resultado" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row modal-body mx-4">
                                <div class="col-md-12">
                                    PRUEBA DE CORTO CIRCUITO
                                </div>
                            </div>
                            <div class="row modal-body mx-4">
                                <div class="col-md-12 col-lg-6">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">INTENSIDAD</i>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Lectura:</i>
                                        <input type="number" class="form-control" name="int_lectura" id="int_lectura{$row['id_pruebas']}" value="{$row['int_lectura']}" placeholder="Lectura" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Valor:</i>
                                        <input type="number" class="form-control" name="int_valor" id="int_valor{$row['id_pruebas']}" value="{$row['int_valor']}" placeholder="Valor" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">TENSION</i>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Lectura:</i>
                                        <input type="number" class="form-control" name="ten_lectura" id="ten_lectura{$row['id_pruebas']}" value="{$row['ten_lectura']}" placeholder="Lectura" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row modal-body mx-4">
                                <div class="col-md-12 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="checkresis" id="checkresis{$row['id_pruebas']}" onchange="">
                                    <label class="form-check-label" for="checkResis">Prueba de resistencia</label>
                                </div>
                            </div>
                            
                HTML;

                            if($row['checkresis'] == 1){
                $output['data'] .= <<<HTML
                                
                                    <div class="row modal-body mx-4">
                                        <div class="col-md-12">
                                            PRUEBA DE RESISTENCIA DE ARROLLAMIENTO
                                        </div>
                                    </div>
                                    <div class="row modal-body mx-4">
                                        <div class="col-md-12 col-lg-6">
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Arrollamiento en BT</i>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Tension u - v:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="bt_tension_u_v" id="bt_tension_u_v{$row['id_pruebas']}" value="{$row['bt_tension_u_v']}" placeholder="Tension u - v" required>
                                                    <span class="input-group-text">
                                                        mV
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Tension v - w:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="bt_tension_v_w" id="bt_tension_v_w{$row['id_pruebas']}" value="{$row['bt_tension_v_w']}" placeholder="Tension u - v" required>
                                                    <span class="input-group-text">
                                                        mV
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Tension w - u:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="bt_tension_w_u" id="bt_tension_w_u{$row['id_pruebas']}" value="{$row['bt_tension_w_u']}" placeholder="Tension u - v" required>
                                                    <span class="input-group-text">
                                                        mV
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Intensidad u - v:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="bt_intensidad_u_v" id="bt_intensidad_u_v{$row['id_pruebas']}" value="{$row['bt_intensidad_u_v']}" placeholder="Intensidad u - v" required>
                                                    <span class="input-group-text">
                                                        mA
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Intensidad v - w:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="bt_intensidad_v_w" id="bt_intensidad_v_w{$row['id_pruebas']}" value="{$row['bt_intensidad_v_w']}" placeholder="Intensidad u - v" required>
                                                    <span class="input-group-text">
                                                        mA
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Intensidad w - u:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="bt_intensidad_w_u" id="bt_intensidad_w_u{$row['id_pruebas']}" value="{$row['bt_intensidad_w_u']}" placeholder="Intensidad u - v" required>
                                                    <span class="input-group-text">
                                                        mA
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Resistencia u - v:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="bt_resistencia_u_v" id="bt_resistencia_u_v{$row['id_pruebas']}" value="{$row['bt_resistencia_u_v']}" placeholder="Resistencia u - v" required>
                                                    <span class="input-group-text">
                                                        mΩ
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Resistencia v - w:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="bt_resistencia_v_w" id="bt_resistencia_v_w{$row['id_pruebas']}" value="{$row['bt_resistencia_v_w']}" placeholder="Resistencia u - v" required>
                                                    <span class="input-group-text">
                                                        mΩ
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Resistencia w - u:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="bt_resistencia_w_u" id="bt_resistencia_w_u{$row['id_pruebas']}" value="{$row['bt_resistencia_w_u']}" placeholder="Resistencia u - v" required>
                                                    <span class="input-group-text">
                                                        mΩ
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-6">
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Arrollamiento en AT</i>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Tension u - v:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="at_tension_u_v" id="at_tension_u_v{$row['id_pruebas']}" value="{$row['at_tension_u_v']}" placeholder="Tension u - v" required>
                                                    <span class="input-group-text">
                                                        mV
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Tension v - w:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="at_tension_v_w" id="at_tension_v_w{$row['id_pruebas']}" value="{$row['at_tension_v_w']}" placeholder="Tension u - v" required>
                                                    <span class="input-group-text">
                                                        mV
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Tension w - u:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="at_tension_w_u" id="at_tension_w_u{$row['id_pruebas']}" value="{$row['at_tension_w_u']}" placeholder="Tension u - v" required>
                                                    <span class="input-group-text">
                                                        mV
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Intensidad u - v:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="at_intensidad_u_v" id="at_intensidad_u_v{$row['id_pruebas']}" value="{$row['at_intensidad_u_v']}" placeholder="Intensidad u - v" required>
                                                    <span class="input-group-text">
                                                        mA
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Intensidad v - w:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="at_intensidad_v_w" id="at_intensidad_v_w{$row['id_pruebas']}" value="{$row['at_intensidad_v_w']}" placeholder="Intensidad u - v" required>
                                                    <span class="input-group-text">
                                                        mA
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Intensidad w - u:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="at_intensidad_w_u" id="at_intensidad_w_u{$row['id_pruebas']}" value="{$row['at_intensidad_w_u']}" placeholder="Intensidad u - v" required>
                                                    <span class="input-group-text">
                                                        mA
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Resistencia u - v:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="at_resistencia_u_v" id="at_resistencia_u_v{$row['id_pruebas']}" value="{$row['at_resistencia_u_v']}" placeholder="Resistencia u - v" required>
                                                    <span class="input-group-text">
                                                        mΩ
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Resistencia v - w:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="at_resistencia_v_w" id="at_resistencia_v_w{$row['id_pruebas']}" value="{$row['at_resistencia_v_w']}" placeholder="Resistencia u - v" required>
                                                    <span class="input-group-text">
                                                        mΩ
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Resistencia w - u:</i>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="at_resistencia_w_u" id="at_resistencia_w_u{$row['id_pruebas']}" value="{$row['at_resistencia_w_u']}" placeholder="Resistencia u - v" required>
                                                    <span class="input-group-text">
                                                        mΩ
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                HTML;
                            }

                $output['data'] .= <<<HTML
                                
                            <div class="modal-footer justify-content-center">
                                <button  type="button" id="btnEditPruebas{$row['id_pruebas']}" onclick="editarPruebas({$row['id_pruebas']})" class="btn btn-primary">Editar</button>
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