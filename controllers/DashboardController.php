<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Dashboard.php';

class DashboardController
{
    private Dashboard $model;

    public function __construct()
    {
        $this->model = new Dashboard();
    }

    public function index(): void
    {
        $prediccion = $this->getPrediccionTarifas();

        $totalCabinas = $this->model->getTotalCabinas();
        $cabinasOcupadas = $this->model->getCabinasOcupadas();

        $porcentajeOcupacion = ($totalCabinas > 0) ? round(($cabinasOcupadas / $totalCabinas) * 100) : 0;

        $temporadaAlta = ($prediccion['tipo'] === 'alta');
        $textoTemporada = $temporadaAlta ? "Temporada Alta" : "Temporada Baja";
        $claseBadge = $temporadaAlta ? "badge-aumento" : "badge-baja";
        $sugerencia = $temporadaAlta ? "MANTENER O SUBIR" : "APLICAR DESCUENTO";

        require __DIR__ . '/../views/dashboard/index.php';
    }

    public function resumen(): void
    {
        $totalCabinasActivas = $this->model->getTotalCabinas();
        $cabinasOcupadas = $this->model->getCabinasOcupadas();
        $cabinasDisponibles = max($totalCabinasActivas - $cabinasOcupadas, 0);

        echo json_encode([
            'response' => '00',
            'totalReservas' => $this->model->getTotalReservas(),
            'cabinasDisponibles' => $cabinasDisponibles,
            'cabinasOcupadas' => $cabinasOcupadas,
            'cabinasMantenimiento' => $this->model->getCabinasMantenimiento(),
            'cabinasInactivas' => $this->model->getCabinasInactivas(),
            'clientesFrecuentes' => $this->model->getClientesFrecuentes()
        ]);
    }

    public function getPrediccionTarifas(): array
    {
        $fechaActual = new DateTime('now', new DateTimeZone('America/Costa_Rica'));
        $mes = (int) $fechaActual->format('n');
        $dia = (int) $fechaActual->format('j');

        $temporadaAlta = false;

        if (in_array($mes, [12, 1, 2, 3, 4])) {
            $temporadaAlta = true;
        } elseif ($mes == 7 || ($mes == 8 && $dia <= 15)) {
            $temporadaAlta = true;
        }

        if ($temporadaAlta) {
            return [
                'tipo' => 'alta',
                'mensaje' => 'Estamos en temporada alta. Se sugiere incrementar tarifas entre un 15% y 25% para maximizar ingresos.',
                'icono' => 'bi-graph-up-arrow',
                'color' => 'success'
            ];
        }

        return [
            'tipo' => 'baja',
            'mensaje' => 'Estamos en temporada baja. Se sugiere aplicar promociones o descuentos del 10% al 20% para incentivar reservas.',
            'icono' => 'bi-graph-down-arrow',
            'color' => 'warning'
        ];
    }
}
