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

async function guardarSeguridad(evento) {

    evento.preventDefault();

    if (
        campoNuevaContrasena.value !==
        campoConfirmarContrasena.value
    ) {

        alert("Las contraseñas no coinciden.");

        campoConfirmarContrasena.focus();

        return;

    }

    const datosFormulario = new FormData();
    datosFormulario.append("action", "cambiarPassword");
    datosFormulario.append("contrasena_actual", document.getElementById("contrasenaActual").value);
    datosFormulario.append("nueva_contrasena", campoNuevaContrasena.value);
    datosFormulario.append("confirmar_contrasena", campoConfirmarContrasena.value);

    try {

        const respuesta = await fetch("api/configuracion.php", {
            method: "POST",
            body: datosFormulario
        });

        const resultado = await respuesta.json();

        if (resultado.response === "00") {

            alert("Contraseña actualizada correctamente.");

            formularioSeguridad.reset();

            barraSeguridad.style.width = "0%";

            textoSeguridad.textContent = "Escriba una contraseña segura.";

        } else {

            alert(resultado.message || "No se pudo actualizar la contraseña.");

        }

    } catch (error) {

        alert("No se pudo conectar con el servidor.");

    }

}

// ===================================================
// FORMULARIO USUARIOS
// ===================================================

async function guardarUsuarios(evento) {

    evento.preventDefault();

    const datosFormulario = new FormData();
    datosFormulario.append("action", "crearUsuario");
    datosFormulario.append("nombre", document.getElementById("usuarioNombre").value);
    datosFormulario.append("email", document.getElementById("usuarioEmail").value);
    datosFormulario.append("password", document.getElementById("usuarioPassword").value);
    datosFormulario.append("rol", document.getElementById("usuarioRol").value.toLowerCase());
    datosFormulario.append("estado", document.getElementById("usuarioEstado").value.toLowerCase());

    try {

        const respuesta = await fetch("api/configuracion.php", {
            method: "POST",
            body: datosFormulario
        });

        const resultado = await respuesta.json();

        if (resultado.response === "00") {

            alert("Usuario creado correctamente.");

            formularioUsuarios.reset();

        } else {

            alert(resultado.message || "No se pudo crear el usuario.");

        }

    } catch (error) {

        alert("No se pudo conectar con el servidor.");

    }

}

// ===================================================
// FORMULARIO HOSPEDAJE
// ===================================================

async function guardarHospedaje(evento) {

    evento.preventDefault();

    const datosFormulario = new FormData();
    datosFormulario.append("action", "actualizarHospedaje");
    datosFormulario.append("nombre", document.getElementById("hospedajeNombre").value);
    datosFormulario.append("provincia", document.getElementById("hospedajeProvincia").value);
    datosFormulario.append("direccion", document.getElementById("hospedajeDireccion").value);
    datosFormulario.append("telefono", document.getElementById("hospedajeTelefono").value);
    datosFormulario.append("email", document.getElementById("hospedajeEmail").value);
    datosFormulario.append("hora_entrada", document.getElementById("hospedajeHoraEntrada").value);
    datosFormulario.append("hora_salida", document.getElementById("hospedajeHoraSalida").value);

    try {

        const respuesta = await fetch("api/configuracion.php", {
            method: "POST",
            body: datosFormulario
        });

        const resultado = await respuesta.json();

        if (resultado.response === "00") {

            alert("Información del hospedaje guardada correctamente.");

        } else {

            alert(resultado.message || "No se pudo guardar la información.");

        }

    } catch (error) {

        alert("No se pudo conectar con el servidor.");

    }

}

// ===================================================
// CARGAR DATOS ACTUALES DEL HOSPEDAJE
// ===================================================

async function cargarHospedaje() {

    try {

        const respuesta = await fetch("api/configuracion.php?action=obtenerHospedaje");

        const resultado = await respuesta.json();

        if (resultado.response === "00" && resultado.data) {

            const datos = resultado.data;

            document.getElementById("hospedajeNombre").value = datos.nombre || "";
            document.getElementById("hospedajeProvincia").value = datos.provincia || "San José";
            document.getElementById("hospedajeDireccion").value = datos.direccion || "";
            document.getElementById("hospedajeTelefono").value = datos.telefono || "";
            document.getElementById("hospedajeEmail").value = datos.email || "";
            document.getElementById("hospedajeHoraEntrada").value = datos.hora_entrada || "";
            document.getElementById("hospedajeHoraSalida").value = datos.hora_salida || "";

        }

    } catch (error) {

        console.error("No se pudo cargar la información del hospedaje.");

    }

}

cargarHospedaje();