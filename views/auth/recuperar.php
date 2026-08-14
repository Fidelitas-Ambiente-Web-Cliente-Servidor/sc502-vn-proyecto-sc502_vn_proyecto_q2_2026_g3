<?php
$pageTitle      = 'Recuperar contraseña | Sistema de Administración de Cabinas';
$cssAuth        = 'index.css';
$authTituloHtml = 'Sistema de Administración de Cabinas';
$authSubtitulo  = 'Sistema de Administración de Cabinas';
$authRowWrapper = false;
require __DIR__ . '/../layout/header_auth.php';
?>
<main class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card shadow border-0 tarjeta-login">

                <div class="card-body p-5">

                    <h2 class="text-center mb-3">

                        Recuperar contraseña

                    </h2>

                    <p class="text-center text-muted mb-4">

                        Ingrese el correo asociado a su cuenta.

                    </p>

                    <form id="formularioRecuperar">

                        <div class="mb-4">

                            <label class="form-label">

                                Correo electrónico

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-envelope"></i>

                                </span>

                                <input
                                    id="correoRecuperacion"
                                    type="email"
                                    class="form-control"
                                    required>

                            </div>

                        </div>

                        <div class="d-grid">

                            <button
                                class="btn btn-primary btn-lg"
                                type="submit">

                                <i class="bi bi-send-fill me-2"></i>

                                Enviar enlace

                            </button>

                        </div>

                    </form>

                    <div
                        id="mensajeRecuperacion"
                        class="alert alert-success mt-4 d-none">

                        <i class="bi bi-check-circle-fill me-2"></i>

                        Se ha enviado un enlace de recuperación al correo indicado.

                        <br><br>

                        <small>

                            (Simulación para efectos académicos)

                        </small>

                    </div>

                    <div class="text-center mt-4">

                        <a href="index.php">

                            <i class="bi bi-arrow-left"></i>

                            Volver al inicio de sesión

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</main>

<footer class="pie-pagina">

    © 2026 Sistema de Administración de Cabinas

</footer>

<script>

const formularioRecuperar = document.getElementById("formularioRecuperar");

const mensajeRecuperacion = document.getElementById("mensajeRecuperacion");

formularioRecuperar.addEventListener("submit", function(evento){

    evento.preventDefault();

    mensajeRecuperacion.classList.remove("d-none");

    formularioRecuperar.reset();

});

</script>

<?php
require __DIR__ . '/../layout/footer_auth.php';
