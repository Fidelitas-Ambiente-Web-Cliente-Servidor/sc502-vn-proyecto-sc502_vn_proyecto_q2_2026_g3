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
        $stmt = $this->db->query("SELECT COUNT(*) FROM cabinas WHERE estado = 'activa'");
        return (int) $stmt->fetchColumn();
    }

    public function getCabinasOcupadas(): int
    {
        $stmt = $this->db->query("
            SELECT COUNT(DISTINCT c.id)
            FROM cabinas c
            INNER JOIN historial_reservas_clientes hrc ON hrc.cabina_id = c.id
            WHERE c.estado = 'activa'
              AND hrc.estado <> 'cancelada'
              AND hrc.fecha_reserva <= NOW()
              AND hrc.fecha_fin > NOW()
        ");
        return (int) $stmt->fetchColumn();
    }
}
