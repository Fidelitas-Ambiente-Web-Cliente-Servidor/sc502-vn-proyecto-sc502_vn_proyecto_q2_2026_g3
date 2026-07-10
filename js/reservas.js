document.addEventListener('DOMContentLoaded', function() {
    const formReserva = document.getElementById('form-reserva');
    const mensajeExito = document.getElementById('mensaje-exito-reserva');

    if (formReserva) {
        formReserva.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simulación de guardado
            mensajeExito.classList.remove('exito-oculto');
            mensajeExito.classList.add('exito-visible');
            
            // Limpiar formulario después de un momento
            setTimeout(() => {
                formReserva.reset();
                mensajeExito.classList.remove('exito-visible');
                mensajeExito.classList.add('exito-oculto');
            }, 3000);
        });
    }
});
