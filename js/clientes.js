document.addEventListener('DOMContentLoaded', function () {

    const formulario = document.getElementById('form-cliente');
    const inputId = document.getElementById('cliente-id');
    const inputNombre = document.getElementById('cliente-nombre');
    const inputCedula = document.getElementById('cliente-cedula');
    const inputTelefono = document.getElementById('cliente-telefono');
    const inputEmail = document.getElementById('cliente-email');
    const errorNombre = document.getElementById('error-cliente-nombre');
    const errorCedula = document.getElementById('error-cliente-cedula');
    const errorTelefono = document.getElementById('error-cliente-telefono');
    const errorEmail = document.getElementById('error-cliente-email');
    const tituloFormulario = document.getElementById('titulo-formulario-cliente');
    const mensajeExito = document.getElementById('mensaje-exito-cliente');
    const cuerpoTabla = document.getElementById('cuerpo-tabla-clientes');
    const mensajeSinClientes = document.getElementById('mensaje-sin-clientes');
    const inputBusqueda = document.getElementById('input-busqueda-clientes');
    const btnGuardar = document.getElementById('btn-guardar-cliente');
    const btnCancelar = document.getElementById('btn-cancelar-cliente');
    const regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const regexTelefono = /^[0-9]{8,}$/;
    const UMBRAL_FIDELIZACION = 3;

    let estadoNombre = false;
    let estadoCedula = false;
    let estadoTelefono = false;
    let estadoEmail = false;

    let clientes = [
        {
            id: 1,
            nombre: 'Ana Rodríguez',
            cedula: '123456789',
            telefono: '88881111',
            email: 'ana.rodriguez@correo.com',
            historial: [
                { fecha: '2026-02-14', cabina: 'Cabina 01' },
                { fecha: '2026-03-20', cabina: 'Cabina 03' },
                { fecha: '2026-04-10', cabina: 'Cabina 02' },
                { fecha: '2026-05-01', cabina: 'Cabina 01' }
            ]
        },
        {
            id: 2,
            nombre: 'Luis Vargas',
            cedula: '234567890',
            telefono: '88882222',
            email: 'luis.vargas@correo.com',
            historial: [
                { fecha: '2026-01-05', cabina: 'Cabina 02' }
            ]
        },
        {
            id: 3,
            nombre: 'María Jiménez',
            cedula: '345678901',
            telefono: '88883333',
            email: 'maria.jimenez@correo.com',
            historial: [
                { fecha: '2026-01-18', cabina: 'Cabina 01' },
                { fecha: '2026-02-22', cabina: 'Cabina 03' },
                { fecha: '2026-03-15', cabina: 'Cabina 03' },
                { fecha: '2026-04-30', cabina: 'Cabina 02' },
                { fecha: '2026-06-02', cabina: 'Cabina 01' }
            ]
        }
    ];

    let contadorId = 4;
    let filaHistorialAbierta = null;

    function esClienteFrecuente(cliente) {
        return cliente.historial.length > UMBRAL_FIDELIZACION;
    }

    function mostrarClientes(filtro = '') {
        cuerpoTabla.innerHTML = '';
        filaHistorialAbierta = null;

        const filtroNormalizado = filtro.trim().toLowerCase();

        const clientesFiltrados = clientes.filter(cliente =>
            cliente.nombre.toLowerCase().includes(filtroNormalizado) ||
            cliente.cedula.toLowerCase().includes(filtroNormalizado)
        );

        if (clientesFiltrados.length === 0) {
            mensajeSinClientes.style.display = 'block';
            return;
        }

        mensajeSinClientes.style.display = 'none';

        clientesFiltrados.forEach(cliente => {
            const fila = document.createElement('tr');
            fila.dataset.id = cliente.id;

            const frecuente = esClienteFrecuente(cliente);

            fila.innerHTML = `
                <td>${cliente.nombre}</td>
                <td>${cliente.cedula}</td>
                <td class="contacto-cliente">
                    <span>${cliente.telefono}</span>
                    <span>${cliente.email}</span>
                </td>
                <td><span class="badge-reservas">${cliente.historial.length}</span></td>
                <td>${frecuente ? '<span class="badge-fidelizacion">⭐ Frecuente · 10% dto.</span>' : '—'}</td>
                <td><button type="button" class="btn-historial" data-id="${cliente.id}">Ver historial</button></td>
                <td>
                    <div class="acciones-cliente">
                        <button type="button" class="btn-icono btn-editar-cliente" data-id="${cliente.id}" title="Editar cliente">✏️</button>
                        <button type="button" class="btn-icono btn-eliminar-cliente" data-id="${cliente.id}" title="Eliminar cliente">🗑️</button>
                    </div>
                </td>
            `;

            cuerpoTabla.appendChild(fila);
        });

        document.querySelectorAll('.btn-historial').forEach(boton => {
            boton.addEventListener('click', function () {
                alternarHistorial(Number(this.dataset.id));
            });
        });

        document.querySelectorAll('.btn-editar-cliente').forEach(boton => {
            boton.addEventListener('click', function () {
                cargarClienteParaEditar(Number(this.dataset.id));
            });
        });

        document.querySelectorAll('.btn-eliminar-cliente').forEach(boton => {
            boton.addEventListener('click', function () {
                eliminarCliente(Number(this.dataset.id));
            });
        });
    }

    function alternarHistorial(id) {
        const filaExistente = document.querySelector(`.fila-historial[data-cliente="${id}"]`);

        if (filaExistente) {
            filaExistente.remove();
            return;
        }

        document.querySelectorAll('.fila-historial').forEach(fila => fila.remove());

        const cliente = clientes.find(c => c.id === id);
        if (!cliente) return;

        const filaOriginal = document.querySelector(`tr[data-id="${id}"]`);
        const filaHistorial = document.createElement('tr');
        filaHistorial.className = 'fila-historial';
        filaHistorial.dataset.cliente = id;

        if (cliente.historial.length === 0) {
            filaHistorial.innerHTML = `<td colspan="7">Este cliente aún no tiene reservas registradas.</td>`;
        } else {
            const items = cliente.historial
                .map(reserva => `<li>${reserva.fecha} — ${reserva.cabina}</li>`)
                .join('');
            filaHistorial.innerHTML = `<td colspan="7"><strong>Historial de reservas:</strong><ul>${items}</ul></td>`;
        }

        filaOriginal.insertAdjacentElement('afterend', filaHistorial);
    }

    function cargarClienteParaEditar(id) {
        const cliente = clientes.find(c => c.id === id);
        if (!cliente) return;

        inputId.value = cliente.id;
        inputNombre.value = cliente.nombre;
        inputCedula.value = cliente.cedula;
        inputTelefono.value = cliente.telefono;
        inputEmail.value = cliente.email;

        tituloFormulario.textContent = 'Editar cliente';
        btnGuardar.textContent = 'Guardar cambios';
        btnCancelar.style.display = 'inline-block';

        estadoNombre = true;
        estadoCedula = true;
        estadoTelefono = true;
        estadoEmail = true;
        errorNombre.textContent = '';
        errorCedula.textContent = '';
        errorTelefono.textContent = '';
        errorEmail.textContent = '';
        actualizarBoton();

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function eliminarCliente(id) {
        clientes = clientes.filter(c => c.id !== id);

        if (inputId.value && Number(inputId.value) === id) {
            reiniciarFormulario();
        }

        mostrarClientes(inputBusqueda.value);
    }

    function reiniciarFormulario() {
        formulario.reset();
        inputId.value = '';
        tituloFormulario.textContent = 'Registrar cliente';
        btnGuardar.textContent = 'Registrar cliente';
        btnCancelar.style.display = 'none';
        errorNombre.textContent = '';
        errorCedula.textContent = '';
        errorTelefono.textContent = '';
        errorEmail.textContent = '';

        estadoNombre = false;
        estadoCedula = false;
        estadoTelefono = false;
        estadoEmail = false;
        actualizarBoton();
    }

    function validarNombre() {
        const valor = inputNombre.value.trim();
        if (valor.length < 3) {
            errorNombre.textContent = 'Ingresa el nombre completo del cliente.';
            estadoNombre = false;
        } else {
            errorNombre.textContent = '';
            estadoNombre = true;
        }
        actualizarBoton();
    }

    function validarCedula() {
        const valor = inputCedula.value.trim();
        if (valor.length < 9) {
            errorCedula.textContent = 'Ingresa una cédula válida.';
            estadoCedula = false;
        } else {
            errorCedula.textContent = '';
            estadoCedula = true;
        }
        actualizarBoton();
    }

    function validarTelefono() {
        const valor = inputTelefono.value.trim();
        if (!regexTelefono.test(valor)) {
            errorTelefono.textContent = 'El teléfono debe tener al menos 8 dígitos.';
            estadoTelefono = false;
        } else {
            errorTelefono.textContent = '';
            estadoTelefono = true;
        }
        actualizarBoton();
    }

    function validarEmail() {
        const valor = inputEmail.value.trim();
        if (!regexEmail.test(valor)) {
            errorEmail.textContent = 'Ingresa un correo electrónico válido.';
            estadoEmail = false;
        } else {
            errorEmail.textContent = '';
            estadoEmail = true;
        }
        actualizarBoton();
    }

    function actualizarBoton() {
        const formularioValido = estadoNombre && estadoCedula && estadoTelefono && estadoEmail;

        if (formularioValido) {
            btnGuardar.removeAttribute('disabled');
        } else {
            btnGuardar.setAttribute('disabled', 'true');
        }
    }

    inputNombre.addEventListener('input', validarNombre);
    inputCedula.addEventListener('input', validarCedula);
    inputTelefono.addEventListener('input', validarTelefono);
    inputEmail.addEventListener('input', validarEmail);

    function mostrarMensajeExito(texto) {
        mensajeExito.textContent = texto;
        mensajeExito.className = 'exito-visible';

        setTimeout(() => {
            mensajeExito.textContent = '';
            mensajeExito.className = 'exito-oculto';
        }, 3000);
    }

    formulario.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!(estadoNombre && estadoCedula && estadoTelefono && estadoEmail)) return;

        const datosCliente = {
            nombre: inputNombre.value.trim(),
            cedula: inputCedula.value.trim(),
            telefono: inputTelefono.value.trim(),
            email: inputEmail.value.trim()
        };

        if (inputId.value) {
            const idEditar = Number(inputId.value);
            clientes = clientes.map(c =>
                c.id === idEditar ? { ...c, ...datosCliente } : c
            );
            mostrarMensajeExito('Cliente actualizado correctamente.');
        } else {
            clientes.push({ id: contadorId, ...datosCliente, historial: [] });
            contadorId++;
            mostrarMensajeExito('Cliente registrado correctamente.');
        }

        reiniciarFormulario();
        mostrarClientes(inputBusqueda.value);
    });

    btnCancelar.addEventListener('click', reiniciarFormulario);

    inputBusqueda.addEventListener('input', function () {
        mostrarClientes(inputBusqueda.value);
    });

    mostrarClientes();
});