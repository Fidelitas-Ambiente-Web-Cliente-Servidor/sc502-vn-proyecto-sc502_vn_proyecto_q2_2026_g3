document.addEventListener('DOMContentLoaded', function () {

    const API_URL = 'api/disponibilidad.php';

    const etiquetasEstado = {
        disponible: 'Disponible',
        ocupada: 'Ocupada',
        inactiva: 'Inactiva',
        mantenimiento: 'Mantenimiento'
    };

    // DOM
    const cuerpoTabla = document.getElementById('cuerpo-tabla-disponibilidad');
    const encabezadoDias = document.getElementById('encabezado-dias');
    const textoRango = document.getElementById('texto-rango-semana');
    const btnAnterior = document.getElementById('btn-semana-anterior');
    const btnSiguiente = document.getElementById('btn-semana-siguiente');
    const btnHoy = document.getElementById('btn-semana-actual');

    let semanaOffset = 0;
    let cargando = false;

    function renderizarCabecera(dias) {
        encabezadoDias.innerHTML = '<th>Cabina</th>' + dias.map(dia => `
            <th>${dia.nombre}<small>${dia.fecha}</small></th>
        `).join('');
    }

    function renderizarFilas(cabinas) {
        if (cabinas.length === 0) {
            cuerpoTabla.innerHTML = '<tr><td colspan="8">No hay cabinas registradas.</td></tr>';
            return;
        }

        cuerpoTabla.innerHTML = cabinas.map(cabina => {
            const celdas = cabina.estados.map(estado => `
                <td><span class="celda-estado celda-${estado}">${etiquetasEstado[estado] ?? estado}</span></td>
            `).join('');

            return `<tr><td class="columna-cabina">${cabina.nombre}</td>${celdas}</tr>`;
        }).join('');
    }

    function cargarCalendario() {
        if (cargando) {
            return;
        }
        cargando = true;

        fetch(`${API_URL}?action=calendario&offset=${semanaOffset}`)
            .then(response => response.json())
            .then(data => {
                if (data.response !== '00') {
                    throw new Error(data.message || 'No se pudo cargar el calendario');
                }

                textoRango.textContent = data.rango;
                renderizarCabecera(data.dias);
                renderizarFilas(data.cabinas);
            })
            .catch(() => {
                textoRango.textContent = '';
                cuerpoTabla.innerHTML = '<tr><td colspan="8">Error al cargar la disponibilidad. Intenta nuevamente.</td></tr>';
            })
            .finally(() => {
                cargando = false;
            });
    }

    // Navegación
    btnAnterior.addEventListener('click', function () {
        semanaOffset--;
        cargarCalendario();
    });

    btnSiguiente.addEventListener('click', function () {
        semanaOffset++;
        cargarCalendario();
    });

    btnHoy.addEventListener('click', function () {
        semanaOffset = 0;
        cargarCalendario();
    });

    cargarCalendario();
});