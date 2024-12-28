
function removeAlert(){//Remueve las alertas que se muestran en el sistema
    setTimeout(function() {
        var alertElement = document.getElementById('miAlert');
        alertElement.parentNode.removeChild(alertElement);
    }, 5000);
}

function obtenerValoresInputsClase(clase) {
    const elementos = document.getElementsByClassName(clase);
    return Array.from(elementos).map(elemento => elemento.value);
}

function obtenerValoresInputsClaseById(clase, id_cliente) {
    const elementos = document.getElementsByClassName(clase + id_cliente);
    return Array.from(elementos).map(elemento => elemento.value);
}

function checkSession() {//Verifica si la sesion sigue activa
    fetch('../control/checksession.php')
        .then(response => response.json())
        .then(data => {
            if(data.redir == true){
                window.location.href = "../vistas/inicio.php";
                return;
            }
        })
        .catch(error => {
            mostrarAlerta('danger', 'Error al verificar la sesión');
        });
}

function enviarFormulario() {
    // Deshabilitar el botón de enviar
    document.getElementById('btnEnviar').disabled = true;

    const formaData = new FormData(document.getElementById('formReset'));

    fetch(document.getElementById('formReset').action, {
        method: document.getElementById('formReset').method,
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            mostrarAlerta(data.tipo, data.mensaje);

            if(data.redir == true){
                setTimeout(function() {
                    window.location.href = "../../index.php";
                }, 3000);
            }else{
                // Volver a habilitar el botón de enviar después de la consulta
                document.getElementById('btnEnviar').disabled = false;
            }
        })
        .catch(error => {
            mostrarAlerta('danger', 'Error al enviar formulario');
        });
        
        removeAlert();
}

function logIn(){//Funcion que realiza el inicio de sesion
    // Deshabilitar el botón de enviar
    document.getElementById('btnEnviar').disabled = true;

    const formaData = new FormData(document.getElementById('formLogIn'));

    fetch(document.getElementById('formLogIn').action, {
        method: document.getElementById('formLogIn').method,
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            console.log(data)
            mostrarAlerta(data.tipo, data.mensaje);

            if(data.redir == true){
                //window.location.href = "../vistas/inicio.php";
                window.location.href = "https://grupozore.com/sistemazore/vistas/inicio.php";
            }else{
                // Volver a habilitar el botón de enviar después de la consulta
                document.getElementById('btnEnviar').disabled = false;
            }

        })
        .catch(error => {
            console.log(error);
            mostrarAlerta('danger', 'Error al iniciar sesion');
        });
}

function mostrarAlerta(tipo, mensaje) {//Muestra una alerta basada en los datos enviados
    const alertaResultado = document.getElementById('alertaResultado');
    alertaResultado.innerHTML = `
        <div class="alert alert-${tipo}" id="miAlert" role="alert">
            ${mensaje}
        </div>
    `;
    removeAlert();
}

function updateConfig() {//Funcion que actualiza la configuracion del servidor
    // Deshabilitar el botón de enviar
    document.getElementById('btnConfig').disabled = true;

    const formaData = new FormData(document.getElementById('formConfig'));

    fetch(document.getElementById('formConfig').action, {
        method: document.getElementById('formConfig').method,
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            const alertaResultado = document.getElementById('alertaResultado');
            alertaResultado.innerHTML = `
                <div class="alert alert-${data.tipo}" id="miAlert" role="alert">
                    ${data.mensaje}
                </div>
            `;
            removeAlert();
            document.getElementById('btnConfig').disabled = false;

            $('#config').modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('body').css('overflow', 'auto');
        })
        .catch(error => {
            mostrarAlerta('danger', 'Error al actualizar la configuracion');
        });
        
        removeAlert();
}