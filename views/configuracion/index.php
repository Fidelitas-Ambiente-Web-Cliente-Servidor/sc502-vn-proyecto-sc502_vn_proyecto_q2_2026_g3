<?php
$pageTitle      = 'Configuración | Sistema de Administración de Cabinas';
$cssAuth        = 'configuracion.css';
$authTituloHtml = 'Sistema de Administración de Cabinas';
$authSubtitulo  = 'Plataforma para la gestión de reservas, usuarios y hospedajes.';
$authRowWrapper = False;
require __DIR__ . '/../layout/header_auth.php';
?>
<!-- ====================================================== -->

<main class="container py-5">

    <div class="mb-4">

            <a href="dashboard.php" class="btn btn-outline-primary">

                <i class="bi bi-arrow-left-circle-fill"></i>

                Panel Principal

            </a>

        </div>

    <div class="row">

        <div class="col">

            <div class="card shadow border-0 mb-4">

                <div class="card-body">

                    <h2>

                        <i class="bi bi-gear-fill"></i>

                        Configuración del Sistema

                    </h2>

                    <p class="mb-0">

                        Desde este módulo puede administrar la información
                        del usuario, la seguridad del sistema y los datos
                        generales del hospedaje.

                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- ============================================ -->

    <ul class="nav nav-tabs mb-4" id="tabsConfiguracion" role="tablist">

        <li class="nav-item">

            <button
                class="nav-link active"

                data-bs-toggle="tab"

                data-bs-target="#usuarios"

                type="button">

                <i class="bi bi-people-fill"></i>

                Usuarios

            </button>

        </li>

        <li class="nav-item">

            <button
                class="nav-link"

                data-bs-toggle="tab"

                data-bs-target="#seguridad"

                type="button">

                <i class="bi bi-shield-lock-fill"></i>

                Seguridad

            </button>

        </li>

        <li class="nav-item">

            <button
                class="nav-link"

                data-bs-toggle="tab"

                data-bs-target="#hospedaje"

                type="button">

                <i class="bi bi-building"></i>

                Hospedaje

            </button>

        </li>

    </ul>

    <!-- ============================================ -->

    <div class="tab-content">

        <!-- ========================================================= -->

        <!-- USUARIOS -->

        <!-- ========================================================= -->

        <div
            class="tab-pane fade show active"
            id="usuarios">

            <div class="card shadow border-0">

                <div class="card-body">

                    <h4 class="mb-4">

                        Gestión de Usuarios

                    </h4>

                    <form id="formularioUsuarios">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Nombre completo

                                </label>

                                <input
                                    type="text"
                                    id="usuarioNombre"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Correo electrónico

                                </label>

                                <input
                                    type="email"
                                    class="form-control"
                                    id="usuarioEmail"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Rol

                                </label>

                                id: <select class="form-select" id="usuarioRol">

                                    <option>Administrador</option>

                                    <option>Operador</option>

                                    <option>Consulta</option>

                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Estado

                                </label>

                                 <select class="form-select"
                                 
                                    id="usuarioEstado">

                                    <option>Activo</option>

                                    <option>Inactivo</option>

                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Contraseña

                                </label>

                                <input
                                    type="password"
                                    id="usuarioPassword"
                                    class="form-control"
                                    required>

                        </div>

                </div>

                        <div class="text-end">

                            <button
                                class="btn btn-primary">

                                <i class="bi bi-floppy-fill"></i>

                                Guardar usuario

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

            <!-- ========================================================= -->

            <!-- SEGURIDAD -->

            <!-- ========================================================= -->

            <div
                class="tab-pane fade"
                id="seguridad">

                <div class="card shadow border-0">

                    <div class="card-body">

                        <h4 class="mb-4">

                            Seguridad del Sistema

                        </h4>

                        <form id="formularioSeguridad">

                            <div class="mb-3">

                                <label class="form-label">

                                    Contraseña actual

                                </label>

                                <input
                                    type="password"
                                    class="form-control"
                                    id="contrasenaActual">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Nueva contraseña

                                </label>

                                <input
                                    type="password"
                                    class="form-control"
                                    id="nuevaContrasena">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Confirmar contraseña

                                </label>

                                <input
                                    type="password"
                                    class="form-control"
                                    id="confirmarContrasena">

                            </div>

                            <div class="mb-3">

                                <div class="progress">

                                    <div
                                        id="barraSeguridad"
                                        class="progress-bar"
                                        style="width:0%;">

                                    </div>

                                </div>

                                <small
                                    id="textoSeguridad"
                                    class="text-muted">

                                    Escriba una contraseña segura.

                                </small>

                            </div>

                            <div class="text-end">

                                <button
                                    class="btn btn-primary"
                                    type="submit">

                                    <i class="bi bi-shield-lock-fill"></i>

                                    Cambiar contraseña

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

            <!-- ========================================================= -->

            <!-- HOSPEDAJE -->

            <!-- ========================================================= -->

            <div
                class="tab-pane fade"
                id="hospedaje">

                <div class="card shadow border-0">

                    <div class="card-body">

                        <h4 class="mb-4">

                            Datos del Hospedaje

                        </h4>

                        <form id="formularioHospedaje">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Nombre del hospedaje

                                    </label>

                                    <input
                                        type="text"
                                        id="hospedajeNombre"
                                        class="form-control">

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Provincia

                                    </label>

                                    <select class="form-select"
                                        id="hospedajeProvincia">

                                        <option>San José</option>
                                        <option>Alajuela</option>
                                        <option>Cartago</option>
                                        <option>Heredia</option>
                                        <option>Guanacaste</option>
                                        <option>Puntarenas</option>
                                        <option>Limón</option>

                                    </select>

                                </div>

                                <div class="col-12 mb-3">

                                    <label class="form-label">

                                        Dirección

                                    </label>

                                    <textarea class="form-control"
                                        id="hospedajeDireccion"
                                        rows="3">
                                    </textarea>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Teléfono

                                    </label>

                                    <input
                                        type="tel"
                                        id="hospedajeTelefono"
                                        class="form-control">

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Correo electrónico

                                    </label>

                                    <input
                                        type="email"
                                        id="hospedajeEmail" 
                                        class="form-control">

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Hora de entrada

                                    </label>

                                    <input
                                        type="time"
                                        id="hospedajeHoraEntrada"
                                        class="form-control">

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Hora de salida

                                    </label>

                                    <input
                                        type="time"
                                        id="hospedajeHoraSalida"
                                        class="form-control">

                                </div>

                            </div>

                            <div class="text-end">

                                <button
                                    class="btn btn-primary"
                                    type="submit">

                                    <i class="bi bi-floppy-fill"></i>

                                    Guardar información

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </main>

    

<footer class="pie-pagina">

        <div class="container">

            © 2026 Sistema de Administración de Cabinas

        </div>

    </footer>

<?php
$jsAuth = 'configuracion.js';
require __DIR__ . '/../layout/footer_auth.php';
