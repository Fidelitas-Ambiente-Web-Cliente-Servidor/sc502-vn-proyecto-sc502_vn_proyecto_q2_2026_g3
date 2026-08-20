<?php

require_once __DIR__ . '/../config/database.php';

class Dashboard
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getTotalCabinas(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM cabinas");
        return (int) $stmt->fetchColumn();
    }

    public function getCabinasOcupadas(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM cabinas WHERE estado = 'ocupada'");
        return (int) $stmt->fetchColumn();
    }
}
