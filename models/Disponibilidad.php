<?php

require_once __DIR__ . '/../config/database.php';

class Disponibilidad
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function obtenerTodasLasCabinas(): array
    {
        $stmt = $this->db->query("SELECT * FROM cabinas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCabinasDisponibles(): array
    {
        $stmt = $this->db->query("SELECT * FROM cabinas WHERE estado = 'disponible'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
