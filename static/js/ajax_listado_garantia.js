
getListadoGarantia()

document.getElementById("campo").addEventListener("keyup", function(){
    getListadoGarantia()
},false)
document.getElementById("num_registros").addEventListener("change", function(){
    getListadoGarantia()
},false)


function getListadoGarantia() {
    let input = document.getElementById("campo").value; // Obtengo el valor escrito en el buscador
    let num_registros = document.getElementById("num_registros").value; // Obtengo la cantidad de registro que desea mostrar
    let content = document.getElementById("contenido"); // Obtengo el contenedor donde estarán los datos de la BD
    let pagina = document.getElementById("pagina").value; // Obtengo el numero de pagina
    let orderCol = document.getElementById("orderCol").value; // Obtengo el numero de pagina
    let orderType = document.getElementById("orderType").value; // Obtengo el numero de pagina
    
    if (pagina == null) {
        pagina = 1;
    }
    
    let url = "../control/ajax_listar_garantia.php"; // Archivo donde se ejecutará la consulta a la BD
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
    .then(response => response.json()) // Recibimos el JSON que viene desde el archivo PHP
    .then(data => {
        content.innerHTML = data.data;
        document.getElementById("lbl-total").innerHTML = `Mostrando ${data.totalFiltro} de ${data.totalRegistros} registros`;
        document.getElementById("nav-paginacion").innerHTML = data.paginacion;
    })
    .catch(err => {
        console.error(err);
        // Aquí podrías mostrar un mensaje de error al usuario o realizar alguna acción específica.
    });
}


function nextPage(pagina){
    document.getElementById('pagina').value = pagina
    getListadoGarantia()
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

    getListadoGarantia();
}