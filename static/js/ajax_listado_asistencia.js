
getListadoAsistencia()

document.getElementById("campo").addEventListener("keyup", function(){
    getListadoAsistencia()
},false)
document.getElementById("num_registros").addEventListener("change", function(){
    getListadoAsistencia()
},false)


function getListadoAsistencia() {
    let input = document.getElementById("campo").value; // Obtengo el valor escrito en el buscador
    let num_registros = document.getElementById("num_registros").value; // Obtengo la cantidad de registro que desea mostrar
    let content = document.getElementById("contenido"); // Obtengo el contenedor donde estarán los datos de la BD
    let selectUser = document.getElementById("selectUser"); // Obtengo el contenedor donde estarán los datos de la BD
    let pagina = document.getElementById("pagina").value; // Obtengo el numero de pagina
    let orderCol = document.getElementById("orderCol").value; 
    let orderType = document.getElementById("orderType").value; 
    
    if (pagina == null) {
        pagina = 1;
    }
    
    let url = "../control/ajax_listar_asistencia.php"; // Archivo donde se ejecutará la consulta a la BD
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
        if(selectUser != null){
            selectUser.innerHTML = data.optionList;
        }
        document.getElementById("lbl-total").innerHTML = `Mostrando ${data.totalFiltro} de ${data.totalRegistros} registros`;
        document.getElementById("nav-paginacion").innerHTML = data.paginacion;
    })
    .catch(err => {
        mostrarAlerta('danger', "Error al cargar listado");
    });
}


function nextPage(pagina){
    document.getElementById('pagina').value = pagina
    getListadoAsistencia()
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

    getListadoAsistencia();
}