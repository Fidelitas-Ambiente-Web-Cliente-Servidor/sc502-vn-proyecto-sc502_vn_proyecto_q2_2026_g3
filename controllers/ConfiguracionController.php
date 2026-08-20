<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Hospedaje.php';

class ConfiguracionController
{
    private Usuario $model;
    private Hospedaje $hospedajeModel;

    public function __construct()
    {
        $this->model = new Usuario();
        $this->hospedajeModel = new Hospedaje();
    }

    public function index(): void
    {
        require __DIR__ . '/../views/configuracion/index.php';
    }

        public function crearUsuario(): void
    {
        $nombre   = trim($_POST['nombre'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $rol      = $_POST['rol'] ?? '';
        $estado   = $_POST['estado'] ?? '';

        if (empty($nombre) || empty($email) || empty($password) || empty($rol) || empty($estado)) {
            echo json_encode(['response' => '01', 'message' => 'Todos los campos son obligatorios']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['response' => '01', 'message' => 'El correo no es válido']);
            return;
        }

        if ($this->model->emailExiste($email)) {
            echo json_encode(['response' => '01', 'message' => 'Ese correo ya está registrado']);
            return;
        }

        $id = $this->model->crear([
            'nombre'   => $nombre,
            'email'    => $email,
            'password' => $password,
            'rol'      => $rol,
            'estado'   => $estado,
        ]);

        echo json_encode(['response' => '00', 'message' => 'Usuario creado correctamente', 'id' => $id]);
    }

        public function cambiarPassword(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['response' => '01', 'message' => 'No hay una sesión activa. Inicie sesión de nuevo.']);
            return;
        }

        $actual     = trim($_POST['contrasena_actual'] ?? '');
        $nueva      = trim($_POST['nueva_contrasena'] ?? '');
        $confirmar  = trim($_POST['confirmar_contrasena'] ?? '');

        if (empty($actual) || empty($nueva) || empty($confirmar)) {
            echo json_encode(['response' => '01', 'message' => 'Todos los campos son obligatorios']);
            return;
        }

        if ($nueva !== $confirmar) {
            echo json_encode(['response' => '01', 'message' => 'Las contraseñas nuevas no coinciden']);
            return;
        }

        if (strlen($nueva) < 8) {
            echo json_encode(['response' => '01', 'message' => 'La nueva contraseña debe tener al menos 8 caracteres']);
            return;
        }

        $usuarioId = $_SESSION['usuario_id'];

        if (!$this->model->verificarPassword($usuarioId, $actual)) {
            echo json_encode(['response' => '01', 'message' => 'La contraseña actual no es correcta']);
            return;
        }

        $this->model->actualizarPassword($usuarioId, $nueva);

        echo json_encode(['response' => '00', 'message' => 'Contraseña actualizada correctamente']);
    }

        public function obtenerHospedaje(): void
    {
        $datos = $this->hospedajeModel->obtener();

        echo json_encode(['response' => '00', 'data' => $datos]);
    }

    public function actualizarHospedaje(): void
    {
        $nombre       = trim($_POST['nombre'] ?? '');
        $provincia    = trim($_POST['provincia'] ?? '');
        $direccion    = trim($_POST['direccion'] ?? '');
        $telefono     = trim($_POST['telefono'] ?? '');
        $email        = trim($_POST['email'] ?? '');
        $horaEntrada  = trim($_POST['hora_entrada'] ?? '');
        $horaSalida   = trim($_POST['hora_salida'] ?? '');

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['response' => '01', 'message' => 'El correo no es válido']);
            return;
        }

        $this->hospedajeModel->actualizar([
            'nombre'       => $nombre,
            'provincia'    => $provincia,
            'direccion'    => $direccion,
            'telefono'     => $telefono,
            'email'        => $email,
            'hora_entrada' => $horaEntrada,
            'hora_salida'  => $horaSalida,
        ]);

        echo json_encode(['response' => '00', 'message' => 'Información del hospedaje actualizada correctamente']);
    }

}