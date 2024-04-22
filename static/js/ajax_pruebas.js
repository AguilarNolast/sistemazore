
function registrarPrueba(){
    
    const requiredInputs = document.querySelectorAll('#nuevaPrueba [required]');
    
    // Verificar que los campos requeridos no estén vacíos
    let camposVacios = false;
    let primerInputVacio = null;

    requiredInputs.forEach(input => {
        if (input.value.trim() === '') {
            camposVacios = true;

            // Si es el primer campo vacío, asignar el foco a ese campo
            if (!primerInputVacio) {
                primerInputVacio = input;
            }
        }
    });

    // Si hay campos vacíos, mostrar una alerta y asignar el foco al primer campo vacío
    if (camposVacios) {
        mostrarAlerta('danger', 'Por favor, complete todos los campos requeridos.');
        
        if (primerInputVacio) {
            primerInputVacio.focus();
        }
        
        removeAlert();

        return;
    }

    const formPruebas = document.getElementById('formPruebas');

    const formaData = new FormData(formPruebas);

    fetch(formPruebas.action, {
        method: formPruebas.method,
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            mostrarAlerta(data.tipo, data.mensaje);
        })
        .then(() => {
            getListadoPruebas();

            formPruebas.reset();
                
            $('#nuevaPrueba').modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('body').css('overflow', 'auto');
    
            removeAlert();

            document.getElementById('btnPruebas').disabled = false;
        })
        .catch(error => {
            mostrarAlerta('danger', error);
        });
        
        removeAlert();
}

function editarPruebas(id_pruebas) {

    const requiredInputs = document.querySelectorAll('#formEditPruebas'+id_pruebas+' [required]');
    
    // Verificar que los campos requeridos no estén vacíos
    let camposVacios = false;
    let primerInputVacio = null;

    requiredInputs.forEach(input => {
        if (input.value.trim() === '') {
            camposVacios = true;

            // Si es el primer campo vacío, asignar el foco a ese campo
            if (!primerInputVacio) {
                primerInputVacio = input;
            }
        }
    });

    // Si hay campos vacíos, mostrar una alerta y asignar el foco al primer campo vacío
    if (camposVacios) {
        mostrarAlerta('danger', 'Por favor, complete todos los campos requeridos.');
        
        if (primerInputVacio) {
            primerInputVacio.focus();
        }
        
        removeAlert();

        return;
    }

    // Deshabilitar el botón de enviar
    document.getElementById('btnEditPruebas'+id_pruebas).disabled = true;

    const formaData = new FormData(document.getElementById('formEditPruebas'+id_pruebas));
    formaData.append("id_pruebas", id_pruebas);
    formaData.append("cliente", document.getElementById('cliente'+id_pruebas).value);
    formaData.append("datos_t", document.getElementById('datos_t'+id_pruebas).value);
    formaData.append("fecha", document.getElementById('fecha'+id_pruebas).value);
    formaData.append("potencia", document.getElementById('potencia'+id_pruebas).value);
    formaData.append("unipotencia", document.getElementById('unipotencia'+id_pruebas).value);
    formaData.append("v1", document.getElementById('v1'+id_pruebas).value);
    formaData.append("v2", document.getElementById('v2'+id_pruebas).value);
    formaData.append("l1", document.getElementById('l1'+id_pruebas).value);
    formaData.append("l2", document.getElementById('l2'+id_pruebas).value);
    formaData.append("fases", document.getElementById('fases'+id_pruebas).value);
    formaData.append("frecuencia", document.getElementById('frecuencia'+id_pruebas).value);
    formaData.append("conexion", document.getElementById('conexion'+id_pruebas).value);
    formaData.append("grupo", document.getElementById('grupo'+id_pruebas).value);
    formaData.append("altitud", document.getElementById('altitud'+id_pruebas).value);
    formaData.append("marca", document.getElementById('marca'+id_pruebas).value);
    formaData.append("serie", document.getElementById('serie'+id_pruebas).value);
    formaData.append("fabricacion", document.getElementById('fabricacion'+id_pruebas).value);
    formaData.append("norma", document.getElementById('norma'+id_pruebas).value);
    formaData.append("uv1", document.getElementById('uv1'+id_pruebas).value);
    formaData.append("uv2", document.getElementById('uv2'+id_pruebas).value);
    formaData.append("uv3", document.getElementById('uv3'+id_pruebas).value);
    formaData.append("tensionu_v", document.getElementById('tensionu_v'+id_pruebas).value);
    formaData.append("tensionv_w", document.getElementById('tensionv_w'+id_pruebas).value);
    formaData.append("tensionw_u", document.getElementById('tensionw_u'+id_pruebas).value);
    formaData.append("intensidadu_v", document.getElementById('intensidadu_v'+id_pruebas).value);
    formaData.append("intensidadv_w", document.getElementById('intensidadv_w'+id_pruebas).value);
    formaData.append("intensidadw_u", document.getElementById('intensidadw_u'+id_pruebas).value);
    formaData.append("at_bt", document.getElementById('at_bt'+id_pruebas).value);
    formaData.append("at_m", document.getElementById('at_m'+id_pruebas).value);
    formaData.append("bt_m", document.getElementById('bt_m'+id_pruebas).value);
    formaData.append("at_bt_und", document.getElementById('at_bt_und'+id_pruebas).value);
    formaData.append("at_m_und", document.getElementById('at_m_und'+id_pruebas).value);
    formaData.append("bt_m_und", document.getElementById('bt_m_und'+id_pruebas).value);
    formaData.append("minimo", document.getElementById('minimo'+id_pruebas).value);
    formaData.append("resultado", document.getElementById('resultado'+id_pruebas).value);
    formaData.append("int_lectura", document.getElementById('int_lectura'+id_pruebas).value);
    formaData.append("int_valor", document.getElementById('int_valor'+id_pruebas).value);
    formaData.append("ten_lectura", document.getElementById('ten_lectura'+id_pruebas).value);

    if(document.getElementById('checkresis'+id_pruebas).checked){
        formaData.append("at_tension_u_v", document.getElementById('at_tension_u_v'+id_pruebas).value);
        formaData.append("at_tension_v_w", document.getElementById('at_tension_v_w'+id_pruebas).value);
        formaData.append("at_tension_w_u", document.getElementById('at_tension_w_u'+id_pruebas).value);
        formaData.append("at_intensidad_u_v", document.getElementById('at_intensidad_u_v'+id_pruebas).value);
        formaData.append("at_intensidad_v_w", document.getElementById('at_intensidad_v_w'+id_pruebas).value);
        formaData.append("at_intensidad_w_u", document.getElementById('at_intensidad_w_u'+id_pruebas).value);
        formaData.append("at_resistencia_u_v", document.getElementById('at_resistencia_u_v'+id_pruebas).value);
        formaData.append("at_resistencia_v_w", document.getElementById('at_resistencia_v_w'+id_pruebas).value);
        formaData.append("at_resistencia_w_u", document.getElementById('at_resistencia_w_u'+id_pruebas).value);
        formaData.append("bt_tension_u_v", document.getElementById('bt_tension_u_v'+id_pruebas).value);
        formaData.append("bt_tension_v_w", document.getElementById('bt_tension_v_w'+id_pruebas).value);
        formaData.append("bt_tension_w_u", document.getElementById('bt_tension_w_u'+id_pruebas).value);
        formaData.append("bt_intensidad_u_v", document.getElementById('bt_intensidad_u_v'+id_pruebas).value);
        formaData.append("bt_intensidad_v_w", document.getElementById('bt_intensidad_v_w'+id_pruebas).value);
        formaData.append("bt_intensidad_w_u", document.getElementById('bt_intensidad_w_u'+id_pruebas).value);
        formaData.append("bt_resistencia_u_v", document.getElementById('bt_resistencia_u_v'+id_pruebas).value);
        formaData.append("bt_resistencia_v_w", document.getElementById('bt_resistencia_v_w'+id_pruebas).value);
        formaData.append("bt_resistencia_w_u", document.getElementById('bt_resistencia_w_u'+id_pruebas).value);
    }
    
    formaData.append("checkresis", document.getElementById('checkresis'+id_pruebas).value);
    formaData.append("medido", document.getElementById('medido'+id_pruebas).value);
    formaData.append("rela_teo", document.getElementById('rela_teo'+id_pruebas).value);

    fetch(document.getElementById('formEditPruebas'+id_pruebas).action, {
        method: document.getElementById('formEditPruebas'+id_pruebas).method,
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            resultado.innerHTML = data.data;
        })
        .then(() => {
            getListadoPruebas();
            
            $('#editarPruebas'+id_pruebas).modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('body').css('overflow', 'auto');

            removeAlert();
        })
        .catch(error => {
            resultado.innerHTML = `
                <div class="alert alert-danger" id="miAlert" role="alert">
                    Error: ${error.message}
                </div>
            `
        });
}

function eliminarPruebas(id_pruebas) {
    
    const resultado = document.getElementById("resultado");

    const url = "../control/eliminarpruebas.php";
    const formaData = new FormData();
    formaData.append("id_pruebas", id_pruebas);

    fetch(url, {
        method: "POST",
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            resultado.innerHTML = data.data;
        })
        .then(() => {
            getListadoPruebas();
            
            $('#eliminarPruebas'+id_pruebas).modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('body').css('overflow', 'auto');

            removeAlert();
        })
        .catch(error => {
            resultado.innerHTML = `
                <div class="alert alert-danger" id="miAlert" role="alert">
                    Error: ${error.message}
                </div>
            `
        });
}

function getPruebas(id_pruebas){
    const url = "../control/get_pruebas.php";
    const formaData = new FormData();
    formaData.append("id_pruebas", id_pruebas);

    let arrayPruebas = [];

    fetch(url, {
        method: "POST",
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            arrayPruebas = data.pruebas;
        })
        .then(() => {
            documentDefinition = generarPdfPruebas(arrayPruebas);

            // Función para verificar si el dispositivo es un iPhone
            function esIPhone() {
                return /iPhone/i.test(navigator.userAgent);
            }

            // Ejemplo de uso
            if (esIPhone()) {
                pdfMake.createPdf(documentDefinition).download();
            } else {
                pdfMake.createPdf(documentDefinition).open();
            }
        })
        .catch(error => {
            resultado.innerHTML = `
                <div class="alert alert-danger" id="miAlert" role="alert">
                    Error: ${error.message}
                </div>
            `
        });
}

