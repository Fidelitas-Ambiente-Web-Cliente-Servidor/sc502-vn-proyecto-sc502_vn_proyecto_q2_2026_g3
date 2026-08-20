<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cabina.php';

class CabinaController
{
    private Cabina $model;
    private array $estadosValidos = ['activa', 'inactiva', 'mantenimiento'];

    public function __construct()
    {
        $this->model = new Cabina();
    }

    public function index(): void
    {
        require __DIR__ . '/../views/cabinas/index.php';
    }

    public function listar(): void
    {
        echo json_encode(['response' => '00', 'cabinas' => $this->model->getAllCabinas()]);
    }

    public function crear(): void
    {
        $datos = $this->validar($_POST);

        if ($datos['error']) 
        {
            echo json_encode(['response' => '01', 'message' => $datos['error']]);
            return;
        }

        $cabinaId = $this->model->createCabina(
            $datos['nombre'],
            $datos['capacidad'],
            $datos['precio'],
            $datos['estado']
        );
        echo json_encode(['response' => '00', 'message' => 'Cabina registrada exitosamente', 'cabinaId' => $cabinaId]);
    }

    public function actualizar(int $id): void
    {
        if (!$this->model->getCabinaById($id)) {
            echo json_encode(['response' => '01', 'message' => 'Cabina no encontrada']);
            return;
        }

        $datos = $this->validar($_POST);

        if ($datos['error']) 
        {
            echo json_encode(['response' => '01', 'message' => $datos['error']]);
            return;
        }

        $this->model->updateCabina(
            $id,
            $datos['nombre'],
            $datos['capacidad'],
            $datos['precio'],
            $datos['estado']
        );
        echo json_encode(['response' => '00', 'message' => 'Cabina actualizada exitosamente']);
    }

    public function eliminar(int $id): void
    {
        if (!$this->model->getCabinaById($id)) {
            echo json_encode(['response' => '01', 'message' => 'Cabina no encontrada']);
            return;
        }

        $this->model->deleteCabina($id);
        echo json_encode(['response' => '00', 'message' => 'Cabina eliminada exitosamente']);
    }

    private function validar(array $data): array
    {
        $nombre = trim($data['nombre'] ?? '');
        $capacidad = trim($data['capacidad'] ?? '');
        $precio = trim($data['precio'] ?? '');
        $estado = trim($data['estado'] ?? 'activa');

        if (empty($nombre)) {
            return ['error' => 'El nombre de la cabina es obligatorio'];
        }

        if (!is_numeric($capacidad) || (int)$capacidad <= 0) {
            return ['error' => 'La capacidad debe ser un número entero positivo'];
        }

        if (!is_numeric($precio) || (float)$precio <= 0) {
            return ['error' => 'El precio debe ser un número positivo'];
        }

        if (!in_array($estado, $this->estadosValidos)) {
            return ['error' => 'El estado de la cabina no es válido'];
        }

        return [
            'error' => null,
            'nombre' => $nombre,
            'capacidad' => (int)$capacidad,
            'precio' => (float)$precio,
            'estado' => $estado
        ];
    }
}