
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
            mostrarAlerta('danger', 'Por favor, complete todos los campos requeridos.');
            
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
                resultado.innerHTML = `
                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error: ${error.message}
                    </div>
                `
            });
            
    }else{
        resultado.innerHTML = `
            <div class="alert alert-danger" id="miAlert" role="alert">
                Las claves ingresadas son diferentes
            </div>
        `

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

        formaData.forEach((value, key) => {
            console.log(`${key}: ${value}`);
        });

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
                resultado.innerHTML = `
                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error: ${error.message}
                    </div>
                `
            });
    }else{
        resultado.innerHTML = `
            <div class="alert alert-danger" id="miAlert" role="alert">
                Las claves ingresadas son diferentes
            </div>
        `

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
            resultado.innerHTML = `
                <div class="alert alert-danger" id="miAlert" role="alert">
                    Error: ${error.message}
                </div>
            `

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
    .catch(err => console.log(err));

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
    .catch(err => console.log(err));
    
    getListadoAsistencia();

    removeAlert();
}

function editarPerfil(id_usuario) {
    var passwordInput1 = document.getElementById("claveperfil").value;
    var passwordInput2 = document.getElementById("rep_claveperfil").value;
    const resultado = document.getElementById("resultado");
    
    var clavesIguales = compararClave3(passwordInput1, passwordInput2);
    if(clavesIguales == true){

        /*const usuario = obtenerValoresInput("usuarioperfil");
        const clave = obtenerValoresInput("claveperfil");
        const nombre = obtenerValoresInput("nombreperfil");
        const apellido = obtenerValoresInput("apellidoperfil");
        const telefono = obtenerValoresInput("telefonoperfil");
        const correo = obtenerValoresInput("correoperfil");

        const url = "../control/editar_usuario.php";
        const formaData = new FormData();
        formaData.append("id_usuario", id_usuario);
        formaData.append("usuario", usuario);
        formaData.append("clave", clave);
        formaData.append("nombre", nombre);
        formaData.append("apellido", apellido);
        formaData.append("telefono", telefono);
        formaData.append("correo", correo);*/

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
                resultado.innerHTML = `
                    <div class="alert alert-danger" id="miAlert" role="alert">
                        Error: ${error.message}
                    </div>
                `
            });
                
            $('#perfil').modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('body').css('overflow', 'auto');

            removeAlert();
    }else{
        resultado.innerHTML = `
            <div class="alert alert-danger" id="miAlert" role="alert">
                Las claves ingresadas son diferentes
            </div>
        `

        removeAlert();
    }
}