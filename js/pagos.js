document.addEventListener('DOMContentLoaded', () => {
    const API_URL = 'api/pagos.php';

    const formPago = document.getElementById('form-pago');
    const selectReserva = document.getElementById('pago-reserva');
    const inputMonto = document.getElementById('pago-monto');
    const inputMetodo = document.getElementById('pago-metodo');
    const inputComprobante = document.getElementById('pago-comprobante');
    const cuerpoTablaPagos = document.getElementById('cuerpo-tabla-pagos');

    const prevContenedor = document.getElementById('previsualizacion-contenedor');
    const imgPrev = document.getElementById('img-previsualizacion');
    const btnIA = document.getElementById('btn-ia-validar');
    const iaStatus = document.getElementById('ia-status');
    const iaResultado = document.getElementById('ia-resultado');

    const mensajeExito = document.getElementById('mensaje-exito-pago');
    const mensajeError = document.getElementById('mensaje-error-pago');

    const formatoMoneda = new Intl.NumberFormat('es-CR', {
        style: 'currency',
        currency: 'CRC',
        maximumFractionDigits: 2
    });

    const metodosTexto = {
        sinpe: 'SINPE Móvil',
        transferencia: 'Transferencia',
        efectivo: 'Efectivo',
        tarjeta: 'Tarjeta'
    };

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

    const renderSinPagos = (texto) => {
        cuerpoTablaPagos.innerHTML = `<tr><td colspan="6" class="text-center text-muted">${texto}</td></tr>`;
    };

    const cargarReservasPendientes = async () => {
        const response = await fetch(`${API_URL}?action=listar_reservas`);
        const data = await response.json();

        if (data.response !== '00') {
            throw new Error(data.message || 'No se pudieron cargar las reservas');
        }

        selectReserva.innerHTML = '<option value="">Seleccione una reserva...</option>';

        if (!data.reservas || data.reservas.length === 0) {
            selectReserva.innerHTML += '<option value="" disabled>No hay reservas pendientes</option>';
            return;
        }

        data.reservas.forEach((reserva) => {
            const option = document.createElement('option');
            option.value = reserva.id;
            option.dataset.pendiente = reserva.pendiente;
            option.textContent = `#${reserva.id} - ${reserva.cliente} (${reserva.cabina}) | Pendiente: ${formatoMoneda.format(Number(reserva.pendiente || 0))}`;
            selectReserva.appendChild(option);
        });
    };

    const cargarPagos = async () => {
        const response = await fetch(`${API_URL}?action=listar_pagos`);
        const data = await response.json();

        if (data.response !== '00') {
            throw new Error(data.message || 'No se pudo cargar el historial de pagos');
        }

        if (!data.pagos || data.pagos.length === 0) {
            renderSinPagos('No hay pagos registrados todavía.');
            return;
        }

        cuerpoTablaPagos.innerHTML = data.pagos.map((pago) => {
            const metodo = metodosTexto[pago.metodo] || pago.metodo;
            const estado = pago.estado === 'verificado' ? 'bg-success' : 'bg-warning text-dark';
            const comprobante = pago.comprobante
                ? `<span class="text-truncate d-inline-block" style="max-width: 180px;" title="${pago.comprobante}">${pago.comprobante}</span>`
                : '<span class="text-muted">Sin archivo</span>';

            return `
                <tr>
                    <td>${pago.fecha}</td>
                    <td>${pago.reserva}</td>
                    <td>${formatoMoneda.format(Number(pago.monto || 0))}</td>
                    <td>${metodo}</td>
                    <td><span class="badge ${estado}">${pago.estado}</span></td>
                    <td>${comprobante}</td>
                </tr>
            `;
        }).join('');
    };

    const inicializarDatos = async () => {
        try {
            await Promise.all([cargarReservasPendientes(), cargarPagos()]);
        } catch (error) {
            mostrarError(error.message);
            renderSinPagos('No fue posible cargar los pagos en este momento.');
        }
    };

    if (inputComprobante) {
        inputComprobante.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) {
                prevContenedor.classList.add('d-none');
                iaResultado.classList.add('d-none');
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                imgPrev.src = e.target.result;
                prevContenedor.classList.remove('d-none');
                iaResultado.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        });
    }

    if (btnIA) {
        btnIA.addEventListener('click', () => {
            btnIA.disabled = true;
            iaStatus.classList.remove('d-none');
            iaResultado.classList.add('d-none');

            setTimeout(() => {
                iaStatus.classList.add('d-none');
                iaResultado.classList.remove('d-none');
                btnIA.disabled = false;

                if (!inputMonto.value) {
                    const optionSeleccionada = selectReserva.options[selectReserva.selectedIndex];
                    const pendiente = Number(optionSeleccionada?.dataset?.pendiente || 0);
                    inputMonto.value = pendiente > 0 ? pendiente : 45000;
                }
            }, 2000);
        });
    }

    if (formPago) {
        formPago.addEventListener('submit', async (e) => {
            e.preventDefault();
            ocultarMensajes();

            const reservaId = selectReserva.value;
            const monto = Number(inputMonto.value);
            const metodo = inputMetodo.value;
            const archivo = inputComprobante.files[0];
            const opcionSeleccionada = selectReserva.options[selectReserva.selectedIndex];
            const pendiente = Number(opcionSeleccionada?.dataset?.pendiente || 0);

            if (!reservaId) {
                mostrarError('Debe seleccionar una reserva para registrar el pago.');
                return;
            }

            if (!monto || monto <= 0) {
                mostrarError('El monto debe ser mayor a cero.');
                return;
            }

            if (pendiente > 0 && monto > pendiente) {
                mostrarError('El monto no puede exceder el pendiente de la reserva.');
                return;
            }

            try {
                const body = new URLSearchParams({
                    action: 'crear',
                    reserva_id: reservaId,
                    monto: monto.toString(),
                    metodo,
                    comprobante: archivo ? archivo.name : ''
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
                    throw new Error(data.message || 'No se pudo registrar el pago');
                }

                mostrarExito(data.message || 'Pago registrado correctamente.');
                formPago.reset();
                prevContenedor.classList.add('d-none');
                iaResultado.classList.add('d-none');

                await Promise.all([cargarReservasPendientes(), cargarPagos()]);
            } catch (error) {
                mostrarError(error.message);
            }
        });
    }

    inicializarDatos();
});
