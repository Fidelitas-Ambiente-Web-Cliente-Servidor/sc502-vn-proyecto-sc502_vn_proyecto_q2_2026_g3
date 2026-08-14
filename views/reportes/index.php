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
                        <h3>₡1,250,000</h3>
                        <p>Ingresos Totales (Mes Actual)</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tarjeta-resumen report-card">
                        <i class="bi bi-percent icono-resumen"></i>
                        <h3>78%</h3>
                        <p>Ocupación Promedio</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tarjeta-resumen report-card">
                        <i class="bi bi-people-fill icono-resumen"></i>
                        <h3>45</h3>
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
                                <tbody>
                                    <tr>
                                        <td>Julio (Actual)</td>
                                        <td>12</td>
                                        <td>₡1,300,000</td>
                                        <td>₡50,000</td>
                                        <td class="fw-bold text-success">₡1,250,000</td>
                                    </tr>
                                    <tr>
                                        <td>Junio</td>
                                        <td>15</td>
                                        <td>₡1,500,000</td>
                                        <td>₡75,000</td>
                                        <td class="fw-bold">₡1,425,000</td>
                                    </tr>
                                    <tr>
                                        <td>Mayo</td>
                                        <td>10</td>
                                        <td>₡1,000,000</td>
                                        <td>₡20,000</td>
                                        <td class="fw-bold">₡980,000</td>
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
                        
                        <div class="mb-3">
                            <label class="form-label d-flex justify-content-between">
                                <span>Cabina 01 - Familiar</span>
                                <span>90%</span>
                            </label>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 90%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-flex justify-content-between">
                                <span>Cabina 02 - Matrimonial</span>
                                <span>65%</span>
                            </label>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 65%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-flex justify-content-between">
                                <span>Cabina 03 - Grupal</span>
                                <span>80%</span>
                            </label>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 80%"></div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-light rounded border">
                            <h5 class="fs-6 fw-bold"><i class="bi bi-info-circle"></i> Análisis de Datos</h5>
                            <p class="small mb-0">La Cabina 01 presenta la mayor rentabilidad del mes. Se recomienda revisar el mantenimiento de la Cabina 02 para mejorar su atractivo.</p>
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
