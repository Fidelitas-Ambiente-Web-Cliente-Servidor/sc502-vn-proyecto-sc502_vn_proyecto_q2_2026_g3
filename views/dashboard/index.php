<?php
$pageTitle    = 'Dashboard | Sistema de Administración de Cabinas';
$paginaActiva = 'dashboard';
$cssModulo    = 'dashboard.css';
require __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid mt-4">
    <h2 class="mb-4">Dashboard</h2>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Cabinas Totales</h6>
                    <p class="card-text fs-3 fw-bold">12</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Reservas Activas</h6>
                    <p class="card-text fs-3 fw-bold">7</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Cabinas Ocupadas</h6>
                    <p class="card-text fs-3 fw-bold">5</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-<?php echo $prediccion['color']; ?> shadow-sm">
                <div class="card-header bg-<?php echo $prediccion['color']; ?> text-white">
                    <i class="bi <?php echo $prediccion['icono']; ?>"></i>
                    Predicción de Tarifas
                </div>
                <div class="card-body">
                    <p class="card-text"><?php echo htmlspecialchars($prediccion['mensaje']); ?></p>
                    <span class="badge bg-<?php echo $prediccion['color']; ?>">
                        Temporada <?php echo ucfirst($prediccion['tipo']); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$footerModulo = 'Módulo de Dashboard';
$jsModulo     = 'dashboard.js';
require __DIR__ . '/../layout/footer.php';
?>
