document.addEventListener('DOMContentLoaded', function() {
    const formPago = document.getElementById('form-pago');
    const inputComprobante = document.getElementById('pago-comprobante');
    const prevContenedor = document.getElementById('previsualizacion-contenedor');
    const imgPrev = document.getElementById('img-previsualizacion');
    const btnIA = document.getElementById('btn-ia-validar');
    const iaStatus = document.getElementById('ia-status');
    const iaResultado = document.getElementById('ia-resultado');
    const mensajeExito = document.getElementById('mensaje-exito-pago');

    // Previsualización de imagen
    if (inputComprobante) {
        inputComprobante.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPrev.src = e.target.result;
                    prevContenedor.classList.remove('d-none');
                    iaResultado.classList.add('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Simulación de IA / OCR
    if (btnIA) {
        btnIA.addEventListener('click', function() {
            btnIA.disabled = true;
            iaStatus.classList.remove('d-none');
            iaResultado.classList.add('d-none');

            // Simular tiempo de procesamiento
            setTimeout(() => {
                iaStatus.classList.add('d-none');
                iaResultado.classList.remove('d-none');
                btnIA.disabled = false;
                
                // Autocompletar monto si está vacío (simulación de extracción)
                const montoInput = document.getElementById('pago-monto');
                if (!montoInput.value) {
                    montoInput.value = 45000;
                }
            }, 2500);
        });
    }

    // Registro de pago
    if (formPago) {
        formPago.addEventListener('submit', function(e) {
            e.preventDefault();
            mensajeExito.classList.remove('exito-oculto');
            mensajeExito.classList.add('exito-visible');

            setTimeout(() => {
                formPago.reset();
                prevContenedor.classList.add('d-none');
                mensajeExito.classList.remove('exito-visible');
                mensajeExito.classList.add('exito-oculto');
            }, 3000);
        });
    }
});
