<?php

require_once __DIR__ . '/../config/database.php';

class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

        public function emailExiste(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return (bool) $stmt->fetch();
    }

    public function crear(array $datos): int
    {
        $sql = "INSERT INTO usuarios (nombre, email, password_hash, rol, estado)
                VALUES (:nombre, :email, :password_hash, :rol, :estado)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'nombre'        => $datos['nombre'],
            'email'         => $datos['email'],
            'password_hash' => password_hash($datos['password'], PASSWORD_DEFAULT),
            'rol'           => $datos['rol'],
            'estado'        => $datos['estado'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function listar(): array
    {
        $stmt = $this->db->query("SELECT id, nombre, email, rol, estado, created_at FROM usuarios ORDER BY nombre");
        return $stmt->fetchAll();
    }

        public function verificarPassword(int $id, string $password): bool
    {
        $usuario = $this->getById($id);

        if (!$usuario) {
            return false;
        }

        return password_verify($password, $usuario['password_hash']);
    }

    public function actualizarPassword(int $id, string $nuevaPassword): void
    {
        $stmt = $this->db->prepare(
            "UPDATE usuarios SET password_hash = :password_hash WHERE id = :id"
        );

        $stmt->execute([
            'password_hash' => password_hash($nuevaPassword, PASSWORD_DEFAULT),
            'id'            => $id,
        ]);
    }
    
}