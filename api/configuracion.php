<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../controllers/ConfiguracionController.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

$controller = new ConfiguracionController();

switch ($action) {
    case 'crearUsuario':
        if ($method === 'POST') {
            $controller->crearUsuario();
        }
        break;

        case 'cambiarPassword':
            if ($method === 'POST') {
                $controller->cambiarPassword();
            }
            break;

            case 'obtenerHospedaje':
                if ($method === 'GET') {
                    $controller->obtenerHospedaje();
                }
                break; 
                
                case 'actualizarHospedaje':
                    if ($method === 'POST') {
                        $controller->actualizarHospedaje();
                    }
                    break;

    default:
        echo json_encode(['response' => '01', 'message' => 'Acción no válida']);
}