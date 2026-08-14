<?php
$pageTitle    = 'Pagos | Sistema de Administración de Cabinas';
$paginaActiva = 'pagos';
$cssModulo    = 'pagos.css';
require __DIR__ . '/../layout/header.php';
?>
    <div class="encabezado-pagina">
        <h1>Módulo de Pagos</h1>
        <p>
            Registre los pagos de las reservas, suba los comprobantes de SINPE Móvil o transferencia y valide la información automáticamente con nuestra herramienta de IA.
        </p>
    </div>

    <main>
        <div id="pagos">

            <div class="row g-4">
                <div class="col-12 col-lg-5">
                    <section id="formulario-pago">
                        <h2>Registrar Pago</h2>

                        <form id="form-pago">
                            <div class="formulario-grupo">
                                <label for="pago-reserva">Seleccionar Reserva</label>
                                <select id="pago-reserva" required>
                                    <option value="">Seleccione una reserva...</option>
                                    <option value="001">#001 - Ana Rodríguez (Pendiente: ₡135,000)</option>
                                    <option value="003">#003 - Roberto Chaves (Pendiente: ₡45,000)</option>
                                </select>
                            </div>

                            <div class="formulario-grupo">
                                <label for="pago-monto">Monto Pagado (₡)</label>
                                <input type="number" id="pago-monto" placeholder="Ej: 45000" required />
                            </div>

                            <div class="formulario-grupo">
                                <label for="pago-metodo">Método de Pago</label>
                                <select id="pago-metodo" required>
                                    <option value="sinpe">SINPE Móvil</option>
                                    <option value="transferencia">Transferencia Bancaria</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta">Tarjeta</option>
                                </select>
                            </div>

                            <div class="formulario-grupo">
                                <label for="pago-comprobante">Foto del Comprobante</label>
                                <input type="file" id="pago-comprobante" accept="image/*" />
                                <small class="text-muted">Suba una captura de pantalla del SINPE o transferencia.</small>
                            </div>

                            <div id="previsualizacion-contenedor" class="mt-3 d-none">
                                <p class="fw-bold mb-2">Vista previa:</p>
                                <img id="img-previsualizacion" src="" alt="Comprobante" class="img-fluid rounded border mb-3" style="max-height: 200px;">
                                
                                <!-- Plus de Innovación: Botón IA -->
                                <div class="d-grid">
                                    <button type="button" id="btn-ia-validar" class="btn btn-info text-white fw-bold">
                                        <i class="bi bi-cpu-fill"></i> Validación de Comprobante por IA
                                    </button>
                                </div>
                                
                                <div id="ia-status" class="mt-2 d-none">
                                    <div class="d-flex align-items-center text-primary">
                                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                        <span>Analizando imagen con OCR...</span>
                                    </div>
                                </div>
                                
                                <div id="ia-resultado" class="mt-2 alert alert-success d-none">
                                    <i class="bi bi-check-circle-fill"></i> <strong>Validación Exitosa:</strong> Monto (₡45,000) y número de teléfono coinciden con el registro.
                                </div>
                            </div>

                            <div class="formulario-acciones mt-4">
                                <button type="submit" id="btn-guardar-pago">Registrar Pago</button>
                            </div>

                            <div id="mensaje-exito-pago" class="exito-oculto">
                                ¡Pago registrado y comprobante guardado correctamente!
                            </div>
                        </form>
                    </section>
                </div>

                <div class="col-12 col-lg-7">
                    <section id="listado-pagos">
                        <h2>Historial de Pagos Recientes</h2>

                        <div class="tabla-contenedor">
                            <table id="tabla-pagos">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Reserva</th>
                                        <th>Monto</th>
                                        <th>Método</th>
                                        <th>Estado</th>
                                        <th>Comprobante</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpo-tabla-pagos">
                                    <tr>
                                        <td>2026-07-09</td>
                                        <td>#002</td>
                                        <td>₡70,000</td>
                                        <td>SINPE</td>
                                        <td><span class="badge bg-success">Verificado</span></td>
                                        <td><button class="btn btn-sm btn-link"><i class="bi bi-eye"></i> Ver</button></td>
                                    </tr>
                                    <tr>
                                        <td>2026-07-08</td>
                                        <td>#005</td>
                                        <td>₡120,000</td>
                                        <td>Transf.</td>
                                        <td><span class="badge bg-success">Verificado</span></td>
                                        <td><button class="btn btn-sm btn-link"><i class="bi bi-eye"></i> Ver</button></td>
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
$footerModulo = 'Módulo de Pagos';
$jsModulo     = 'pagos.js';
require __DIR__ . '/../layout/footer.php';
