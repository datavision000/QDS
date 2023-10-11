const chkIdioma = document.querySelector("#btn-idioma");

let idioma = "español";
let idiomaSeleccionado = localStorage.getItem("idioma");
chkIdioma.checked = idiomaSeleccionado === "ingles";

console.log(location.pathname)

if (idiomaSeleccionado) {
  cargarTextos(idiomaSeleccionado);
}

chkIdioma.addEventListener("click", () => {
  if (chkIdioma.checked) {
    idioma = "ingles";
  } else {
    idioma = "español";
  }

  localStorage.setItem("idioma", idioma);
  cargarTextos(idioma);
});

function cargarTextos(lang) {
  const url = "vistas/js/json/" + lang + ".json";
  const request = new XMLHttpRequest();
  request.open("GET", url, true);
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      const data = JSON.parse(request.responseText);
      actualizarTextos(data);
    } else if (request.readyState === 4 && request.status !== 200) {
      console.error("Error al cargar el archivo JSON");
    }
  };
  request.send();
}

function actualizarTextos(data) {
  document.querySelector(".aop1").textContent = data.aop1;
  document.querySelector(".aop3").textContent = data.aop3;
  document.querySelector(".aop4").textContent = data.aop4;

  document.querySelector("#sub-rastreo").textContent = data.sub_rastreo;
  document.querySelector("#p-rastreo").textContent = data.p_rastreo;
  document.querySelector("#submit-rastreo").value = data.submit_rastreo;

  document.querySelector("#h2-contacto").textContent = data.h2_contacto;
  document.querySelector("#h2-msg").textContent = data.h2_msg;
  document.querySelector("#msg-nombre").placeholder = data.msg_nombre;
  document.querySelector("#msg-mail").placeholder = data.msg_mail;
  document.querySelector("#txt-mensaje-footer").placeholder = data.msg;
  document.querySelector("#msg-submit").value = data.msg_submit;
}

window.addEventListener("DOMContentLoaded", () => {
  if (!idiomaSeleccionado) {
    // Si no hay idioma seleccionado en localStorage, se carga el idioma por defecto (español)
    cargarTextos("español");
  }
});