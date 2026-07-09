document.addEventListener('DOMContentLoaded', function () {
 
    // Datos simulados
    const cabinas = [
        { id: 1, nombre: 'Cabina 01' },
        { id: 2, nombre: 'Cabina 02' },
        { id: 3, nombre: 'Cabina 03' }
    ];
 
    const nombresDias = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
    const etiquetasEstado = {
        disponible: 'Disponible',
        ocupada: 'Ocupada',
        mantenimiento: 'Mantenimiento'
    };
 
    // DOM
    const cuerpoTabla = document.getElementById('cuerpo-tabla-disponibilidad');
    const encabezadoDias = document.getElementById('encabezado-dias');
    const textoRango = document.getElementById('texto-rango-semana');
    const btnAnterior = document.getElementById('btn-semana-anterior');
    const btnSiguiente = document.getElementById('btn-semana-siguiente');
    const btnHoy = document.getElementById('btn-semana-actual');
 
    let semanaOffset = 0; // 0 = semana actual, -1 = anterior, 1 = siguiente...
 
    // Utilidades de fecha
    function obtenerLunesDeLaSemana(offset) {
        const hoy = new Date();
        const diaSemana = hoy.getDay(); // 0 = domingo
        const diferenciaHastaLunes = diaSemana === 0 ? -6 : 1 - diaSemana;
        const lunes = new Date(hoy);
        lunes.setDate(hoy.getDate() + diferenciaHastaLunes + offset * 7);
        lunes.setHours(0, 0, 0, 0);
        return lunes;
    }
 
    function formatearFecha(fecha) {
        return fecha.toLocaleDateString('es-CR', { day: '2-digit', month: '2-digit' });
    }
 
    function calcularEstado(cabinaId, indiceDia, offset) {
        const valor = (cabinaId * 3 + indiceDia * 2 + offset * 5) % 5;
        if (valor <= 2) return 'disponible';
        if (valor === 3) return 'ocupada';
        return 'mantenimiento';
    }
 
    // Renderizado
    function renderizarCalendario() {
        const lunes = obtenerLunesDeLaSemana(semanaOffset);
        const dias = [];
 
        for (let i = 0; i < 7; i++) {
            const fecha = new Date(lunes);
            fecha.setDate(lunes.getDate() + i);
            dias.push(fecha);
        }
 
        // Encabezado de días
        encabezadoDias.innerHTML = '<th>Cabina</th>' + dias.map((fecha, i) => `
            <th>${nombresDias[i]}<small>${formatearFecha(fecha)}</small></th>
        `).join('');
 
        // Texto del rango de semana
        const domingo = dias[6];
        textoRango.textContent = `Semana del ${formatearFecha(lunes)} al ${formatearFecha(domingo)}`;
 
        // Filas por cabina
        cuerpoTabla.innerHTML = cabinas.map(cabina => {
            const celdas = dias.map((_, indiceDia) => {
                const estado = calcularEstado(cabina.id, indiceDia, semanaOffset);
                return `<td><span class="celda-estado celda-${estado}">${etiquetasEstado[estado]}</span></td>`;
            }).join('');
 
            return `<tr><td class="columna-cabina">${cabina.nombre}</td>${celdas}</tr>`;
        }).join('');
    }
 
    // Navegación
    btnAnterior.addEventListener('click', function () {
        semanaOffset--;
        renderizarCalendario();
    });
 
    btnSiguiente.addEventListener('click', function () {
        semanaOffset++;
        renderizarCalendario();
    });
 
    btnHoy.addEventListener('click', function () {
        semanaOffset = 0;
        renderizarCalendario();
    });
 
    renderizarCalendario();
});
 