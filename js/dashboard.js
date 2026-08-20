document.addEventListener('DOMContentLoaded', function () {

    const API_URL = 'api/dashboard.php';

    // DOM
    const valorReservas = document.getElementById('valor-reservas');
    const valorDisponibles = document.getElementById('valor-disponibles');
    const valorOcupadas = document.getElementById('valor-ocupadas');
    const valorFrecuentes = document.getElementById('valor-frecuentes');
    const listaAlertas = document.getElementById('lista-alertas');
    const mensajeSinAlertas = document.getElementById('mensaje-sin-alertas');

    function renderizarResumen(data) {
        valorReservas.textContent = data.totalReservas;
        valorDisponibles.textContent = data.cabinasDisponibles;
        valorOcupadas.textContent = data.cabinasOcupadas;
        valorFrecuentes.textContent = data.clientesFrecuentes.length;
    }

    function renderizarAlertas(data) {
        const alertas = [];

        data.cabinasMantenimiento.forEach(nombre => {
            alertas.push({
                icono: 'bi-tools',
                texto: `${nombre} está en mantenimiento y no puede reservarse.`
            });
        });

        data.cabinasInactivas.forEach(nombre => {
            alertas.push({
                icono: 'bi-slash-circle',
                texto: `${nombre} está inactiva y no puede reservarse.`
            });
        });

        if (data.cabinasDisponibles === 0) {
            alertas.push({
                icono: 'bi-exclamation-triangle-fill',
                texto: 'No hay cabinas disponibles en este momento.'
            });
        } else {
            alertas.push({
                icono: 'bi-door-open-fill',
                texto: `Hay ${data.cabinasDisponibles} cabina(s) disponible(s) para nuevas reservas.`
            });
        }

        data.clientesFrecuentes.forEach(cliente => {
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

    function cargarResumen() {
        fetch(`${API_URL}?action=resumen`)
            .then(response => response.json())
            .then(data => {
                if (data.response !== '00') {
                    throw new Error(data.message || 'No se pudo cargar el resumen');
                }

                renderizarResumen(data);
                renderizarAlertas(data);
            })
            .catch(() => {
                listaAlertas.innerHTML = '';
                mensajeSinAlertas.textContent = 'Error al cargar el resumen del panel. Intenta nuevamente.';
                mensajeSinAlertas.style.display = 'block';
            });
    }

    cargarResumen();
});