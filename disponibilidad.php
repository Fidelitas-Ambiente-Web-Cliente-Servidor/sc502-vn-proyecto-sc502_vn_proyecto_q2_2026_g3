<?php
session_start();

// Validación
if (!empty($_SESSION)) {
    if ($_SESSION["rol"] == "admin") {
        // Permitido
    } else {
        session_destroy();
        echo "Acceso denegado";
        exit();
    }
} else {
    echo "Eres invitado";
    exit();
}

$cabinasEstados = [
    [
        "nombre" => "Cabina 01 (Matrimonial)",
        "dias" => ["disponible", "disponible", "ocupada", "ocupada", "disponible", "mantenimiento", "mantenimiento"]
    ],
    [
        "nombre" => "Cabina 02 (Familiar)",
        "dias" => ["ocupada", "ocupada", "ocupada", "disponible", "disponible", "disponible", "disponible"]
    ],
    [
        "nombre" => "Cabina 03 (Estudio)",
        "dias" => ["disponible", "disponible", "disponible", "disponible", "disponible", "disponible", "disponible"]
    ]
];

$diasSemana = ["Lunes 06", "Martes 07", "Miércoles 08", "Jueves 09", "Viernes 10", "Sábado 11", "Domingo 12"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Disponibilidad</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/bootstrap.bundle.min.js"></script>
    <style>
        .celda-disponible { background-color: #d1e7dd !important; color: #0f5132; text-align: center; }
        .celda-ocupada { background-color: #f8d7da !important; color: #842029; text-align: center; }
        .celda-mantenimiento { background-color: #fff3cd !important; color: #664d03; text-align: center; }
    </style>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg bg-light px-3">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">LOGO</a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="panelControl.php">Panel de Control</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../cabinas.html">Cabinas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="disponibilidad.php">Disponibilidad</a>
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
        <section id="principal">
            <h2 class="mb-4">Calendario Semanal de Ocupación</h2>
            
            <div class="mb-4">
                <span class="badge p-2 bg-success text-dark">■ Libre</span>
                <span class="badge p-2 bg-danger text-dark">■ Ocupada</span>
                <span class="badge p-2 bg-warning text-dark">■ Taller</span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle m-0">
                    <thead>
                        <tr>
                            <th>Cabina / Habitación</th>
                            <?php foreach ($diasSemana as $dia) { ?>
                                <th class="text-center"><?php echo $dia; ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaEstudiantes = $cabinasEstados as $cabina) { ?>
                            <tr>
                                <td class="fw-bold"><?php echo $cabina["nombre"]; ?></td>
                                <?php foreach ($cabina["dias"] as $estado) { 
                                    $claseCelda = "";
                                    $textoCelda = "";
                                    
                                    if ($estado == "disponible") {
                                        $claseCelda = "celda-disponible";
                                        $textoCelda = "Libre";
                                    } elseif ($estado == "ocupada") {
                                        $claseCelda = "celda-ocupada";
                                        $textoCelda = "Ocupada";
                                    } elseif ($estado == "mantenimiento") {
                                        $claseCelda = "celda-mantenimiento";
                                        $textoCelda = "Taller";
                                    }
                                ?>
                                    <td class="<?php echo $claseCelda; ?>"><?php echo $textoCelda; ?></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="text-center py-4 bg-light mt-5">
        <p class="m-0">Sistema de Administración de Cabinas © 2026</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let celdasOcupadas = document.querySelectorAll(".celda-ocupada");
            
            celdasOcupadas.forEach(function (element) {
                element.addEventListener("click", function (event) {
                    console.log("Texto celda: " + event.target.innerText);
                });
            });
        });
    </script>

</body>
</html>
