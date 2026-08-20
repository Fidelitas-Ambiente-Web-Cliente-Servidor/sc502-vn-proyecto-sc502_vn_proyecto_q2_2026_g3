<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Disponibilidad.php';

class DisponibilidadController
{
    private Disponibilidad $model;

    public function __construct()
    {
        $this->model = new Disponibilidad();
    }

    public function index(): void
    {
        $cabinas = $this->model->obtenerTodasLasCabinas();
        $cabinasDisponibles = $this->model->obtenerCabinasDisponibles();

        require __DIR__ . '/../views/disponibilidad/index.php';
    }
}
