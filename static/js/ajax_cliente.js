

function closeList(){
    var listaOverlay = document.getElementById('lista-overlay');

    listaOverlay.style.display = 'none';
}

function cleanInputCliente(){
    const input_cliente = document.getElementById("input_cliente");

    input_cliente.value = "";
}

async function getCliente() {
    try {
        const input_cliente = document.getElementById("input_cliente").value;
        const csrf_token = document.getElementById("csrf_token").value;
        const url = "../control/ajax_cargar_clientes.php";

        const formData = new FormData();
        formData.append('input_cliente', input_cliente);
        formData.append('csrf_token', csrf_token);

        var listaOverlay = document.getElementById('lista-overlay');

        listaOverlay.addEventListener('blur', function() {
            listaOverlay.style.display = 'none';
        });

        const response = await fetch(url, {
            method: "POST",
            body: formData
        });

        if (!response.ok) {
            throw new Error(`Error al realizar la solicitud: ${response.statusText}`);
        }

        const data = await response.json();
        listaOverlay.style.display = 'block';
        listaOverlay.innerHTML = data.data;
    } catch (err) {
        mostrarAlerta('danger', 'Error al cargar cliente');
    }
} 

function getContacto(e) {
    var dataClient = e.params.data;

    var partes = dataClient.id.split(',');

    // Asignar cada parte a una variable específica
    var id_cliente = partes[0];
    var razon_social = partes[1];
    var ruc = partes[2];

    const contactoContainer = document.getElementById("contacto");
    const inputIdCliente = document.getElementById("idcliente");
    const razonSocial = document.getElementById("razonSocial");

    inputIdCliente.value = id_cliente;
    razonSocial.value = razon_social;
    
    const url = "../control/ajax_contacto_cliente.php";
    const formData = new FormData();
    formData.append('id_cliente', id_cliente);

    fetch(url, {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error al realizar la solicitud: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        contactoContainer.innerHTML = data.data;
        mostrarContacto(data.id_contacto);
    })
    .catch(error => {
        mostrarAlerta('danger', 'Error al cargar cliente');
        console.log(error)
    });
}

function getContacto2(id_cliente, razon_social, ruc) {

    const contactoContainer = document.getElementById("contacto");
    const inputIdCliente = document.getElementById("idcliente");
    const razonSocial = document.getElementById("razonSocial");

    inputIdCliente.value = id_cliente;
    razonSocial.value = razon_social;
    
    const url = "../control/ajax_contacto_cliente.php";
    const formData = new FormData();
    formData.append('id_cliente', id_cliente);

    fetch(url, {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error al realizar la solicitud: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        contactoContainer.innerHTML = data.data;
        mostrarContacto(data.id_contacto);
    })
    .catch(error => {
        mostrarAlerta('danger', 'Error al cargar cliente');
        console.log(error)
    });
}

function contactoEditarCoti(id_cliente, nombre_entidad) {
    const contactoContainer = document.getElementById("contacto");
    const inputIdCliente = document.getElementById("idcliente");
    const razonSocial = document.getElementById("razonSocial");
    inputIdCliente.value = id_cliente;
    razonSocial.value = nombre_entidad;

    var listaOverlay = document.getElementById('lista-overlay');
    
    const url = "../control/ajax_contacto_cliente.php";
    const formData = new FormData();
    formData.append('id_cliente', id_cliente);

    fetch(url, {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error al realizar la solicitud: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        listaOverlay.style.display = 'none';
        contactoContainer.innerHTML = data.data;
        mostrarContacto(data.id_contacto);
    })
    .catch(error => {
        mostrarAlerta('danger', 'Error al cargar contactos');
    });
}

function mostrarContacto(id_contacto) {

    const telefonoInput = document.getElementById("telefono");
    const correoInput = document.getElementById("correo");

    const url = "../control/ajax_contacto.php";
    const formData = new FormData();
    formData.append('id_contacto', id_contacto);
    fetch(url, {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error al realizar la solicitud: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        // Actualiza los valores de los elementos del formulario
        telefonoInput.value = data.telefono;
        correoInput.value = data.correo;
    })
    .catch(error => {
        mostrarAlerta('danger', 'Error al cargar contacto');
    });
}

