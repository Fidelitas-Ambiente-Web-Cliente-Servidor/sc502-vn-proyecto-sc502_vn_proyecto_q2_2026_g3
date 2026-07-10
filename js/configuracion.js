// ===================================================
// VARIABLES
// ===================================================

const campoNuevaContrasena = document.getElementById("nuevaContrasena");

const campoConfirmarContrasena = document.getElementById("confirmarContrasena");

const barraSeguridad = document.getElementById("barraSeguridad");

const textoSeguridad = document.getElementById("textoSeguridad");

const formularioSeguridad = document.getElementById("formularioSeguridad");

const formularioUsuarios = document.getElementById("formularioUsuarios");

const formularioHospedaje = document.getElementById("formularioHospedaje");

// ===================================================
// EVENTOS
// ===================================================

campoNuevaContrasena.addEventListener(
    "input",
    actualizarNivelSeguridad
);

formularioSeguridad.addEventListener(
    "submit",
    guardarSeguridad
);

formularioUsuarios.addEventListener(
    "submit",
    guardarUsuarios
);

formularioHospedaje.addEventListener(
    "submit",
    guardarHospedaje
);

// ===================================================
// MEDIDOR DE SEGURIDAD
// ===================================================

function actualizarNivelSeguridad() {

    const contrasena = campoNuevaContrasena.value;

    let nivelSeguridad = 0;

    if (contrasena.length >= 8) {

        nivelSeguridad += 25;

    }

    if (/[A-Z]/.test(contrasena)) {

        nivelSeguridad += 25;

    }

    if (/[0-9]/.test(contrasena)) {

        nivelSeguridad += 25;

    }

    if (/[^A-Za-z0-9]/.test(contrasena)) {

        nivelSeguridad += 25;

    }

    barraSeguridad.style.width = nivelSeguridad + "%";

    if (nivelSeguridad <= 25) {

        barraSeguridad.className = "progress-bar bg-danger";

        textoSeguridad.textContent = "Contraseña débil";

    }

    else if (nivelSeguridad <= 50) {

        barraSeguridad.className = "progress-bar bg-warning";

        textoSeguridad.textContent = "Contraseña media";

    }

    else if (nivelSeguridad <= 75) {

        barraSeguridad.className = "progress-bar bg-info";

        textoSeguridad.textContent = "Contraseña buena";

    }

    else {

        barraSeguridad.className = "progress-bar bg-success";

        textoSeguridad.textContent = "Contraseña muy segura";

    }

}

// ===================================================
// FORMULARIO SEGURIDAD
// ===================================================

function guardarSeguridad(evento) {

    evento.preventDefault();

    if (
        campoNuevaContrasena.value !==
        campoConfirmarContrasena.value
    ) {

        alert("Las contraseñas no coinciden.");

        campoConfirmarContrasena.focus();

        return;

    }

    alert("Contraseña actualizada correctamente (simulación).");

    formularioSeguridad.reset();

    barraSeguridad.style.width = "0%";

    textoSeguridad.textContent =
        "Escriba una contraseña segura.";

}

// ===================================================
// FORMULARIO USUARIOS
// ===================================================

function guardarUsuarios(evento) {

    evento.preventDefault();

    alert("Información del usuario guardada correctamente.");

}

// ===================================================
// FORMULARIO HOSPEDAJE
// ===================================================

function guardarHospedaje(evento) {

    evento.preventDefault();

    alert("Información del hospedaje guardada correctamente.");

}