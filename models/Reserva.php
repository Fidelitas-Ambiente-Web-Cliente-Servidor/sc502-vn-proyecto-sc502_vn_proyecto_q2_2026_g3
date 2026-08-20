<?php

require_once __DIR__ . '/../config/database.php';

class Reserva
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllReservas(): array
    {
        $stmt = $this->db->query('
            SELECT
                hrc.id,
                hrc.cliente_id,
                hrc.cabina_id,
                hrc.fecha_reserva,
                hrc.fecha_fin,
                hrc.huespedes,
                hrc.estado,
                cli.nombre AS cliente,
                cab.nombre AS cabina,
                cab.capacidad,
                cab.precio AS total_reserva,
                COALESCE(SUM(p.monto), 0) AS total_pagado,
                GREATEST(cab.precio - COALESCE(SUM(p.monto), 0), 0) AS pendiente
            FROM historial_reservas_clientes hrc
            INNER JOIN clientes cli ON cli.id = hrc.cliente_id
            INNER JOIN cabinas cab ON cab.id = hrc.cabina_id
            LEFT JOIN pagos p ON p.reserva_id = hrc.id
            GROUP BY
                hrc.id,
                hrc.cliente_id,
                hrc.cabina_id,
                hrc.fecha_reserva,
                hrc.fecha_fin,
                hrc.huespedes,
                hrc.estado,
                cli.nombre,
                cab.nombre,
                cab.capacidad,
                cab.precio
            ORDER BY hrc.fecha_reserva DESC
        ');

        return $stmt->fetchAll();
    }

    public function getReservaById(int $id): ?array
    {
        $stmt = $this->db->prepare('
            SELECT
                hrc.id,
                hrc.cliente_id,
                hrc.cabina_id,
                hrc.fecha_reserva,
                hrc.fecha_fin,
                hrc.huespedes,
                hrc.estado,
                cli.nombre AS cliente,
                cab.nombre AS cabina,
                cab.capacidad,
                cab.precio AS total_reserva,
                COALESCE(SUM(p.monto), 0) AS total_pagado,
                GREATEST(cab.precio - COALESCE(SUM(p.monto), 0), 0) AS pendiente
            FROM historial_reservas_clientes hrc
            INNER JOIN clientes cli ON cli.id = hrc.cliente_id
            INNER JOIN cabinas cab ON cab.id = hrc.cabina_id
            LEFT JOIN pagos p ON p.reserva_id = hrc.id
            WHERE hrc.id = :id
            GROUP BY
                hrc.id,
                hrc.cliente_id,
                hrc.cabina_id,
                hrc.fecha_reserva,
                hrc.fecha_fin,
                hrc.huespedes,
                hrc.estado,
                cli.nombre,
                cab.nombre,
                cab.capacidad,
                cab.precio
        ');
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function getClientes(): array
    {
        $stmt = $this->db->query('
            SELECT id, nombre, cedula
            FROM clientes
            ORDER BY nombre ASC
        ');

        return $stmt->fetchAll();
    }

    public function getCabinasReservables(): array
    {
        $stmt = $this->db->query('
        SELECT id, nombre, capacidad, precio, estado
        FROM cabinas
        WHERE estado = "activa"
        ORDER BY nombre ASC
    ');

        return $stmt->fetchAll();
    }

    public function getCabinaReservableById(int $id): ?array
    {
        $stmt = $this->db->prepare('
        SELECT id, nombre, capacidad, precio, estado
        FROM cabinas
        WHERE id = :id
          AND estado = "activa"
    ');
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function existeCliente(int $clienteId): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM clientes WHERE id = :id');
        $stmt->execute(['id' => $clienteId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function createReserva(int $clienteId, int $cabinaId, string $fechaReserva, string $fechaFin, int $huespedes): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO historial_reservas_clientes (cliente_id, cabina_id, fecha_reserva, fecha_fin, huespedes, estado)
            VALUES (:cliente_id, :cabina_id, :fecha_reserva, :fecha_fin, :huespedes, "activa")
        ');
        $stmt->execute([
            'cliente_id' => $clienteId,
            'cabina_id' => $cabinaId,
            'fecha_reserva' => $fechaReserva,
            'fecha_fin' => $fechaFin,
            'huespedes' => $huespedes
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function updateReserva(int $id, int $clienteId, int $cabinaId, string $fechaReserva, string $fechaFin, int $huespedes): bool
    {
        $stmt = $this->db->prepare('
            UPDATE historial_reservas_clientes
            SET cliente_id = :cliente_id,
                cabina_id = :cabina_id,
                fecha_reserva = :fecha_reserva,
                fecha_fin = :fecha_fin,
                huespedes = :huespedes
            WHERE id = :id
        ');

        return $stmt->execute([
            'id' => $id,
            'cliente_id' => $clienteId,
            'cabina_id' => $cabinaId,
            'fecha_reserva' => $fechaReserva,
            'fecha_fin' => $fechaFin,
            'huespedes' => $huespedes
        ]);
    }

    public function actualizarEstado(int $id, string $estado): bool
    {
        $stmt = $this->db->prepare('
            UPDATE historial_reservas_clientes
            SET estado = :estado
            WHERE id = :id
        ');

        return $stmt->execute([
            'id' => $id,
            'estado' => $estado
        ]);
    }

    public function existeTraslapeCabina(int $cabinaId, string $fechaReserva, string $fechaFin, ?int $excluirId = null): bool
    {
        $sql = '
            SELECT COUNT(*)
            FROM historial_reservas_clientes
            WHERE cabina_id = :cabina_id
              AND estado <> "cancelada"
              AND fecha_reserva < :fecha_fin
              AND fecha_fin > :fecha_reserva
        ';

        $params = [
            'cabina_id' => $cabinaId,
            'fecha_reserva' => $fechaReserva,
            'fecha_fin' => $fechaFin
        ];

        if ($excluirId !== null) {
            $sql .= ' AND id <> :excluir_id';
            $params['excluir_id'] = $excluirId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }
}
