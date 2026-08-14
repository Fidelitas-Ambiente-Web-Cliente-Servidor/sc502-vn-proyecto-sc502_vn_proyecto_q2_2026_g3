<?php

require_once __DIR__ . '/../config/database.php';

class Cabina
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllCabinas(): array
    {
        $stmt = $this->db->query("SELECT * FROM cabinas ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    public function getCabinaById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM cabinas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function createCabina(string $nombre, int $capacidad, float $precio, string $estado): int
    {
        $stmt = $this->db->prepare("INSERT INTO cabinas (nombre, capacidad, precio, estado) VALUES (:nombre, :capacidad, :precio, :estado)");
        $stmt->execute([
            'nombre' => $nombre,
            'capacidad' => $capacidad,
            'precio' => $precio,
            'estado' => $estado
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function updateCabina(int $id, string $nombre, int $capacidad, float $precio, string $estado): bool
    {
        $stmt = $this->db->prepare("UPDATE cabinas SET nombre = :nombre, capacidad = :capacidad, precio = :precio, estado = :estado WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'nombre' => $nombre,
            'capacidad' => $capacidad,
            'precio' => $precio,
            'estado' => $estado
        ]);
    }

    public function deleteCabina(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM cabinas WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}