function añadirContacto() {
    const contContactos = document.getElementById("cont_contactos");
    const cantidadItem = contContactos.querySelectorAll('div.cont').length;
    const numeroItem = cantidadItem + 1;

    const nuevoItem = `
        <div class="row justify-content-center">
            <div class="md-form mb-2">
                <i class="grey-text">Nombre</i>
                <input type="text" class="form-control nombre" name="nombre[]" id="nombre${numeroItem}" placeholder="Nombre y apellido" required>
            </div>
            <div class="md-form mb-2">
                <i class="grey-text">Telefono</i>
                <input type="text" class="form-control telefono" name="telefono[]" id="telefono${numeroItem}" placeholder="Telefono">
            </div>
            <div class="md-form mb-2">
                <i class="grey-text">Correo electronico</i>
                <input type="text" class="form-control correo" name="correo[]" id="correo${numeroItem}" placeholder="Correo electronico">
            </div>
            <div class="md-form mb-2">
                <i class="grey-text">Cargo</i>
            <input type="text" class="form-control cargo" name="cargo[]" id="cargo1" placeholder="Cargo" required>
            </div>
            <div class="md-form mb-2">
                <button type="button" class="btn btn-primary" onclick="removeContacto(${numeroItem})" id="removecontacto">x</button>
            </div>            
        </div>
    `;

    const divContacto = document.createElement('div');
    divContacto.id = 'cont' + numeroItem;
    divContacto.setAttribute("class", "cont");
    divContacto.innerHTML = nuevoItem;
    contContactos.append(divContacto);
}

function añadirContacto2(item, id_cliente) {
    const contContactos = document.getElementById("cont_contactos"+id_cliente);
    const cantidadItem = contContactos.querySelectorAll('div.cont'+item+id_cliente).length;
    const numeroItem = cantidadItem + 10;

    const nuevoItem = `
        <div class="row justify-content-center">
            <div class="md-form mb-2">
                <i class="grey-text">Nombre</i>
                <input type="text" class="form-control nombrenuevo${id_cliente}" name="nombre[]" id="nombre${numeroItem}${id_cliente}" placeholder="Nombre y apellido" required>
            </div>
            <div class="md-form mb-2">
                <i class="grey-text">Telefono</i>
                <input type="text" class="form-control telefononuevo${id_cliente}" name="telefono[]" id="telefono${numeroItem}${id_cliente}" placeholder="Telefono" required>
            </div>
            <div class="md-form mb-2">
                <i class="grey-text">Correo electronico</i>
                <input type="text" class="form-control correonuevo${id_cliente}" name="correo[]" id="correo${numeroItem}${id_cliente}" placeholder="Correo electronico" required>
            </div>                              
            <div class="md-form mb-2">
                <i class="grey-text">Cargo</i>
                <input type="text" class="form-control cargonuevo${id_cliente}"  name="cargo[]" id="cargo${numeroItem}${id_cliente}" placeholder="Cargo" required>
            </div> 
            <div class="md-form mb-2">
                <button type="button" class="btn btn-primary" onclick="removeContacto(${numeroItem}${id_cliente})" id="removecontacto">x</button>
            </div>  
        </div>
    `;

    const divContacto = document.createElement('div');
    divContacto.id = 'cont' + numeroItem + id_cliente;
    divContacto.setAttribute("class", "cont");
    divContacto.innerHTML = nuevoItem;
    contContactos.append(divContacto);
}

function removeContacto(id) {
    const cont = document.getElementById("cont" + id);

    // Verificar si el elemento existe antes de intentar eliminarlo
    if (cont) {
        cont.remove();
    } else {
        mostrarAlerta('danger', 'Error al remover contacto');
    }
}

function obtenerValoresInput(id) {
    return document.getElementById(id).value;
}

function obtenerValoresInputsClase(clase) {
    const elementos = document.getElementsByClassName(clase);
    return Array.from(elementos).map(elemento => elemento.value);
}

