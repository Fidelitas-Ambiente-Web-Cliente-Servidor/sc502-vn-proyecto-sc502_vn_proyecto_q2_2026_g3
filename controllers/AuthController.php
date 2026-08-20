<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController
{
    private Usuario $model;

    public function __construct()
    {
        $this->model = new Usuario();
    }

    public function showLogin(): void
    {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function showRecuperar(): void
    {
        require __DIR__ . '/../views/auth/recuperar.php';
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            echo json_encode(['response' => '01', 'message' => 'Correo y contraseña son obligatorios']);
            return;
        }

        $usuario = $this->model->getByEmail($email);

        if (!$usuario) {
            echo json_encode(['response' => '01', 'message' => 'Correo o contraseña incorrectos']);
            return;
        }

        if ($usuario['estado'] !== 'activo') {
            echo json_encode(['response' => '01', 'message' => 'Este usuario está inactivo']);
            return;
        }

        if (!password_verify($password, $usuario['password_hash'])) {
            echo json_encode(['response' => '01', 'message' => 'Correo o contraseña incorrectos']);
            return;
        }

        session_start();
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol'] = $usuario['rol'];

        echo json_encode(['response' => '00', 'message' => 'Inicio de sesión exitoso']);
    }
}
