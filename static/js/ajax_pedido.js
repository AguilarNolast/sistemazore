//Funcion que registra un pedido especifico de una coti, carga la cotizacion y la envia por correo
function registrarPedido(id_coti, generatePdfCoti){

    const resultado = document.getElementById("resultado");

    var checkboxes = document.querySelectorAll('.form-check-input');

    var itemsMarcados = Array.from(checkboxes)
        .filter(function (checkbox) {
            return checkbox.checked;
        })
        .map(function (checkbox) {
            return checkbox.value;
        });

    // Verificar si al menos uno está marcado
    if (itemsMarcados.length === 0) {
        return mostrarAlerta('danger', 'Debes marcar al menos un producto');
    }

    arrayDeValores = Object.values(itemsMarcados);

    // Deshabilitar el botón de enviar
    document.querySelector('[class^="btnEnviarPedido"]').disabled = true;

    const url = "../control/getcotipedido.php";
    const formaData = new FormData();
    formaData.append("id_coti", id_coti);
    formaData.append("itemsMarcados", itemsMarcados);

    let arrayCoti = [];
    let arrayCont = [];
    let arrayProd = [];
    let arrayUser = [];

    fetch(url, {
        method: "POST",
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            arrayCoti = data.resCoti;
            arrayCont = data.resCont;
            arrayProd = data.resProd;
            arrayUser = data.resUser;

            documentDefinition = generatePdfCoti(arrayCoti,arrayCont,arrayProd,arrayUser);

            pdfMake.createPdf(documentDefinition).getBlob(function (blob) {
                const formData = new FormData(document.getElementById('formCoti'));
                formData.append('pdf', blob, 'coti_pedido.pdf');
                formData.append('correlativo', arrayCoti['correlativo']);
    
                if(formData.get('archivos[]').size == 0){
                    formData.delete('archivos[]');
                }
    
                /*const formData = new FormData();
    
                formData.append('pdf', blob, 'coti_pedido.pdf');
                formData.append('correlativo', arrayCoti['correlativo']);
    
                document.getElementById('formCoti').elements.forEach(item => {
                    if(item.tagName == 'INPUT'){
                        const value = item.value;
                        const name = item.name;
    
                        if(name === 'archivos[]') {
                            
                        console.log(name,value);
                            if(value.size > 0){
                                formData.append(name, value, value.name);
                                
                            }
                            return;
                        }
                        formData.append(name, value);
                        
                    }
                })*/
    
                // Utilizar fetch para enviar el PDF al servidor y adjuntarlo al correo
                fetch(document.getElementById('formCoti').action, {
                    method: document.getElementById('formCoti').method,
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    mostrarAlerta(data.tipo, data.mensaje);
    
                    if(data.redir == true){
                        setTimeout(function() {
                            window.location.href = "../vistas/listacotizacion.php";
                        }, 3000);
                    }else{
                        // Volver a habilitar el botón de enviar después de la consulta
                        document.getElementById('btnEnviar').disabled = false;
                    }
                })
                .catch(error => {
                    mostrarAlerta('danger', "Error al registrar pedido");
                });
            });
        })
        .catch(err => {
            mostrarAlerta('danger', "Error al registrar pedido");

            // Volver a habilitar el botón de enviar después de la consulta
            document.getElementById('btnEnviar').disabled = false;
        });

}

//Obtiene los datos de la cotizacion de un pedido especifico para luego llamar al script que genera la coti y mostrarla en pantalla
function pdfCotiPedido(arrayId, generatePdfCoti){
    
    arrayId = arrayId.split(',');

    let arrayIdKeys = {
        id_pedidos: arrayId[0],
        id_coti: arrayId[1]
    };
    
    const resultado = document.getElementById("resultado");

    const url = "../control/getpdfcotipedido.php";
    const formaData = new FormData();
    formaData.append("id_coti", arrayIdKeys['id_coti']);
    formaData.append("id_pedido", arrayIdKeys['id_pedidos']);

    let arrayCoti = [];
    let arrayCont = [];
    let arrayProd = [];
    let arrayUser = [];

    fetch(url, {
        method: "POST",
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            arrayCoti = data.resCoti;
            arrayCont = data.resCont;
            arrayProd = data.resProd;
            arrayUser = data.resUser;

            documentDefinition = generatePdfCoti(arrayCoti,arrayCont,arrayProd,arrayUser);
    
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
            mostrarAlerta('danger', "Error al generar PDF");
        });

}

