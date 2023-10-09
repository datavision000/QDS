var filas = document.querySelectorAll(".fila-opcion");
var btnLimpiar = document.querySelector(".btn-limpiar");

    // Agregar un evento de clic a cada fila
    filas.forEach(function (fila) {
        fila.addEventListener("click", function () {
            // Toggle (alternar) la clase "fila-seleccionada" en la fila actual
            fila.classList.toggle("fila-seleccionada");
            fila.classList.toggle("fila-opcion");
        });
    });

    btnLimpiar.addEventListener("click", function () {
        filas.forEach(function (fila) {
            if(fila.classList.contains("fila-seleccionada")){
                fila.classList.toggle("fila-seleccionada");
                fila.classList.toggle("fila-opcion");
            }
        });
    });

/***************   OBTENER DATOS DE LA TABLA   ********************/

let botonEnvio = document.getElementById('submit-as-lote-2');
botonEnvio.addEventListener('click', enviarDatos);
let todo = [];
function enviarDatos() {
    // Obtener los valores ingresados en los campos de entrada
    var tabla = document.getElementById("tabla-lote");
        for(let i = 0; i < tabla.rows.length; i++){

        let contenido = [];
        var fila = tabla.rows[i];
        

        // Verifica si la fila está seleccionada y obtiene sus datos
        if(fila.classList.contains("fila-seleccionada")){
            //console.log(fila);
            
            for(let j = 0; j < fila.cells.length; j++){
                let celda = fila.cells[j];
                contenido[j] = celda.textContent;
            }
            console.log(contenido);
            todo.push(contenido);
        }
        

    }
    var jsonString = JSON.stringify(todo);

    // Crear un objeto FormData para enviar datos al servidor
    var formData = new FormData();
    formData.append('todo', jsonString);

    // Realizar la solicitud AJAX usando XMLHttpRequest
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../controladores/api/paquete/eliminarDato.php', true);
    xhr.send(formData);
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Manejar la respuesta del servidor si es necesario
            let respuesta = xhr.responseText;
            location.href = "op-paquetes.php?datos=" + respuesta;

        }
    };

  }