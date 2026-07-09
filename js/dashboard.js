document.addEventListener('DOMContentLoaded', function () {
 
    // Datos simulados
    const cabinas = [
        { id: 1, nombre: 'Cabina 01', capacidad: 4, precio: 45000, estado: 'disponible' },
        { id: 2, nombre: 'Cabina 02', capacidad: 6, precio: 60000, estado: 'ocupada' },
        { id: 3, nombre: 'Cabina 03', capacidad: 2, precio: 30000, estado: 'mantenimiento' }
    ];
 
    const clientes = [
        {
            nombre: 'Ana Rodríguez',
            historial: [
                { fecha: '2026-02-14', cabina: 'Cabina 01' },
                { fecha: '2026-03-20', cabina: 'Cabina 03' },
                { fecha: '2026-04-10', cabina: 'Cabina 02' },
                { fecha: '2026-05-01', cabina: 'Cabina 01' }
            ]
        },
        {
            nombre: 'Luis Vargas',
            historial: [
                { fecha: '2026-01-05', cabina: 'Cabina 02' }
            ]
        },
        {
            nombre: 'María Jiménez',
            historial: [
                { fecha: '2026-01-18', cabina: 'Cabina 01' },
                { fecha: '2026-02-22', cabina: 'Cabina 03' },
                { fecha: '2026-03-15', cabina: 'Cabina 03' },
                { fecha: '2026-04-30', cabina: 'Cabina 02' },
                { fecha: '2026-06-02', cabina: 'Cabina 01' }
            ]
        }
    ];
 
    const UMBRAL_FIDELIZACION = 3;
 
    // DOM
    const valorReservas = document.getElementById('valor-reservas');
    const valorDisponibles = document.getElementById('valor-disponibles');
    const valorOcupadas = document.getElementById('valor-ocupadas');
    const valorFrecuentes = document.getElementById('valor-frecuentes');
    const listaAlertas = document.getElementById('lista-alertas');
    const mensajeSinAlertas = document.getElementById('mensaje-sin-alertas');
 
    // Calculos
    function calcularTotalReservas() {
        return clientes.reduce((total, cliente) => total + cliente.historial.length, 0);
    }
 
    function contarCabinasPorEstado(estado) {
        return cabinas.filter(cabina => cabina.estado === estado).length;
    }
 
    function obtenerClientesFrecuentes() {
        return clientes.filter(cliente => cliente.historial.length > UMBRAL_FIDELIZACION);
    }
 
    // Renderizado de tarjetas
    function renderizarResumen() {
        valorReservas.textContent = calcularTotalReservas();
        valorDisponibles.textContent = contarCabinasPorEstado('disponible');
        valorOcupadas.textContent = contarCabinasPorEstado('ocupada');
        valorFrecuentes.textContent = obtenerClientesFrecuentes().length;
    }
 
    // Renderizado de alertas
    function renderizarAlertas() {
        const alertas = [];
 
        cabinas.forEach(cabina => {
            if (cabina.estado === 'mantenimiento') {
                alertas.push({
                    icono: 'bi-tools',
                    texto: `${cabina.nombre} está en mantenimiento y no puede reservarse.`
                });
            }
        });
 
        const disponibles = contarCabinasPorEstado('disponible');
        if (disponibles === 0) {
            alertas.push({
                icono: 'bi-exclamation-triangle-fill',
                texto: 'No hay cabinas disponibles en este momento.'
            });
        } else {
            alertas.push({
                icono: 'bi-door-open-fill',
                texto: `Hay ${disponibles} cabina(s) disponible(s) para nuevas reservas.`
            });
        }
 
        obtenerClientesFrecuentes().forEach(cliente => {
            alertas.push({
                icono: 'bi-star-fill',
                texto: `${cliente.nombre} es cliente frecuente: aplica descuento automático.`
            });
        });
 
        listaAlertas.innerHTML = '';
 
        if (alertas.length === 0) {
            mensajeSinAlertas.style.display = 'block';
            return;
        }
 
        mensajeSinAlertas.style.display = 'none';
 
        alertas.forEach(alerta => {
            const item = document.createElement('div');
            item.className = 'alerta-item';
            item.innerHTML = `
                <i class="bi ${alerta.icono} alerta-icono"></i>
                <span class="alerta-texto">${alerta.texto}</span>
            `;
            listaAlertas.appendChild(item);
        });
    }
 
    renderizarResumen();
    renderizarAlertas();
});
 