function activarResis() {
    checkResis = document.getElementById("checkResis");

    const pruebaResis = document.getElementById("pruebaResis");

    if(checkResis.checked){
        

        const nuevoItem = `
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
                            <input type="number" class="form-control" name="bt_tension_u_v" placeholder="Tension u - v" required>
                            <span class="input-group-text">
                                mV
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Tension v - w:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_tension_v_w" placeholder="Tension u - v" required>
                            <span class="input-group-text">
                                mV
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Tension w - u:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_tension_w_u" placeholder="Tension u - v" required>
                            <span class="input-group-text">
                                mV
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Intensidad u - v:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_intensidad_u_v" placeholder="Intensidad u - v" required>
                            <span class="input-group-text">
                                mA
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Intensidad v - w:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_intensidad_v_w" placeholder="Intensidad u - v" required>
                            <span class="input-group-text">
                                mA
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Intensidad w - u:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_intensidad_w_u" placeholder="Intensidad u - v" required>
                            <span class="input-group-text">
                                mA
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Resistencia u - v:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_resistencia_u_v" placeholder="Resistencia u - v" required>
                            <span class="input-group-text">
                                mΩ
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Resistencia v - w:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_resistencia_v_w" placeholder="Resistencia u - v" required>
                            <span class="input-group-text">
                                mΩ
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Resistencia w - u:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_resistencia_w_u" placeholder="Resistencia u - v" required>
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
                            <input type="number" class="form-control" name="at_tension_u_v" placeholder="Tension u - v" required>
                            <span class="input-group-text">
                                mV
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Tension v - w:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_tension_v_w" placeholder="Tension u - v" required>
                            <span class="input-group-text">
                                mV
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Tension w - u:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_tension_w_u" placeholder="Tension u - v" required>
                            <span class="input-group-text">
                                mV
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Intensidad u - v:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_intensidad_u_v" placeholder="Intensidad u - v" required>
                            <span class="input-group-text">
                                mA
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Intensidad v - w:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_intensidad_v_w" placeholder="Intensidad u - v" required>
                            <span class="input-group-text">
                                mA
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Intensidad w - u:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_intensidad_w_u" placeholder="Intensidad u - v" required>
                            <span class="input-group-text">
                                mA
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Resistencia u - v:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_resistencia_u_v" placeholder="Resistencia u - v" required>
                            <span class="input-group-text">
                                mΩ
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Resistencia v - w:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_resistencia_v_w" placeholder="Resistencia u - v" required>
                            <span class="input-group-text">
                                mΩ
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Resistencia w - u:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_resistencia_w_u" placeholder="Resistencia u - v" required>
                            <span class="input-group-text">
                                mΩ
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        pruebaResis.innerHTML = nuevoItem;
    }else{
        pruebaResis.innerHTML = '';
    }
}

function activarResisEdit(id_pruebas) {
    checkResis = document.getElementById("checkResis"+id_pruebas);

    const pruebaResis = document.getElementById("pruebaResis"+id_pruebas);

    if(checkResis.checked){
        

        const nuevoItem = `
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
                            <input type="number" class="form-control" name="bt_tension_u_v" id="bt_tension_u_v${id_pruebas}" placeholder="Tension u - v" required>
                            <span class="input-group-text">
                                mV
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Tension v - w:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_tension_v_w" id="bt_tension_v_w${id_pruebas}" placeholder="Tension u - v" required>
                            <span class="input-group-text">
                                mV
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Tension w - u:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_tension_w_u" id="bt_tension_w_u${id_pruebas}" placeholder="Tension u - v" required>
                            <span class="input-group-text">
                                mV
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Intensidad u - v:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_intensidad_u_v" id="bt_intensidad_u_v${id_pruebas}" placeholder="Intensidad u - v" required>
                            <span class="input-group-text">
                                mA
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Intensidad v - w:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_intensidad_v_w" id="bt_intensidad_v_w${id_pruebas}" placeholder="Intensidad u - v" required>
                            <span class="input-group-text">
                                mA
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Intensidad w - u:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_intensidad_w_u" id="bt_intensidad_w_u${id_pruebas}" placeholder="Intensidad u - v" required>
                            <span class="input-group-text">
                                mA
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Resistencia u - v:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_resistencia_u_v" id="bt_resistencia_u_v${id_pruebas}" placeholder="Resistencia u - v" required>
                            <span class="input-group-text">
                                mΩ
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Resistencia v - w:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_resistencia_v_w" id="bt_resistencia_v_w${id_pruebas}" placeholder="Resistencia u - v" required>
                            <span class="input-group-text">
                                mΩ
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Resistencia w - u:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="bt_resistencia_w_u" id="bt_resistencia_w_u${id_pruebas}" placeholder="Resistencia u - v" required>
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
                            <input type="number" class="form-control" name="at_tension_u_v" id="at_tension_u_v${id_pruebas}" placeholder="Tension u - v" required>
                            <span class="input-group-text">
                                mV
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Tension v - w:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_tension_v_w" id="at_tension_v_w${id_pruebas}" placeholder="Tension u - v" required>
                            <span class="input-group-text">
                                mV
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Tension w - u:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_tension_w_u" id="at_tension_w_u${id_pruebas}" placeholder="Tension u - v" required>
                            <span class="input-group-text">
                                mV
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Intensidad u - v:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_intensidad_u_v" id="at_intensidad_u_v${id_pruebas}" placeholder="Intensidad u - v" required>
                            <span class="input-group-text">
                                mA
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Intensidad v - w:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_intensidad_v_w" id="at_intensidad_v_w${id_pruebas}" placeholder="Intensidad u - v" required>
                            <span class="input-group-text">
                                mA
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Intensidad w - u:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_intensidad_w_u" id="at_intensidad_w_u${id_pruebas}" placeholder="Intensidad u - v" required>
                            <span class="input-group-text">
                                mA
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Resistencia u - v:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_resistencia_u_v" id="at_resistencia_u_v${id_pruebas}" placeholder="Resistencia u - v" required>
                            <span class="input-group-text">
                                mΩ
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Resistencia v - w:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_resistencia_v_w" id="at_resistencia_v_w${id_pruebas}" placeholder="Resistencia u - v" required>
                            <span class="input-group-text">
                                mΩ
                            </span>
                        </div>
                    </div>
                    <div class="md-form mb-2">
                        <i class="grey-text">Resistencia w - u:</i>
                        <div class="input-group">
                            <input type="number" class="form-control" name="at_resistencia_w_u" id="at_resistencia_w_u${id_pruebas}" placeholder="Resistencia u - v" required>
                            <span class="input-group-text">
                                mΩ
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        pruebaResis.innerHTML = nuevoItem;
    }else{
        pruebaResis.innerHTML = '';
    }
}

