<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Reporte.php';

class ReporteController
{
    private Reporte $model;

    public function __construct()
    {
        $this->model = new Reporte();
    }

    public function index(): void
    {
        require __DIR__ . '/../views/reportes/index.php';
    }

    public function resumen(): void
    {
        $resumen = $this->model->getResumenGeneral();
        $ingresosPorMes = $this->model->getIngresosPorMes();
        $ocupacionPorCabina = $this->model->getOcupacionPorCabinaMesActual();

        echo json_encode([
            'response' => '00',
            'resumen' => $resumen,
            'ingresos_por_mes' => $ingresosPorMes,
            'ocupacion_por_cabina' => $ocupacionPorCabina,
            'analisis' => $this->generarAnalisis($ocupacionPorCabina)
        ]);
    }

    private function generarAnalisis(array $ocupacionPorCabina): string
    {
        if (empty($ocupacionPorCabina)) {
            return 'Aún no hay datos suficientes para generar análisis de ocupación.';
        }

        usort($ocupacionPorCabina, static function (array $a, array $b): int {
            return $b['porcentaje'] <=> $a['porcentaje'];
        });

        $mejor = $ocupacionPorCabina[0];
        $porcentaje = number_format((float) $mejor['porcentaje'], 1);

        if ((float) $mejor['porcentaje'] <= 0) {
            return 'No se registró ocupación en el mes actual. Se recomienda activar promociones para aumentar reservas.';
        }

        return sprintf(
            '%s es la cabina con mejor ocupación del mes (%s%%). Enfocar campañas similares en las demás cabinas puede mejorar el rendimiento general.',
            $mejor['cabina'],
            $porcentaje
        );
    }
}
