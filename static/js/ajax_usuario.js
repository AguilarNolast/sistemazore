
function registrarUsuario() {
    var passwordInput1 = document.getElementById("clave").value;
    var passwordInput2 = document.getElementById("rep_clave").value;
    const resultado = document.getElementById("resultado");
    
    var clavesIguales = compararClave(passwordInput1, passwordInput2);
    if(clavesIguales == true){

        const requiredInputs = document.querySelectorAll('#nuevoUser [required]');
    
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
            mostrarAlerta('warning', 'Por favor, complete todos los campos requeridos.');
            
            if (primerInputVacio) {
                primerInputVacio.focus();
            }
            
            removeAlert();

            return;
        }

        const formUser = document.getElementById('formUser');

        const formaData = new FormData(formUser);

        fetch(formUser.action, {
            method: formUser.method,
            body: formaData,
        })
            .then(response => response.json())
            .then(data => {
                resultado.innerHTML = data.data;
            })
            .then(() => {
                
                getListadoUsuarios();

                formUser.reset();
                    
                $('#nuevoUser').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                $('body').css('overflow', 'auto');

                removeAlert();
            })
            .catch(error => {
                mostrarAlerta('danger', "Error al registrar usuario");
            });
            
    }else{
        mostrarAlerta('warning', "Las claves ingresadas son diferentes");
        removeAlert();
    }
}

function editarUsuario(id_usuario) {
    var passwordInput1 = document.getElementById("clave"+id_usuario).value;
    var passwordInput2 = document.getElementById("rep_clave"+id_usuario).value;
    const resultado = document.getElementById("resultado");
    
    var clavesIguales = compararClave(passwordInput1, passwordInput2);
    if(clavesIguales == true){

        const formUserEdit = document.getElementById('formUserEdit'+id_usuario);

        const formaData = new FormData(formUserEdit);

        const usuario = obtenerValoresInput("usuario"+id_usuario);
        const clave = obtenerValoresInput("clave"+id_usuario);
        const nombre = obtenerValoresInput("nombre"+id_usuario);
        const apellido = obtenerValoresInput("apellido"+id_usuario);
        const telefono = obtenerValoresInput("telefono"+id_usuario);
        const correo = obtenerValoresInput("correo"+id_usuario);

        formaData.append("id_usuario", id_usuario);
        formaData.append("usuario", usuario);
        formaData.append("clave", clave);
        formaData.append("nombre", nombre);
        formaData.append("apellido", apellido);
        formaData.append("telefono", telefono);
        formaData.append("correo", correo);

        fetch(formUserEdit.action, {
            method: formUserEdit.method,
            body: formaData,
        })
            .then(response => response.json())
            .then(data => {
                resultado.innerHTML = data.data;
            })
            .then(() => {
                
                getListadoUsuarios();

                formUserEdit.reset();
                    
                $('#nuevoUser'+id_usuario).modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                $('body').css('overflow', 'auto');

                removeAlert();
            })
            .catch(error => {
                mostrarAlerta('danger', "Error al editar usuario");
            });
    }else{
        mostrarAlerta('warning', "Las claves ingresadas son diferentes");
        removeAlert();
    }
}

function eliminarUsuario(id_usuario) {              
    const resultado = document.getElementById("resultado");

    const url = "../control/eliminarusuario.php";
    const formaData = new FormData();
    formaData.append("id_usuario", id_usuario);

    fetch(url, {
        method: "POST",
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            resultado.innerHTML = data.data;
        })
        .catch(error => {
            mostrarAlerta('danger', "Error al eliminar usuario");

            removeAlert();
        });

        getListadoUsuarios();
            
        $('#nuevoUser'+id_usuario).modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();

        $('body').css('overflow', 'auto');
        
        removeAlert();
}

function compararClave(clave, rep_clave){

    let clave_input = "";
    let rep_clave_input = "";

    if(clave == undefined && rep_clave == undefined){
        clave_input = document.getElementById("clave").value;
        rep_clave_input = document.getElementById("rep_clave").value;
    }else{
        clave_input = clave;
        rep_clave_input = rep_clave;
    }

    const clave_span = document.getElementById("clave_span");
    const rep_clave_span = document.getElementById("rep_clave_span");
    
    if(clave_input != rep_clave_input){
        clave_span.innerHTML  =  `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
            </svg>
        `
        rep_clave_span.innerHTML  =  `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
            </svg>
        `
        return false;
    }else{
        clave_span.innerHTML  =  `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
            </svg>
        `
        rep_clave_span.innerHTML  =  `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
            </svg>
        `
        return true;
    }
}

