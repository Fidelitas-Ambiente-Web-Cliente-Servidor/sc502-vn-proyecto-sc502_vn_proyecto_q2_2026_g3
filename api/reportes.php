<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../controllers/ReporteController.php';

$action = $_GET['action'] ?? $_POST['action'] ?? 'resumen';
$method = $_SERVER['REQUEST_METHOD'];

$controller = new ReporteController();

switch ($action) {
    case 'resumen':
        if ($method === 'GET') {
            $controller->resumen();
        }
        break;

    default:
        echo json_encode(['response' => '01', 'message' => 'Acción no válida']);
}
