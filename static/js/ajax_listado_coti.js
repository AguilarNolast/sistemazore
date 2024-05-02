
getListadoCoti()

document.addEventListener('click', function (event) {
    if(document.getElementById("input_cliente") || document.getElementById('lista-overlay')){
        const input_cliente = document.getElementById("input_cliente");
        var listaOverlay = document.getElementById('lista-overlay');
        const isClickInside = input_cliente.contains(event.target) || listaOverlay.contains(event.target);

        if (!isClickInside) {
            // Ocultar la lista si se hace clic fuera de la entrada de búsqueda y la lista de resultados
            listaOverlay.style.display = 'none';
        }
    }
});

document.getElementById("campo").addEventListener("keyup", function(){
    getListadoCoti()
},false)
document.getElementById("num_registros").addEventListener("change", function(){
    getListadoCoti()
},false)

function getListadoCoti() {
    let input = document.getElementById("campo").value; // Obtengo el valor escrito en el buscador
    let num_registros = document.getElementById("num_registros").value; // Obtengo la cantidad de registro que desea mostrar
    let content = document.getElementById("contenido"); // Obtengo el contenedor donde estarán los datos de la BD
    let solesFil = document.getElementById("solesFil"); // Obtengo el contenedor donde estarán los datos de la BD
    let dolarFil = document.getElementById("dolarFil"); // Obtengo el contenedor donde estarán los datos de la BD
    let selectUser = document.getElementById("selectUser"); // Obtengo el contenedor donde estarán los datos de la BD
    let pagina = document.getElementById("pagina").value; // Obtengo el numero de pagina
    let orderCol = document.getElementById("orderCol").value; // Obtengo el numero de pagina
    let orderType = document.getElementById("orderType").value; // Obtengo el numero de pagina
    const progressContainer = document.getElementById("progressContainer");//Contenedor de la barra de progreso
    
    if (pagina == null) {
        pagina = 1;
    }
    
    let url = "../control/ajax_listar_coti.php"; // Archivo donde se ejecutará la consulta a la BD
    let formData = new FormData(); // Creamos un FormData para poder enviar los datos
    formData.append('campo', input); // Agregamos los datos del input del buscador al FormData
    formData.append('registros', num_registros); // Agregamos la cantidad de registros al FormData
    formData.append('pagina', pagina); 
    formData.append('orderCol', orderCol); 
    formData.append('orderType', orderType);

    fetch(url, { // Generamos la petición con fetch
        method: "POST",
        body: formData
    })
    .then(response => {
        // Almacenar el cuerpo de la respuesta en una variable
        return response.clone().text().then(text => {
            return { response, text };
        });
    })
    .then(({ response, text }) => {
        progressContainer.innerHTML = `
            <div class="progress" role="progressbar" aria-label="Animated striped example" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress-bar"></div>
            </div>
        `;
        // Actualizar la barra de progreso durante la carga
        const totalBytes = parseInt(response.headers.get('Content-Length'));
        let loadedBytes = 0;
    
        const reader = response.body.getReader();
    
        function read() {
            reader.read().then(({ done, value }) => {
                if (done) {
                    // Ocultar la barra de progreso cuando la carga haya finalizado
                    progressContainer.style.display = "none";
                    return;
                }
    
                loadedBytes += value.byteLength;
                const progress = Math.round((loadedBytes / totalBytes) * 100);
                updateProgressBar(progress);
    
                // Continuar leyendo el cuerpo de la respuesta
                read();
            });
        }
    
        read();

        // Parsear el cuerpo de la respuesta y procesar los datos
        const data = JSON.parse(text); // Recibimos el JSON que viene desde el archivo PHP
        content.innerHTML = data.data;
        
        if(selectUser != null){
            selectUser.innerHTML = data.optionList;
            solesFil.value = (parseFloat(data.soles)).toFixed(2);
            dolarFil.value = (parseFloat(data.dolares)).toFixed(2);
        }
        document.getElementById("lbl-total").innerHTML = `Mostrando ${data.totalFiltro} de ${data.totalRegistros} registros`;
        document.getElementById("nav-paginacion").innerHTML = data.paginacion;
        
        let botonesGetPDF = document.querySelectorAll('[class^="getpdf"]');

        botonesGetPDF.forEach(btn => {
            btn.addEventListener('click', function() {
                // Llamada a la función getDataPDF y pasando el id del botón y generatePdfCoti como parámetros
                getDataPDF(btn.id, generatePdfCoti);
            });
        });

        let btnPdfFicha = document.querySelectorAll('[class^="btnPdfFicha"]');

        btnPdfFicha.forEach(btn => {
            btn.addEventListener('click', function() {
                generarPdfFicha(btn.id, generarFichaTec);
            });
        });

        let btnCrearFicha = document.querySelectorAll('[class^="btnCrearFicha"]');

        btnCrearFicha.forEach(btn => {
            btn.addEventListener('click', function() {
                generarpdf(btn.id, generarFichaTec);
            });
        });
    })
    .catch(err => {
        console.error(err);
        // Aquí podrías mostrar un mensaje de error al usuario o realizar alguna acción específica.
    });

    // Función para actualizar la barra de progreso
    const updateProgressBar = (progress) => {
        const progressBar = document.getElementById("progress-bar");//Barra de progreso
        progressBar.style.width = `${progress}%`;
        progressBar.setAttribute("aria-valuenow", progress);
        progressBar.textContent = `${progress}%`;
    };
}

function nextPage(pagina){
    document.getElementById('pagina').value = pagina
    getListadoCoti()
}

let columns = document.getElementsByClassName("sort")
let tamanio = columns.length

for (let i = 0; i< tamanio; i++){
    columns[i].addEventListener("click", ordenar)
}

function ordenar(e){

    let elemento = e.target

    document.getElementById('orderCol').value = elemento.cellIndex

    if(elemento.classList.contains("asc")){
        document.getElementById("orderType").value = "asc"
        elemento.classList.remove("asc")
        elemento.classList.add("desc")
    }else{
        document.getElementById("orderType").value = "desc"
        elemento.classList.remove("desc")
        elemento.classList.add("asc")
    }

    getListadoCoti();
}