

//Funcion que controla cuando haces click fuera de la lista de los productos, y cierra la lista
document.addEventListener('click', function (event) {

    cerrarLista(event);
    
});

function cerrarLista(event){
    let inputProds = document.querySelectorAll('[class^="prod"]');
    let listaItems = document.querySelectorAll('[class^="lista-overlayPro"]');

    let isClickInsideInput;
    let isClickInsideList;

    inputProds.forEach(inputP => {
        isClickInsideInput = inputP.contains(event.target);
    });

    listaItems.forEach(listaI => {
        isClickInsideList = listaI.contains(event.target);
    });
        
    if (!isClickInsideInput && !isClickInsideList) {
        listaItems.forEach(listaI => {
            listaI.style.display = 'none';
        });
    }
}

function addItem(){

    let contItems = document.getElementById("cont_items")
    var newItems = document.createElement('div');
    newItems.classList.add('form-row');
    
    /*items = contItems.querySelectorAll('[id^="item"]').length;

    let numeroItem = parseInt(items) + 1*/

    newItems.innerHTML = `
        <div class="form-group col-sm-12 col-md-12 col-lg-1">
            <input type="number" min="1" class="cantidad form-control" onkeyup="total_producto(this.id)" value="1" id="cantidad" name="cantidad[]" placeholder="Cant" required>
        </div>
        <div class="form-group col-sm-12 col-md-12 col-lg-3">
            <input type="text" class="prod form-control" onkeyup="getProducto(this)" id="pro" placeholder="Producto" autocomplete="off" required>
            <input type="hidden" class="idproducto" name="idproducto[]" id="idproducto">
            <div class="contenedor">
                <div class="producto_lista lista-overlayPro" id="producto_lista">
                </div>
            </div>
        </div>
        <div class="form-group col-sm-12 col-md-12 col-lg-4">
            <textarea class="descripcion form-control" id="descripcion" name="descripcion[]" rows="4" placeholder="Describa el producto" required></textarea>
        </div>
        <div class="form-group col-sm-12 col-md-12 col-lg-1">
            <input type="number" class="precio form-control" onkeyup="total_producto(this.id)" id="precio" name="precio[]" placeholder="Precio unitario" required>
        </div>
        <div class="form-group col-sm-12 col-md-12 col-lg-1">
            <input type="number" class="descuento form-control" onkeyup="total_producto(this.id)" value="0" id="descuento" name="descuento[]" placeholder="Descuento">
        </div>
        <div class="form-group col-sm-12 col-md-12 col-lg-1">
            <input type="number" class="total_producto form-control" id="total_producto" readonly placeholder="Total" required>
        </div>
        <div class="form-group col-sm-12 col-md-12 col-lg-1">
            <button type="button" class="btn btn-primary btn-block" onclick="eliminar_Item(this)" id="btnitem">
                X
            </button>
        </div>
    `;
    contItems.appendChild(newItems);

    /*document.getElementById("sig_item").value = numeroItem;
     
    let divTotal = document.createElement('div');
    divTotal.setAttribute("id", "item"+numeroItem);
    divTotal.setAttribute("class", "form-row");
    divTotal.innerHTML = nuevoItem;
    cont_items.append(divTotal);*/ 

}

function eliminar_Item(button){
    var div = button.parentNode;
    div.parentNode.removeChild(div);
    calcular_total()
    
    /*id_item = id_item.replace("btn", "");
    
    var itemRemover = document.getElementById(id_item);

    // Verifica si el elemento existe antes de intentar eliminarlo
    if (itemRemover) {
        // Obtén el padre del elemento y luego elimina el elemento hijo
        var contenedorItems = document.getElementById("cont_items");
        contenedorItems.removeChild(itemRemover);
        
        // Obtener el contenedor principal
        let contItems = document.getElementById("cont_items");
        
        items = contItems.querySelectorAll('[id^="item"]');
        
        for (let i = 0; i < items.length; i++) {
            numeroActual = items[i].id.match(/\d+/)[0];
            numeroProx = i + 1;
            
            num_item = items[i].querySelector(".num_item");
            cantidad = items[i].querySelector("#cantidad"+numeroActual);
            producto = items[i].querySelector("#pro"+numeroActual);
            idproducto = items[i].querySelector("#idproducto"+numeroActual);
            producto_lista = items[i].querySelector("#producto_lista"+numeroActual);
            descripcion = items[i].querySelector("#descripcion"+numeroActual);
            precio = items[i].querySelector("#precio"+numeroActual);
            descuento = items[i].querySelector("#descuento"+numeroActual);
            total_producto = items[i].querySelector("#total_producto"+numeroActual);
            btnitem = items[i].querySelector("#btnitem"+numeroActual);
            
            items[i].id = "item" + numeroProx;
            //num_item.innerHTML = numeroProx;
            cantidad.id = "cantidad" + numeroProx;
            producto.id = "pro" + numeroProx;
            idproducto.id = "idproducto" + numeroProx;
            producto_lista.id = "producto_lista" + numeroProx;
            descripcion.id = "descripcion" + numeroProx;
            precio.id = "precio" + numeroProx;
            descuento.id = "descuento" + numeroProx;
            total_producto.id = "total_producto" + numeroProx;
            btnitem.id = "btnitem" + numeroProx;
            
        }
        
        calcular_total()
    } else {
        console.log("El elemento no existe.");
    }*/

}

