<?php
$pageTitle    = 'Reportes | Sistema de Administración de Cabinas';
$paginaActiva = 'reportes';
$cssModulo    = ['dashboard.css', 'reportes.css'];
require __DIR__ . '/../layout/header.php';
?>
    <div class="encabezado-pagina">
        <h1>Módulo de Reportes</h1>
        <p>
            Análisis detallado de ingresos, niveles de ocupación y rendimiento del negocio.
        </p>
    </div>

    <main>
        <div id="reportes">

            <!-- Resumen Rápido -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="tarjeta-resumen report-card">
                        <i class="bi bi-cash-stack icono-resumen"></i>
                        <h3 id="resumen-ingresos">₡0</h3>
                        <p>Ingresos Totales (Mes Actual)</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tarjeta-resumen report-card">
                        <i class="bi bi-percent icono-resumen"></i>
                        <h3 id="resumen-ocupacion">0%</h3>
                        <p>Ocupación Promedio</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tarjeta-resumen report-card">
                        <i class="bi bi-people-fill icono-resumen"></i>
                        <h3 id="resumen-huespedes">0</h3>
                        <p>Huéspedes Atendidos</p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Reporte de Ingresos -->
                <div class="col-12 col-lg-7">
                    <section id="reporte-ingresos">
                        <h2>Detalle de Ingresos por Mes</h2>
                        <div class="tabla-contenedor">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Mes</th>
                                        <th>Reservas</th>
                                        <th>Subtotal</th>
                                        <th>Descuentos</th>
                                        <th>Total Neto</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpo-tabla-ingresos">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Cargando reporte de ingresos...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

                <!-- Reporte de Ocupación por Cabina -->
                <div class="col-12 col-lg-5">
                    <section id="reporte-ocupacion">
                        <h2>Ocupación por Cabina</h2>

                        <div id="contenedor-ocupacion-cabinas">
                            <div class="mb-3 text-muted">Cargando ocupación por cabina...</div>
                        </div>

                        <div class="mt-4 p-3 bg-light rounded border">
                            <h5 class="fs-6 fw-bold"><i class="bi bi-info-circle"></i> Análisis de Datos</h5>
                            <p id="analisis-datos" class="small mb-0">Generando análisis...</p>
                        </div>
                    </section>
                </div>
            </div>

        </div>
    </main>

    

<?php
$footerModulo = 'Módulo de Reportes';
$jsModulo     = 'reportes.js';
require __DIR__ . '/../layout/footer.php';
