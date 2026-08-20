-- Crear y seleccionar la base de datos
CREATE DATABASE IF NOT EXISTS cabinas_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE cabinas_db;

CREATE TABLE IF NOT EXISTS cabinas (
  id           INT           NOT NULL AUTO_INCREMENT,
  nombre       VARCHAR(100)  NOT NULL,
  capacidad    INT           NOT NULL,
  precio       DECIMAL(10, 2) NOT NULL,
  estado       ENUM('activa', 'inactiva', 'mantenimiento') NOT NULL DEFAULT 'activa',
  created_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS clientes (
  id           INT           NOT NULL AUTO_INCREMENT,
  nombre       VARCHAR(150)  NOT NULL,
  cedula       VARCHAR(20)   NOT NULL UNIQUE,
  telefono     VARCHAR(15)   NOT NULL UNIQUE,
  email        VARCHAR(100)  NOT NULL UNIQUE,
  created_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS historial_reservas_clientes (
  id            INT           NOT NULL AUTO_INCREMENT,
  cliente_id    INT           NOT NULL,
  cabina_id     INT           NOT NULL,
  huespedes     INT           NOT NULL DEFAULT 1,
  fecha_reserva DATETIME      NOT NULL,
  fecha_fin     DATETIME      NOT NULL,
  estado        ENUM('activa', 'finalizada', 'cancelada') NOT NULL DEFAULT 'activa',
  created_at    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
  FOREIGN KEY (cabina_id)  REFERENCES cabinas(id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET @huespedes_exists := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'historial_reservas_clientes'
    AND COLUMN_NAME = 'huespedes'
);

SET @alter_huespedes_sql := IF(
  @huespedes_exists = 0,
  'ALTER TABLE historial_reservas_clientes ADD COLUMN huespedes INT NOT NULL DEFAULT 1 AFTER cabina_id',
  'SELECT 1'
);

PREPARE stmt FROM @alter_huespedes_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

CREATE TABLE IF NOT EXISTS pagos (
  id            INT           NOT NULL AUTO_INCREMENT,
  reserva_id    INT           NOT NULL,
  monto         DECIMAL(10,2) NOT NULL,
  metodo        ENUM('sinpe', 'transferencia', 'efectivo', 'tarjeta') NOT NULL,
  comprobante   VARCHAR(255)  NULL,
  estado        ENUM('pendiente', 'verificado') NOT NULL DEFAULT 'verificado',
  created_at    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (reserva_id) REFERENCES historial_reservas_clientes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO cabinas (nombre, capacidad, precio, estado) VALUES
  ('Cabina 01', 4, 45000, 'activa'),
  ('Cabina 02', 6, 75000, 'activa'),
  ('Cabina 03', 2, 30000, 'mantenimiento'),
  ('Cabina 04', 8, 100000, 'activa'),
  ('Cabina 05', 6, 75000, 'inactiva');

INSERT IGNORE INTO clientes (nombre, cedula, telefono, email) VALUES
  ('Juan Pérez', '123456789', '43211234', 'juan.perez@email.com'),
  ('María Gómez', '987654321', '12345678', 'maria.gomez@email.com');

INSERT INTO historial_reservas_clientes (cliente_id, cabina_id, huespedes, fecha_reserva, fecha_fin, estado) VALUES
  (1, 1, 2, '2026-07-01 10:00:00', '2026-07-01 14:00:00', 'finalizada'),
  (2, 2, 4, '2026-07-02 15:00:00', '2026-07-02 18:00:00', 'activa');

INSERT INTO pagos (reserva_id, monto, metodo, comprobante, estado) VALUES
  (1, 30000, 'sinpe', 'sinpe_20260701_juan.png', 'verificado'),
  (2, 25000, 'transferencia', 'transfer_20260702_maria.jpg', 'verificado');

  CREATE TABLE IF NOT EXISTS usuarios (
  id            INT           NOT NULL AUTO_INCREMENT,
  nombre        VARCHAR(150)  NOT NULL,
  email         VARCHAR(100)  NOT NULL UNIQUE,
  password_hash VARCHAR(255)  NOT NULL,
  rol           ENUM('administrador', 'operador', 'consulta') NOT NULL DEFAULT 'operador',
  estado        ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  created_at    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO usuarios (nombre, email, password_hash, rol, estado)
VALUES ('Administrador', 'admin@cabinas.com', '$2y$10$wsQAbgMj8xRT.8vNZ/t8OOEA7XneFqNms.Z2e6fFxdfg7R6sDy.hu', 'administrador', 'activo');

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

INSERT INTO historial_reservas_clientes (cliente_id, cabina_id, huespedes, fecha_reserva, fecha_fin, estado) VALUES
  (1, 1, 3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), DATE_ADD(CURDATE(), INTERVAL 3 DAY), 'activa'),
  (2, 1, 2, DATE_ADD(CURDATE(), INTERVAL 8 DAY), DATE_ADD(CURDATE(), INTERVAL 10 DAY), 'activa'),
  (2, 2, 4, DATE_SUB(CURDATE(), INTERVAL 1 DAY), DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'activa'),
  (1, 4, 5, DATE_ADD(CURDATE(), INTERVAL 5 DAY), DATE_ADD(CURDATE(), INTERVAL 6 DAY), 'activa');