


// Cierra Select2 al hacer clic fuera de él
$(document).on('click', function(e) {
    if (!$(e.target).closest('.select2').length) {
        $('.product-list').select2('close');
    }
});

document.addEventListener("DOMContentLoaded", function(event) {
    calcular_total();
});

document.getElementById("input_cliente").addEventListener("keyup", function(){
    getCliente()
},false)

document.getElementById("input_cliente").addEventListener("focus", function(){
    getCliente()
},false)

function tipingPago(){
    const divPago = document.getElementById("divPago");

    divPago.innerHTML = `
        <label for="">Metodo de pago</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="pago" id="pago" placeholder="Ingrese el metodo de pago" maxlength="50">
            <span class="input-group-text" id="" onclick="selectPago()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                </svg>
            </span>
        </div>
    `;
}

function selectPago(){
    const divPago = document.getElementById("divPago");

    divPago.innerHTML = `
        <label for="">Metodo de pago</label>
        <div class="input-group mb-3">
            <select name="pago" id="pago" class="form-select">
                <option >Contado</option>
                <option >Credito 30 dias</option>
                <option >Credito 45 dias</option>
                <option >Credito 60 dias</option>
                <option >Credito 90 dias</option>
                <option >Cheque 30 dias</option>
            </select>
            <span class="input-group-text" id="" onclick="tipingPago()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                </svg>
            </span>
        </div>
    `;
}

function tipingEntrega(){
    const divEntrega = document.getElementById("divEntrega");

    divEntrega.innerHTML = `
        <label for="">Tiempo de entrega</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="tiempo" id="tiempo" placeholder="Ingrese el tiempo de entrega" maxlength="50">
            <span class="input-group-text" id="" onclick="selectEntrega()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                </svg>
            </span>
        </div>
    `;
}

function selectEntrega(){
    const divEntrega = document.getElementById("divEntrega");

    divEntrega.innerHTML = `
        <label for="">Tiempo de entrega</label>
        <div class="input-group mb-3">
            <select name="tiempo" id="tiempo" class="form-select">
                <option >Stock</option>
                <option >3-5 dias utiles</option>
                <option >5-7 dias utiles</option>
                <option >7-9 dias utiles</option>
                <option >10-12 dias utiles</option>
                <option >12-15 dias utiles</option>
                <option >15-20 dias utiles</option>
            </select>
            <span class="input-group-text" id="" onclick="tipingEntrega()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                </svg>
            </span>
        </div>
    `;
}

function iniciarSelect2Prod(listaProd){
    // Inicializa Select2
    listaProd.select2({
        closeOnSelect: true
    });

    // Cargar datos de la API
    $.ajax({
        type: 'GET',
        url: '../control/ajax_cargar_productos.php',
        success: function(response) {
            $.each(response, function(indice, row) {
                listaProd.append("<option value='" + row.id_productos + "'>" + row.nombre + "</option>");
            });
            listaProd.trigger('change'); // Para actualizar Select2 con los nuevos datos
        }
    });

    // Evento de selección de una opción en Select2
    listaProd.on('select2:select', function(e) {
        mostrarProducto(e);
    });
}

function iniciarSelect2Edit(listaProd){

    // Encuentra el input asociado a este select
    var inputElement = listaProd.closest('.form-group').find('.idproducto');
    var idSeleccionado = inputElement.val();

    // Inicializa Select2
    listaProd.select2({
        closeOnSelect: true
    });

    // Cargar datos de la API
    $.ajax({
        type: 'GET',
        url: '../control/ajax_cargar_productos.php',
        success: function(response) {
            $.each(response, function(indice, row) {
                listaProd.append("<option value='" + row.id_productos + "'>" + row.nombre + "</option>");
            });
            listaProd.trigger('change'); // Para actualizar Select2 con los nuevos datos
             
            // Marcar la opción seleccionada
            listaProd.val(idSeleccionado).trigger('change');
        }
    });

    // Evento de selección de una opción en Select2
    listaProd.on('select2:select', function(e) {
        mostrarProducto(e);
    });
}

function iniciarSelect2Client(listaClient){
    // Inicializa Select2
    listaClient.select2({
        closeOnSelect: true
    });

    // Cargar datos de la API
    $.ajax({
        type: 'GET',
        url: '../control/ajax_cargar_clientes.php',
        success: function(response) {
            $.each(response, function(indice, row) {
                listaClient.append("<option value='" + row.id_clientes + "," + row.razon_social + "," + row.ruc +"'>" + row.ruc + " - " + row.razon_social +"</option>");
            });
            listaClient.trigger('change'); // Para actualizar Select2 con los nuevos datos
        }
    });

    // Evento de selección de una opción en Select2
    listaClient.on('select2:select', function(e) {
        getContacto(e);
    });
}

function reiniciarSelect2Client() {
    listaProd = $('.client-list')
    
    // Elimina todas las opciones menos la primera
    listaProd.find('option').not(':first').remove();

    // Destruye la instancia actual de Select2
    listaProd.select2('destroy');

    // Inicializa Select2 de nuevo
    iniciarSelect2Client(listaProd);
}