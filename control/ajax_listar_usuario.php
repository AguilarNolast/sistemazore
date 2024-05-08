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

    $usuario = new Usuario();
    list($resultado, $totalFiltro, $totalRegistros, $columns) = $usuario->listado_usuarios($campo, $limit, $pagina, $orderCol, $orderType);

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
                    <td>{$row[2]}</td>  
                    <td>{$row[3]}</td>  
                    <td>{$row[1]}</td>  
                    <td>{$row[5]}</td>  
                    <td>{$row[4]}</td>  
                    <td>
                        <div class="d-flex flex-row justify-content-center">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarUsuario{$row[0]}"><i class="fas fa-pen"></i></button>

                            <button type="button" class="btn btn-danger" style="margin-left: 10px" data-bs-toggle="modal" data-bs-target="#eliminarUsuario{$row[0]}"><i class="far fa-trash-can"></i></button>
                        </div>   
                    </td>   
                    <td>   
                        <!-- Modal -->
                        <div class="modal fade" id="editarUsuario{$row[0]}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form method="POST" action="../control/editar_usuario.php" id="formUserEdit{$row[0]}">
                                    <div class="modal-content">
                                        <div class="modal-header text-center">
                                            <h4 class="modal-title">EDITAR USUARIO</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body mx-4 text-justify">
                                            <div class="md-form mb-4">
                                                <i class="grey-text">Nombre</i>
                                                <input type="text" class="form-control validate" name="nombre" id="nombre{$row[0]}" value="{$row[2]}" placeholder="nombre" required>
                                            </div>
                                            <div class="md-form mb-4">
                                                <i class="grey-text">Apellido</i>
                                                <input type="text" class="form-control validate" name="apellido" id="apellido{$row[0]}" value="{$row[3]}" placeholder="apellido" required>
                                            </div>
                                            <div class="md-form mb-4">
                                                <i class="grey-text">Usuario</i>
                                                <input type="text" class="form-control validate" name="usuario" id="usuario{$row[0]}" value="{$row[1]}" placeholder="Usuario" required>
                                            </div>
                                            <div class="md-form mb-4">
                                                <i class="grey-text">Correo electronico</i>
                                                <input type="email" name="correo" class="form-control validate" id="correo{$row[0]}" value="{$row[5]}" placeholder="Correo electronico" required>
                                            </div>
                                            <div class="md-form mb-4">
                                                <i class="grey-text">Telefono</i>
                                                <input type="text" class="form-control validate" name="telefono" id="telefono{$row[0]}" value="{$row[4]}" placeholder="Telefono" required>
                                            </div>
                                            <div class="input-group md-form mb-4">
                                                <span class="input-group-text"><i class="fas fa-lock prefix grey-text"></i></span> 
                                                <input type="password" onkeyup="compararClave2({$row[0]})" class="form-control validate" name="clave" id="clave{$row[0]}" placeholder="Contraseña" required>        
                                                <span class="input-group-text" id="clave_span{$row[0]}"></span>
                                            </div>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text"><i class="fas fa-lock prefix grey-text"></i></span>
                                                <input type="password" onkeyup="compararClave2({$row[0]})" class="form-control validate" name="rep_clave" id="rep_clave{$row[0]}" placeholder="Repita contraseña" aria-describedby="basic-addon2" required>        
                                                <span class="input-group-text" id="rep_clave_span{$row[0]}"></span>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" onclick="editarUsuario({$row[0]})" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal fade" id="eliminarUsuario{$row[0]}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-header text-center">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <h4 class="modal-title">¿Estas seguro?</h4>
                                    <div class="modal-footer justify-content-center">
                                    <input type="hidden" name="id_cliente" value="{$row[0]}">
                                    <button  type="submit"  onclick="eliminarUsuario({$row[0]})" class="btn btn-danger">Ok</button>
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
                <td colspan="5">Sin resultados</td>
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