document.addEventListener('DOMContentLoaded', function () {

    const API_URL = 'api/cabinas.php';

    const formulario = document.getElementById('form-cabina');
    const inputId = document.getElementById('cabina-id');
    const inputNombre = document.getElementById('cabina-nombre');
    const inputCapacidad = document.getElementById('cabina-capacidad');
    const inputPrecio = document.getElementById('cabina-precio');
    const selectEstado = document.getElementById('cabina-estado');
    const errorNombre = document.getElementById('error-cabina-nombre');
    const errorCapacidad = document.getElementById('error-cabina-capacidad');
    const errorPrecio = document.getElementById('error-cabina-precio');
    const tituloFormulario = document.getElementById('titulo-formulario-cabina');
    const btnGuardar = document.getElementById('btn-guardar-cabina');
    const btnCancelar = document.getElementById('btn-cancelar-cabina');
    const mensajeExito = document.getElementById('mensaje-exito-cabina');
    const cuerpoTabla = document.getElementById('cuerpo-tabla-cabinas');
    const mensajeSinCabinas = document.getElementById('mensaje-sin-cabinas');

    const etiquetasEstado = {
        activa: 'Activa',
        inactiva: 'Inactiva',
        mantenimiento: 'En mantenimiento'
    };

    let estadoNombre = false;
    let estadoCapacidad = false;
    let estadoPrecio = false;

    let cabinas = [];

    function cargarCabinas() {
        fetch(`${API_URL}?action=listar`)
            .then(response => response.json())
            .then(data => {
                cabinas = data.cabinas ?? [];
                renderizarCabinas();
            })
            .catch(() => {
                mostrarMensajeExito('Error al cargar las cabinas. Intenta nuevamente.', true);
            });
    }

    function renderizarCabinas() {
        cuerpoTabla.innerHTML = '';

        if (cabinas.length === 0) {
            mensajeSinCabinas.style.display = 'block';
            return;
        }

        mensajeSinCabinas.style.display = 'none';

        cabinas.forEach(cabina => {
            const fila = document.createElement('tr');

            fila.innerHTML = `
                <td>${cabina.nombre}</td>
                <td>${cabina.capacidad} personas</td>
                <td>₡${Number(cabina.precio).toLocaleString('es-CR')}</td>
                <td><span class="badge-estado badge-${cabina.estado}">${etiquetasEstado[cabina.estado]}</span></td>
                <td>
                    <div class="acciones-cabina">
                        <button type="button" class="btn-icono btn-editar" data-id="${cabina.id}" title="Editar cabina">✏️</button>
                        <button type="button" class="btn-icono btn-eliminar" data-id="${cabina.id}" title="Eliminar cabina">🗑️</button>
                    </div>
                </td>
            `;

            cuerpoTabla.appendChild(fila);
        });

        document.querySelectorAll('.btn-editar').forEach(boton => {
            boton.addEventListener('click', function () {
                cargarCabinaParaEditar(Number(this.dataset.id));
            });
        });

        document.querySelectorAll('.btn-eliminar').forEach(boton => {
            boton.addEventListener('click', function () {
                eliminarCabina(Number(this.dataset.id));
            });
        });
    }

    function validarNombre() {
        const valor = inputNombre.value.trim();
        if (valor.length < 2) {
            errorNombre.textContent = 'Ingresa un nombre o número válido.';
            estadoNombre = false;
        } else {
            errorNombre.textContent = '';
            estadoNombre = true;
        }
        actualizarBoton();
    }

    function validarCapacidad() {
        const valor = inputCapacidad.value;
        if (!valor || Number(valor) < 1) {
            errorCapacidad.textContent = 'La capacidad debe ser mayor a 0.';
            estadoCapacidad = false;
        } else {
            errorCapacidad.textContent = '';
            estadoCapacidad = true;
        }
        actualizarBoton();
    }

    function validarPrecio() {
        const valor = inputPrecio.value;
        if (!valor || Number(valor) < 0) {
            errorPrecio.textContent = 'Ingresa un precio válido.';
            estadoPrecio = false;
        } else {
            errorPrecio.textContent = '';
            estadoPrecio = true;
        }
        actualizarBoton();
    }

    function actualizarBoton() {
        const formularioValido = estadoNombre && estadoCapacidad && estadoPrecio;

        if (formularioValido) {
            btnGuardar.removeAttribute('disabled');
        } else {
            btnGuardar.setAttribute('disabled', 'true');
        }
    }

    inputNombre.addEventListener('input', validarNombre);
    inputCapacidad.addEventListener('input', validarCapacidad);
    inputPrecio.addEventListener('input', validarPrecio);

    function cargarCabinaParaEditar(id) {
        const cabina = cabinas.find(c => c.id === id);
        if (!cabina) return;

        inputId.value = cabina.id;
        inputNombre.value = cabina.nombre;
        inputCapacidad.value = cabina.capacidad;
        inputPrecio.value = cabina.precio;
        selectEstado.value = cabina.estado;

        tituloFormulario.textContent = 'Editar cabina';
        btnGuardar.textContent = 'Guardar cambios';
        btnCancelar.style.display = 'inline-block';

        estadoNombre = true;
        estadoCapacidad = true;
        estadoPrecio = true;
        errorNombre.textContent = '';
        errorCapacidad.textContent = '';
        errorPrecio.textContent = '';
        actualizarBoton();

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function eliminarCabina(id) {
        if (!confirm('¿Estás seguro de que deseas eliminar esta cabina?')) return;

        const datos = new URLSearchParams({ action: 'eliminar', id });

        fetch(API_URL, {
            method: 'POST',
            body: datos
        })
            .then(respuesta => respuesta.json())
            .then(res => {
                if (res.response === '00') {
                    if (inputId.value && Number(inputId.value) === id) {
                        reiniciarFormulario();
                    }
                    cargarCabinas();
                } else {
                    mostrarMensajeExito(res.message || 'Error al eliminar la cabina. Intenta nuevamente.', true);
                }
            })
            .catch(() => {
                mostrarMensajeExito('No se pudo conectar con el servidor.', true);
            });
    }

    function reiniciarFormulario() {
        formulario.reset();
        inputId.value = '';
        selectEstado.value = 'activa';
        tituloFormulario.textContent = 'Registrar cabina';
        btnGuardar.textContent = 'Registrar cabina';
        btnCancelar.style.display = 'none';
        errorNombre.textContent = '';
        errorCapacidad.textContent = '';
        errorPrecio.textContent = '';

        estadoNombre = false;
        estadoCapacidad = false;
        estadoPrecio = false;
        actualizarBoton();
    }

    function mostrarMensajeExito(texto, esError = false) {
        mensajeExito.textContent = texto;
        mensajeExito.className = esError ? 'exito-visible exito-error' : 'exito-visible';

        setTimeout(() => {
            mensajeExito.textContent = '';
            mensajeExito.className = 'exito-oculto';
        }, 3000);
    }

    formulario.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!(estadoNombre && estadoCapacidad && estadoPrecio)) return;

        const idEditar = inputId.value;
        const datosCabina = new URLSearchParams({
            action: idEditar ? 'actualizar' : 'crear',
            id: idEditar || '',
            nombre: inputNombre.value.trim(),
            capacidad: inputCapacidad.value,
            precio: inputPrecio.value,
            estado: selectEstado.value
        });

        btnGuardar.setAttribute('disabled', 'true');

        fetch(API_URL, {
            method: 'POST',
            body: datosCabina
        })
            .then(respuesta => respuesta.json())
            .then(res => {
                if (res.response === '00') {
                    mostrarMensajeExito(idEditar ? 'Cabina actualizada con éxito.' : 'Cabina registrada con éxito.');
                    reiniciarFormulario();
                    cargarCabinas();
                } else {
                    mostrarMensajeExito(res.message || 'Error al guardar la cabina. Intenta nuevamente.', true);
                    actualizarBoton();
                }
            }).catch(() => {
                mostrarMensajeExito('No se pudo conectar con el servidor.', true);
                actualizarBoton();
            });
    });

    btnCancelar.addEventListener('click', reiniciarFormulario);

    cargarCabinas();
});