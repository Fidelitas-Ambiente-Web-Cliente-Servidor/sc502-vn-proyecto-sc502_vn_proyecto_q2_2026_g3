<?php

require_once __DIR__ . '/../config/database.php';

class Cliente
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllClientes(): array
    {
        $stmt = $this->db->query("SELECT * FROM clientes ORDER BY nombre ASC");
        $clientes = $stmt->fetchAll();

        foreach ($clientes as &$cliente) {
            $cliente['historial'] = $this->getHistorialReservas((int) $cliente['id']);
        }

        return $clientes;
    }

    public function getClienteById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $cliente = $stmt->fetch() ?: null;

        if ($cliente) {
            $cliente['historial'] = $this->getHistorialReservas($id);
        }

        return $cliente;
    }

    public function createCliente(string $nombre, string $cedula, string $email, string $telefono): int
    {
        $stmt = $this->db->prepare("INSERT INTO clientes (nombre, cedula, email, telefono) VALUES (:nombre, :cedula, :email, :telefono)");
        $stmt->execute([
            'nombre' => $nombre,
            'cedula' => $cedula,
            'email' => $email,
            'telefono' => $telefono
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function updateCliente(int $id, string $nombre, string $cedula, string $email, string $telefono): bool
    {
        $stmt = $this->db->prepare("UPDATE clientes SET nombre = :nombre, cedula = :cedula, email = :email, telefono = :telefono WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'nombre' => $nombre,
            'cedula' => $cedula,
            'email' => $email,
            'telefono' => $telefono
        ]);
    }
    public function getHistorialReservas(int $clienteId): array
    {
        $stmt = $this->db->prepare('
            SELECT hrc.fecha_reserva AS fecha, c.nombre AS cabina
            FROM historial_reservas_clientes hrc
            JOIN cabinas c ON c.id = hrc.cabina_id
            WHERE hrc.cliente_id = :id
              AND hrc.estado <> "cancelada"
            ORDER BY hrc.fecha_reserva ASC
        ');
        $stmt->execute(['id' => $clienteId]);
        return $stmt->fetchAll();
    }

    public function cedulaExiste(string $cedula, ?int $excluirId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM clientes WHERE cedula = :cedula";
        $params = ['cedula' => $cedula];

        if ($excluirId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $excluirId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function emailExiste(string $email, ?int $excluirId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM clientes WHERE email = :email";
        $params = ['email' => $email];

        if ($excluirId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $excluirId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function telefonoExiste(string $telefono, ?int $excluirId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM clientes WHERE telefono = :telefono";
        $params = ['telefono' => $telefono];

        if ($excluirId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $excluirId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function deleteCliente(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM clientes WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}