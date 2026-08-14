<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Cliente.php';
require_once __DIR__ . '/controllers/ClienteController.php';

$controller = new ClienteController();
$controller->index();
