document.addEventListener('DOMContentLoaded', function () {

    const formulario = document.getElementById('form-cabina');
    const inputId = document.getElementById('cabina-id');
    const inputNombre = document.getElementById('cabina-nombre');
    const inputCapacidad = document.getElementById('cabina-capacidad');
    const inputPrecio = document.getElementById('cabina-precio');
    const selectEstado = document.getElementById('cabina-estado');
    const errorNombre = document.getElementById('error-cabina-nombre');
    const errorCapacidad = document.getElementById('error-cabina-capacidad');
    const errorPrecio = document.getElementById('error-cabina-precio');
    const tituloFormulario = document.getElementById('titulo-formulario-cabina');
    const btnGuardar = document.getElementById('btn-guardar-cabina');
    const btnCancelar = document.getElementById('btn-cancelar-cabina');
    const mensajeExito = document.getElementById('mensaje-exito-cabina');
    const cuerpoTabla = document.getElementById('cuerpo-tabla-cabinas');
    const mensajeSinCabinas = document.getElementById('mensaje-sin-cabinas');

    const etiquetasEstado = {
        disponible: 'Disponible',
        ocupada: 'Ocupada',
        mantenimiento: 'En mantenimiento'
    };

    let estadoNombre = false;
    let estadoCapacidad = false;
    let estadoPrecio = false;

    let cabinas = [
        { id: 1, nombre: 'Cabina 01', capacidad: 4, precio: 45000, estado: 'disponible' },
        { id: 2, nombre: 'Cabina 02', capacidad: 6, precio: 60000, estado: 'ocupada' },
        { id: 3, nombre: 'Cabina 03', capacidad: 2, precio: 30000, estado: 'mantenimiento' }
    ];

    let contadorId = 4;

    function renderizarCabinas() {
        cuerpoTabla.innerHTML = '';

        if (cabinas.length === 0) {
            mensajeSinCabinas.style.display = 'block';
            return;
        }

        mensajeSinCabinas.style.display = 'none';

        cabinas.forEach(cabina => {
            const fila = document.createElement('tr');

            fila.innerHTML = `
                <td>${cabina.nombre}</td>
                <td>${cabina.capacidad} personas</td>
                <td>₡${cabina.precio.toLocaleString('es-CR')}</td>
                <td><span class="badge-estado badge-${cabina.estado}">${etiquetasEstado[cabina.estado]}</span></td>
                <td>
                    <div class="acciones-cabina">
                        <button type="button" class="btn-icono btn-editar" data-id="${cabina.id}" title="Editar cabina">✏️</button>
                        <button type="button" class="btn-icono btn-eliminar" data-id="${cabina.id}" title="Eliminar cabina">🗑️</button>
                    </div>
                </td>
            `;

            cuerpoTabla.appendChild(fila);
        });

        document.querySelectorAll('.btn-editar').forEach(boton => {
            boton.addEventListener('click', function () {
                cargarCabinaParaEditar(Number(this.dataset.id));
            });
        });

        document.querySelectorAll('.btn-eliminar').forEach(boton => {
            boton.addEventListener('click', function () {
                eliminarCabina(Number(this.dataset.id));
            });
        });
    }

    function validarNombre() {
        const valor = inputNombre.value.trim();
        if (valor.length < 2) {
            errorNombre.textContent = 'Ingresa un nombre o número válido.';
            estadoNombre = false;
        } else {
            errorNombre.textContent = '';
            estadoNombre = true;
        }
        actualizarBoton();
    }

    function validarCapacidad() {
        const valor = inputCapacidad.value;
        if (!valor || Number(valor) < 1) {
            errorCapacidad.textContent = 'La capacidad debe ser mayor a 0.';
            estadoCapacidad = false;
        } else {
            errorCapacidad.textContent = '';
            estadoCapacidad = true;
        }
        actualizarBoton();
    }

    function validarPrecio() {
        const valor = inputPrecio.value;
        if (!valor || Number(valor) < 0) {
            errorPrecio.textContent = 'Ingresa un precio válido.';
            estadoPrecio = false;
        } else {
            errorPrecio.textContent = '';
            estadoPrecio = true;
        }
        actualizarBoton();
    }

    function actualizarBoton() {
        const formularioValido = estadoNombre && estadoCapacidad && estadoPrecio;

        if (formularioValido) {
            btnGuardar.removeAttribute('disabled');
        } else {
            btnGuardar.setAttribute('disabled', 'true');
        }
    }

    inputNombre.addEventListener('input', validarNombre);
    inputCapacidad.addEventListener('input', validarCapacidad);
    inputPrecio.addEventListener('input', validarPrecio);

    function cargarCabinaParaEditar(id) {
        const cabina = cabinas.find(c => c.id === id);
        if (!cabina) return;

        inputId.value = cabina.id;
        inputNombre.value = cabina.nombre;
        inputCapacidad.value = cabina.capacidad;
        inputPrecio.value = cabina.precio;
        selectEstado.value = cabina.estado;

        tituloFormulario.textContent = 'Editar cabina';
        btnGuardar.textContent = 'Guardar cambios';
        btnCancelar.style.display = 'inline-block';

        estadoNombre = true;
        estadoCapacidad = true;
        estadoPrecio = true;
        errorNombre.textContent = '';
        errorCapacidad.textContent = '';
        errorPrecio.textContent = '';
        actualizarBoton();

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function eliminarCabina(id) {
        cabinas = cabinas.filter(c => c.id !== id);
        renderizarCabinas();
    }

    function reiniciarFormulario() {
        formulario.reset();
        inputId.value = '';
        selectEstado.value = 'disponible';
        tituloFormulario.textContent = 'Registrar cabina';
        btnGuardar.textContent = 'Registrar cabina';
        btnCancelar.style.display = 'none';
        errorNombre.textContent = '';
        errorCapacidad.textContent = '';
        errorPrecio.textContent = '';

        estadoNombre = false;
        estadoCapacidad = false;
        estadoPrecio = false;
        actualizarBoton();
    }

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

        if (!(estadoNombre && estadoCapacidad && estadoPrecio)) return;

        const datosCabina = {
            nombre: inputNombre.value.trim(),
            capacidad: Number(inputCapacidad.value),
            precio: Number(inputPrecio.value),
            estado: selectEstado.value
        };

        if (inputId.value) {
            const idEditar = Number(inputId.value);
            cabinas = cabinas.map(c => c.id === idEditar ? { id: idEditar, ...datosCabina } : c);
            mostrarMensajeExito('Cabina actualizada correctamente.');
        } else {
            cabinas.push({ id: contadorId, ...datosCabina });
            contadorId++;
            mostrarMensajeExito('Cabina registrada correctamente.');
        }

        renderizarCabinas();
        reiniciarFormulario();
    });

    btnCancelar.addEventListener('click', reiniciarFormulario);

    renderizarCabinas();
});