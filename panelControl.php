<?php
session_start();

// Validación
if (!empty($_SESSION)) {
    if ($_SESSION["rol"] == "admin") {
        // Acceso permitido
    } else {
        session_destroy();
        echo "Acceso denegado";
        exit();
    }
} else {
    echo "Eres invitado";
    exit();
}

const saltoLinea = "<br>";
$esTemporadaAlta = true; 

if ($esTemporadaAlta) {
    $prediccionTitulo = "Temporada Alta Detectada";
    $prediccionMensaje = "Alta demanda turística en Costa Rica. Se sugiere un incremento del 15% en las tarifas base de las cabinas.";
    $prediccionAlertaClase = "alert-success";
    $badgeTexto = "Tarifa Sugerida: Alta";
} else {
    $prediccionTitulo = "Temporada Baja Detectada";
    $prediccionMensaje = "Época de visitación moderada o lluviosa. Se sugiere mantener tarifas base u ofrecer promociones de 3x2.";
    $prediccionAlertaClase = "alert-warning";
    $badgeTexto = "Tarifa Sugerida: Base / Oferta";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/bootstrap.bundle.min.js"></script>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg bg-light px-3">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">LOGO</a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="panelControl.php">Panel de Control</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../cabinas.html">Cabinas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="disponibilidad.php">Disponibilidad</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../clientes.html">Clientes</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container my-5">
        <div id="principal">
            <h2 class="mb-4">Panel de Control Centralizado</h2>
            <p>Bienvenido al sistema, <?php echo $_SESSION["usuario"]; ?></p>
            
            <div class="row text-center mb-5">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="p-4 bg-white" style="border: 2px solid orange; border-radius: 10px;">
                        <h3>Reservas Activas</h3>
                        <p class="display-6 text-warning fw-bold">12</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="p-4 bg-white" style="border: 2px solid green; border-radius: 10px;">
                        <h3>Cabinas Disponibles</h3>
                        <p class="display-6 text-success fw-bold">5</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="p-4 bg-white" style="border: 2px solid red; border-radius: 10px;">
                        <h3>Próximas Alertas</h3>
                        <p class="display-6 text-danger fw-bold">2</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <section class="p-4 rounded" style="background-color: #f8f9fa; border: 1px solid #ddd;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Módulo de Predicción de Ocupación Automática</h4>
                            <span class="badge bg-dark p-2"><?php echo $badgeTexto; ?></span>
                        </div>
                        <p>Lógica predictiva basada en temporadas para evitar el uso fragmentado de herramientas.</p>
                        
                        <div class="alert <?php echo $prediccionAlertaClase; ?> d-block p-3">
                            <h5 class="fw-bold"><?php echo $prediccionTitulo; ?></h5>
                            <p class="mb-0"><?php echo $prediccionMensaje; ?></p>
                        </div>
                    </section>
                </div>
            </div>

        </div>
    </main>

    <footer class="text-center py-4 bg-light mt-5">
        <p class="m-0">Sistema de Administración de Cabinas © 2026</p>
    </footer>

</body>
</html>
