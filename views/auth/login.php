<?php
$pageTitle      = 'Inicio de Sesión';
$cssAuth        = 'index.css';
$authTituloHtml = 'Sistema de ';
$authSubtitulo  = 'Administración de Cabinas';
$authRowWrapper = true;
require __DIR__ . '/../layout/header_auth.php';
?>
    <!-- ==========================================================
                            CONTENIDO
    =========================================================== -->

    <main class="container py-5">

        <div class="row g-5 align-items-center">

            <!-- ==========================================
                    PANEL IZQUIERDO
            =========================================== -->

            <div class="col-lg-6">

                <div class="card shadow border-0 tarjeta-informacion">

                    <img
                        src="img/loginCabina.png"
                        alt="Cabina"
                        class="card-img-top imagen-cabina">

                    <div class="card-body p-4">

                        <h2 class="mb-3">

                            Administre su hospedaje
                            de forma inteligente

                        </h2>

                        <p>

                            Centraliza la gestión
                            de reservas, clientes y disponibilidad
                            para pequeños hospedajes y cabinas
                            turísticas.

                        </p>

                        <hr>

                        <div class="beneficio">

                            <i class="bi bi-shield-check"></i>

                            Seguridad mediante autenticación
                            en dos pasos.

                        </div>

                        <div class="beneficio">

                            <i class="bi bi-calendar-check"></i>

                            Gestión centralizada de reservas.

                        </div>

                        <div class="beneficio">

                            <i class="bi bi-phone"></i>

                            Compatible con computadoras,
                            tablets y teléfonos móviles.

                        </div>

                    </div>

                </div>

            </div>

            <!-- ==========================================
                    LOGIN
            =========================================== -->

            <div class="col-lg-6">

                <div class="card shadow border-0 tarjeta-login">

                    <div class="card-body p-5">

                        <h2 class="text-center">

                            Iniciar Sesión

                        </h2>

                        <p
                            class="text-center text-muted mb-4"
                            id="mensajeBienvenida">

                            Bienvenido.

                        </p>

                        <form
                            id="formularioLogin">

                            <!-- Correo -->

                            <div class="mb-4">

                                <label class="form-label">

                                    Correo electrónico

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="bi bi-envelope"></i>

                                    </span>

                                    <input
                                        type="email"
                                        id="correoElectronico"
                                        class="form-control"
                                        placeholder="correo@ejemplo.com"
                                        required>

                                </div>

                            </div>

                            <!-- Contraseña -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Contraseña

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="bi bi-lock"></i>

                                    </span>

                                    <input
                                        type="password"
                                        id="contrasena"
                                        class="form-control"
                                        required>

                                    <button
                                        type="button"
                                        id="botonMostrarContrasena"
                                        class="btn btn-outline-secondary">

                                        <i class="bi bi-eye"></i>

                                    </button>

                                </div>

                            </div>
                    
                            <!-- Barra de seguridad -->

                            <div class="mb-4">

                                <div class="progress">

                                    <div
                                        id="barraSeguridad"
                                        class="progress-bar"
                                        role="progressbar"
                                        style="width:0%;">

                                    </div>

                                </div>

                                <small
                                    id="textoSeguridad"
                                    class="text-muted">

                                    Escriba una contraseña.

                                </small>

                            </div>

                            <!-- Recordarme -->

                            <div class="d-flex justify-content-between align-items-center mb-4">

                                <div class="form-check">

                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        id="recordarme">

                                    <label
                                        class="form-check-label"
                                        for="recordarme">

                                        Recordarme

                                    </label>

                                </div>

                                <a
                                    href="recuperar.php"
                                    class="link-recuperar">

                                    ¿Olvidó su contraseña?

                                </a>

                            </div>

                            <!-- Botón -->

                            <div class="d-grid">

                                <button
                                    type="submit"
                                    class="btn btn-primary btn-lg">

                                    <i class="bi bi-box-arrow-in-right me-2"></i>

                                    Acceder al sistema

                                </button>

                            </div>

                        </form>

                        <!-- Tarjeta de seguridad -->

                        <div class="alert alert-primary mt-4 mb-0">

                            <div class="d-flex">

                                <i class="bi bi-shield-lock-fill me-3"></i>

                                <div>

                                    <strong>

                                        Acceso protegido

                                    </strong>

                                    <br>

                                    Este sistema utiliza autenticación
                                    en dos pasos (2FA) para
                                    reforzar la seguridad del
                                    inicio de sesión.

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>

    <!-- ==========================================
                    PIE DE PÁGINA
    =========================================== -->

    

<footer class="pie-pagina">

        <div class="container">

            <p class="mb-0">

                © 2026 Sistema de Administración de Cabinas

            </p>

        </div>

    </footer>

    <!-- ==========================================
                MODAL AUTENTICACIÓN 2FA
         =========================================== -->

        <div class="modal fade" id="modalDosPasos" tabindex="-1">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">

                        <h5 class="modal-title">

                            <i class="bi bi-shield-lock-fill me-2"></i>

                            Verificación en dos pasos

                        </h5>

                    </div>

                    <div class="modal-body">

                        <p>

                            Se ha enviado un código de verificación
                            al correo electrónico registrado.

                        </p>

                        <div class="alert alert-info">

                            <strong>Simulación:</strong>

                            El código aparece en la consola del navegador (F12).

                        </div>

                        <input
                            type="text"
                            id="codigoDosPasos"
                            class="form-control"
                            maxlength="6"
                            placeholder="Ingrese el código">

                    </div>

                    <div class="modal-footer">

                        <button
                            class="btn btn-primary"
                            id="botonVerificarCodigo">

                            Verificar

                        </button>

                    </div>

                </div>

            </div>

        </div>

        

    

<?php
$jsAuth = 'index.js';
require __DIR__ . '/../layout/footer_auth.php';
