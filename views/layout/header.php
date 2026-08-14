<?php

function navActiva(string $pagina, string $actual): string
{
    return $pagina === $actual ? ' class="active"' : '';
}

$paginaActiva = $paginaActiva ?? '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($pageTitle ?? 'Sistema de Administración de Cabinas') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <link rel="stylesheet" href="css/style.css" />
    <?php foreach ((array) ($cssModulo ?? []) as $hoja): ?>
    <link rel="stylesheet" href="css/<?= htmlspecialchars($hoja) ?>" />
    <?php endforeach; ?>
</head>

<body>

    <header class="navbar">
        <div class="logo">Sistema de Cabinas</div>

        <nav>
            <a href="dashboard.php"<?= navActiva('dashboard', $paginaActiva) ?>>Dashboard</a>
            <a href="cabinas.php"<?= navActiva('cabinas', $paginaActiva) ?>>Cabinas</a>
            <a href="clientes.php"<?= navActiva('clientes', $paginaActiva) ?>>Clientes</a>
            <a href="disponibilidad.php"<?= navActiva('disponibilidad', $paginaActiva) ?>>Disponibilidad</a>
            <a href="reservas.php"<?= navActiva('reservas', $paginaActiva) ?>>Reservas</a>
            <a href="pagos.php"<?= navActiva('pagos', $paginaActiva) ?>>Pagos</a>
            <a href="reportes.php"<?= navActiva('reportes', $paginaActiva) ?>>Reportes</a>
            <a href="configuracion.php"<?= navActiva('configuracion', $paginaActiva) ?>>Configuración</a>
            <a href="index.php">Cerrar sesión</a>
        </nav>
    </header>
