<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../controllers/DashboardController.php';

$action = $_GET['action'] ?? 'resumen';
$method = $_SERVER['REQUEST_METHOD'];

$controller = new DashboardController();

switch ($action) {
    case 'resumen':
        if ($method === 'GET') {
            $controller->resumen();
        }
        break;

    default:
        echo json_encode(['response' => '01', 'message' => 'Acción no válida']);
}