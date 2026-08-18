<?php

require_once __DIR__ . '/../config/database.php';

class Pago
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getReservasConPendiente(): array
    {
        $stmt = $this->db->query('
            SELECT
                hrc.id,
                cli.nombre AS cliente,
                cab.nombre AS cabina,
                hrc.fecha_reserva,
                hrc.fecha_fin,
                cab.precio AS total_reserva,
                COALESCE(SUM(p.monto), 0) AS total_pagado,
                GREATEST(cab.precio - COALESCE(SUM(p.monto), 0), 0) AS pendiente
            FROM historial_reservas_clientes hrc
            INNER JOIN clientes cli ON cli.id = hrc.cliente_id
            INNER JOIN cabinas cab ON cab.id = hrc.cabina_id
            LEFT JOIN pagos p ON p.reserva_id = hrc.id
            WHERE hrc.estado = "activa"
            GROUP BY hrc.id, cli.nombre, cab.nombre, hrc.fecha_reserva, hrc.fecha_fin, cab.precio
            HAVING pendiente > 0
            ORDER BY hrc.fecha_reserva DESC
        ');

        return $stmt->fetchAll();
    }

    public function getReservaById(int $id): ?array
    {
        $stmt = $this->db->prepare('
            SELECT
                hrc.id,
                cli.nombre AS cliente,
                cab.nombre AS cabina,
                hrc.fecha_reserva,
                hrc.fecha_fin,
                cab.precio AS total_reserva,
                COALESCE(SUM(p.monto), 0) AS total_pagado,
                GREATEST(cab.precio - COALESCE(SUM(p.monto), 0), 0) AS pendiente
            FROM historial_reservas_clientes hrc
            INNER JOIN clientes cli ON cli.id = hrc.cliente_id
            INNER JOIN cabinas cab ON cab.id = hrc.cabina_id
            LEFT JOIN pagos p ON p.reserva_id = hrc.id
            WHERE hrc.id = :id
              AND hrc.estado = "activa"
            GROUP BY hrc.id, cli.nombre, cab.nombre, hrc.fecha_reserva, hrc.fecha_fin, cab.precio
        ');
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function createPago(int $reservaId, float $monto, string $metodo, ?string $comprobante): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO pagos (reserva_id, monto, metodo, comprobante, estado)
            VALUES (:reserva_id, :monto, :metodo, :comprobante, "verificado")
        ');
        $stmt->execute([
            'reserva_id' => $reservaId,
            'monto' => $monto,
            'metodo' => $metodo,
            'comprobante' => $comprobante
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function getPagoById(int $id): ?array
    {
        $stmt = $this->db->prepare('
            SELECT
                p.id,
                p.reserva_id,
                p.monto,
                p.metodo,
                p.estado,
                p.comprobante,
                p.created_at,
                CONCAT("#", hrc.id, " - ", cli.nombre) AS reserva
            FROM pagos p
            INNER JOIN historial_reservas_clientes hrc ON hrc.id = p.reserva_id
            INNER JOIN clientes cli ON cli.id = hrc.cliente_id
            WHERE p.id = :id
        ');
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function getAllPagos(): array
    {
        $stmt = $this->db->query('
            SELECT
                p.id,
                DATE(p.created_at) AS fecha,
                CONCAT("#", hrc.id, " - ", cli.nombre) AS reserva,
                p.monto,
                p.metodo,
                p.estado,
                p.comprobante
            FROM pagos p
            INNER JOIN historial_reservas_clientes hrc ON hrc.id = p.reserva_id
            INNER JOIN clientes cli ON cli.id = hrc.cliente_id
            ORDER BY p.created_at DESC
        ');

        return $stmt->fetchAll();
    }
}
