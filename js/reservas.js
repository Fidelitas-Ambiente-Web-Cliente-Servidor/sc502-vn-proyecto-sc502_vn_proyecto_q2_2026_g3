document.addEventListener('DOMContentLoaded', () => {
    const API_URL = 'api/reservas.php';

    const formReserva = document.getElementById('form-reserva');
    const selectCliente = document.getElementById('reserva-cliente');
    const selectCabina = document.getElementById('reserva-cabina');
    const inputEntrada = document.getElementById('reserva-entrada');
    const inputSalida = document.getElementById('reserva-salida');
    const inputHuespedes = document.getElementById('reserva-huespedes');
    const cuerpoTablaReservas = document.getElementById('cuerpo-tabla-reservas');

    const btnGuardar = document.getElementById('btn-guardar-reserva');
    const btnCancelarEdicion = document.getElementById('btn-cancelar-edicion');

    const mensajeExito = document.getElementById('mensaje-exito-reserva');
    const mensajeError = document.getElementById('mensaje-error-reserva');

    const formatoMoneda = new Intl.NumberFormat('es-CR', {
        style: 'currency',
        currency: 'CRC',
        maximumFractionDigits: 2
    });

    const estadoConfig = {
        activa: { clase: 'bg-warning text-dark', texto: 'Activa' },
        finalizada: { clase: 'bg-success', texto: 'Finalizada' },
        cancelada: { clase: 'bg-danger', texto: 'Cancelada' }
    };

    let reservaEditandoId = null;
    let reservasActuales = [];

    const ocultarMensajes = () => {
        mensajeExito.className = 'exito-oculto';
        mensajeError.className = 'exito-oculto';
        mensajeError.textContent = '';
    };

    const mostrarExito = (texto) => {
        mensajeExito.textContent = texto;
        mensajeExito.className = 'exito-visible';
    };

    const mostrarError = (texto) => {
        mensajeError.textContent = texto;
        mensajeError.className = 'exito-visible exito-error';
    };

    const resetFormulario = () => {
        formReserva.reset();
        reservaEditandoId = null;
        btnGuardar.textContent = 'Confirmar Reserva';
        btnCancelarEdicion.classList.remove('visible');
    };

    const formatoFecha = (valor) => {
        const fecha = new Date(String(valor).replace(' ', 'T'));
        if (Number.isNaN(fecha.getTime())) {
            return valor;
        }

        return fecha.toLocaleDateString('es-CR');
    };

    const valorFechaInput = (valor) => String(valor || '').split(' ')[0];

    const estadoVisualReserva = (reserva) => {
        if (reserva.estado === 'cancelada') {
            return estadoConfig.cancelada;
        }

        if (reserva.estado === 'finalizada') {
            return estadoConfig.finalizada;
        }

        if (Number(reserva.pendiente || 0) <= 0) {
            return { clase: 'bg-primary', texto: 'Pendiente de cierre' };
        }

        return { clase: 'bg-warning text-dark', texto: 'Pendiente pago' };
    };

    const renderSinReservas = (texto) => {
        cuerpoTablaReservas.innerHTML = `<tr><td colspan="7" class="text-center text-muted">${texto}</td></tr>`;
    };

    const cargarDatosFormulario = async () => {
        const response = await fetch(`${API_URL}?action=datos_formulario`);
        const data = await response.json();

        if (data.response !== '00') {
            throw new Error(data.message || 'No se pudo cargar la información base de reservas');
        }

        selectCliente.innerHTML = '<option value="">Seleccione un cliente...</option>';
        data.clientes.forEach((cliente) => {
            const option = document.createElement('option');
            option.value = cliente.id;
            option.textContent = `${cliente.nombre} (${cliente.cedula})`;
            selectCliente.appendChild(option);
        });

        selectCabina.innerHTML = '<option value="">Seleccione una cabina...</option>';
        data.cabinas.forEach((cabina) => {
            const option = document.createElement('option');
            option.value = cabina.id;
            option.textContent = `${cabina.nombre} - Capacidad ${cabina.capacidad} (${formatoMoneda.format(Number(cabina.precio || 0))})`;
            selectCabina.appendChild(option);
        });
    };

    const cargarReservas = async () => {
        const response = await fetch(`${API_URL}?action=listar`);
        const data = await response.json();

        if (data.response !== '00') {
            throw new Error(data.message || 'No se pudieron cargar las reservas');
        }

        reservasActuales = data.reservas || [];

        if (reservasActuales.length === 0) {
            renderSinReservas('No hay reservas registradas.');
            return;
        }

        cuerpoTablaReservas.innerHTML = reservasActuales.map((reserva) => {
            const estado = estadoVisualReserva(reserva);
            const pendiente = Number(reserva.pendiente || 0);
            const puedeFinalizar = reserva.estado === 'activa' && pendiente <= 0;
            const editarDeshabilitado = reserva.estado === 'cancelada';

            return `
                <tr>
                    <td>#${reserva.id}</td>
                    <td>${reserva.cliente}</td>
                    <td>${reserva.cabina}</td>
                    <td>${formatoFecha(reserva.fecha_reserva)}</td>
                    <td>${formatoFecha(reserva.fecha_fin)}</td>
                    <td>
                        <span class="badge ${estado.clase}">${estado.texto}</span>
                        <div class="text-muted small">Pendiente: ${formatoMoneda.format(pendiente)}</div>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" data-action="editar" data-id="${reserva.id}" ${editarDeshabilitado ? 'disabled' : ''}>
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" data-action="finalizar" data-id="${reserva.id}" ${puedeFinalizar ? '' : 'disabled'}>
                            <i class="bi bi-check-circle"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-action="cancelar" data-id="${reserva.id}" ${reserva.estado === 'cancelada' ? 'disabled' : ''}>
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    };

    const cargarDatos = async () => {
        await Promise.all([cargarDatosFormulario(), cargarReservas()]);
    };

    const guardarReserva = async () => {
        const body = new URLSearchParams({
            action: reservaEditandoId ? 'actualizar' : 'crear',
            cliente_id: selectCliente.value,
            cabina_id: selectCabina.value,
            fecha_reserva: inputEntrada.value,
            fecha_fin: inputSalida.value,
            huespedes: inputHuespedes.value
        });

        if (reservaEditandoId) {
            body.append('id', reservaEditandoId.toString());
        }

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body
        });

        const data = await response.json();
        if (data.response !== '00') {
            throw new Error(data.message || 'No se pudo guardar la reserva');
        }

        mostrarExito(data.message || 'Reserva guardada exitosamente.');
        resetFormulario();
        await cargarReservas();
    };

    const cambiarEstadoReserva = async (id, estado) => {
        const body = new URLSearchParams({
            action: 'cambiar_estado',
            id: id.toString(),
            estado
        });

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body
        });

        const data = await response.json();
        if (data.response !== '00') {
            throw new Error(data.message || 'No se pudo actualizar el estado de la reserva');
        }

        mostrarExito(data.message || 'Estado de la reserva actualizado.');
        await cargarReservas();
    };

    const activarModoEdicion = (id) => {
        const reserva = reservasActuales.find((item) => Number(item.id) === Number(id));
        if (!reserva) {
            mostrarError('No se encontró la reserva seleccionada para edición.');
            return;
        }

        reservaEditandoId = Number(reserva.id);
        selectCliente.value = reserva.cliente_id;
        selectCabina.value = reserva.cabina_id;
        inputEntrada.value = valorFechaInput(reserva.fecha_reserva);
        inputSalida.value = valorFechaInput(reserva.fecha_fin);
        inputHuespedes.value = reserva.huespedes;

        btnGuardar.textContent = 'Guardar Cambios';
        btnCancelarEdicion.classList.add('visible');
    };

    if (formReserva) {
        formReserva.addEventListener('submit', async (event) => {
            event.preventDefault();
            ocultarMensajes();

            try {
                await guardarReserva();
            } catch (error) {
                mostrarError(error.message);
            }
        });
    }

    if (btnCancelarEdicion) {
        btnCancelarEdicion.addEventListener('click', () => {
            ocultarMensajes();
            resetFormulario();
        });
    }

    if (cuerpoTablaReservas) {
        cuerpoTablaReservas.addEventListener('click', async (event) => {
            const button = event.target.closest('button[data-action]');
            if (!button) {
                return;
            }

            const accion = button.dataset.action;
            const id = Number(button.dataset.id || 0);
            if (id <= 0) {
                return;
            }

            ocultarMensajes();

            try {
                if (accion === 'editar') {
                    activarModoEdicion(id);
                    return;
                }

                if (accion === 'finalizar') {
                    await cambiarEstadoReserva(id, 'finalizada');
                    return;
                }

                if (accion === 'cancelar') {
                    await cambiarEstadoReserva(id, 'cancelada');
                }
            } catch (error) {
                mostrarError(error.message);
            }
        });
    }

    cargarDatos().catch((error) => {
        mostrarError(error.message || 'No se pudo inicializar el módulo de reservas.');
        renderSinReservas('No fue posible cargar la información de reservas.');
    });
});
