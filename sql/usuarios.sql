USE cabinas_db;

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