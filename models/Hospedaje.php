<?php

require_once __DIR__ . '/../config/database.php';

class Hospedaje
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function obtener(): ?array
    {
        $stmt = $this->db->query("SELECT * FROM hospedaje WHERE id = 1");
        return $stmt->fetch() ?: null;
    }

    public function actualizar(array $datos): void
    {
        $sql = "UPDATE hospedaje SET
                    nombre = :nombre,
                    provincia = :provincia,
                    direccion = :direccion,
                    telefono = :telefono,
                    email = :email,
                    hora_entrada = :hora_entrada,
                    hora_salida = :hora_salida
                WHERE id = 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'nombre'       => $datos['nombre'],
            'provincia'    => $datos['provincia'],
            'direccion'    => $datos['direccion'],
            'telefono'     => $datos['telefono'],
            'email'        => $datos['email'],
            'hora_entrada' => $datos['hora_entrada'] ?: null,
            'hora_salida'  => $datos['hora_salida'] ?: null,
        ]);
    }
}