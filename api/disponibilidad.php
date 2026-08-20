<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../controllers/DisponibilidadController.php';

$action = $_GET['action'] ?? 'calendario';
$method = $_SERVER['REQUEST_METHOD'];

$controller = new DisponibilidadController();

switch ($action) {
    case 'calendario':
        if ($method === 'GET') {
            $controller->calendario();
        }
        break;

    default:
        echo json_encode(['response' => '01', 'message' => 'Acción no válida']);
}