<?php

    require_once "../modelo/clase_ficha.php"; //Llamo a la clase 

    $id_coti = $_POST["id_coti"]  ?? null;
    $tipoequipo = $_POST["tipoequipo"]  ?? null;
    $marca = $_POST["marca"]  ?? null;
    $potencia = $_POST["potencia"]  ?? null;
    $unipotencia = $_POST["unipotencia"]  ?? null;
    $fases = $_POST["fases"]  ?? null;
    $tensionpri = $_POST["tensionpri"]  ?? null;
    $neutroent = $_POST["neutroent"]  ?? null;
    $neutrosal = $_POST["neutrosal"]  ?? null;
    $bornespri = $_POST["bornespri"]  ?? null;
    $tensionsecun = $_POST["tensionsecun"]  ?? null;
    $bornessec = $_POST["bornessec"]  ?? null;
    $factorpot = $_POST["factorpot"]  ?? null;
    $grupocon = $_POST["grupocon"]  ?? null;
    $factor = $_POST["factor"]  ?? null;
    $gradopro = $_POST["gradopro"]  ?? null;
    $clase = $_POST["clase"]  ?? null;
    $altitud = $_POST["altitud"]  ?? null;
    $montaje = $_POST["montaje"]  ?? null;
    $material = $_POST["material"]  ?? null;
    $altoficha = $_POST["altoficha"]  ?? null;
    $anchoficha = $_POST["anchoficha"]  ?? null;
    $largoficha = $_POST["largoficha"]  ?? null;
    $pesoficha = $_POST["pesoficha"]  ?? null;
    $conpri = $_POST["conpri"]  ?? null;
    $consec = $_POST["consec"]  ?? null;
    $regEnt1 = $_POST["regEnt1"]  ?? null;
    $regEnt2 = $_POST["regEnt2"]  ?? null;
    $regSal = $_POST["regSal"]  ?? null;

    $ficha_obj = new Ficha();

    try {
        $resultado = $ficha_obj->registrar_ficha(
            $id_coti,
            $tipoequipo,
            $marca,
            $potencia,
            $unipotencia,
            $fases,
            $tensionpri,
            $neutroent,
            $neutrosal,
            $bornespri,
            $tensionsecun,
            $bornessec,
            $factorpot,
            $grupocon,
            $factor,
            $gradopro,
            $clase,
            $altitud,
            $montaje,
            $material,
            $altoficha,
            $anchoficha,
            $largoficha,
            $pesoficha,
            $conpri,
            $consec,
            $regEnt1,
            $regEnt2,
            $regSal
        );
    
        // Mostrar resultados
        $output = [];
        $output['data'] = $resultado;
    
        echo json_encode($output, JSON_UNESCAPED_UNICODE); // Enviamos los datos encriptados en un JSON
    } catch (Exception $e) {
        // Manejo de errores
        $errorOutput = ['error' => $e->getMessage()];
        echo json_encode($errorOutput, JSON_UNESCAPED_UNICODE);
    }

?>