<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../controllers/AuthController.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

$controller = new AuthController();

switch ($action) {
    case 'login':
        if ($method === 'POST') {
            $controller->login();
        }
        break;

    default:
        echo json_encode(['response' => '01', 'message' => 'Acción no válida']);
}