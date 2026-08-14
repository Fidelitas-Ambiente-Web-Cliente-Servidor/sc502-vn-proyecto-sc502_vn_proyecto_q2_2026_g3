<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Cabina.php';
require_once __DIR__ . '/controllers/CabinaController.php';

$controller = new CabinaController();
$controller->index();