function generarPdfPruebas(arrayPruebas){

    // Objeto Date con la fecha original
    let fecha = new Date(`${arrayPruebas['fecha']}T00:00:00`);

    // Objeto Intl.DateTimeFormat para formatear la fecha
    let formatoFecha = new Intl.DateTimeFormat('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });

    // Formatear la fecha
    let nuevoFormato = formatoFecha.format(fecha);

    //Calculo del valor de W1
    var tension_res = (parseFloat(arrayPruebas['tensionu_v']) + parseFloat(arrayPruebas['tensionv_w']) + parseFloat(arrayPruebas['tensionw_u'])) / 3;
    var intensidad_res = (parseFloat(arrayPruebas['intensidadu_v']) + parseFloat(arrayPruebas['intensidadv_w']) + parseFloat(arrayPruebas['intensidadw_u'])) / 3;
    var valorW1 = tension_res * intensidad_res;

    valorW1CC = arrayPruebas['int_lectura'] * 1 * arrayPruebas['ten_lectura'] * 1;
    valorWr = valorW1CC;
    perdNucleo = 1 * 1 * valorWr;

    relaError = ((arrayPruebas['rela_teo']-(arrayPruebas['uv1'] + arrayPruebas['uv2'] + arrayPruebas['uv3'])/3)*100)/arrayPruebas['rela_teo'];

    kCortoCir = (arrayPruebas['int_valor']/(1*arrayPruebas['int_lectura']));

    tenValor = arrayPruebas['ten_lectura']*kCortoCir;

    let pruebaArro = [];

    if(arrayPruebas['checkresis'] == 1){

        pruebaArro = {
            style: 'table',
            table: {
                    widths: [55, 55, 55, 55, 55, 55, 55, 55],
               
                body: [
                    [{text: '5.- PRUEBA DE RESISTENCIA DE ARROLLAMIENTO:', style: 'tableHeader', colSpan: 8, alignment: 'justify', bold:'true'},{},{},{},{},{},{},{}],
                    [{text: 'Arrollamiento en BT', style: 'tableHeader', colSpan: 4, alignment: 'center', bold:'true'},{},{},{},{text: 'Arrollamiento en AT', style: 'tableHeader', colSpan: 4, alignment: 'center', bold:'true'},{},{},{}],
                    ['Fases','Tension','Intensidad','Resistencia','Fases','Tension','Intensidad','Resistencia'],
                      ['u - v',arrayPruebas['at_tension_u_v'],arrayPruebas['at_intensidad_u_v'],arrayPruebas['at_resistencia_u_v'],'u - v',arrayPruebas['bt_tension_u_v'],arrayPruebas['bt_intensidad_u_v'],arrayPruebas['bt_resistencia_u_v']],
                      ['v - w',arrayPruebas['at_tension_v_w'],arrayPruebas['at_intensidad_v_w'],arrayPruebas['at_resistencia_v_w'],'v - w',arrayPruebas['bt_tension_v_w'],arrayPruebas['bt_intensidad_v_w'],arrayPruebas['bt_resistencia_v_w']],
                      ['w - u',arrayPruebas['at_tension_w_u'],arrayPruebas['at_intensidad_w_u'],arrayPruebas['at_resistencia_w_u'],'w - u',arrayPruebas['bt_tension_w_u'],arrayPruebas['bt_intensidad_w_u'],arrayPruebas['bt_resistencia_w_u']],
                ]
            },
            fontSize: 10,
            alignment: 'center',
            margin:[20, 0, 0, 10],
        }
    }   
      
    img_lab = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEA3ADcAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wAARCADJAToDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD9U6KKKACiiigAooooAKKKKACiiigBOaWiigBGozS0lAC0UUUAIelLRRQA3+KhqWuV+JPipvBvgXXNZSK5uJbO1eSOG1TdKz4woUEEE5I7EDvxTWrsJuyufOniD4t67pfxa0nx05uJPAcmunw1KtvdyyRRRNEUSbyFIWZ2uOPkSR0AOSB0+sl/Wvl34ofDOy8L/sjXEbWdtNc6X5XiWaOVolSa7WQTM5OFQEsT90KCRxX0b4V1Rtb8N6TqLAq13aRXBDDnLorYP51vVtZNGFO97M2aKKaDXOdA6iiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKTvS0UAFFFJj3oAWiiigAooooAKKKKACik/ipaAGivGf2kNLn8X6LoXhCGK7X+2dSjzeQD/R4vIZZfLnzwVk27Qh+9jv0r2WvI/GF9dan8ffBeieRNNp1tY3WqSs0StCsikJEd+0sr5J7qMevStaXxX7GNR2Vjr/iRotlr/w88Q6ZfW8c9jPYTJJAxQKQEJwd3yjoOTgDvXN/s16tc618EvCU17FJb3aWaxSwyHJQqSAM5YNxj5gzKf4eK9LubdLm3khkQNHIux1PQgjFeIfsnWH/AAjPhXxR4ZmvpJ7/AEzX7syQTErJBFI+6ICNlUpGV4T5QGCkjPWnH3qb8mK1po94puOadRWJuFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAJnmloooAKQ9KWigBvpXj2i29mn7THiRo5CLxtFtZJlZWDEFiikZUgrxyVIOeCO9ewNXh3he98v9q/xxaJcgxv4fsZ5rZZVP7wOyh2QHIJXjLLkjoQOK2o683oZVLaXPca8P+G1rqPhz9oLx/pJ8q40vUI49aM6xgNFKzKixMwI/hVjypPP3+1dprnxu8D+H7iWC78Uac9zEYxJa2s4uJ03khS0Ue51U4PzEY4615DY+K9Gvv2uNB1Lw3rdveW+v6Lcw6ikVxBIDLbEBIhGcSxsu8lsZ7AgDmrpwlaV10IlON1Y+naKarbhTq5joCiiigAooooAKKSlpAFFFFMAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACim0UtgHUmabu968y+N/wAYl+FOh2iWNj/bPifVpxZ6TpXmCIXE7dNzsQFUdeTlsYXnkVFOTshNpbml8Vvi1pPwp0B769828vpPks9Mtl33F1IThVVeoGerHhe/YH5d+Hvwl1b9pP4keO9T8dy3nhzSrPVFtLjwzpV20MV5EYVdop2Rw0gEmMmRF3Y+VY+c+6fCX4NXmn3X/CYfEG5t/Enj64dpI7hkEkOkRt/y72hZcxgj7zKFDH8zV+BOpXd58TvjKkzIlta6+kUMcbJj/UqSzAFsMST1xx/DXbGapwlyb/8ABOSV5TXNsd34d+DHgbwrFAuk+FNIsjbwrBHNHZp5qxrnanmEb8DJxk8ZNfN/7RHhHwf8Lvjl8EdZ0eHTfDRuNddb62s7WOKGaMIW85xHHu37sLvPGDtyM5r6+uriO1haSWRY40BZnZgAAOvXpXw18cpL34vaL4o+J8kEk/hLw7fW1tpdrJa/vWWG4T7RMQ5jYRvnoAx+UFQwNXhLyneb0sFa0VZLU+74hhcU+uL1b4reE/DWn6dea54k0vQIdQRWtv7UvY7YyZAOF3sMnnGByK8y1b9uf4PaTqVzpx8S3FzeQu0arb6XdtHO6nG2KYxCJznjIfHvXIqVSW0Tf2ke59A0mRXzJY/tsL4osJ7rwt8LvG+uwxN5Ymis42jLkjb80Ly5QjdyoYrtOVHUP1D4sftCa1pto2ifCHS9IlukLC6vNZW9Fuc/dlgf7I4OMH5Wb04NX9XmnaWgvbRPpjNJuUdx+dfNn9n/ALUGqXsT3Oq/D7SrPIBisUuvMPQkt5kcgJ4xtUgYJ+bIzV29+B/xW1yzWLUPjZeKzI6vHaaPFBksRjDxSRtlV3gFSucqeoOT2KXxSQvaPsfQjSKOSwAHOc03zd3Q+3Hrn/GvnOb9mTXl061hm+J/jvUtSRXL3UfiGWyh6jYnlhZMjjGWLNyxLE4x3fwr+Dt/8Pda1HUrvxlrmupdJ5Safe3by20K4TBCuWPmAq5LqV3ea24MQDRKnTjG6nccajk7WPVh0paKK5jYKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiik/lQAtFNzQDSuA6im5pN3HNFwH0marvMFADPyeAO5+n+eK8e+N/7RnhP4b6Br1jH4w0GHxlbQp5WjzanCl2rOVCsYSdw4bcNwwcc8VrTpyqNRiiJTUVdnsRkwTk4HeuY8VfFPwf4FaH/AISPxXo2gecC0f8Aauow24cAjcV3suQMgceor4CvviV4A8Sfao/7X8bfGbUL65eOPQobK6kEG2IkpIbjYAjMzEfZ43Cg4CtkE1PD/gPx74ihvL/wd+zw+lyJMxntvEmoSSQTFlAXZa3Ys438vqHKyL2JyMV6UcDBfHP9DleIb+FH3HqH7S3wp0+1muH+I3haRYoXuNkGsW8jtGo6qquWb8AcngZJAr5t+G/7SXgGXx9rfxM8ea7JY6zfL9k0fSrWG5uY7LTwfk3OibQ8hG/a2wj+4Dk1458Rf2avG1hrPgvS/E9voNveeKbz7HDZ+H7C3tnjKsJGWXyYokB2lzkyOCU6npX0DD+wHZpYz2o1yzjkDAW9xJpf2hkQfwujyFHzgdVwcHjmvTp4XL6NLmq1Hd9jmnUrVGrLY1W/4KIeDLq6tbXSvBPjrXZLydra2On6ZbnzpAThVV7hWJYDIGN2O1eeaL+014+0vWPGkGg+BtL0nULvUDLAPFOsJasrHAVPKlZN7qMF1WUYyMeld/Z/CnxH8P8AxVZ+HfBnjjTNPvriJpLm10jwrp9qI12582YqACcnKoBk55PBY8h8N/g/42/4Trxd4g0uPwp4i1231JrK+XxRayGUJ8rCSHyJTDGXBOcpu6gsR8tYwWFjGTitPMblO+psa98C/jx8fBat40+IPh3RvDUse46Z4Yjle3kJGQZFbHmAY2lTKw7gg1L48/ZBn8P/AAp1u7fx3r2q6tpuk3ItLQzGHTgxBbaINzFU/wBkP/hVr4E/H/Tvhj4Xv9M8ZeGtY8JabFqkyrqkedR0eN5ZWCQQSxDcuMZKFAI884zX0prU9n4z8D6h/Zlxb6rbX9jKIJoHEsUu5DtKspIIzjkHB7GuaWJr0ZqEdEuyNVCE483U8L+Bf7Mvwh1r4c+EvElj4Ut55bqyhuWM1xcSRmUxqJR5byFdu4N8mNue1e4+FfhX4N8DzzzeHPCeiaBNOuyWTS9PhtmkXO7DFFGRnnnvXnP7HOptffALQbOW4kuLvSWm024kkhaI+bFIQ4+b72CcZUkcYzkGvb93SvOrVJxk4tm9OMeVCCGNeVQZp20emKeuO1Fcx0WGhR6D8qPLFOpaQyPbtpG4BIHNS0lADFZtoJHOOaeOlFLTAKKKKACiiigAooooAKKKKACiiigAopvNYHjPxppHw/8ADd9r+v36adpFinmXFzIGIQFgoGACxJZgAACSSABk4oWuiE3bc6GivIvi3+0X4d+F/g2LXo5o9fe6lSK0s7G5TdNuAYvuzwioQxYjoVABLqDwvgH9urwZ428XWGgT6Trfh+S93rBfaotstq8ignYGSdnydpx8vUhThiAfQp4DFVabrQptxW7MHXpxfK2fS9JkCvjTVv8Agp14AtvtsFh4a8TXN2sEj2jXEMEMNxKFJjjJErSR7iAMmP5c8jtXnWqftt/FTxRY2mpWmkx+GtFvHuNkltYlp4SoZBH58zPDMEZ0ZmSNeY+wJSu6hkmOxDSULX2voZzxVOCve5+gzXSKwBbHOMZ9s15J43/a0+GPgjT2uJfEsGsuRMIYNDBvWnljUsYA8eY0lJBULI689SByPijRdN1P9pDXL8eJvG19ZSaPps+pR3TWlxffZ4wUaYxoi8Fgq/Ksil8AKjAVueB/GX7Pfw/1TUr250vxtrskVzC/2u6gXyTmQCSWOOJ1ZkQL5jeapdlYhfM5Udc8leHnKnUvKUd1FbHOsU5q60PYde/b9M2rwWPg/wCHGt+I/tcDNaSXcjWRmmUkmMR+VI5GwbshS3UGMAbqgs7z9qr4sXdswutI+F+l+T55uYLKO4klY9E2T+YyjHUNGrD17D0/VPj78Kfg3qF3oBlTTJo0W7kt9P02Zk3TDzMZjjIDsrBtpxwwPrjo9L/aI8Bap4CvPGNtq7todrKbed2srgTCUAZQQFPNZsZOAhJAJ6AkcTi6cVOlQ0ezepfNzO0pnlNj+yf408VT2N58RPirq+stHbGF7PTnkt0RyMFkaN0U5BYEvESflz05b4q/ZX+HHwr8KaZNo/hWTVLqPUrSJbm4mhd4keZQzuZSBsz12gtkjA6keq+D/wBpb4ceOtQtrDSPEiTX1yxSKC4t5oGdhnjEiLydpI9RyMjmvOv2lPjv4Vt9NsdAi1mwGo2/iSxi1C0vkuY5IYkmDtIhRMjkKd5xGV3ZbFRH646ijytP0sP90o3ufROk6Fp2hWMdnp9jBYWqDCwW0Sxov0UDAq6IkXoBXGat8YPBWg+HbHXr/wAV6Ta6NfNi0vmvE8q5/wCuZzh8dflz69KseE/il4R8dTzQeHvE+k65PCgkeKwvI5mCkkZIViQMg84xXnOnVac2nbudClDRJnkfjW3tdd/bK8DWswSQ6boV1dqoeON4pQ4Ctn78g2uflBKjqVGQa9Y+J3xC0/4Y+EbrWr5lZwwgtbXzFV7u5Y4jhTJ5Zm7DnAPpXgvjb4leENL/AGzvCVxfazDb40O4s4LlX2wtcPJ80bucA4WPIOSAwIODxWvq3jzwh8Qfi1pmt65rmj6b4Z8KvPDYf2pcRxG/vWABmgfftkhCgcgnJzlRjNd0sNU9xuLtbscyqRXNr1PS/hH4BuvC2n3usa1d/wBpeKdbZLnUrwr5eSF+SMIDtUIp2/LgHGcZrlPhHrN9aax8Y5rti32HVmlhEkSqu0QbsBgS2OOhPHbrXqOg+OfDni6wnvNE13TdWtIeJbixuklSM9Rkqx28c8+tfNv/AAnFlq3gL41WWlalaxeI7jV5rBILOaB7mSVlRVIjDkZKnjc3P+zWUKdSopLl7FNxi42Z7B4L8FWHjD4O6Xp17eXEtpfQm5860laDmQs2NoJDKC5/dvvRsfMGBxXnGu+DNd/Zj/s/xX4Zlutd8IWqlfEmiRFY2EPGbu3hBEe5OCY0Cs2Tt4xHXtej+INF8I6Lo2l3+qWljcRWcMQiupkikO2MD7u484HQE/U9a35Lmw1jSXbfb3mn3CFWbIeKRDwRnJBBzisk6kX7y91lcsZLR6ng37HWsWUmleO9Js5WlSz8QzXgkYRq0iXSLPG5UNlQVYABkTofvckfRijpXyv+zDYXPgP40fEbwVHLAujWu2/sYFjBcxOwEbCQHG0DcuwgFcDtX0+byONtpkAPYE9e351WMpuNZ2W9vxKoP3EpFyg57CqrXQxkNxng+vrSrcLIMq+cHBwf0rhs97HTzLYtUVVadV/5ac/Xjpz+lBm24y/fuevvTs+wcy3LVFVhITk547HP/wBajzuRzz0xk549qVmO6LNFR7unelJ28k4+tIY+ioRMP72SKjNwFbBfHPAPWqSb2FzLuWqKYp3AEHPFIzYzzSGPOaWooplmjV1cOrAEMDkH6U9uFOKAHUVmaXfXd5BI93ZSWDieWNYpJEZmRXZUk+UnAdQGA6gMAQDkDToAKKKKAG8etc3488L2HjPwdrWhanZ/b7HULSS3mtg4VnDKQArZ+VvRuxAI6V0tIQMGhe67omUeZWZ8D+Gfgn8QfidY3HgXUdCj+Hvh7R9VgvRpsfmFrSCSKRs2+okXCXM6+ZhgSoXI2+VhQO08H/8ABN/wVoWrXV7q+sXuvRG8WeC2u7a2mTy1R08mdZo5ElHzk5VUwUTHK7j9heWvUrg/SlZQe1et/amKjTdKEuWL6I5lhobvc+Xbj/gn38NX1gX1jc61pdptwNPhuI3jyf8AaljeTG0Bcb8Yz3rq/FH7HvgHxD4L0Dw1AmpaXa6G9w9jcW188k0fnOHmBabfkMyqeR8uMLtFe7YA7UKtZ/2jirxftH7u3kP2NPax4t8Pv2V/Bfw8utUuLVLjUZr+1ks2bUVhk8uCRQJUUiMHa+1ch9w44rzy6/YT8Gf8JBHEdd1O1snZXh0+2CfPCmwyJJI6sW3NxkbflcgDIDD6sasW+8O6dfa/p2sXFosup6fFPDa3TdYUl8vzQOejeWnJH8NVHMsXGUpqo7y38yPq9OyVjxP4ifsb+GfiL46uvEt1rmsWU10sIe3tpIfLHlwrCu3dGW6Ip5J+bnpxXJfGD4PeFPhT8BbTwW0mu6lFrevWtrHqEENrNcw3UjEJMysqIEG3DbAr4JweSR9WM20ZY4Hv/n19+9fNv7bmuTaT4R8NyW+8H+0POWRZmRVeNQ652AvzgjcuSAWGMNkdOFxeKxEqeHc3yp3XlYyq06dOLnY5a/8AgL8M/gPqnhzWde8QXV9qFjN9qttNKxyNesobb+55LYPII4BUN0UkeE+PLHwT438dare6dN4q8K6/canHqZ0zXoraFJZiwl8lXG7yQSY2Lyb1ZSu3OTj6B+HuoJ40/bE1h7yWaddK0lEhs763yLeQqnzQsWGEIJIbaxbcfm6Ftr9vbSNNj/Z5164EdhbanLPDHBLPEhkmZmClEJ5LFFzgZJVCCNucexDGVaeKiqzcpyS18n0scjpqVNygtEVvGngnwT4ubwd8GJNX1TTNW0+yXUobm3McofI5hlkPUv1wAuV+6QRxa8JfB/wd+zHJfX+r67dzzeIJRpNv9nR4mCyMQiCNXLO43cuo46hVAJrxtvG3jCH4sfDvX9O8Gw+Idck8NRSwaRshtmnYRgPJ5xVtjoPlX5jwSNuTWv8AtQeJ/H3jL/hEhrXgRPD9ha6xA9pMl9HdXFw7NGccRq0OzliFLB9pVhgVn9XxPNDCqfuy1tfrqJVafK521WiPIP2jvgnoPw98Svo2ieKm1TxWzxyW2jy6fJFOisrO8n2vcyOVVThQm7JJ5wceleKPhP4P8feGfg5eeHvGS6PDrEJ02KH+yLm5FxOCZZSdsgWF1fPDhc84PauY1rS/E/xM+O3xE8Runn2fh1BfWN5AGV0ggbhxhlG4qpUqnzbdysVBwc681TQNJ8Q+B/FOltFq+gX+trqGo+FZoRdXunXBUCWUI5ZipO3aC44wFJIGfaqPE8tKMarcoq/o+XYy5o9VufT+j/slad4d+Dfj7wnc6/CT4kTfcaobZkSJUClC8ZlKtt25J3L8vHA5r47+DfwV0Hxl4j0m10zxdb6zMPECW7rZ6PIQ8cTecZGDSqY4vl6gvkkEV+jd/wCPNB8XfD3xFqGlahb6laQWU4uFjcZjxGxKuCPlOOoYdsV+cH7D/ifR/C/xl0DU9VW503TJ7a/itNQX93ZSSMQArnawLY4G1lwRkjmvOy2piamHxVW/vadN2dNVQjKKWx6f+0J8E9K0b49SSal8QbO1l8WamL77HLpl1JPZxNtjG0xiRX+YYBdoRya9H8Q/sz6XrPwsu/g94U8Zadc61peqrrd1p13FGEKsQVWZQruhBG5GXHPVSK4HUPiufiN+1BZaw8N3P4YXWI9K028tLaRlJjGd67cHeSSScFwuSNqmt/xb8UZPhP8AtAfGDxIJLe41Q6JHa2Gnw3A893EihJCr5JVd3QZ6YVetRVhivZ0aN9Yq6VtnexMZU+aTPI/gb4e0H4NftUat/bni3TNCk0MPDJPEHIu3IXfEgVQFzk53kkgcA9uyv/2XdT8TLq/iLw3rfhe70C9lvLptVTUjFaRiSQiRC4SRt+Dls4GVI3L0qf8AZD+E/hrxJ4+vIvF1rb+NL/UfD/8Aa732oRxzws8t0VZo1PzRPuDDp3+UgAV6h46/Z3PwZ+DfxfbSvEV3qHh/VNJb7Nod6oKWrA5J8wuoPBIAwvGNxc4rfFY108Tyxl775U9Fb5CjT5qe2hz/AMRv2ePFfxn+D/w1vfDXjHR/FX9j6YbH7OXeKxvgxEZmjmRmAMcaGPJiYyDOdmSBJ+yb8Idb8OyeM4tL8V+H5rZtLfTpbLQdUlkfTdR35xJHGBECAGUyD58hgMgVl/B79tbwn8J/htpHhjU9G8T6zf2qus11aLaNGXLsVRPMuI3OFKjG3itz9jrx/ZNd/GTxNqNsNM0kXUN7PPO0hdY8SsqlMEcL1IycnHzcGvPqRxdLD1aLj7qkuiu7s1UouUJJ7nEeEfgX8S7PxhZ+HNS+KOmaf4oLrfT6dpviW6mu9pI3ShZIxubCtyy9A3bJrov2uvh34+tfHt/4wg8TJbeHPs0S2iPrr6e0DIctBGm5VMrNtZSOX5DMpVAfJdB+KE+mfGLSviK2sRzrq+vTPerHPCJ0to5o12NBGd+DbtH8xUAEFW5ILe9ft1eJNNjvPhfHe2Lapp9vqcmq3Sw+TIZbYRmJ0RDMjbm84YfhBjlgSqt6MliKeNoykk7rsvmjBShKnJRZzFjqHiTUvgZP4e0z4p2d14wn1Q608n/CQSrcJpqPCrqLh5AYgpIJTKqdzLgZLDN+C+j/ABif45aPouua3qWhXVi0V7eWOra4moS3FhvdmPkxyyhVZh5fmNsxuUBjgLWjoMf7Lt74s0mzt9P1uG8vLjEFxNquomB2Gzgg3BBX94ATtwMfMRxXpekaVc6d+2xJBpExj0xNE+031vBMkvlhl8tY2R23QR5jgZFjGCVY4ALtXn1MS4qtBQSTTeqV/kacl3CVz0H45ftEaT8C10hb6xutVnvG8x7e0I3xW6lRJLk4DFd64UlS2fY1mftA3fjzxD4Q0G7+G1hqGotfSKZxbTx2U9vC6blmKzvGcqQoKH5huOUOCK7b4hfCPQPihqGjSeI7YahZadKZvsLIrRXB4IWXK5aPcFYoMB9oDblyp7qNBt6Zr5yFeFFwnBarvseg4Sm2m9D4w+CfgX9oDSfFV7Nqbapp+ktos1uv/CTa3HqAW5ZcwtHEk8hdlcYYtJF8rHDNwK5K3/Zm+NPiySwudTXybu23lZ/EXiF2n3dN3mweaFYqz7TGq4AIOPlr9ADGMU3ZXoLNasZSnGMdfJGf1WOibZW0izfT9MtbV5nuXhiWNppDlnIAG4k9Seuaq3Flfya7aXMd0osFhljmtSv3nJQrJnHO3awxxw5Na+PajaPQV4jbbud1tLGJ4mutVtdPX+ybOO9upJ4Yikk3lLHG0qLJKTg58uNnk28btgXI3ZD/AAnY6lpfhjSLHWNVOuatbWkUN5qht1tzeTKgWSYxKSse9gW2jhd2O1bGB6UbR6UgtrcNo9KWiigYUUUUAFJS0UAFNZtqk0ZNcr8StevvC/w/8Qatpka3GpWWnzz2sUiM6yTLGxjUqvJBcKMDk54qoxcmorcmT5VdnU57d6ViMV5P+zX8QNa+JXwZ0LXvEA26tN50crbFQyhJnjWXC8fOqhuABz0HQeTyftVeJ7Xwz8YdX+yaXeQeF9b/ALL0m4WB4rfy3kEcbzsJpGmwxU/u1TfvjA8sMzJ1RwtSc3TS1X+djCVaEYpt7n1bu/Kms23opNfFa/Hr9pjUbHR9UsPBeiz6Jqgtltry10iW7aRpAMy+XHf5jjJDN+92hQyhnyDnqvjJ+0d8SPhd4H+HyXmgaRa+M/EcMkN/Z+YZBbXQMSKsOGKcvLgb3IyRyQDXT/Z9bnVNNNvz7Gft48vMe6/GD4dWnxc+Guu+EdQkkt7bVLfyWaPqrBgyEj+JdyLlf4hkd6+Trf8AZk+M/inUvBfhrxJqtjH4K8P3EMnmG5EqmGJ0IjEIVWkZgm0+axChmwTyG7PVf2qviX8NYbq58cfCq6GmxyLJ9ssXfZBAABKHkVZImk3EbNzxK5baCChY9d8Vv2ntU8EeN/Cnh7w54Oj8US+IrH7XZvJqv2QyOQ5ESfuXVmxHknfxnp3PRQjisLeNOKd9nv07mE5U6jUmO+KXwG8Rp8Trb4mfDa/06z8WJDHZz6frG82VzCBsbOwFwSgQbV2jMSnI+bPKa18Kvi/8f9c0S2+IJ0/wr4R06a3vLnT9NkSR7uZF3EDh8Aszo25wNvARid414v2tNa8PeJBZeOfhjrHhbTPNWBtTiaW7i8xmCIEIgVZOd2QjswABCsMle3+J37Rln8N/FmieHV8Oatr2oatbNcW6ad5RZsBiFVGdXYnYRgLxxxjOJhLEwlGKjeSWj7FNUmm7nk/wT+EfiXQ/jFo+q2sllF4d0ayu9DvooB5Lq0TqLdRFypUjJBXGMe/Or+3Z4Z1DXPCfhmay+zpHHqP2eV57gRgNKuyIbS6bh5m0HDAjcDzzil4E/aSh0j47J4a1rwT4n8IN40nEtg2t20QQzLDhlykjMNzJJ1BAwORkCsHXPj8fE37QOq3Uvw+8QeK9M8D3MllY/wDCP6e1xMLs/K0r5GwDB+Ul0wGI2v8AeHVGpiZYlYhLWK/4BHLH2bj3O1/Zr+AetfCvx94lu9RtJF0y402CzhuLy7S6luXVmeSRsH5dxb7p444PauQ8Qfsn6p4b/aE0LW/CNjJ/wjkt+uoXMhNr9msW3EtGIAYm298jeckdxivaPhX+0boPxY8TahoMOma54c1mxO77Dr1qLaWdcDLqu4nAzyrhWGQcYIJtfHD9obRPgOuivq+k6zrDapNJEkeiwRzPEEALO6s6/Lg9FySeACeK544zGrESa+KSsy/Z0XBLojK+JH7NPh3xBFe6h4dFx4T15opSJ9D8qIyyFSRuV0ZVYsfmddjt0LEcV+eng74B/ETxx4Ot30XRbHV20y4mtvs96iRXE0jz7W/eHauAVMhw7Z4xuPFfrFDq0GqaDHqULbbae2FzGzjaQpXdzuwARx1xjvXyd8I/jF4R+Fvh/W/EXiO4dbfU9UkW1nggluJLqSMsSAQpA+9gBjyeFrvyrHYnC0qvsld6feRWpw5lfY5DSv2bfizpt58NtM1bVZLbTn1CS4vH8KpGJ9OdkPmOZ3RAqMu3oXYMMKHHT0D4V/s43Gh/F/4nRXml6rZ6FqmlJp9vrFxcQSi9LMD5zMqpJ5x7gjAxy24ivVvhv+054R+JmvW+iWsWq6Rrtx5rRadqli8bskYBLF13IoIOQGYMcfdra+Ovxf0r4H/DfUvFOrC5cRlba2htYfMlmuZPliRQSFGWxyxCjuwriq4/FzqcslaT/wA7lxo0muZHxb8E/FPir9n7xp4+f/hDtU8YxaY0eg20mjyu9vI/mbkBRIWYswIzJt+U7l2n71emNp/xl8W/DP4l6/4ksYtN0/WdPeSy0WW+uFuoSCuQsQx5SCPd8uQ7tncFGBXsX7LNjpul/A3SdSjuftMupGbVNRu5JXOZ5HZ5fvk7AOm0HaMZHHNdZ8N/jN4S+L41L/hF9RbUY9PkWKdzbTQrk7sbS6qHB2nlcjpzyM1Wxk3VdZU9U1d+hEaUeVRb0Zxn7Ifg0+Ff2ffDUN3pkmmajfLJe3kVxnzGkkkJDMO2UCY7YxXxWnhvU7HSfiX4astAvLPWvFHiOLTorl7FyxtPtM8sluwwZAjgA7F3ZGD8vy1+kOmeONH1PxXqfhq3uJDrGmxRT3Ns8EibUkztZXZdrg4/hJxxnGaZ468caD8NfDVxr/iK8FjpVrtDzeU8hBYgKFRAWZiTwqgn0Fc9DHVKdScnG7nr873RpUoQaVnax8s+IP8Agnzo2k+Fb290/wASa5qviGCwk2W0yW/2S5uNmcmIRhwu7ovm5xgFjyT8/atqHiWHR/hVceOtB1IaX4YvLqylW+snTfb+fbSw28izRhd5XfGqZC7Y0AXvX6beH/EmneKtDs9X0u4F3p15GJoJwCA6HoRkDr/Lmsbwz8SfDPjTWtZ0nR9RW/vtHKpexrG4WJmLDaGKgMQUO7BO3jdjIz2Us2xOrrrnt36dCJYeH2dLngH/AA2d4Vs1uZbD4eeJtStbcqwuNLsYJkiV13ln2yZTJ3EnGO+7mub8Z/GjQ/g3+1LqXinWdP1hZdY8P29lBYI0KyXEW7zfNEUpQxsrBkYM+enygrz9Fa/8bvB/hv4naP4BvbmZPEGpKpiAt2EEQYNsDynCBnKlVUFmJIyACCeo8VeKtF8G6adT1u9isbQOsYllJOWY4AAAJ5+nFcvt4qX8J2krbv7y+VtP39jg/g/+0LY/GLV57Oy8K+I9Iijtjc/b9TtYltmwUBi3xyviU7wQjBSVBIyOa9fXHavC9Q/bA+E2i3CQP4kaWUztAywafcybSCgdiVjwFHmKd3ThsfdONjxR+1J8NPCN5FbXfiaG4nltReR/YYpLmN4ihZWEkasg3BTtyw3HAHUZ4p4apKfuU2r7G0KsYr3pHr/8NIteB6f+2t8Kr3T9KupdcvrV9QTcto2k3ck0JzjbKI42EZBwOTg5BBOQT1l/+0d8ONMudcgn8WWYm0OO0m1BYy0nkJdMFgY7QQQxZckZ27l3Y3DOU8PWpvknBp+hoq0HqmepUVyPgD4k+HfijoC614W1iHV9O8wxGSHIMbgAlHVsMhwythgDhlOMEGptF8faH4j8Ra1oOn6itzqujPGt/bqrAxF13JgkANxnO0nBBB54rHkmm01sac8WrpnUUUyNsinLUFi0UUUAFFFFABRTfxo/GkAcV4v+19prax+zz4ttlDHdHA5VI1cuq3MTMm1iM7gCDg9Dxk4B9m/GuQ+Knw/s/il4D1nwvqG1INQhwsjb8RyqweJyEdCwWREYruAYLtJwTW+HmqdWM30aMqi5otHyp+z/APCf4leOPhvo+rR/EfUfCnh+ZJILXSbe1ffLbgyBJlkLrs3s28MAxZNh3Aldnm+i+defBf486fdyahea7a6lZzXn2YmW3ncXaB3WQxZY+Ykhcf3VDZAbdX234S+H1/8ADX4PJ4V0fURreq2NjLFb3mrqqLPOVYr5ojQ4XcQDhWbHZu/ifh79lXxnefDv4h6J4h8Q6baa34su7a/+32MUlzBbyI8MrJ5DGMMPMEq7wwO0o3B+Rfp6eZRlKpKo0k5Ra010Z5csO1ypI4n4U/C347694T8Lar4e+IWmaN4entoprWGKaRjap5LJH/o7W4R9ocZjYqCy5YkgNVv9sODXtJuvg1Bdatb3njBJvJOqGKW1t5bwyW6xuBGXEQaRhkbZOGAYFflbS039kv4ktpv2bTfjNqWn6TEnl2tlHb31r8qxKgUBbpDbgMpGFDcAN94kV6Fqf7L+s+KtY+Fuo+JPHLa3ceDpGe9ll0759SIJKdZSsJDBCWCM77BlumJljKcMUq/MnvsrdGONOUoOHKeK/HC6+KlzrmkeCPiTqmlWvhbxFfQwJe2NpHNAVEiHaYyfOPzukbHAAJjzwXJ7H42afeaX+0n8KdP8NW1pNrVpp2LFbxG+zpgzBpJTH85UJuwu7ALr05Ne9/HX4R2/xt+HF74Zlu/sE8jLNb3RjMqxSKcfPFuAkQqWUqxxhsj5gCOE8H/sw3vh3xd4C8R3fjnVtX1Tw7p/2K6muWl/09NrKEP70hIwWBKneWKqXZmAYc0cfCUYuSStdWto7rct0ZXaWtzyfxbr/jf4kfELS/hl8ULnQvC04v0vbO6s7BpIr7bIwt2hVpWwWWObaZCCpX5kO4AdX8UYdSl/az8MQ+Friyi8X2/hiQWzakplthb+f+8MyJscnJXCrJzwcAdfVP2hvgTD8b/Ctpa22pjQPEWl3KXel6wtssxt5QQQGUkFkJCsUDLlkQk/Lg8b8VP2ffiD4s8aaP4p8N/ElND1PSrYRQltJid2Pl7JgZM/dkPzkOsighcJ8vOdPFU5cuysmrdL9/mOVOpHTc5/xV8Iviv8VtQ0vUfHuk+BJ38PXMt7o8umyXplEqtGY0khZvLcZQks27BxtRTk15d8Fv8AhcPhnVPiWPBmjaTBqT6nJf3q64kt+srKCvkROtxES6gDnBQnjOc19GeFfh38Y9K8YaZdav8AFCDW9C83zbzTzpEFvhAmPKjCIXO5iT5hkGAoG1t2R0fwn+E0/wAOfEHjvU7i/jvn8S6udSURwshiQRqiqxJO5+OTwPQCiGMjRpyg0mn/AJj9m5NPU+fP2YZNe+LXxtuvGnirxFFJrOg6c2nzaE1p5E8LS43fJ0EYK9Rk792a5b4xfE7wH4y+NXjYeNpbw6dpukPomk2ktriM3QYmYiSNpIyrfIQ85jC7sFccn2f4j/s9+MI/iXqHi/4eazZ6YdXt5bfUbG8nmto8vFs8xfIUlmBxJl/mVuVZRXoHwN+Ctr8JPBcenXk8es67PL9p1HVpU/e3VxjG9iSzcDhcsTW1TFUYz+sxfRWS0sSqc2nTPP8A4K/FeDx58A/EdpKLfT73RbSawa2jaOZ44/KIhdkBAy2QcEBT2wK+QviRc+KtD8efBGxsdJhk1W70vdp8Yw0c11I7Bm8uQoikFcjcuMccgAn2/wCOHwj1X4S6l8TLvQbDSYfBnjTT7fTrLTlvFhWHUHlLSStDINhV2wCEyQCCATkDvPjB+y/r/jHTPAmu+E9Sh0DxZoNhFZzwSzGJZo8KWX7SiPICpDADJVgT0I3VtRxFKlW9pF2jN9fT/MzlGUlyvdHCwfHe30Dxl4T/AOF3eAPJ8T6YqyabrWn2hASR/k4jDMr4GDuVyQ7YWNetcX+0148sfi98VvDGk+K9TPh3wtpMpuBBDZ/aLgGWIbBPnKg7T8yqjFPeuv8ADX7O3xV+IfxC0rW/FtnD4Qg0C8a7hkM0VzLeuCfulZZCoYYBZxwP4C3Nem/Bv9l+W51DxR4j+Mel+HfFviTVtSaeCNrZLy1tYQfk8vzYxhjgZwMjHXmuqVbBYSoqsXeSXTv5ExjVmuXY5L9hn4oG68OeK/BmoXU+oto8s17BqIuWlhe3LbPLRiSVVSuVC54Yn6+XeAYvE3gO60/4w6PrEl14bm1qfTtYjvkuXmiR59m8wjYkwCMP3hkwpHG85x738UvgX4m0r4m6f4o+HOl6MumzaV/Yt/pNxu8jDy4WU2wkiiaNUZ8jdnLZCMea6/8AZ3+EOs+AfhHd+FfF39nzSXF5cyLaafGot4IJCAsSDaAQMNgY4DAHpXFUxdL36sLWm1dfmaqnLSL+ycR8KfiX4Y8dftbeJLrQb64lhm8NwxKJX2QyzCbdLsiZFdJFAG/qDx0K4rrf2zLq+tfgTqk1jZtfMl1atNEkQlPkiVd7AbGPAycqCwxkc1g/sz/APXvgz4z8RR3hY6G0RS1likjWO5Z5S4PlIODGgVdx2dcKuOa679q/wX4g8f8AwfvNH8M6RHrWqS3tq4t5PKBEayqXdTKyqGVckfMDnoa4qkqFLGQlSd4q2ptHnlSd1qeHfAH4ma/8LfhV8StK1y3kt7Xwza211o8GqXieb/pUcr+U0kUa7cMowuzeCWyORjD/AGJb6fSvi54xe+1COBE0drq/tWeUb5PMicXJ3ZBBRydzsXIkBBIYmu6+JH7Jmo+NviF4KmtrLTNO0FtFg03xBPBEgmT7O0bxLEMc42EKzFwrBTtIBrF+J3wV+JGjfEbxzqvg3wbY6lY+K4F0v7RHfxx/ZbfZF5jGJpIlBPlbeNzKNp+YZSvUjXw8qdSF1epq/LX+mcfJUUov+U8t8UXlt8SLz4g/Fe08U2MPiPTdTtZtCtZbyOOZrOI5+WNw+VdWUxDIBd3ztzge9ftSa7H43/Zt0PWJrVI9QuZbadLSYFNs2P3iDcQ2B84yoJIB52ndXoPhv9l/wS3hDQ7bxP4Y0PWtes7GO3m1D7EgIYHefL44UMTj/wDXXzl4u+AvxEh8Fn4eW/hgXOgaRr0lzpF9NMlyZIpzIFKlcNEF8wh8qP8AWOS20ZZ08RQxFWFpWUHpftYpwnCGvUj+Jfxd+Hninw3ceGtJ+FNzovjLUbO2t4by90u2t5LfzGAhb5HWVwpgRkXaqPsQZU52e2fDD9nnQf8AhTOnDx34N0nUPE0ME7k6pBBey2oZmKospQ4G0Kdq5AJIBIGa6746fA3Tvil8KZ9Bt7Sysr+2tQumSCEGO3ZVACKBj5CAFx24PaqH7M2r+LNW+DZ0/wAZ6dq1rrumTT6e02qIyz3kYAeOUFxuYbXEe45LGJmPJwOarjISw8VQbVpa63f/AAxcabjN854F/wAE6/hfpeveD9f8S67aafrlxut9Li+22qTvCyQiSciQluJDcRgqMf6sE7gVxy+vyG4+MX7R0kGu3dxeJ4Z1CWG3jnEZlEf2UF/NgIT9yV8sI21sMQwPzsfob9h/whf+DvhTqseoaVfaPNea3PcpZ6lBJDcRIIoYlD71Uuf3f3woB6ivOPHngnxFf+OP2kjp3hW6C3nhp4rS/gtblbu8na2jMSW0u3a6kq4dIyeUi4yTWkcYqmLqTm97W+9Eypv2cUjyr4E/FbxH8ANU0fW7iyv7n4f+IUlNykapMjmKTyWni5Xymjd4w24kOmQAxClPdv2WZfCx+PHxbXRZheSy3JuYLuSJzJskndrhd56DzztAAXckcRG/YWqb4a/s8j4ifslaH4Y8TWEuh63BcXd/p015ZeXdWMpu5pInaNjuG5HwyZVikhU7STjm/wBiPwP4x8HfEfxjba5o99pVlbQfZ5ZLyC6RLm4E3ytAzKIpowqyHfy2HTGAXz2YzEYXGUq9e6VS9vVX0ZFOE4yguh9rRjAoWk7UZ96+JPb6D6KKKBhRSUtAEbGvCvF3x08QeH/2ovCXw7g0m1u/Dmsac09xfZcXEE226YMDnaUAtQpUrktMpBAXDe6PXyX4+ieX9ujwYTDaxgaX8k7ysJCPJvg20FcbhlcBT0LE/eArvwVKNaUubpFs48RNx2NX4K/tbXHjb4oal4U8RW9vDDe3twmg3VjaTxnyk3ssV0HJKSGJN28hFJ3KVRtof0vVfjMNN/aC0j4dMlqkF5ozai08jkzNMZHWONAOMbIZic9cDHTn4p0H9neTxt4P8e3Wg6rdxeKfBuqssMVurPJdGGM+Y8R+aRJWdT5WJXKyRIwI3Ejtfhh8Q4fjp+1Z4G8STSi0NnoDosNtKs0Ut1GJQSGKH5WS6lbAYE+WhzjK17+Jy7DznKeH+GMdfJ2OCniJpJT6np3xf/am8RXHixvBvwh0nT/Euv2s5S+ur2OS5t4FUDcEiiZS5DEKzb1CMuPmJFXPgx+1ZrHiT4lR/D34geFW8K+I5rVp7Wbc0cd04Ylk8mT5kyoJQh5A/ly5KbQG89/Ya1O/0Pwz8V9FtbX+0PGlndG8htLxTD5zm2WKOOSQqMZnt5VYEkpzwBiqs3x8+Iuh/GjwLp/xI8H+E9O1SWeNIcWLTXlvFcym3LQTpPKI9zxqTwflT5sD5lxlhYc0sNCmm4q9767Gkaslao3ubvjX4xa94y+MD6l4J8D61q2p+Bb6awvU0/WFtI7+Fp3gAlj8t/PjDLJIAMeWeeeq+jzftGX/APwqXxX4k1zwPrOiy6Haq7reOtpBfSM5j220xcNt3YxJgDDgruPy14d8E5viZH8b/jFaeBbvRJZ4tYmlvbXxPcTrAc3VwkckaxB23FUwWYj5VQdBXuGvWPxAsf2efHEXxK/4R3XNQjspRa/2VC8kTxrEuJZxKqgyCTL/ACrgBRgEisK9KlCcKSSa9311HCUpJyN79nL9oax+PHh29l+ypo+uabN5V3pRufNeNTzHICQGKsO+OoYZOKdo/wC0lpGs6D8QdZg0rUX0/wAI3Ulq5jCO97sAJaJQ2dpJ4J7c5HIHy1oXh3XvgdpXgf4t6bdTXOgaxY20er2ulRrFsDBSNoWRoWUhTh5RIMyMA0RZGGf4Pe48R/s0/HXW/PuH1O/1eza7hhklNtnzbaRmjBZ5D8rlSWOTtAxjmuitl1JuVWk7wul6NuzQRxE9Iy3Pqv4jftT+Hvh/4H8L+Jv7M1PWLXxLAX02OySPLuVVkR8uMFy6qMBueOuAaFr+1Y011awy/Cj4lW6zeXuml0JVhiDypEGJaUHgvuIxu2Att4IrwT4ma5rl/wDDP9nWPwxZGXX5rY3On2kEw2zTJFGwQDeqEAZ3b8AKx2spJI92+HPif9oC48WW1n4y8I+Fk0SRFabUNPu3g8g4+baC8plJJGBtjAwfmbIriq4WnSoxmt3frro7bFwqylOyNT4kftTeHfh346bwf/ZGs+IPEWxHFlo8UUjsWUOo+eROSOcAE4GcY5qD4d/tbeGvH3i628NS6NrnhzVLkMIo9YgjTfIrMrR4SRjuG05ONvbdnivAfiVqmvab+3HPf+C9Hj1nXrezjM1is8O69h8iNZRvkZRGV/d8g84HHJxgaB4n8V/Hf9q7RLbXrpfB93psk09vaJCry2QgZS0B+cgyyb2Bf+7navp6McrpSoqf9zmbvr9xk68udn2H8Xvj/wCGvg5DajVk1C+vbkF4LPTbRpnkGcfe4jUkkABmBYnCgnisnwB+094a8ceIIdCvdN1zwdrlwSLXTvE1kLWS54yDGVdwcgEgEgnB44NeP/swaXpfib9o74u61qmiafYeJLXUWQWo2TvCRIcypLwcnOCQvtntW9+39Y2Fr8LNC157OCbUtM121azllVT5RZssQrnYSfLXqGxjOPTyVh6ftI0GtXbX1N/aT5XUOg+KLaX8VPj54N8HMsl9Doccus3n2b95CjAhBFOcEKTgFckZIrQv/wBsHwBY2+s3MMup6paaVO0FxdafYNNCrA4YmQHYqg/xMQDj14rF/Y7ml8UeE7nxpd2XlNqKRwW+oyTGWS/hQswkd2YvIQzEBm7ABcLxXjf7MfjH4g+HdP8AGq+Dfh9b+NbOTV7h5Gm1OLS445Qzjy1doSZCfxCnjIrf6vHlqRlqqdvxepHtHo4/aPprwn+0x4E8W+Ddb8U22oXFvo2jBnvJ7qzkQIq91OCJMZAwpJBIBAJAr0Dwj4r03xx4esNf0adrjS7+ITW8zxPEzrk8lHAZec8EA14b8UtU8S6r+y344e88F/8ACC6lDp7CDS1v45xzgswaAqAMlsc5POR2rv8A9m9o3+CPhAQ6f/ZMSWCILMx7PLIyCce5+b/gVefUpR9k6sdNbfgdEJvn5WSeOv2hPAPw38QjRfEevx6Zf+Us5V7eZ0VGJClnVCq5x3I6VD4W/aS+HPjPVhpejeJYry/Z441hNvMhLORsA3IAQcjBzj3r5f8AjJ48k0v9tW1u7Lw1deL5rKwWxbS4gjGQOjb2RUUlioOSJeBxjb1P0R4C8dXPirULY3fwr1jwxJEsrJf30FuFtn2tnbht/wA44yinkkHgEjrrYFUacJNfEr9DONa8mjsPHnxi8IfDWS1i8Ra9a6dcXakwWx3STSqBlmEaBm2ju2MDvUvg/wCLHhLx5o66noeuWt5aGJZm3MYpI0IZgXjfDplUY/MBwpNfKX7Ffwx8OeMta+IniXW/C9jeX66pc2UN1qKia4eN5XaTzY3HUgKA7LuI3LkgV7nbfso+BtK+L2n/ABC0m0bRtStbeS2Gm2EUMdjIsgZZWaIRcM4chiDhuCckZrCtRo0ZOk2+ZfcVCU5+90OgP7SHwzzGP+E10cCRsR5uQMtwQOSOoO4eq/MMrzWnpvxw8A6vo9/q1n4y0OfTdPMYvLn7fGFtTIMxiQk/IWwcBsZII6ggfNXx++CXhax+OPws07RvC3hXTtI1S6MWq6etpDZm8j8yMEnayGUhNwCbW5I/hrq/2vPD3h3wT8HdM0/TfDui2WkvrVq81jDH9kRlQqcIIQMMVULnKkLkAngHZYWhN04wbvL0J9rUXNfoevWP7QHw31K0muIfHOg/Z4Swlkkv4kWPGcl9xG0cHk/XpXV614u0Tw7psWoatq+n6bYTMiR3d5cpFE7PjaoZiASc8Dqe1fAvxe+KnwR1bwfqmj6J8O5vDuu3Fkwh1Gz062gjs5VbdC0qRzIxbcp2nYT8zAjaxB634oX3i/4afs1/CWHWx5+rf2uJLiS8upXkt1/fTQK0m9Wyo8tCOcAY6Lmtp5b8PS7tr+ZMcQ9eY+1tU1iz0Oxku7+6itLWFdzzTMFVR6nn/OK5vwv8XvBfjbWLnStB8Sabqt7boskkVncLJgHkEEcNwOqk9Oa+YNSsZP2k/wBrjUvCviiCefwV4XtTKNHa9MUbziKL96yBMu5N2F+VgF8oMHOSres+Kv2V9ItpNAvvhtLZfDvXdHl+TUbPT0nM0ZiaMiRSR5jHcGLPktggkZ3Dllh6VG0KrfM1fy8rlxqTm3KK0R7IniTSpNbk0aLUbSXWIoFupNOSdGnSFmKrKY924ISpG7GCQQOaxNc+LHgzwzqFzZ6z4p0jSbm3RJJI9QvI4NquxVSS5A5IxjPp6jPzh4HvWX9t/wCI1tbfYI9Qu9KkhE8KGN3dEs3XzVZgZSquBuQ9G6DDFfEPCNx4H0rVfEOg/GvQNRt/FJvjdSaus9zvQiJ497xRssjxM4chlDiXeu7/AFalemnlyk3zO7snZb6mU8Q+iP0X/wCEn0YaDDrX9qWY0aaEXCah56fZ2iKgq4fO0oVwd2cYIrkbr9ob4YafdRW9x4+8NpO77Qq6pCdpAPLYY7RxjJ4zxnPFeU/Av4K+GvHHwRHhvxT4n0/4raBFeiexmhgltfsQWNVSIL5zPHtUn5QUwJCuMdfHfB3wU8G+MP2pPFXw51TS5ovDGj6VJNb6fa39xCqENZsjB1cSZ2z5J37ckjDAAiKWDoy9p7ST91X2NHWnaLS3PtbWvih4V8O6Daa5qGvWUGj3UqQwXwmDwyuxwoVlyD0PPbBzXSWl3FfQRzwSLNBIu5JFOVYHkEHuCMV+dH7Uni7Q/Fvj6LwhoGnr4f0DwPDdWqAWYtopLlwpZYY2UZUv5Ue8FV/eSyNmNN9fUP7FvimHXfgfpmnHU21K/wBFlksrgSJsaBWbzbaIdmVbaWAKwJ3KAThsqLxOVvDYOGK6y6fkOnifaVXT6H0AtLSL0pa8I7xrUv8ADS0UARsNwrym/wDgdDefHax+JC6vPE0OnNZSaWsKbJXOQsnmfeACMwZCDkhCCu1g/rNG0elXCpKnflZnKmpbnmXwc+Es3wntfEkc+vT6++r6vNqSTXMCQtBGyoqQ4T5SFCdQqg54UVzmg/s16ZoPx41D4i22pNHHdxSFdISHYFuZNvmzFw3zBsM2wr1djn7oX3DaPSmhR6VrHEVYuTUvi3IdGDsmtj5a+Ln7G9z4p+IT+L/A/itfA1/MjtcR2lrMrzTybvNkE0NxGY/MBUsAjZZd5y3Il+En7H95oPjjTfHvj7xPN4m8U29uN1rG0jW8dxh03GVz5kyrEQihgBku5XlBH9QKBnpQVC84FdCzDEqn7Pm0/G3a5H1eHNdnyC/7GvjnTfFniXUPDfxal8NWOr3fm7Y7Gee7aEM7RxPcG5V2KeYy7iSXwCcHAHd237PPjHWvBfirwt40+KM3izStUsbe2sI5NFiiNhNE29ZmcyM1wSwQlXPIUjPOa1f2qfjFrXwX+H9pq2gx6e2oXd+tkrakMxJmJ3BPzoAPk6lgAD75HED4kftAeDNBuPEvirw/4EvNLt7dpZrexvrizk3FhsfzG80bdvybQrEsVI44HVbE16casmrXsu+ljD93Tk4nrOk/BnT7f4H2Hw11S6OqWUOkppct35CIzlUCiZUcOFcMA653AMB1xXl/gn9kO98I/CXxz4Jbxob6PxFdJPBdy6dkW6oIseYnmDzGbyvmKNGPmO0Kea9h8CfFjQvHHwt0zx3Hcrpmi3lgNQla+kSMWqBcyCU7tqlOQ2Txitzw34w0PxdbTXGh6vZ6tbwvskksbhJ1RiM7SVJAOCDjOea4lWr04uCel7v1NuSEnzHiXxB/Zlv7zwb8O9N8Ha3bWOpeCF2WN1q8LTF8iNSxKnCsFRiCVYEgA4GTWSvw9/achltrZPiV4deG3kEx1CaxR5Lj51JilgW3VQm3fgpIr8AFudy/QU3jPQ4dcTRX1mwj1iSNpUsGukE7IMFnEed2BuHOMcj1rYjmEyhlOR69vah4iryqMlda7ruL2cZN2Z4X4b/Z/wBWsP2mNW+Jmqa4l7aPYG1tLbgybpFQSDaECxRr5YCgM7MOXckCs79oj9ma9+Jfirw/4u8I6lDofirTby3lmlmlkjjljjcHduj5EipvQcAMrEEgV9CzTLDC8sjqsSDLMxwAB1JPYD+lfL6ftd+JPGnivW9O+HHw9uPF2laRIFm1L7YIUkI64JXYuRyF3mQjB8vBzW9CeJqy9pTfwq3lbsRUVOCUX11N7xv+z34i0/4rH4kfD7X4tI1eaHbqGlXURe2vjgliQCMlsL1K4IJDLkg87/wz/wDEz41+LPDut/F3WdBsdM0OczW2g+F4rj/SAw6zSSS/IwICnYDkcZGTXZ/Db9qTT/iBovjBJNDvND8UeFrSS71HRL5tpXCs2A+3IyVKncgIPO0jBPnHjD9uifTfhfoGv2HhV7TWdankMEF4Wns0tY3AeVpkCZLDO1F+YZDMAOu9P63OShCPvLRP/gkful71z63jgSGIRxhUVRhQOAK+RPh14F+PXwkt9V0/w9oXg+dbjVJb2a6v55ZJLyJnY7A6um1gCArFAAeCP4q2tQ/bK1vwjPa3Pi/4ZapomhXSRvb38Nz5/m7xkAFo0jzt+bG8NjtX0d4f8QWnizQbLV9Mk8yzu4llhkdCPlPQkHB9axUcRg4t1I3jJ211V0XenWaSex8/TW/xt+Jfwl8baJ4w8KaBp99f2jQ6XDHIvzMzcJKollTAUff3jJI+Qc45X4X3X7Q/w98JeHPBll8O9OaysUCnUr3UIBtiBLNG5SdiJCTtDCMqO4r2X4c/tF6F4+8fa74TKf2fqlhNIkAllUpdRoQCVPHzg5LIN2Bg55ro9U+K1jpHxY0fwM9jdNdajYTX63y7BbwiMjMbksCGIOQAG4BJAHNaSqVaa9lOmv5tvImMYt8ye2h8x+OvDPxR0z9q8eNfD/gL+2Xl0azV2k2G2SRY3WaMStIgDqWwDkj2PSvWvhh8Tvi/4o1KO08U/DKy0q2muts04vyv2a1JIJKlWWVh0+VgW4O1RyYPCn7avgXxd8SB4Oii1C3mnuzZWGoywp9nu5N2wKuHLjLAgFlA4zkAjP0A2I17AAZOf51OJq1YxjTrU7NJW9CoQi3zRkfJVn4N+K37OPjXxZqPg/wjpPjfwprl59rFrBeNDewqFyFdnBZsEsFx5vGF+QCuz+GUfxW8efFCLxr4qtm8HeFrK0ntbfw2t3I8t2xI/ezR58vjkq5ww2kYAfNdxqP7RHgvT/idYeBW1QS61dTfZj5YBigmK7lhdyQPMboFGSMrnGRnu/EOvWPhXRb3VdRmW2sbOIzTStwFVRkmoqVajSU6fvSW/VhGEVqnojx34r6B4l1742/Cu/0qwml0ixlmnvZjGSkGdud4MygNs3jO0tn1BKtX/bC8O+IfF3w/0nSPD9ldXbXWqRpcy2ZlMlvEAW3kIpBXcAG3dASRlgAe6+Gvxo0L4lfD+48X2qzabpttJNHcLfGPfD5XLFxG7AfLhsEg4I4Fcr4b/a7+G3i7xZZ6Dp+qzNcXu0WkrWsgSZiwULwMocsAd4Xb3xkVEPbwkmo/AN+ztq9yf9oj4In4r+B4l02W3svFOlnz9O1OSDeytxuT5WUYfHfKhgrFDgCvGvHGnfEj42fBfwLZ634c1LTdes/ES22sxpavG00KxNtuVTOwruZCd4ZC6OV24Uj6A+Kf7Q3gT4LrCvivXFs55CjfZYIXnmjRs/vHjjDMsfyn5j1IwMkgHo/AfxG8NfErSBqfhvV7fVrQ43NE3zRFgGCyIcMhKkEBgDgg4qo4ivThG60TuvmL2dOTtc8D+M+k+Lfg/wDFqy+J3hTw9J4k0+ayNpq+n6eJBPeSBCsby+WrDAJUl1jJ+UAjABWa3+Nnjv44X+j6T4N8L6z4OtfPjbVtd1SEKlrgBnSIMpWU4DjnHPl7gA5I96vPHWhW3i618MSajEuu3ULTxWuCSyAE5JHAJ2tgEgsEYjIUkc140+P3gb4d61d6PrusfY9RtbOO/lt0tppWELsyIw2KdxJRsAZJCk44JGkakqqSdO80t/L0Fyxj10ueDQx+OH/af+LEHhbSobW/vNJki0/WL6F0hhmW1t2ikLkEOhldUKqD0z/yzYVk2nx8vbfSW8G/G34Z6l4kvYZdkd5eadbSrdTb5HBaMpHFtVVGx4fMyEYnlSW+pvhv8VPDfxY0+8v/AAzeyX9raz+RJNJZTW4D4yQPNRSRjHzAEHIwa6/7PGzbyik4xnHP+eBTeM5XarDVJLs1YPYqXwnyV+yDb3fw58J+MfEeq6ZqWgeDtT1C3/snT57e4uLkl2I83YqM7RlZbaJZMZYQs7AD5j598O9T1K4/aM+K/iefS9SMmiaLqky/ZEuvNYx3tvNBF5Zk3v5ghOE3BXVdqqqNtX77WMAdKQQpuJ2jmo+vy5qk3H4yvq6slfY+EPgv+x83xh8ON4x8a6rr/h3Ub68mnisbCK2t/OQuSbiVJbd23yPubIPzKFbnea+hvgV+zjb/AAV1zX9UPiW/12XUPKt4I7qGGFLa0iRFhj2xqAzKFILjaCCPlBGT7YsYHUUu0L0GKWKzLE4pOM5e726L0NKeHhTaa3FXpS0UV5Z1BRRRQAUUUUAFFFFACY5zR94UtJQB8k/8FAoJNe8MeDPDixWz/wBqaoyRPNcBG80oIkQIDvZX89gWUfKQpyMgN5N8cPA/j/4anSrz4leMNU8V+Abh7e2uprGRYFSNcSCM2JzFI4aMHJSQssbH5WIA+s/jP8Brb4y+IPBd/c6q2ljw7fG93W8AaeYb4pAiuWwiloEDblbK5A2nDDsfH3gex+Ing3UfD2pH/RLxAjMEVirBgVIDAgkEA+oIHfmvew2YKjTp07Kyvf5nnVMO5ylJM+OP2kvGHh/TfiZ8MtEs1i8Q6NYWsD2mhz3he0ubiXclv5sWSFcZRllKswVnGME1jaT4m8S/B3x9q66b4Ai+Gl9rmmX1/Ppf/CRpe20RSF2TUFhLeWu1k2eUijCrkBgCF98i/Y30ZfAFjpNzr+pS+JrCKWOz8U2zvbXNsrsWWFPLcEwRk/KjuxQZ2sCc0/wn+zTp3wr8P+IfEGoG6+KXjSTTZ4nm1yWSSS7j8oKbRTIZnCSbQpDGT72OBha6ljMLGCSV7Jp+dzn9jVbbZ4LZfs6+Bde/ZrvfiLqDwx+KJLY6gmrG+vEgLGRT88H2gxySt93cVOXKnnGT9T/sr38d7+z34FkhjWFP7MiXZHEkYBAwcKihOvXaMV+cnjDxh4V0jQdTtIF1vRLiG7Eo8NXWq282laZcIH3FJMgySAMEUGKNxk7nOBn790v4G6rfeAvhTbaX4v1Hwu3hiOGSaCwlkaG/jKoWimCugccHG4MBuJwTV5lTjGgry+KV/RWHh5PmfkegfGmO5m+Evi5LTZ5raZcDc7FQFMbBzkdMKWNeRfsI+HrbQ/goswTF9eXs00rMGVmQHbESrY/gAGRwffrX0lJCskbRuqurAgq3IIPUV8ia9+x14y8K6xdH4Z+PrzQ9G1KdnuLOe6nhe2U5OUaMnzT/AAjcEZVA+c15mFqU6lGeGnLlu07nTWUlNTSueifE74sfDfS9F+J1taXth/wkVjpT/wBq/ZYGMhYr5UaPJGMsyuVUgNujzzt618cahbaxp/7MfgmPUdLtdQjfxNqFxLdahF5oghBRliWRx8gkwU4zvAPzda+sfh7+yTD4V+GviXS9W1CDxB4p8QWbQXd/eRM8AIJaNVUtvKh8MSzbi3PHSvOfFn7GHjK7+C/hXwjputaTe32n6ne3N8tw8sFu0dwuCibUctt6ZZc/Nnr19bBYjCYeSjz3tJb9rP8AU5KtOpUV7GbceKvj58UPhLcaQvw3gXTbuNYLVoYl0+Xyt3ylI5pV8vaAAHOAwI2oBzX0j+zX8P8AWfhl8G9B0TXhEutpGZLuOCV5UjdiSY1ZmPQf3dqZzgCvQ/DOmNo3h3S9PkVVe0tYoCqEsoKoFwCQCRx1IFajR/u/8K8fFY728fZQgoxveyOulQ5NW7s/JCxh1XS21zxXpMiqPDWrTzveEvMYna5k2ufLG9RngyFkVRkFxkV7B4V+IWs/HL9p7wHrAmXT2gtorW+tH2iIptY3MKuSPMSTK4GM8bcNya+iP2d/gHrPw3n8bHxdp/hqZNcupWhXSSzolu7OTCVkgRgpzuYGSQFix4FcL8Of2TfEHws/aMstZ0Z7aXwRb+c8b3G0vFG64SEKGByvIV1GAMDBr6upm2FxUKkaqSlGFovueesPUpyVtm9T0Pw3+xp4E8J+PNN8Sac2pQ2+nXbahbaOtz/okdyc7ZAMbiEy21c4AOOQNte56hYpqGnz2sjMsc8bQsyMVYBhjgjoeetWlX5aWvhatarWalUldrQ9eMIxVkj4h8QfCXw18Gf2kfhjYWMGoXNrdyLK11JctPcmWJsJJM8km0IuRuKrkj3rW+PXxab4vfEiX4U6Xe29j4YtoXudX1LzgJL4xwmU2sIK4HHlkvlgQ2ADhiPSPir8JfEPjL9oj4eeJI7cXPhrRg0srwXAikhmU7lZgT+8UkAYUZ5IPByJPH37Hfw98WapruvDSbn+2L6CQ+XDeyJCZyvyyLGW2KxIA6BTkkjPNe/TxdBzpTxDu1Gyt0dzgdGpaUYnzf8ABvx74Wf9j34kaNqtzbaPfs01wbD7aPO2zpEsYDShw2ZB5Y+UrwoK5OW8v1WHxofh74D03xJZy6F4F0+9caTcR2ZimuHkYu8reaT5pAkk2ZVFdWyA23cPbfhr+xrrniL4Da7B4q0WPQfiDJcn7D9qnidPKQRsEcxmVUEjCRGIy4UnB5yZL74Y/G34keFPDvwu1Xw++i+GtLvY0l8RXE1lKnkRRr5SeXGyMQhA2lEBJChjwzH244vBwrTlTaa5m3forbo5HTqOKUlsebxax40uv2nvEmq+A7KHxN4pe4kvopjZMtv5Jt4wCySyoEzHsT5pBub5l27gB6t8Efi/o/gDwf8AFnXL3wevh7x5by+ZqUNm8sdlqEwaXyEj8xnWKXfI6sgzuPzLuB+Xc1b4SeNv2fvilqXin4d6I3inR9RsxaLaXN2801oMLhSXfcyho0KksxwWX5QBnF+Gf7Emo+MbXWNX+JN1deHNY1C7a8t7fQJot8BeSRpt+5ZUAdnBVFdyFxlt2dueIxGCrQTqNKCUbW+J900OnTrRlZbnmXwHuNT1z9pjwnq+s3TRahrlxNqVxJnEhlSB0IkikX5A6wxKpUYYHKEkErr/ABq8K/Ej4nftGeJ7e08MX3iCHTpIzZWt3aY08WiwoVjldjFDMHfzWKNI+VcrjhgOi+IH7IM/hb4n6PaeEtN1bXNLuA9/cSzXEcC22xhmJJvs5jVjlSiyZLYbLjrXc61Z/Gv4J+OPE154ftrr4iaJrwM1qup3ck6aVKAQsar5gO0ZOQiIHCpufzMu9VsbS9vGvhGneNrPSxUacuVxqd7ndfsm/E628beG9a0P/hGrDwrf+HLr7Nc2OlwCG3LvuLusYysbGZZ1KBn+5u3fPge+rmvmv9lj4JeIPB+ta3438TSS6Tqmuo+dAt2xBGZJjNJNKuT+9L5CqGOxC2SS5C/SlfHY7k9vL2b0PUoX9mrj+tLikUd6WuE6xA27P1xTqRVxS0AFFFFABRRRQAUUUUAFFFFABRRRQBGVHoKXtjtQ3SkbpUrUdhdo6immNW6jjpQn3adVO61QrXK32KAzFjEhfGN20ZqxtC8ACj1pFpcze4rKOyHZ70nFDdKbSvq0JuzF6nml2gcgdsUi9aX+EU9dRofgdxRtoaloQyLaM9cUbfQUN95aev3qXbzBoTj0o4op9USR7R1xRx0xS0n8RqE9NBibRnJpDjtg0r9KYv3hRfWxMvdsL5eOe/ehR1yKWlp7AtuYNo64GenSm7Ae1SUL96q9AaEVRnJFOAHNJQvejZleQ+iiigYUUUUAFFFFAH//2Q==';

  //colors variables

  const primary= '#2e559c'
  const secondary = '#5381c1'
  const thrid = '#d9d9d9'
  const fourth = '#484c44'
  const fifth = '#e6e6e6'
  const sixth = '#89cff7'
  
  

  // Definir la estructura del documento
  const documentDefinition = {

  content: [ 
     
      {
          style: 'table',
          table: {
                  widths: [340, 60, 86],
              body: [
                  
                  [{text:'PROTOCOLO DE PRUEBAS DE TRANSFORMADOR DE DISTRIBUCION', bold: 'true'}, {text:'FECHA:',  border:[true, true, false, true]}, {text: nuevoFormato, border:[false, true, true, true] }]
              ]
          },
          fontSize: 11,
          margin:[20, 0, 0, 0],
      },
      
      {
          style: 'table',
          table: {
                  widths: [90, 405],
              body: [
                  
                  [{text: 'CLIENTE:',bold: 'true', border:[true, true, false, true]}, {text: arrayPruebas['cliente'], alignment: 'justify', border:[false, true, true, true]}]
              ]
          },
          fontSize: 11,
          margin:[20, 0, 0, 0],
      },
      
      {
          style: 'table',
          table: {
                  widths: [110, 300, 40, 27],
              body: [
                  
                  [{text: 'DATOS TECNICOS:', bold: 'true', border:[true, true, false, true]}, {text: arrayPruebas['datos_t'], alignment: 'justify', border:[false, true, true, true]}, {text: 'Aceite:', border:[true, true, false, true]}, {text: 'NO', border:[false, true, true, true]}]
              ]
          },
          fontSize: 11,
          margin:[20, 0, 0, 10],
      },
      
      {
          style: 'table',
          table: {
                  widths: [70, 60, 20, 80, 70, 80, 70],
             
              body: [
                  [{text: 'POTENCIA:', border:[true, true, true, false]}, arrayPruebas['potencia'], 'KVA', {text: 'FASES:', border:[true, true, true, false]}, arrayPruebas['fases'] + 'Ø', {text: 'MARCA', border:[true, true, true, false]},{text: arrayPruebas['marca'], bold: 'true'}],
                  [{text: 'V1:', border:[true, false, true, false]}, arrayPruebas['v1'], 'V.', {text: 'FRECUENCIA:', border:[true, false, true, false]}, arrayPruebas['frecuencia']+'HZ', {text: 'TIPO:', border:[true, false, true, false]}, {text: 'SECO', bold: 'true'}],
                  [{text: 'V2:', border:[true, false, true, false]}, arrayPruebas['v2'], 'V.', {text: 'CONEXION:', border:[true, false, true, false]}, arrayPruebas['conexion'], {text: 'N° DE SERIE:', border:[true, false, true, false]}, arrayPruebas['serie']],
                  [{text: 'L1:', border:[true, false, true, false]}, arrayPruebas['l1'], 'A.', {text: 'GRUPO:', border:[true, false, true, false]}, arrayPruebas['grupo'], {text: 'AÑO FABRIC.:', border:[true, false, true, false]}, '2023'],
                  [{text: 'L2:', border:[true, false, true, true]}, arrayPruebas['l2'], 'A.', {text: 'ALTITUD:', border:[true, false, true, true]}, arrayPruebas['altitud']+'msnm', {text: 'NORMA:', border:[true, false, true, true]}, arrayPruebas['norma']]
              ]
          },
          fontSize: 10,
          alignment: 'justify',
          margin:[20, 0, 0, 10],
      },
      
        {
          style: 'table',
          table: {
                  widths: [50, 60, 60, 50, 60, 60, 60, 40],
             
              body: [
                  [{text: '1.1- PRUEBA DE RELACION DE TRANSFORMACION:', style: 'tableHeader', colSpan: 4, alignment: 'justify', bold: 'true', border:[true, true, false, true]}, {}, {}, {}, {text: arrayPruebas['v1']+'/'+arrayPruebas['v2'], bold:'true', border:[false, true, true, true]}, 'POLARIDAD :', 'Correcta',{text: 'Error de relacion +/- 0.5%',rowSpan: 4, alignment: 'justify'}],
                  [{text: 'Posición', border:[true, true, true, false]}, {text: 'TENSIONES COMPARADAS', style: 'tableHeader', colSpan: 2, alignment: 'justify'}, {}, {text: 'Relación', border:[true, true, true, false]}, 'U - V', 'U - V', 'U - V',{}],
                  [{text: 'Conmut.', border:[true, false, true, true]}, 'V1', 'V2', {text: 'Teórica', border:[true, false, true, true]}, 'u - v', 'u - v', 'u - v',{}],
                  ['1', '', '', '', '', '', '',{}],
                  ['2', arrayPruebas['v1'], arrayPruebas['v2'], arrayPruebas['rela_teo'], arrayPruebas['uv1'], arrayPruebas['uv2'], arrayPruebas['uv3'],{text: relaError.toFixed(2),rowSpan: 2}],
                  ['3', '', '', '', '', '', '',{}]
              ]
          },
          fontSize: 10,
          alignment: 'justify',
          margin:[20, 0, 0, 10],
      },
      
      {
          style: 'table',
          table: {
                  widths: [30, 30, 40, 40, 30, 30, 30, 35, 30, 40, 30, 40],
             
              body: [
                  [{text: '2.- PRUEBA DE VACIO:', style: 'tableHeader', colSpan: 12, alignment: 'justify', bold:'true'}, {}, {}, {},{}, {},{},{},{},{},{},{}],
                  [{text: 'TENSION', style: 'tableHeader', colSpan: 4, alignment: 'center'}, {}, {}, {},{text: 'INTENSIDAD', style: 'tableHeader', colSpan: 3, alignment: 'center'}, {}, {}, {rowSpan: 3, text: ''}, {text: 'WATIMETRO', style: 'tableHeader', colSpan: 3, alignment: 'center'}, {}, {}, 'Perdid.'],
                  ['Fases', 'u-v', 'v-w', 'w-u', 'u-v', 'v-w', 'w-u','','W1', valorW1.toFixed(2),'watios',''],
                  ['Valor', arrayPruebas['tensionu_v'], arrayPruebas['tensionv_w'], arrayPruebas['tensionw_u'], arrayPruebas['intensidadu_v'], arrayPruebas['intensidadv_w'], arrayPruebas['intensidadw_u'], '', 'W2', '', 'watios', Math.round(valorW1)]
              ]
          },
          fontSize: 10,
          alignment: 'center',
          margin:[20, 0, 0, 10],
      },
      
      {
          style: 'table',
          table: {
                  widths: [70, 150],
              body: [
                  
                  [{text: '3.- MEDIDA DE LA RESISTENCIA ', style: 'tableHeader', colSpan: 2, alignment: 'center', bold:'true', border:[true, true, true, false]},{}],
                  [{text: ' DE AISLAMIENTO:', style: 'tableHeader', colSpan: 2, alignment: 'center', bold:'true', border:[true, false, true, true]},{}],
                  [{text: 'AT-BT:', border:[true, true, false, true]},{text: arrayPruebas['at_bt']+' G-ohm, 1000VDC', border:[false, true, true, true]}],
                  [{text: 'AT-M::', border:[true, true, false, true]}, {text: arrayPruebas['at_m']+' G-ohm, 1000VDC', border:[false, true, true, true]}],
                  [{text: 'BT-M::', border:[true, true, false, true]}, {text: arrayPruebas['bt_m']+' G-ohm, 1000VDC', border:[false, true, true, true]}]
              ]
          },
          fontSize: 11,
          alignment: 'center',
          margin:[20, 0, 0, 10],
      },
      
      {
          style: 'table',
          table: {
                  widths: [150, 70],
              body: [
                  
                  [{text: 'MEDIDA DEL ESPESOR DE PINTURA ', style: 'tableHeader', colSpan: 2, alignment: 'center', bold:'true'},{}],
                  ['Valor minimo (µm)',arrayPruebas['minimo']],
                  ['Valor medido (µm)',arrayPruebas['medido']],
                  ['RESULTADO',arrayPruebas['resultado']]
              ]
          },
          fontSize: 11,
          alignment: 'center',
          absolutePosition: {x: 315, y: 360},
          margin:[0, 0, 0, 0],
          zIndex: 10
      },
      
      
      {
          style: 'table',
          table: {
                  widths: [40, 45, 45, 40, 20, 40, 50, 50, 50, 43],
             
              body: [
                  [{text: '4.- PRUEBA DE CORTOCIRCUITO:', style: 'tableHeader', colSpan: 10, alignment: 'justify', bold:'true'},{},{},{},{},{},{},{},{},{}],
                  [{text: 'INTENSIDAD', style: 'tableHeader', colSpan: 3},{},{}, {text: 'TENSION', style: 'tableHeader', colSpan: 3},{},{}, {text: 'WATIMETROS', style: 'tableHeader', colSpan: 3},{},{},{rowSpan:2, text: 'Perdid. Nucleo', margin:[0, 0, 0, 0]}],
                  [{text: 'LECTURA', style: 'tanleHeader', colSpan:2, alignment:'center'}, {}, 'VALOR', {text: 'LECTURA', style: 'tanleHeader', colSpan:2, alignment:'center'},{}, 'VALOR', {text: 'K=', border:[true, true, false, true]}, {text: 1, border:[false, true, false, true]},{text: '', border:[false, true, true, true]},''],
                  [{text: 'K=', border:[true, true, false, true]},  {text: kCortoCir.toFixed(2), border:[false, true, false, true]}, 'AMP', {text: 'K=', border:[true, true, false, true]},  {text: kCortoCir.toFixed(2), border:[false, true, false, true]}, 'Volt', 'W1', 'W2','Wr','K=' + kCortoCir + 'x' + 1],
                  [{text: arrayPruebas['int_lectura'], style: 'tanleHeader', colSpan:2, alignment:'center'}, {}, arrayPruebas['int_valor'], {text: arrayPruebas['ten_lectura'], style: 'tanleHeader', colSpan:2, alignment:'center'}, {},tenValor.toFixed(1), valorW1CC.toFixed(1), '',valorWr.toFixed(1), Math.round(perdNucleo) + 'w']
              ]
          },
          fontSize: 10,
          alignment: 'center',
          margin:[20, 0, 0, 10],
      },
      
      pruebaArro,
      
      {
          style: 'table',
          table: {
                  heights: [50, 20, 70],
                  widths: [162, 162, 162],
             
              body: [
                  [{text: 'OBSERVACIONES:', style: 'tableHeader', colSpan: 3, alignment: 'justify', bold:'true'},{},{}],
                  ['LABORATORIO DE PRUEBAS','REVISADO POR:','SUPERVISADO POR']
              ]
          },
          fontSize: 10,
          alignment: 'center',
          margin:[20, 0, 0, 10],
      },
      {
      image: img_lab,
          absolutePosition: {x: 60, y: 750},
          width: 140,
          height: 70,
          margin:[0, 0, 0, 0],
          //pageBreak: 'before', // Opcionalmente puedes agregar un salto de página antes de la imagen
          zIndex: 10 // Cambia el valor según sea necesario para que la imagen se superponga correctamente
      },
          
    ],
      
    styles: {
              header: {
              bold: true,
              fontSize: 32
              },
              smallText:{
                  fontSize: 8,
                  color: fourth,
                  bold: true
              },
              tableTotal:{
                  fontSize: 8,
                  color: 'black',
                  bold: true
              },
              notas:{
                  fontSize: 6,
                  color: fourth,
                  bold: true
              },
              footer:{
                  fontSize: 8
              },
              table:{
                  alignment: 'center',
                  fontSize: 9,
                  color: fourth,
              },
              table1:{
                  alignment: 'center',
                  fontSize: 8,
                  color: fourth,
              },
              table2:{
                  alignment: 'center',
                  fontSize: 8,
                  color: fourth,
              }
          },
          layout: {
              defaultBorder: false,
          },
          defaultStyle: {
              fontSize: 10
          },
    pageMargins: [20, 10, 20, 10],
  };

  return documentDefinition;

}