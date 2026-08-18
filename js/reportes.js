document.addEventListener('DOMContentLoaded', async () => {
    const API_URL = 'api/reportes.php';

    const resumenIngresos = document.getElementById('resumen-ingresos');
    const resumenOcupacion = document.getElementById('resumen-ocupacion');
    const resumenHuespedes = document.getElementById('resumen-huespedes');
    const cuerpoTablaIngresos = document.getElementById('cuerpo-tabla-ingresos');
    const contenedorOcupacion = document.getElementById('contenedor-ocupacion-cabinas');
    const analisisDatos = document.getElementById('analisis-datos');

    const formatoMoneda = new Intl.NumberFormat('es-CR', {
        style: 'currency',
        currency: 'CRC',
        maximumFractionDigits: 2
    });

    const formatoPorcentaje = (valor) => `${Number(valor || 0).toFixed(1)}%`;

    const renderError = (mensaje) => {
        cuerpoTablaIngresos.innerHTML = `<tr><td colspan="5" class="text-center text-danger">${mensaje}</td></tr>`;
        contenedorOcupacion.innerHTML = '<div class="mb-3 text-danger">No se pudo cargar la ocupación por cabina.</div>';
        analisisDatos.textContent = mensaje;
    };

    const renderIngresos = (ingresos) => {
        if (!ingresos || ingresos.length === 0) {
            cuerpoTablaIngresos.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No hay ingresos registrados.</td></tr>';
            return;
        }

        cuerpoTablaIngresos.innerHTML = ingresos.map((fila) => `
            <tr>
                <td>${fila.mes}</td>
                <td>${fila.reservas}</td>
                <td>${formatoMoneda.format(Number(fila.subtotal || 0))}</td>
                <td>${formatoMoneda.format(Number(fila.descuentos || 0))}</td>
                <td class="fw-bold text-success">${formatoMoneda.format(Number(fila.total_neto || 0))}</td>
            </tr>
        `).join('');
    };

    const renderOcupacion = (ocupaciones) => {
        if (!ocupaciones || ocupaciones.length === 0) {
            contenedorOcupacion.innerHTML = '<div class="mb-3 text-muted">No hay datos de ocupación disponibles.</div>';
            return;
        }

        const colores = ['bg-primary', 'bg-info', 'bg-success', 'bg-warning'];

        contenedorOcupacion.innerHTML = ocupaciones.map((item, index) => {
            const porcentaje = Number(item.porcentaje || 0);
            const color = colores[index % colores.length];

            return `
                <div class="mb-3">
                    <label class="form-label d-flex justify-content-between">
                        <span>${item.cabina}</span>
                        <span>${formatoPorcentaje(porcentaje)}</span>
                    </label>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar ${color}" role="progressbar" style="width: ${porcentaje}%"></div>
                    </div>
                </div>
            `;
        }).join('');
    };

    try {
        const response = await fetch(`${API_URL}?action=resumen`);
        const data = await response.json();

        if (data.response !== '00') {
            throw new Error(data.message || 'No se pudo obtener la información de reportes');
        }

        resumenIngresos.textContent = formatoMoneda.format(Number(data.resumen?.ingresos_totales || 0));
        resumenOcupacion.textContent = formatoPorcentaje(data.resumen?.ocupacion_promedio || 0);
        resumenHuespedes.textContent = Number(data.resumen?.huespedes_atendidos || 0);

        renderIngresos(data.ingresos_por_mes || []);
        renderOcupacion(data.ocupacion_por_cabina || []);
        analisisDatos.textContent = data.analisis || 'No hay análisis disponible por el momento.';
    } catch (error) {
        renderError(error.message);
    }
});