function registrarCliente() {
    const resultado = document.getElementById("resultado");

    const requiredInputs = document.querySelectorAll('#nuevoCli [required]');
    
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

    const numero = obtenerValoresInput("numero");
    const entidad = obtenerValoresInput("entidad");
    const direccion = obtenerValoresInput("direccion");
    const distrito = obtenerValoresInput("distrito");
    const departamento = obtenerValoresInput("departamento");
    const tipocliente = obtenerValoresInput("tipocliente");
    const pagocliente = obtenerValoresInput("pagocliente");

    const nombre = obtenerValoresInputsClase("nombre");
    const telefono = obtenerValoresInputsClase("telefono");
    const correo = obtenerValoresInputsClase("correo");
    const cargo = obtenerValoresInputsClase("cargo");

    const url = "../control/registrar_cliente.php";
    const formaData = new FormData();

    formaData.append("numero", numero);
    formaData.append("entidad", entidad);
    formaData.append("direccion", direccion);
    formaData.append("distrito", distrito);
    formaData.append("departamento", departamento);
    formaData.append("tipocliente", tipocliente);
    formaData.append("pagocliente", pagocliente);

    formaData.append("nombre", JSON.stringify(nombre));
    formaData.append("telefono", JSON.stringify(telefono));
    formaData.append("correo", JSON.stringify(correo));
    formaData.append("cargo", JSON.stringify(cargo));

    fetch(url, {
        method: "POST",
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            resultado.innerHTML = data.data;

            document.getElementById("numero").value = "";
            document.getElementById("entidad").value = "";
            document.getElementById("direccion").value = "";
            document.getElementById("distrito").value = "";
            document.getElementById("departamento").value = "";
            document.getElementById("pagocliente").value = "";
            document.getElementById("tipocliente").selectedIndex = 0;
            document.getElementById("pagocliente").selectedIndex = 0;

            n = document.getElementsByClassName("nombre");
            t = document.getElementsByClassName("telefono");
            c = document.getElementsByClassName("correo");
            ca = document.getElementsByClassName("cargo");

            for (var i = 0; i < n.length; i++) {
                n[i].value = "";
            }

            for (var i = 0; i < t.length; i++) {
                t[i].value = "";
            }

            for (var i = 0; i < c.length; i++) {
                c[i].value = "";
            }

            for (var i = 0; i < ca.length; i++) {
                ca[i].value = "";
            }
        })
        .catch(error => {
            mostrarAlerta('danger', 'Error al registrar cliente');
        });

        getListadoClientes();
            
        $('#nuevoCli').modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove(); 

        $('body').css('overflow', 'auto');

        removeAlert();
}

function registrarCliente2() {
    const resultado = document.getElementById("resultado");

    const requiredInputs = document.querySelectorAll('#nuevoCli [required]');
    
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

    const numero = obtenerValoresInput("numero");
    const entidad = obtenerValoresInput("entidad");
    const direccion = obtenerValoresInput("direccion");
    const distrito = obtenerValoresInput("distrito");
    const departamento = obtenerValoresInput("departamento");
    const tipocliente = obtenerValoresInput("tipocliente");
    const pagocliente = obtenerValoresInput("pagocliente");

    const nombre = obtenerValoresInputsClase("nombre");
    const telefono = obtenerValoresInputsClase("telefono");
    const correo = obtenerValoresInputsClase("correo");
    const cargo = obtenerValoresInputsClase("cargo");

    const url = "../control/registrar_cliente2.php";
    const formaData = new FormData();

    formaData.append("numero", numero);
    formaData.append("entidad", entidad);
    formaData.append("direccion", direccion);
    formaData.append("distrito", distrito);
    formaData.append("departamento", departamento);
    formaData.append("tipocliente", tipocliente);
    formaData.append("pagocliente", pagocliente);

    formaData.append("nombre", JSON.stringify(nombre));
    formaData.append("telefono", JSON.stringify(telefono));
    formaData.append("correo", JSON.stringify(correo));
    formaData.append("cargo", JSON.stringify(cargo));

    fetch(url, {
        method: "POST",
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            resultado.innerHTML = data.data;

            if(data.id_cliente!='' && data.nombre_entidad!='' && data.ruc!=''){
                getContacto2(data.id_cliente, data.nombre_entidad, data.ruc);

                document.getElementById("numero").value = "";
                document.getElementById("entidad").value = "";
                document.getElementById("direccion").value = "";
                document.getElementById("distrito").value = "";
                document.getElementById("departamento").value = "";
                document.getElementById("pagocliente").value = "";
                document.getElementById("tipocliente").selectedIndex = 0;
                document.getElementById("pagocliente").selectedIndex = 0;

                n = document.getElementsByClassName("nombre");
                t = document.getElementsByClassName("telefono");
                c = document.getElementsByClassName("correo");
                ca = document.getElementsByClassName("cargo");

                for (var i = 0; i < n.length; i++) {
                    n[i].value = "";
                }

                for (var i = 0; i < t.length; i++) {
                    t[i].value = "";
                }

                for (var i = 0; i < c.length; i++) {
                    c[i].value = "";
                }

                for (var i = 0; i < ca.length; i++) {
                    ca[i].value = "";
                }
                
                reiniciarSelect2Client() 
            }
        })
        .catch(error => {
            mostrarAlerta('danger', 'Error al cargar cliente');
            console.log(error)
        });
            
        $('#nuevoCli').modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove(); 

        removeAlert();
}