function getProducto(producto){
    
    //id_pro = id_pro.replace("pro", "");
    
    //let producto = document.getElementById("pro"+id_pro).value //Obtengo el valor escrito en el buscador
    var div = producto.parentNode;
    let producto_lista = div.querySelector('.producto_lista');
    //let producto_lista = document.getElementById("producto_lista"+id_pro) //Obtengo el contenedor donde estaran los datos de la BD

    let url = "../control/ajax_cargar_productos.php" // Archivo donde se ejecutara la consulta a la BD
    let formaData = new FormData() // Creamos un form data para poder enviar los datos
    formaData.append('producto', producto.value) //Agregamos los datos del input del buscador al Formdata
    //formaData.append('idproducto', id_pro) //Agregamos los datos del input del buscador al Formdata

    fetch(url, { // Generamos la peticion con fetch
        method: "POST",
        body: formaData
    }).then(response => response.json()) //Recibimos el JSON que viene desde el archivo PHP
    .then(data => {
        producto_lista.style.display = 'block';
        producto_lista.innerHTML = data.data
    }).catch(err => console.log(err)) //Capturamos un posible error
}

function total_producto(item){
    var divContainer = item.closest('.form-row');
    
    /*if(typeof(iditem) == 'string'){
        iditem = iditem.replace(/[^0-9]+/g, "");
    }*/
    var cantidad = divContainer.querySelector('.cantidad').value;
    var precio = divContainer.querySelector('.precio').value;
    var descuento = divContainer.querySelector('.descuento').value;
    /*let cantidad = document.getElementById("cantidad"+iditem).value
    let precio = document.getElementById("precio"+iditem).value
    let descuento = document.getElementById("descuento"+iditem).value*/
    
    let total_producto = divContainer.querySelector('.total_producto');

    let subtotal = cantidad * precio

    let porcentaje = descuento / 100

    total = subtotal - (subtotal * porcentaje)

    total_producto.value = total;

    calcular_total()
}

function mostrarProducto(producto, item){
    let moneda = document.getElementById("moneda").value;

    var divContainer = item.closest('.form-row');
    
    let inputProd = divContainer.querySelector('.prod');
    let descripcion = divContainer.querySelector('.descripcion'); //Obtengo el contenedor donde estaran los datos de la BD
    let precio = divContainer.querySelector('.precio'); //Obtengo el contenedor donde estaran los datos de la BD
    let idproducto = divContainer.querySelector('.idproducto'); //Obtengo el contenedor donde estaran los datos de la BD
    idproducto.value = producto

    var producto_lista = divContainer.querySelector('.producto_lista');
    let url = "../control/ajax_mostrar_productos.php" // Archivo donde se ejecutara la consulta a la BD
    let formaData = new FormData() // Creamos un form data para poder enviar los datos
    formaData.append('producto', producto) //Agregamos los datos del input del buscador al Formdata

    fetch(url, { // Generamos la peticion con fetch
        method: "POST",
        body: formaData
    }).then(response => response.json()) //Recibimos el JSON que viene desde el archivo PHP
    .then(data => {
        producto_lista.style.display = 'none'
        descripcion.value = data.descripcion
        inputProd.value = data.nombre
        precio.value = data.precio;
        total_producto(divContainer);
    }).catch(err => console.log(err)) //Capturamos un posible error

}

