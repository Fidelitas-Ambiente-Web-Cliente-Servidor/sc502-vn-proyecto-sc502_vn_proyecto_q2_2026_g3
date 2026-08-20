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

        public function getTotalReservas(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM historial_reservas_clientes");
        return (int) $stmt->fetchColumn();
    }

    public function getCabinasMantenimiento(): array
    {
        $stmt = $this->db->query("SELECT nombre FROM cabinas WHERE estado = 'mantenimiento' ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getCabinasInactivas(): array
    {
        $stmt = $this->db->query("SELECT nombre FROM cabinas WHERE estado = 'inactiva' ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getClientesFrecuentes(int $umbral = 3): array
    {
        $stmt = $this->db->prepare("
            SELECT cli.nombre AS nombre, COUNT(*) AS total_reservas
            FROM historial_reservas_clientes hrc
            INNER JOIN clientes cli ON cli.id = hrc.cliente_id
            GROUP BY hrc.cliente_id, cli.nombre
            HAVING COUNT(*) > :umbral
            ORDER BY total_reservas DESC
        ");
        $stmt->execute(['umbral' => $umbral]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