function editarCliente(id_cliente) {

    checkSession();
    
    const resultado = document.getElementById("resultado");

    const numero = obtenerValoresInput("numero" + id_cliente);
    const entidad = obtenerValoresInput("entidad" + id_cliente);
    const direccion = obtenerValoresInput("direccion" + id_cliente);
    const distrito = obtenerValoresInput("distrito" + id_cliente);
    const departamento = obtenerValoresInput("departamento" + id_cliente);
    const tipocliente = obtenerValoresInput("tipocliente" + id_cliente);
    const pagocliente = obtenerValoresInput("pagocliente" + id_cliente);
    const usercliente = obtenerValoresInput("usercliente" + id_cliente);

    const id_contacto = obtenerValoresInputsClaseById("id_contacto", id_cliente);
    const nombre = obtenerValoresInputsClaseById("nombre", id_cliente);
    const telefono = obtenerValoresInputsClaseById("telefono", id_cliente);
    const correo = obtenerValoresInputsClaseById("correo", id_cliente);
    const cargo = obtenerValoresInputsClaseById("cargo", id_cliente);

    const nombrenuevo = obtenerValoresInputsClaseById("nombrenuevo", id_cliente);
    const telefononuevo = obtenerValoresInputsClaseById("telefononuevo", id_cliente);
    const correonuevo = obtenerValoresInputsClaseById("correonuevo", id_cliente);
    const cargonuevo = obtenerValoresInputsClaseById("cargonuevo", id_cliente);

    const url = "../control/editar_cliente.php";
    const formaData = new FormData();
    formaData.append("id_cliente_edit", id_cliente);
    formaData.append("numeroedit", numero);
    formaData.append("entidadedit", entidad);
    formaData.append("direccionedit", direccion);
    formaData.append("distritoedit", distrito);
    formaData.append("departamentoedit", departamento);
    formaData.append("tipoclienteedit", tipocliente);
    formaData.append("pagoclienteedit", pagocliente);
    formaData.append("userclienteedit", usercliente);

    formaData.append("id_contactoedit", JSON.stringify(id_contacto));
    formaData.append("nombreedit", JSON.stringify(nombre));
    formaData.append("telefonoedit", JSON.stringify(telefono));
    formaData.append("correoedit", JSON.stringify(correo));
    formaData.append("cargoedit", JSON.stringify(cargo));

    formaData.append("nombrenuevo", JSON.stringify(nombrenuevo));
    formaData.append("telefononuevo", JSON.stringify(telefononuevo));
    formaData.append("correonuevo", JSON.stringify(correonuevo));
    formaData.append("cargonuevo", JSON.stringify(cargonuevo));

    fetch(url, {
        method: "POST",
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            resultado.innerHTML = data.data;

            getListadoClientes();
        })
        .catch(error => {
            mostrarAlerta('danger', 'Error al editar cliente');
            console.log(error);
        });
            
        $('#nuevoCli'+ id_cliente).modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();

        $('body').css('overflow', 'auto');

        removeAlert();
}

function deleteContacto(id_contacto,item,id_cliente) { 
    const resultado = document.getElementById("resultado");
    
    let cont = document.getElementById("cont"+item+id_cliente);
    
    const url = "../control/eliminarcontacto.php";
    const formaData = new FormData();
    formaData.append("id_contacto", id_contacto);

    fetch(url, {
        method: "POST",
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            resultado.innerHTML = data.data;
    
            cont.remove();

        })
        .catch(err => {
            mostrarAlerta('danger', 'Error al eliminar contacto');
        });

        removeAlert();
}

function getButtonDelete(id_contacto,item,id_cliente){
    let divButton = document.getElementById("divButton"+id_contacto);
    
    divButton.innerHTML = `
        <div class='row'>
            <button type="button" class="btn btn-success col" style="margin-left: 10px" onclick="deleteContacto(${id_contacto},${item},${id_cliente})">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                    <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                </svg>
            </button> 
            <button type="button" class="btn btn-primary col" onclick="notDeleteContact(${id_contacto},${item},${id_cliente})" style="margin-left: 10px">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                </svg>
            </button>
        <div>
    `
}

function notDeleteContact(id_contacto,item,id_cliente){
    let divButton = document.getElementById("divButton"+id_contacto);

    divButton.innerHTML = `
        
        <button type="button" class="btn btn-danger" style="margin-left: 10px" onclick="getButtonDelete(${id_contacto},${item},${id_cliente})"><i class="far fa-trash-can"></i></button>
        
    `
}

function eliminarCliente(id_cliente) {
    const resultado = document.getElementById("resultado");

    const url = "../control/eliminarcliente.php";
    const formaData = new FormData();
    formaData.append("id_cliente", id_cliente);

    fetch(url, {
        method: "POST",
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            resultado.innerHTML = data.data;
        })
        .catch(err => {
            mostrarAlerta('danger', 'Error al eliminar cliente');
        });

        getListadoClientes()
            
        $('#nuevoCli'+ id_cliente).modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();

        $('body').css('overflow', 'auto');

        removeAlert();
} 