function compararClave2(id_usuario){

    clave_input = document.getElementById("clave"+id_usuario).value;
    rep_clave_input = document.getElementById("rep_clave"+id_usuario).value;

    const clave_span = document.getElementById("clave_span"+id_usuario);
    const rep_clave_span = document.getElementById("rep_clave_span"+id_usuario);
    
    if(clave_input != rep_clave_input){
        clave_span.innerHTML  =  `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
            </svg>
        `
        rep_clave_span.innerHTML  =  `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
            </svg>
        `
        return false;
    }else{
        clave_span.innerHTML  =  `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
            </svg>
        `
        rep_clave_span.innerHTML  =  `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
            </svg>
        `
        return true;
    }
}

function compararClave3(clave, rep_clave){

    let clave_input = "";
    let rep_clave_input = "";

    if(clave == undefined && rep_clave == undefined){
        clave_input = document.getElementById("claveperfil").value;
        rep_clave_input = document.getElementById("rep_claveperfil").value;
    }else{
        clave_input = clave;
        rep_clave_input = rep_clave;
    }

    const clave_span = document.getElementById("clave_spanperfil");
    const rep_clave_span = document.getElementById("rep_clave_spanperfil");
    
    if(clave_input != rep_clave_input){
        clave_span.innerHTML  =  `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
            </svg>
        `
        rep_clave_span.innerHTML  =  `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
            </svg>
        `
        return false;
    }else{
        clave_span.innerHTML  =  `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
            </svg>
        `
        rep_clave_span.innerHTML  =  `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
            </svg>
        `
        return true;
    }
}

function registrarEntrada(id_usuario){
    const resultado = document.getElementById("resultado");

    const url = "../control/registrar_entrada.php";
    const formaData = new FormData();
    formaData.append("id_usuario", id_usuario);

    fetch(url, {
        method: "POST",
        body: formaData,
    })
    .then(response => response.json())
    .then(data => {
        resultado.innerHTML = data.data;

    })
    .catch(error => {
        mostrarAlerta('danger', 'Error al registrar entrada');
    });

    getListadoAsistencia();

    removeAlert();
}

function registrarSalida(id_usuario){
    const resultado = document.getElementById("resultado");

    const url = "../control/registrar_salida.php";
    const formaData = new FormData();
    formaData.append("id_usuario", id_usuario);

    fetch(url, {
        method: "POST",
        body: formaData,
    })
    .then(response => response.json())
    .then(data => {
        resultado.innerHTML = data.data;
    })
    .catch(error => {
        mostrarAlerta('danger', 'Error al registrar salida');
    });
    
    getListadoAsistencia();

    removeAlert();
}

function editarPerfil(id_usuario) {
    var passwordInput1 = document.getElementById("claveperfil").value;
    var passwordInput2 = document.getElementById("rep_claveperfil").value;
    const resultado = document.getElementById("resultado");
    
    var clavesIguales = compararClave3(passwordInput1, passwordInput2);
    if(clavesIguales == true){

        const formPerfil = document.getElementById('formPerfil');

        const formaData = new FormData(formPerfil);
        formaData.append("id_usuario", id_usuario);

        fetch(formPerfil.action, {
            method: formPerfil.method,
            body: formaData,
        })
            .then(response => response.json())
            .then(data => {
                resultado.innerHTML = data.data;
            })
            .catch(error => {
                mostrarAlerta('danger', "Error al editar perfil");
            });
        
            $('#perfil').modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('body').css('overflow', 'auto');

            removeAlert();
    }else{
        mostrarAlerta('warning', "Las claves ingresadas son diferentes");
        
        removeAlert();
    }
}

