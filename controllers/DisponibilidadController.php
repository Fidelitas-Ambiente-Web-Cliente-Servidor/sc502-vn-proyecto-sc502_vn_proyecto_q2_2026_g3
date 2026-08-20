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

    public function calendario(): void
    {
        $offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;

        $timezone = new DateTimeZone('America/Costa_Rica');
        $hoy = new DateTimeImmutable('now', $timezone);

        $diaSemanaISO = (int) $hoy->format('N'); // 1 = lunes ... 7 = domingo
        $lunes = $hoy
            ->modify('-' . ($diaSemanaISO - 1) . ' days')
            ->modify(($offset * 7) . ' days')
            ->setTime(0, 0, 0);
        $domingo = $lunes->modify('+6 days');

        $nombresDias = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
        $dias = [];
        for ($i = 0; $i < 7; $i++) {
            $fecha = $lunes->modify("+{$i} day");
            $dias[] = [
                'nombre'    => $nombresDias[$i],
                'fecha'     => $fecha->format('d/m'),
                'fecha_iso' => $fecha->format('Y-m-d')
            ];
        }

        echo json_encode([
            'response' => '00',
            'rango'    => 'Semana del ' . $lunes->format('d/m') . ' al ' . $domingo->format('d/m'),
            'dias'     => $dias,
            'cabinas'  => $this->model->obtenerCalendarioSemana($lunes, $domingo)
        ]);
    }
}
