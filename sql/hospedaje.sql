USE cabinas_db;

CREATE TABLE IF NOT EXISTS hospedaje (
  id            INT           NOT NULL,
  nombre        VARCHAR(150)  NULL,
  provincia     VARCHAR(50)   NULL,
  direccion     TEXT          NULL,
  telefono      VARCHAR(30)   NULL,
  email         VARCHAR(100)  NULL,
  hora_entrada  TIME          NULL,
  hora_salida   TIME          NULL,
  actualizado   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO hospedaje (id, nombre, provincia)
VALUES (1, 'Mi Hospedaje', 'San José');