function filtrarAsistencia() {
    let num_registros = document.getElementById("num_registros").value; // Obtengo la cantidad de registro que desea mostrar
    let content = document.getElementById("contenido"); // Obtengo el contenedor donde estarán los datos de la BD
    let pagina = document.getElementById("pagina").value; // Obtengo el numero de pagina
    let orderCol = document.getElementById("orderCol").value; // Obtengo el numero de pagina
    let orderType = document.getElementById("orderType").value; // Obtengo el numero de pagina

    checkDateAsistencia = document.getElementById("checkDateAsistencia");

    if(!checkDateAsistencia.checked){
        var dateIn = document.getElementById("dateIn").value; // Fecha de inicio para el filtro
        var dateOut = document.getElementById("dateOut").value; // Fecha final para el filtro
        
        if(isNaN(Date.parse(dateIn)) || isNaN(Date.parse(dateOut))){
            mostrarAlerta('warning', 'Seleccione una fecha valida');
            
            removeAlert();

            return;
        }

        if (dateIn >= dateOut) {
            mostrarAlerta('warning', 'Debe seleccionar una fecha de inicio menor a la final');
            
            removeAlert();

            return;
        }
    }else{
        var dateIn = false;
        var dateOut = false;
    }
    
    if (pagina == null) {
        pagina = 1;
    }

    const formFilterUsuario = document.getElementById('formFilterUsuario');

    const formData = new FormData(formFilterUsuario);
    
    formData.append('registros', num_registros); // Agregamos la cantidad de registros al FormData
    formData.append('pagina', pagina); 
    formData.append('orderCol', orderCol); 
    formData.append('orderType', orderType);

    fetch(formFilterUsuario.action, { // Generamos la petición con fetch
        method: formFilterUsuario.method,
        body: formData
    })
    .then(response => response.json()) // Recibimos el JSON que viene desde el archivo PHP
    .then(data => {
        content.innerHTML = data.data;
        document.getElementById("lbl-total").innerHTML = `Mostrando ${data.totalFiltro} de ${data.totalRegistros} registros`;
        document.getElementById("nav-paginacion").innerHTML = data.paginacion;
    })
    .catch(err => {
        mostrarAlerta('danger', "Se ha producido un error");
    });
}

function nextPageFilterAsistencia(pagina){
    document.getElementById('pagina').value = pagina
    filtrarAsistencia()
}

function eliminarFiltroAsistencia(){
    const formFilterUsuario = document.getElementById('formFilterUsuario');
    formFilterUsuario.reset();
    var dateIn = document.getElementById("dateIn"); // Fecha de inicio para el filtro
    var dateOut = document.getElementById("dateOut"); // Fecha final para el filtro
    dateIn.disabled = true;
    dateOut.disabled = true;
    getListadoAsistencia();
}

document.addEventListener("DOMContentLoaded", function() {
    checkDateAsistencia = document.getElementById("checkDateAsistencia");

    checkDateAsistencia.addEventListener("change", function(event){
        disabledDatesInputAsistencia();
    });
});

function disabledDatesInputAsistencia(){
    var dateIn = document.getElementById("dateIn"); // Fecha de inicio para el filtro
    var dateOut = document.getElementById("dateOut"); // Fecha final para el filtro

    if(dateIn.disabled == true){
        dateIn.disabled = false;
        dateOut.disabled = false;
    }else{
        dateIn.disabled = true;
        dateOut.disabled = true;
    }
}

document.addEventListener("DOMContentLoaded", function() {
    // Obtener el botón para generar el Excel
    var buttonExcel = document.getElementById("excelAsist");

    // Agregar un event listener para el evento 'click' del botón
    buttonExcel.addEventListener("click", function(event) {
        // Evitar el comportamiento predeterminado del botón
        event.preventDefault();
        checkDateAsistencia = document.getElementById("checkDateAsistencia");

        // Obtener los valores de los campos del formulario

        if(!checkDateAsistencia.checked){
            var dateIn = document.getElementById("dateIn").value; // Fecha de inicio para el filtro
            var dateOut = document.getElementById("dateOut").value; // Fecha final para el filtro
            if(isNaN(Date.parse(dateIn)) || isNaN(Date.parse(dateOut))){
                mostrarAlerta('warning', 'Seleccione una fecha valida');
                
                removeAlert();
    
                return;
            }
    
            if (dateIn >= dateOut) {
                mostrarAlerta('warning', 'Debe seleccionar una fecha de inicio menor a la final');
                
                removeAlert();
    
                return;
            }
        }else{
            var dateIn = false;
            var dateOut = false;
        }

        var selectUser = document.getElementById("selectUser").value; // Usuario seleccionado para el filtro

        // Crear un formulario dinámicamente
        var form = document.createElement("form");
        form.action = '../control/excelasistencia.php';
        form.method = 'POST';

        // Crear campos ocultos y agregarlos al formulario
        var dateInField = document.createElement("input");
        dateInField.type = "hidden";
        dateInField.name = "dateIn";
        dateInField.value = dateIn;
        form.appendChild(dateInField);

        var dateOutField = document.createElement("input");
        dateOutField.type = "hidden";
        dateOutField.name = "dateOut";
        dateOutField.value = dateOut;
        form.appendChild(dateOutField);

        var selectUserField = document.createElement("input");
        selectUserField.type = "hidden";
        selectUserField.name = "selectUser";
        selectUserField.value = selectUser;
        form.appendChild(selectUserField);

        // Agregar el formulario al documento y enviarlo
        document.body.appendChild(form);
        form.submit();
    });
});