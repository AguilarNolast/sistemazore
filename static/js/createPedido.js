document.addEventListener('DOMContentLoaded', function() {
    
    let btnEnviarPedido = document.querySelector('[class^="btnEnviarPedido"]');
    btnEnviarPedido.addEventListener('click', function() {
        registrarPedido(btnEnviarPedido.id, generatePdfCoti);
      });

    $('#archivos').change(function () {
        var files = $(this)[0].files;
        var filesList = '';
        
        for (var i = 0; i < files.length; i++) {
            filesList += '<li>' + files[i].name + '</li>';
        }

        // Actualiza la lista de archivos
        $('#listaArchivos').html(filesList);
    });

});