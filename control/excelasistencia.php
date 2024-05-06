<?php

    require '../vendor/autoload.php';
    require_once "../modelo/clase_usuario.php"; //Llamo a la clase

    $limit = isset($_POST['registros']) ? $_POST['registros'] : 10; //Dato que viene de la vista para hacer el limite
    $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : 0; 

    $dateIn = $_POST['dateIn'] ?? null;
    $dateOut = $_POST['dateOut'] ?? null;
    $selectUser = $_POST['selectUser'] ?? null;

    $usuario = new Usuario();
    list($resultado, $totalFiltro, $totalRegistros, $columns) = $usuario->filtro_asistencia(NULL, $pagina, $dateIn, $dateOut, $selectUser);
   
    $num_rows = $resultado->num_rows;

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    // Establecer estilos para el Header
    $styleHeader = [
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'], // Color del texto
        ],
        'fill' => [
            'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => '0053D2'], // Color de fondo
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
        'alignment' => [
            'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ];

    // Establecer estilos para el Body
    $styleBody = [
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'], // Color del texto
        ],
        'fill' => [
            'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => '9EC5FF'], // Color de fondo
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
        'alignment' => [
            'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ];

    //Generar el excel
    try{

        // Crear una nueva instancia de la clase Spreadsheet
        $spreadsheet = new Spreadsheet();
    
        // Obtener la hoja activa
        $sheet = $spreadsheet->getActiveSheet();

        $indice=1;

        if ($num_rows > 0){//Verificamos que haya algun resultado
            
            // Agregar datos a la hoja de cálculo
            $sheet->setCellValue('A1', 'Nombre y apellido');
            $sheet->setCellValue('B1', 'Fecha');
            $sheet->setCellValue('C1', 'Hora entrada');
            $sheet->setCellValue('D1', 'Hora salida');
            $sheet->setCellValue('E1', 'Tiempo de trabajo');
            
            while($row = $resultado->fetch_array()){ 

                $indice++;
    
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
                // Agregar datos a la hoja de cálculo
                $sheet->setCellValue('A'.$indice, $array_datos[2] . ' ' . $array_datos[3]);
                $sheet->setCellValue('B'.$indice, date("d-m-Y", strtotime($row[3])));
                $sheet->setCellValue('C'.$indice, $hora_inicio);
                $sheet->setCellValue('D'.$indice, $hora_final);
                $sheet->setCellValue('E'.$indice, $intervaloFormateado);

                // Aplicar estilos a las celdas de datos
                $sheet->getStyle('A'.$indice.':E'.$indice)->applyFromArray($styleBody);
                    
            }
        }else{
            $sheet->setCellValue('A1', 'Sin resultados');
        }
        
        // Crear un objeto de escritura para el formato Xlsx
        $writer = new Xlsx($spreadsheet);
    
        // Definir el nombre del archivo
        $filename = 'Listado asistencia-'. date('d-m-Y') .'.xlsx';
    
        // Configurar el encabezado para descargar el archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Aplicar estilos a las celdas A1:E1
        $sheet->getStyle('A1:E1')->applyFromArray($styleHeader);

        // Configurar el ancho automático de las columnas basado en el contenido
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Guardar el archivo en el sistema de archivos
        $writer->save('php://output');

    } catch (Exception $e) {
        echo 'Se produjo un error: ',  $e->getMessage(), "\n";
    }

?>
