<?php
 
$mesActual = (int) date("n"); // 1 (enero) a 12 (diciembre)
$mesesTemporadaAlta = [12, 1, 2, 3, 4, 7];
$temporadaAlta = in_array($mesActual, $mesesTemporadaAlta);
$textoTemporada = $temporadaAlta ? "Temporada alta" : "Temporada baja";
 
// Datos simulados
$totalCabinas = 3;
$cabinasOcupadas = 1;
$porcentajeOcupacion = round(($cabinasOcupadas / $totalCabinas) * 100);
 
$sugerencia = "";
$claseBadge = "";
 
if ($temporadaAlta && $porcentajeOcupacion >= 60) {
    $sugerencia = "Aumentar tarifas entre un 10% y un 15%. La demanda es alta y la ocupación acompaña.";
    $claseBadge = "badge-aumento";
} elseif ($temporadaAlta && $porcentajeOcupacion < 60) {
    $sugerencia = "Mantener tarifas actuales y reforzar publicidad para aprovechar la temporada alta.";
    $claseBadge = "badge-estable";
} elseif (!$temporadaAlta && $porcentajeOcupacion < 40) {
    $sugerencia = "Bajar tarifas entre un 10% y un 20% para atraer clientes durante la temporada baja.";
    $claseBadge = "badge-baja";
} else {
    $sugerencia = "Mantener tarifas actuales, la ocupación es estable para la temporada.";
    $claseBadge = "badge-estable";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard | Sistema de Administración de Cabinas</title>
 
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
  />
 
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/dashboard.css" />
</head>
<body>
 
  <header class="navbar">
    <div class="logo">Sistema de Cabinas</div>
 
    <nav>
      <a href="dashboard.php" class="active">Dashboard</a>
      <a href="cabinas.html">Cabinas</a>
      <a href="clientes.html">Clientes</a>
      <a href="disponibilidad.php">Disponibilidad</a>
    </nav>
  </header>
 
  <div class="encabezado-pagina">
    <h1>Panel Principal</h1>
    <p>
      Resumen general de reservas, disponibilidad de cabinas y alertas
      importantes del sistema.
    </p>
  </div>
 
  <main>
    <div id="dashboard">
 
      <!-- Tarjetas de resumen -->
      <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
          <div class="tarjeta-resumen">
            <i class="bi bi-calendar-check icono-resumen"></i>
            <h3 id="valor-reservas">0</h3>
            <p>Reservas registradas</p>
          </div>
        </div>
 
        <div class="col-6 col-lg-3">
          <div class="tarjeta-resumen">
            <i class="bi bi-door-open icono-resumen"></i>
            <h3 id="valor-disponibles">0</h3>
            <p>Cabinas disponibles</p>
          </div>
        </div>
 
        <div class="col-6 col-lg-3">
          <div class="tarjeta-resumen">
            <i class="bi bi-house-lock icono-resumen"></i>
            <h3 id="valor-ocupadas">0</h3>
            <p>Cabinas ocupadas</p>
          </div>
        </div>
 
        <div class="col-6 col-lg-3">
          <div class="tarjeta-resumen">
            <i class="bi bi-star icono-resumen"></i>
            <h3 id="valor-frecuentes">0</h3>
            <p>Clientes frecuentes</p>
          </div>
        </div>
      </div>
 
      <!-- Alertas y predicción -->
      <div class="row g-4">
        <div class="col-12 col-lg-6">
          <section id="panel-alertas">
            <h2>Próximas alertas</h2>
 
            <div id="lista-alertas"></div>
 
            <p id="mensaje-sin-alertas" class="sin-alertas" style="display:none;">
              No hay alertas por el momento.
            </p>
          </section>
        </div>
 
        <div class="col-12 col-lg-6">
          <section id="panel-prediccion">
            <h2>Predicción de Ocupación</h2>
            <p class="subtitulo-prediccion">Sugerencia automática de tarifas (calculada en PHP)</p>
 
            <div class="dato-prediccion">
              <span>Temporada actual</span>
              <strong><?php echo $textoTemporada; ?></strong>
            </div>
 
            <div class="dato-prediccion">
              <span>Ocupación actual</span>
              <strong><?php echo $porcentajeOcupacion; ?>% (<?php echo $cabinasOcupadas; ?> de <?php echo $totalCabinas; ?> cabinas)</strong>
            </div>
 
            <span class="badge-prediccion <?php echo $claseBadge; ?>">
              <?php echo ($claseBadge === 'badge-aumento') ? 'Sugerencia: aumentar' : (($claseBadge === 'badge-baja') ? 'Sugerencia: bajar' : 'Sugerencia: mantener'); ?>
            </span>
 
            <div class="recomendacion-prediccion">
              <?php echo $sugerencia; ?>
            </div>
          </section>
        </div>
      </div>
 
    </div>
  </main>
 
  <footer>
    <p>Sistema de Administración de Cabinas © 2026</p>
    <p>Módulo de Dashboard</p>
    <p>Creado por grupo 3</p>
  </footer>
 
  <script src="js/dashboard.js"></script>
 
</body>
</html>
