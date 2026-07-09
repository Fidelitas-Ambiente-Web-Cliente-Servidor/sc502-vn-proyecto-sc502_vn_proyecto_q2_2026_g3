// ============================================
// VARIABLES GLOBALES
// ============================================

const mensajeBienvenida = document.getElementById("mensajeBienvenida");

const formularioLogin = document.getElementById("formularioLogin");

const campoContrasena = document.getElementById("contrasena");

const botonMostrarContrasena = document.getElementById("botonMostrarContrasena");

const barraSeguridad = document.getElementById("barraSeguridad");

const textoSeguridad = document.getElementById("textoSeguridad");

// Modal Bootstrap
const modalDosPasos = new bootstrap.Modal(
    document.getElementById("modalDosPasos")
);

const campoCodigoDosPasos = document.getElementById("codigoDosPasos");

const botonVerificarCodigo = document.getElementById("botonVerificarCodigo");

let codigoGeneradoDosPasos = "";

let cantidadIntentos = 0;

// ============================================
// INICIALIZACIÓN
// ============================================

mostrarSaludo();

// ============================================
// EVENTOS
// ============================================

botonMostrarContrasena.addEventListener(
    "click",
    mostrarOcultarContrasena
);

campoContrasena.addEventListener(
    "input",
    actualizarNivelSeguridad
);

formularioLogin.addEventListener(
    "submit",
    validarInicioSesion
);

botonVerificarCodigo.addEventListener(
    "click",
    verificarCodigoDosPasos
);

// ============================================
// FUNCIONES
// ============================================

function mostrarSaludo() {

    const horaActual = new Date().getHours();

    if (horaActual < 12) {

        mensajeBienvenida.textContent =
            "Buenos días. Bienvenido nuevamente.";

    } else if (horaActual < 18) {

        mensajeBienvenida.textContent =
            "Buenas tardes. Bienvenido nuevamente.";

    } else {

        mensajeBienvenida.textContent =
            "Buenas noches. Bienvenido nuevamente.";

    }

}

// ============================================

function mostrarOcultarContrasena() {

    if (campoContrasena.type === "password") {

        campoContrasena.type = "text";

        botonMostrarContrasena.innerHTML =
            '<i class="bi bi-eye-slash"></i>';

    } else {

        campoContrasena.type = "password";

        botonMostrarContrasena.innerHTML =
            '<i class="bi bi-eye"></i>';

    }

}

// ============================================

function actualizarNivelSeguridad() {

    const contrasena = campoContrasena.value;

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

        barraSeguridad.className =
            "progress-bar bg-danger";

        textoSeguridad.textContent =
            "Contraseña débil";

    } else if (nivelSeguridad <= 50) {

        barraSeguridad.className =
            "progress-bar bg-warning";

        textoSeguridad.textContent =
            "Contraseña media";

    } else if (nivelSeguridad <= 75) {

        barraSeguridad.className =
            "progress-bar bg-info";

        textoSeguridad.textContent =
            "Contraseña buena";

    } else {

        barraSeguridad.className =
            "progress-bar bg-success";

        textoSeguridad.textContent =
            "Contraseña muy segura";

    }

}

// ============================================

function validarInicioSesion(evento) {

    evento.preventDefault();

    cantidadIntentos++;

    if (cantidadIntentos >= 3) {

        alert(
            "Ha superado la cantidad máxima de intentos. Espere 30 segundos."
        );

        const botonIngresar =
            formularioLogin.querySelector("button[type='submit']");

        botonIngresar.disabled = true;

        setTimeout(function () {

            cantidadIntentos = 0;

            botonIngresar.disabled = false;

        }, 30000);

        return;

    }

    codigoGeneradoDosPasos = generarCodigoDosPasos();

    console.clear();

    console.log("====================================");
    console.log("SIMULADOR DE AUTENTICACIÓN 2FA");
    console.log("Código generado:");
    console.log(codigoGeneradoDosPasos);
    console.log("====================================");

    campoCodigoDosPasos.value = "";

    modalDosPasos.show();

}

// ============================================

function verificarCodigoDosPasos() {

    if (
        campoCodigoDosPasos.value ===
        codigoGeneradoDosPasos.toString()
    ) {

        modalDosPasos.hide();

        alert("Inicio de sesión exitoso.");

        window.location.href = "panelControl.php";

    } else {

        alert("El código es incorrecto.");

        campoCodigoDosPasos.focus();

    }

}

// ============================================

function generarCodigoDosPasos() {

    return Math.floor(
        100000 + Math.random() * 900000
    );

}