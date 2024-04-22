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

    require_once "../modelo/clase_usuario.php"; //Llamo a la clase
    require_once "../modelo/clase_clientes.php"; //Llamo a la clase

    $cliente = new Clientes();
    list($resultado, $totalFiltro, $totalRegistros, $columns) = $cliente->listado_clientes($campo, $limit, $pagina, $orderCol, $orderType);

    $num_rows = $resultado->num_rows; 

    $usuario = new Usuario();

    //Mostrar resultados
    $output = [];
    $output['totalRegistros'] = $totalRegistros;
    $output['totalFiltro'] = $totalFiltro;
    $output['data'] = '';
    $output['paginacion'] = '';

    if ($num_rows > 0){//Verificamos que haya algun resultado
        while($row = $resultado->fetch_array()){ 

            $arraytipocliente = [
                'Distribuidor' => 'Distribuidor',
                'Cliente final' => 'Cliente final',
            ];

            $arraypago = [

                'Contado' => 'Contado',
                'Credito 30 dias' => 'Credito 30 dias',
                'Credito 45 dias' => 'Credito 45 dias',
                'Credito 60 dias' => 'Credito 60 dias',
                'Credito 90 dias' => 'Credito 90 dias',
                'Cheque 30 dias' => 'Cheque 30 dias',
                'Adelanto 50%, Saldo al finalizar' => 'Adelanto 50%, Saldo al finalizar',

            ];

            $datosUser = $usuario->getUser($row[6]);
            $arrayUser = $datosUser->fetch_assoc();

            $output['data'] .= <<<HTML

                <tr>
                    <td>{$row[1]}</td>
                    <td>{$row[2]}</td>
                    <td>{$row[3]}</td>
                    <td>{$arrayUser['nombres']} {$arrayUser['apellidos']}</td>
                    <td>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalContactos{$row[0]}">Ver mas</button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarCliente{$row[0]}"><i class="fas fa-pen"></i></button>
                        
                        <button type="button" class="btn btn-danger" style="margin-left: 10px" data-bs-toggle="modal" data-bs-target="#eliminarCliente{$row[0]}"><i class="far fa-trash-can"></i></button>
                    </td>
                    <td>
                    <form>
                    <div class="modal fade" id="modalContactos{$row[0]}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">MOSTRAR CONTACTOS</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body mx-4">
                                    Contacto
                                    <div class="row justify-content-center">
                HTML;
            
                $list_contacto2 = $cliente->listado_contacto($row[0]);
                while($contacto2 = $list_contacto2->fetch_array()){
                                        
                $output['data'] .= <<<HTML

                                            <div class="col-lg-3 col-md-3 col-xs-12">
                                                <input type="text" class="form-control shownombre{$row[0]}" placeholder="Nombre y apellido" value="{$contacto2[1]}" readonly>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12">
                                                <input type="text" class="form-control showtelefono{$row[0]}"  value="{$contacto2[2]}" placeholder="Telefono" readonly>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12">
                                                <input type="text" class="form-control showcorreo{$row[0]}"  value="{$contacto2[3]}" placeholder="Correo electronico" readonly>
                                            </div>                                 
                                            <div class="col-lg-3 col-md-3 col-xs-12">
                                                <input type="text" class="form-control showcargo{$row[0]}"  value="{$contacto2[4]}" placeholder="Cargo" readonly>
                                            </div>                                 
                                
                HTML;

                }

                $item = 1;

                $output['data'] .= <<<HTML
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 

                    <div class="modal fade" id="eliminarCliente{$row[0]}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header text-center">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <h4 class="modal-title">¿Estas seguro?</h4>
                            <div class="modal-footer justify-content-center">
                            <input type="hidden" name="id_cliente" value="{$row[0]}">
                            <button  type="submit"  onclick="eliminarCliente({$row[0]})" class="btn btn-danger">Ok</button>
                            </div>
                        </div>
                        </div>
                    </div> 

                    <div class="modal fade" id="editarCliente{$row[0]}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header text-center">
                                <h4 class="modal-title">EDITAR DATOS DEL CLIENTE</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body mx-4 text-justify">
                                <div class="row justify-content-center">
                                    <div class="md-form mb-2">
                                        <i class="grey-text">RUC/DNI</i>
                                        <input type="text" class="form-control validate" id="numero{$row[0]}" value="{$row[1]}"  placeholder="RUC / DNI" required>
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Razon social / Nombre</i>
                                        <input type="text" class="form-control validate" value="{$row[2]}" id="entidad{$row[0]}" placeholder="Razon social / Nombre y apellido" required>
                                    </div><br><br>
                                    <div class="md-form mb-2 row">
                                        <i class="grey-text col">Dirección</i>
                                        <i class="grey-text col">Distrito</i>
                                        <i class="grey-text col">Departamento</i>
                                    </div>
                                    <div class="md-form mb-2 row">
                                        <input type="text" id="direccion{$row[0]}" value="{$row[3]}" class="form-control col-4" placeholder="Dirección" required="">
                                        <input type="text" id="distrito{$row[0]}" value="{$row[4]}" class="form-control col-4" placeholder="Distrito" required="">
                                        <input type="text" id="departamento{$row[0]}" value="{$row[5]}" class="form-control col-4" placeholder="Departamento" required="">
                                    </div>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Tipo de cliente</i>
                                        <select name="tipocliente" id="tipocliente{$row[0]}" class="form-select">
                HTML;                         

                $tipoClienteSel = $row[7];
                foreach ($arraytipocliente as $clave => $valor) {
                    $selected = ($tipoClienteSel === $clave) ? 'selected' : '';
                    $output['data'] .= <<<HTML
                                            <option value="{$clave}" {$selected}>{$valor}</option>
                    HTML;
                    
                }

                $output['data'] .= <<<HTML
                                        </select>
                                    </div><br><br>
                                    <div class="md-form mb-2">
                                        <i class="grey-text">Tipo de pago</i>
                                        <div class="input-group" id="inputpago{$row[0]}"  onselectstart="return false;">
                                            <select name="pagocliente" id="pagocliente{$row[0]}" class="form-select">

                HTML;                         

                $pagoSelec = $row[8];
                foreach ($arraypago as $clave => $valor) {
                    $selected = ($pagoSelec === $clave) ? 'selected' : '';
                    $output['data'] .= <<<HTML
                                            <option value="{$clave}" {$selected}>{$valor}</option>
                    HTML;
                    
                }

                $output['data'] .= <<<HTML

                                            </select>
                                            <span class="input-group-text" onclick="tipearPago({$row[0]})">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                </svg>
                                            </span> 
                                        </div>
                                    </div>
                                </div>
                                
                                Contacto
                                <div id="cont_contactos{$row[0]}" class="row justify-content-center">

                HTML;

                $list_contacto = $cliente->listado_contacto($row[0]);
                while($contacto = $list_contacto->fetch_array()){
                
                    $output['data'] .= <<<HTML

                                        <div class="row" id="cont{$item}{$row[0]}">
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Nombre</i>
                                                <input type="text" class="form-control nombre{$row[0]}" placeholder="Nombre y apellido" value="{$contacto[1]}" id="nombre{$row[0]}">
                                                <input class="id_contacto{$row[0]}" type="hidden" value="{$contacto[0]}">
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Telefono</i>
                                                <input type="text" class="form-control telefono{$row[0]}"  value="{$contacto[2]}" id="telefono{$row[0]}" placeholder="Telefono">
                                            </div>
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Correo electronico</i>
                                                <input type="text" class="form-control correo{$row[0]}"  value="{$contacto[3]}" id="correo{$row[0]}" placeholder="Correo electronico">
                                            </div>                                 
                                            <div class="md-form mb-2">
                                                <i class="grey-text">Cargo</i>
                                                <input type="text" class="form-control cargo{$row[0]}"  value="{$contacto[4]}" id="cargo{$row[0]}" placeholder="Cargo">
                                            </div> 
                                            <div class="col-lg-2 col-md-2 col-xs-12" id="divButton{$contacto[0]}">
                                                <button type="button" class="btn btn-danger" style="margin-left: 10px" onclick="getButtonDelete({$contacto[0]},{$item},{$row[0]})"><i class="far fa-trash-can"></i></button>
                                            </div>                                      
                                        </div>                                    
                                    
                    HTML;
                    $item++;

                }
                            
                $output['data'] .= <<<HTML
                            
                                </div>

                                <button type="button" class="btn btn-primary" onclick="añadirContacto2({$item}, {$row[0]})" id="addcontacto">Agregar contacto</button>

                                <div class="modal-footer justify-content-center">
                                    <button  type="button" onclick="editarCliente({$row[0]})" class="btn btn-primary">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                    </td>      
                </tr>      
            HTML;
                
        }
    }else{
        $output['data'] .= <<<HTML
                <tr>
                    <td colspan="7">Sin resultados</td>
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