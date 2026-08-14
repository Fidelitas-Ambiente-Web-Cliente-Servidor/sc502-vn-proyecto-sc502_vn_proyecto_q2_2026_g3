<?php
$pageTitle    = 'Reservas | Sistema de Administración de Cabinas';
$paginaActiva = 'reservas';
$cssModulo    = 'reservas.css';
require __DIR__ . '/../layout/header.php';
?>
    <div class="encabezado-pagina">
        <h1>Módulo de Reservas</h1>
        <p>
            Gestione las estadías de sus clientes. Cree nuevas reservas, asigne cabinas disponibles y controle las fechas de entrada y salida.
        </p>
    </div>

    <main>
        <div id="reservas">

            <div class="row g-4">
                <div class="col-12 col-lg-4">
                    <section id="formulario-reserva">
                        <h2>Nueva Reserva</h2>

                        <form id="form-reserva">
                            <div class="formulario-grupo">
                                <label for="reserva-cliente">Cliente</label>
                                <select id="reserva-cliente" required>
                                    <option value="">Seleccione un cliente...</option>
                                    <option value="1">Ana Rodríguez (123456789)</option>
                                    <option value="2">Juan Pérez (987654321)</option>
                                    <option value="3">María López (456789123)</option>
                                </select>
                            </div>

                            <div class="formulario-grupo">
                                <label for="reserva-cabina">Cabina</label>
                                <select id="reserva-cabina" required>
                                    <option value="">Seleccione una cabina...</option>
                                    <option value="101">Cabina 01 - Familiar (₡45,000)</option>
                                    <option value="102">Cabina 02 - Matrimonial (₡35,000)</option>
                                    <option value="103">Cabina 03 - Grupal (₡60,000)</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="formulario-grupo">
                                        <label for="reserva-entrada">Fecha Entrada</label>
                                        <input type="date" id="reserva-entrada" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="formulario-grupo">
                                        <label for="reserva-salida">Fecha Salida</label>
                                        <input type="date" id="reserva-salida" required />
                                    </div>
                                </div>
                            </div>

                            <div class="formulario-grupo">
                                <label for="reserva-huespedes">Cantidad de Huéspedes</label>
                                <input type="number" id="reserva-huespedes" min="1" max="10" placeholder="Ej: 2" required />
                            </div>

                            <div class="formulario-acciones">
                                <button type="submit" id="btn-guardar-reserva">Confirmar Reserva</button>
                            </div>

                            <div id="mensaje-exito-reserva" class="exito-oculto">
                                ¡Reserva creada exitosamente!
                            </div>
                        </form>
                    </section>
                </div>

                <div class="col-12 col-lg-8">
                    <section id="listado-reservas">
                        <h2>Reservas Activas / Próximas</h2>

                        <div class="tabla-contenedor">
                            <table id="tabla-reservas">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Cabina</th>
                                        <th>Entrada</th>
                                        <th>Salida</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpo-tabla-reservas">
                                    <tr>
                                        <td>#001</td>
                                        <td>Ana Rodríguez</td>
                                        <td>Cabina 01</td>
                                        <td>2026-07-15</td>
                                        <td>2026-07-18</td>
                                        <td><span class="badge bg-warning text-dark">Pendiente Pago</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#002</td>
                                        <td>Juan Pérez</td>
                                        <td>Cabina 02</td>
                                        <td>2026-07-10</td>
                                        <td>2026-07-12</td>
                                        <td><span class="badge bg-success">Confirmada</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>

        </div>
    </main>

    

<?php
$footerModulo = 'Módulo de Reservas';
$jsModulo     = 'reservas.js';
require __DIR__ . '/../layout/footer.php';
