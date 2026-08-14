<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController
{
    private Cliente $model;

    public function __construct()
    {
        $this->model = new Cliente();
    }

    public function index(): void
    {
        require __DIR__ . '/../views/clientes/index.php';
    }
    
    public function listar(): void
    {
        echo json_encode(['response' => '00', 'clientes' => $this->model->getAllClientes()]);
    }

    public function crear(): void
    {
        $datos = $this->validar($_POST);

        if ($datos['error']) 
        {
            echo json_encode(['response' => '01', 'message' => $datos['error']]);
            return;
        }

        if ($this->model->cedulaExiste($datos['cedula'])) {
            echo json_encode(['response' => '01', 'message' => 'El cliente ya existe']);
            return;
        }

        if ($this->model->emailExiste($datos['email'])) {
            echo json_encode(['response' => '01', 'message' => 'El correo electrónico ya está registrado']);
            return;
        }

        if ($this->model->telefonoExiste($datos['telefono'])) {
            echo json_encode(['response' => '01', 'message' => 'El número de teléfono ya está registrado']);
            return;
        }

        $clienteId = $this->model->createCliente(
            $datos['nombre'],
            $datos['cedula'],
            $datos['email'],
            $datos['telefono']
        );
        echo json_encode(['response' => '00', 'message' => 'Cliente registrado exitosamente', 'clienteId' => $clienteId]);
    }

    public function actualizar(int $id): void
    {
        if (!$this->model->getClienteById($id)) {
            echo json_encode(['response' => '01', 'message' => 'Cliente no encontrado']);
            return;
        }

        $datos = $this->validar($_POST);

        if ($datos['error']) 
        {
            echo json_encode(['response' => '01', 'message' => $datos['error']]);
            return;
        }

        if ($this->model->cedulaExiste($datos['cedula'], $id)) {
            echo json_encode(['response' => '01', 'message' => 'El cliente ya existe']);
            return;
        }

        if ($this->model->emailExiste($datos['email'], $id)) {
            echo json_encode(['response' => '01', 'message' => 'El correo electrónico ya está registrado']);
            return;
        }

        if ($this->model->telefonoExiste($datos['telefono'], $id)) {
            echo json_encode(['response' => '01', 'message' => 'El número de teléfono ya está registrado']);
            return;
        }

        $this->model->updateCliente(
            $id,
            $datos['nombre'],
            $datos['cedula'],
            $datos['email'],
            $datos['telefono']
        );

        echo json_encode(['response' => '00', 'message' => 'Cliente actualizado exitosamente']);
    }

    public function eliminar(int $id): void
    {
        if (!$this->model->getClienteById($id)) {
            echo json_encode(['response' => '01', 'message' => 'Cliente no encontrado']);
            return;
        }

        $this->model->deleteCliente($id);
        echo json_encode(['response' => '00', 'message' => 'Cliente eliminado exitosamente']);
    }

    public function historialReservas(int $clienteId): void
    {
        if (!$this->model->getClienteById($clienteId)) {
            echo json_encode(['response' => '01', 'message' => 'Cliente no encontrado']);
            return;
        }

        $historial = $this->model->getHistorialReservas($clienteId);
        echo json_encode(['response' => '00', 'historial' => $historial]);
    }

    private function validar(array $input): array
    {
        $nombre = trim($input['nombre'] ?? '');
        $cedula = trim($input['cedula'] ?? '');
        $email = trim($input['email'] ?? '');
        $telefono = trim($input['telefono'] ?? '');

        if (empty($nombre) || empty($cedula) || empty($email) || empty($telefono)) {
            return ['error' => 'Todos los campos son obligatorios'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['error' => 'Correo electrónico inválido'];
        }

        if (!preg_match('/^\d+$/', $telefono)) {
            return ['error' => 'Número de teléfono inválido'];
        }

        return [
            'error' => null,
            'nombre' => $nombre,
            'cedula' => $cedula,
            'email' => $email,
            'telefono' => $telefono
        ];
    }
}