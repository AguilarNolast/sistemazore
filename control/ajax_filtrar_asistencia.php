<?php

    session_start();

    //Agregamos el limite

    $limit = isset($_POST['registros']) ? $_POST['registros'] : 10; //Dato que viene de la vista para hacer el limite
    $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 0; 

    $dateIn = $_POST["dateIn"] ?? null;
    $dateOut = $_POST["dateOut"] ?? null;
    $selectUser = $_POST["selectUser"] ?? null;

    require_once "../modelo/clase_usuario.php"; //Llamo a la clase

    $usuario = new Usuario();
    list($resultado, $totalFiltro, $totalRegistros, $columns) = $usuario->filtro_asistencia($limit, $pagina, $dateIn, $dateOut, $selectUser);

    $num_rows = $resultado->num_rows; 

    //Mostrar resultados
    $output = [];
    $output['totalRegistros'] = $totalRegistros;
    $output['totalFiltro'] = $totalFiltro;
    $output['data'] = '';
    $output['paginacion'] = '';

    if ($num_rows > 0){//Verificamos que haya algun resultado
        while($row = $resultado->fetch_array()){ 

            $user = new Usuario();

            $datos_user = $user->getUser($row[0]);

            $array_datos = $datos_user->fetch_array();

            $hora_inicio = date("h:i:s A", strtotime($row[1]));
            $hora_final = $row[2] != '00:00:00' ? date("h:i:s A", strtotime($row[2])) : $row[2];
            
            if($row[2] != '00:00:00'){

                $entrada = new DateTime($hora_inicio);
                $salida = new DateTime($hora_final);
    
                // Calcula la diferencia entre las dos horas
                $intervalo = $entrada->diff($salida);
    
                // Obtiene directamente los valores de horas, minutos y segundos
                $horas = $intervalo->h;
                $minutos = $intervalo->i;
                $segundos = $intervalo->s;

                // Formatea la salida
                $intervaloFormateado = sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);

            }else{
                $hora_final = $row[2];

                $intervaloFormateado = '00:00:00';
            }

            $fecha = date("d-m-Y", strtotime($row[3]));

            $output['data'] .= <<<HTML
                <tr>
                <td>{$array_datos[2]} {$array_datos[3]}</td>
                <td>{$array_datos[1]}</td>
                <td>{$fecha}</td>
                <td>{$hora_inicio}</td>
                <td>{$hora_final}</td>
                <td>{$intervaloFormateado}</td>
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
                $output['paginacion'] .= '<li class="page-item"><a class="page-link" href="#" onclick="nextPageFilterAsistencia(' . $i . ')">' . $i . '</a></li>'; 
            }
        }

        $output['paginacion'] .= '</ul>';
        $output['paginacion'] .= '</nav>';
    }

    echo json_encode($output, JSON_UNESCAPED_UNICODE); //Enviamos los datos encriptados en un JSON

?>