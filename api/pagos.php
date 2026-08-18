<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../controllers/PagoController.php';

$action = $_GET['action'] ?? $_POST['action'] ?? 'listar_pagos';
$method = $_SERVER['REQUEST_METHOD'];

$controller = new PagoController();

switch ($action) {
    case 'listar_reservas':
        if ($method === 'GET') {
            $controller->listarReservasPendientes();
        }
        break;

    case 'listar_pagos':
        if ($method === 'GET') {
            $controller->listarPagos();
        }
        break;

    case 'crear':
        if ($method === 'POST') {
            $controller->crear();
        }
        break;

    default:
        echo json_encode(['response' => '01', 'message' => 'Acción no válida']);
}
