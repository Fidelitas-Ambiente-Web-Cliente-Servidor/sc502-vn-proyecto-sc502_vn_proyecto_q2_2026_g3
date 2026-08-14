<?php
$pageTitle    = 'Dashboard | Sistema de Administración de Cabinas';
$paginaActiva = 'dashboard';
$cssModulo    = 'dashboard.css';
require __DIR__ . '/../layout/header.php';
?>
 
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
 
  

<?php
$footerModulo = 'Módulo de Dashboard';
$jsModulo     = 'dashboard.js';
require __DIR__ . '/../layout/footer.php';
