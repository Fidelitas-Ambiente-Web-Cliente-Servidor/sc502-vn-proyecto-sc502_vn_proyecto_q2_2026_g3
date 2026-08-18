<?php
$pageTitle    = 'Disponibilidad | Sistema de Administración de Cabinas';
$paginaActiva = 'disponibilidad';
$cssModulo    = 'disponibilidad.css';
require __DIR__ . '/../layout/header.php';
?>
 
  <div class="encabezado-pagina">
    <h1>Disponibilidad de Cabinas</h1>
    <p>
      Consulta de un vistazo qué cabinas están libres, ocupadas o en
      mantenimiento durante la semana.
    </p>
  </div>
 
  <main>
    <div id="disponibilidad">
      <section id="panel-calendario">
 
        <div class="calendario-header">
          <div>
            <h2>Calendario semanal</h2>
            <span id="texto-rango-semana"></span>
          </div>
 
          <div class="navegacion-semana">
            <button type="button" id="btn-semana-anterior">
              <i class="bi bi-chevron-left"></i> Anterior
            </button>
            <button type="button" id="btn-semana-actual">Hoy</button>
            <button type="button" id="btn-semana-siguiente">
              Siguiente <i class="bi bi-chevron-right"></i>
            </button>
          </div>
        </div>
 
        <div class="tabla-contenedor">
          <table id="tabla-disponibilidad">
            <thead>
              <tr id="encabezado-dias">
              </tr>
            </thead>
            <tbody id="cuerpo-tabla-disponibilidad">
            </tbody>
          </table>
        </div>
 
        <div class="leyenda-disponibilidad">
          <span class="leyenda-item">
            <span class="leyenda-color" style="background:#d4edda;"></span>
            Disponible
          </span>
          <span class="leyenda-item">
            <span class="leyenda-color" style="background:#f8d7da;"></span>
            Ocupada
          </span>
          <span class="leyenda-item">
            <span class="leyenda-color" style="background:#fff3cd;"></span>
            En mantenimiento
          </span>
        </div>
 
      </section>
    </div>
  </main>
 
  

<?php
$footerModulo = 'Módulo de Disponibilidad';
$jsModulo     = 'disponibilidad.js';
require __DIR__ . '/../layout/footer.php';
