<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formulario Dinámico con Montos</title>
<style>
    .form-group {
        margin-bottom: 10px;
    }
</style>
</head>
<body>
<h1>Formulario Dinámico con Montos</h1>

<form id="myForm">
    <div id="formFields">
        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre[]" required>
            <label>Apellido:</label>
            <input type="text" name="apellido[]" required>
            <label>Ciudad:</label>
            <input type="text" name="ciudad[]" required>
            <label>Monto:</label>
            <input type="number" name="monto[]" onkeyup="sumarMontos()" onchange="sumarMontos()" required>
            <button type="button" onclick="removeField(this)">Eliminar</button>
        </div>
    </div>
    <button type="button" onclick="addField()">Agregar más campos</button>
    <button type="submit">Enviar</button>
</form>

<label>Total Monto: <span id="totalMonto">0</span></label>

<script>
function addField() {
    var formFields = document.getElementById('formFields');
    var newField = document.createElement('div');
    newField.classList.add('form-group');
    newField.innerHTML = `
        <label>Nombre:</label>
        <input type="text" name="nombre[]" required>
        <label>Apellido:</label>
        <input type="text" name="apellido[]" required>
        <label>Ciudad:</label>
        <input type="text" name="ciudad[]" required>
        <label>Monto:</label>
        <input type="number" name="monto[]" onchange="sumarMontos()" required>
        <button type="button" onclick="removeField(this)">Eliminar</button>
    `;
    formFields.appendChild(newField);
}

function removeField(button) {
    var div = button.parentNode;
    div.parentNode.removeChild(div);
    sumarMontos();
}

function sumarMontos() {
    var montos = document.getElementsByName('monto[]');
    var total = 0;
    for (var i = 0; i < montos.length; i++) {
        total += parseFloat(montos[i].value) || 0;
    }
    document.getElementById('totalMonto').textContent = total.toFixed(2);
}

document.getElementById('myForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    var data = {};
    for (var pair of formData.entries()) {
        if (!data[pair[0]]) {
            data[pair[0]] = [];
        }
        data[pair[0]].push(pair[1]);
    }
    console.log(data); // Aquí puedes enviar los datos a otra página o hacer cualquier otra cosa
});
</script>
</body>
</html>
