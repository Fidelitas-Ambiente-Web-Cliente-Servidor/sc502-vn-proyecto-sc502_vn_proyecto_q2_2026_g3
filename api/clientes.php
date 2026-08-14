<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../controllers/ClienteController.php';

$action = $_GET['action'] ?? $_POST['action'] ?? 'listar';
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : null);

$controller = new ClienteController();

switch ($action) {
    case 'listar':
        if ($method === 'GET') {
            $controller->listar();
        }
        break;

    case 'crear':
        if ($method === 'POST') {
            $controller->crear();
        }
        break;

    case 'actualizar':
        if ($method === 'POST' && $id !== null) {
            $controller->actualizar($id);
        }
        break;
    
    case 'eliminar':
        if ($method === 'POST' && $id !== null) {
            $controller->eliminar($id);
        }
        break;

    default:
        echo json_encode(['response' => '01', 'message' => 'Acción no válida']);
}  