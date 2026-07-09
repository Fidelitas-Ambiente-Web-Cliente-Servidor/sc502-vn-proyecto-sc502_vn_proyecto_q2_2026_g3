<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Disponibilidad | Sistema de Administración de Cabinas</title>
 
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
  />
 
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/disponibilidad.css" />
</head>
<body>
 
  <header class="navbar">
    <div class="logo">Sistema de Cabinas</div>
 
    <nav>
      <a href="dashboard.php">Dashboard</a>
      <a href="cabinas.html">Cabinas</a>
      <a href="clientes.html">Clientes</a>
      <a href="disponibilidad.html" class="active">Disponibilidad</a>
    </nav>
  </header>
 
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
 
  <footer>
    <p>Sistema de Administración de Cabinas © 2026</p>
    <p>Módulo de Disponibilidad</p>
    <p>Creado por grupo 3</p>
  </footer>
 
  <script src="js/disponibilidad.js"></script>
 
</body>
</html>
