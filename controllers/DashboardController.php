<?php
class DashboardController {
    public function index()
    {
        $prediccion = $this->getPrediccionTarifas();

        require __DIR__ . '/../views/dashboard/index.php';
    }

    public function getPrediccionTarifas()
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