function calcular_total(){
    let td_subtotal = document.getElementById("subtotal")
    let td_igv = document.getElementById("igv")
    let td_total = document.getElementById("totalgeneral")
    let divContainer = document.getElementById("cont_items")
    let porcentaje_igv =  18 / 100

    let cantidad_item = divContainer.querySelectorAll('.cantidad');
    let precio = divContainer.querySelectorAll('.precio');
    let descuento = divContainer.querySelectorAll('.descuento');
    //let cantidad_item = cont_items.querySelectorAll('div[id*="item"]').length
    let subtotal_completo = 0
    td_subtotal.value = ''

    // Verificar si los tres NodeList tienen la misma longitud
    if (cantidad_item.length === precio.length && cantidad_item.length === descuento.length) {
        for (let i = 0; i < cantidad_item.length; i++) {
            let cantidad = cantidad_item[i].value;
            let precio_unitario = precio[i].value;
            let descuento_unitario = descuento[i].value;

            subtotal = cantidad * precio_unitario;
    
            descuento = descuento_unitario / 100;

            subtotal_und = subtotal - (subtotal * descuento);

            subtotal_completo += subtotal_und;
            // Realizar cualquier operación que necesites con los valores obtenidos
            console.log(`Elemento ${i+1}: Cantidad=${cantidad}, Precio=${precio_unitario}, Descuento=${descuento_unitario}`);
        
        }
    } else {
        console.error("Los NodeList no tienen la misma longitud.");
    }

    subtotal_completo = parseFloat(subtotal_completo.toFixed(2));

    if(Number.isNaN(subtotal_completo) == false){
        
        td_subtotal.value = subtotal_completo.toLocaleString('en-US');

        let igv = subtotal_completo * porcentaje_igv

        igv = parseFloat(igv.toFixed(2))

        td_igv.value = igv.toLocaleString('en-US');

        totalTotal = subtotal + igv;

        td_total.value = parseFloat(totalTotal.toFixed(2)).toLocaleString('en-US');
        
    }
}

function registrarProducto() {
    const resultado = document.getElementById("resultado");

    const requiredInputs = document.querySelectorAll('#formProd [required]');
    
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

    const nombre = obtenerValoresInput("nombre");
    const descripcion = obtenerValoresInput("descripcion");
    const precio = obtenerValoresInput("precio");
    const alto = obtenerValoresInput("alto");
    const ancho = obtenerValoresInput("ancho");
    const largo = obtenerValoresInput("largo");
    const peso = obtenerValoresInput("peso");

    const url = "../control/registrar_producto.php";
    const formaData = new FormData();
    formaData.append("nombre", nombre);
    formaData.append("descripcion", descripcion);
    formaData.append("precio", precio);
    formaData.append("alto", alto);
    formaData.append("ancho", ancho);
    formaData.append("largo", largo);
    formaData.append("peso", peso);

    fetch(url, {
        method: "POST",
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            resultado.innerHTML = data.data;
        })
        .then(() => {
            getListadoProductos();
                
            $('#nuevoProd').modal('hide');
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

function editarProducto(id_producto) {
    const resultado = document.getElementById("resultado");

    const nombre = obtenerValoresInput("nombre"+id_producto);
    const descripcion = obtenerValoresInput("descripcion"+id_producto);
    const precio = obtenerValoresInput("precio"+id_producto);
    const alto = obtenerValoresInput("alto"+id_producto);
    const ancho = obtenerValoresInput("ancho"+id_producto);
    const largo = obtenerValoresInput("largo"+id_producto);
    const peso = obtenerValoresInput("peso"+id_producto);

    const url = "../control/editar_producto.php";
    const formaData = new FormData();
    formaData.append("id_producto", id_producto);
    formaData.append("nombre", nombre);
    formaData.append("descripcion", descripcion);
    formaData.append("precio", precio);
    formaData.append("alto", alto);
    formaData.append("ancho", ancho);
    formaData.append("largo", largo);
    formaData.append("peso", peso);

    fetch(url, {
        method: "POST",
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            resultado.innerHTML = data.data;
        })
        .then(() => {
            getListadoProductos();
            
            $('#nuevoProd'+id_producto).modal('hide');
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

function eliminarProducto(id_producto) {
    
    const resultado = document.getElementById("resultado");

    const url = "../control/eliminarproducto.php";
    const formaData = new FormData();
    formaData.append("id_producto", id_producto);

    fetch(url, {
        method: "POST",
        body: formaData,
    })
        .then(response => response.json())
        .then(data => {
            resultado.innerHTML = data.data;
        })
        .then(() => {
            getListadoProductos();
            
            $('#nuevoProd'+id_producto).modal('hide');
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