//Obtiene los datos de la cotizacion de un pedido especifico para luego llamar al script que genera la coti, adjuntarla en un correo y enviarlo mientras que anula el pedido
function anularPedido(arrayId, generatePdfCoti){

    arrayId = arrayId.split(',');

    let arrayIdKeys = {
        id_pedidos: arrayId[0],
        id_coti: arrayId[1]
    };
    
    const resultado = document.getElementById("resultado");

    const url = "../control/getpdfcotipedido.php";
    const formaData = new FormData();
    formaData.append("id_coti", arrayIdKeys['id_coti']);
    formaData.append("id_pedido", arrayIdKeys['id_pedidos']);

    let arrayCoti = [];
    let arrayCont = [];
    let arrayProd = [];
    let arrayUser = [];

    fetch(url, {
        method: "POST",
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            arrayCoti = data.resCoti;
            arrayCont = data.resCont;
            arrayProd = data.resProd;
            arrayUser = data.resUser;
            
            documentDefinition = generatePdfCoti(arrayCoti,arrayCont,arrayProd,arrayUser);
            
            pdfMake.createPdf(documentDefinition).getBlob(function (blob) {
                const formData = new FormData(document.getElementById('formanular'+arrayIdKeys['id_pedidos']));
                formData.append('pdf', blob, 'coti_pedido.pdf');
                formData.append('correlativo', arrayCoti['correlativo']);
                formData.append('nombrecliente', arrayCoti['razon_social']);
                formData.append('id_pedido', arrayIdKeys['id_pedidos']);
    
                // Utilizar fetch para enviar el PDF al servidor y adjuntarlo al correo
                fetch(document.getElementById('formanular'+arrayIdKeys['id_pedidos']).action, {
                    method: document.getElementById('formanular'+arrayIdKeys['id_pedidos']).method,
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    mostrarAlerta(data.tipo, data.mensaje);
    
                    if(data.redir == true){
                        setTimeout(function() {
                            window.location.href = "../vistas/listapedidos.php";
                        }, 3000);
                    }else{
                        // Volver a habilitar el botón de enviar después de la consulta
                        document.getElementById('btnAnular'+arrayIdKeys['id_pedidos']).disabled = false;
                    }
                })
                .catch(error => {
                    mostrarAlerta('danger', 'Error al anular pedido');
                });
            });
            
        })
        .catch(error => {
            mostrarAlerta('danger', 'Error al anular pedido');
        });

}

function filtrarPedido() {
    let dateIn = new Date(document.getElementById("dateIn").value);//Fecha de inicio para el filtro
    let dateOut = new Date(document.getElementById("dateOut").value);//Fecha final para el filtro
    let num_registros = document.getElementById("num_registros").value; // Obtengo la cantidad de registro que desea mostrar
    let content = document.getElementById("contenido"); // Obtengo el contenedor donde estarán los datos de la BD
    let solesFil = document.getElementById("solesFil"); // Obtengo el contenedor donde estarán los datos de la BD
    let dolarFil = document.getElementById("dolarFil"); // Obtengo el contenedor donde estarán los datos de la BD
    let pagina = document.getElementById("pagina").value; // Obtengo el numero de pagina
    let orderCol = document.getElementById("orderCol").value; // Obtengo el numero de pagina
    let orderType = document.getElementById("orderType").value; // Obtengo el numero de pagina

    if(isNaN(Date.parse(dateIn)) || isNaN(Date.parse(dateOut))){
        mostrarAlerta('danger', 'Seleccione una fecha valida');
        
        removeAlert();

        return;
    }

    if (dateIn >= dateOut) {
        mostrarAlerta('danger', 'Debe seleccionar una fecha de inicio menor a la final');
        
        removeAlert();

        return;
    }
    
    if (pagina == null) {
        pagina = 1;
    }

    const formFilterPedido = document.getElementById('formFilterPedido');

    const formData = new FormData(formFilterPedido);
    
    formData.append('registros', num_registros); // Agregamos la cantidad de registros al FormData
    formData.append('pagina', pagina); 
    formData.append('orderCol', orderCol); 
    formData.append('orderType', orderType);

    fetch(formFilterPedido.action, { // Generamos la petición con fetch
        method: formFilterPedido.method,
        body: formData
    })
    .then(response => response.json()) // Recibimos el JSON que viene desde el archivo PHP
    .then(data => {
        solesFil.value = (parseFloat(data.soles)).toFixed(2);
        dolarFil.value = (parseFloat(data.dolares)).toFixed(2);
        content.innerHTML = data.data;
        document.getElementById("lbl-total").innerHTML = `Mostrando ${data.totalFiltro} de ${data.totalRegistros} registros`;
        document.getElementById("nav-paginacion").innerHTML = data.paginacion;
        
        // Tu código JavaScript que selecciona los botones
        let btnAnularPedidos = document.querySelectorAll('[class^="anularpedido"]');

        btnAnularPedidos.forEach(boton => {
            boton.addEventListener('click', function() {
                anularPedido(boton.id, generatePdfCoti);
            });
        });
        
        let btnpdfpedido = document.querySelectorAll('[class^="btnpdfpedido"]');

        btnpdfpedido.forEach(boton => {
            boton.addEventListener('click', function() {
                pdfCotiPedido(boton.id, generatePdfCoti);
            });
        });
    })
    .catch(err => {
        mostrarAlerta('danger', "Se ha producido un error" + err);
    });
}

function nextPageFilterPedido(pagina){
    document.getElementById('pagina').value = pagina
    filtrarPedido()
}

function eliminarFiltroPedidos(){
    const formFilterPedido = document.getElementById('formFilterPedido');
    formFilterPedido.reset();
    getListadoPedidos();
}