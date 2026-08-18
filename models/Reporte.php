<?php

require_once __DIR__ . '/../config/database.php';

class Reporte
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getResumenGeneral(): array
    {
        $inicioMes = new DateTimeImmutable('first day of this month 00:00:00');
        $finMes = $inicioMes->modify('+1 month');

        $stmtIngresos = $this->db->prepare('
            SELECT COALESCE(SUM(monto), 0) AS total
            FROM pagos
            WHERE created_at >= :inicio
              AND created_at < :fin
        ');
        $stmtIngresos->execute([
            'inicio' => $inicioMes->format('Y-m-d H:i:s'),
            'fin' => $finMes->format('Y-m-d H:i:s')
        ]);
        $ingresos = (float) $stmtIngresos->fetchColumn();

        $stmtHuespedes = $this->db->prepare('
            SELECT COALESCE(SUM(huespedes), 0) AS total
            FROM historial_reservas_clientes
            WHERE estado <> "cancelada"
              AND fecha_reserva >= :inicio
              AND fecha_reserva < :fin
        ');
        $stmtHuespedes->execute([
            'inicio' => $inicioMes->format('Y-m-d H:i:s'),
            'fin' => $finMes->format('Y-m-d H:i:s')
        ]);
        $huespedes = (int) $stmtHuespedes->fetchColumn();

        $ocupacionCabinas = $this->getOcupacionPorCabinaMesActual();
        $ocupacionPromedio = 0.0;
        if (count($ocupacionCabinas) > 0) {
            $ocupacionPromedio = array_sum(array_column($ocupacionCabinas, 'porcentaje')) / count($ocupacionCabinas);
        }

        return [
            'ingresos_totales' => round($ingresos, 2),
            'ocupacion_promedio' => round($ocupacionPromedio, 2),
            'huespedes_atendidos' => $huespedes
        ];
    }

    public function getIngresosPorMes(int $cantidadMeses = 6): array
    {
        $cantidadMeses = max(1, $cantidadMeses);

        $inicioRango = (new DateTimeImmutable('first day of this month 00:00:00'))
            ->modify('-' . ($cantidadMeses - 1) . ' months');
        $finRango = (new DateTimeImmutable('first day of next month 00:00:00'));

        $stmt = $this->db->prepare('
            SELECT
                DATE_FORMAT(created_at, "%Y-%m") AS periodo,
                COUNT(DISTINCT reserva_id) AS reservas,
                COALESCE(SUM(monto), 0) AS subtotal
            FROM pagos
            WHERE created_at >= :inicio
              AND created_at < :fin
            GROUP BY DATE_FORMAT(created_at, "%Y-%m")
            ORDER BY periodo ASC
        ');
        $stmt->execute([
            'inicio' => $inicioRango->format('Y-m-d H:i:s'),
            'fin' => $finRango->format('Y-m-d H:i:s')
        ]);

        $datos = [];
        foreach ($stmt->fetchAll() as $fila) {
            $datos[$fila['periodo']] = [
                'reservas' => (int) $fila['reservas'],
                'subtotal' => (float) $fila['subtotal']
            ];
        }

        $resultado = [];
        $cursor = $inicioRango;
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        for ($i = 0; $i < $cantidadMeses; $i++) {
            $periodo = $cursor->format('Y-m');
            $subtotal = $datos[$periodo]['subtotal'] ?? 0;
            $reservas = $datos[$periodo]['reservas'] ?? 0;
            $descuentos = 0.0;

            $resultado[] = [
                'mes' => $meses[(int) $cursor->format('n')] . ' ' . $cursor->format('Y'),
                'reservas' => $reservas,
                'subtotal' => round($subtotal, 2),
                'descuentos' => $descuentos,
                'total_neto' => round($subtotal - $descuentos, 2)
            ];

            $cursor = $cursor->modify('+1 month');
        }

        return $resultado;
    }

    public function getOcupacionPorCabinaMesActual(): array
    {
        $inicioMes = new DateTimeImmutable('first day of this month 00:00:00');
        $finMes = $inicioMes->modify('+1 month');
        $horasMes = (float) (($finMes->getTimestamp() - $inicioMes->getTimestamp()) / 3600);

        $stmt = $this->db->prepare('
            SELECT
                c.id,
                c.nombre,
                COALESCE(SUM(
                    GREATEST(
                        TIMESTAMPDIFF(
                            HOUR,
                            GREATEST(hrc.fecha_reserva, :inicio_calc),
                            LEAST(hrc.fecha_fin, :fin_calc)
                        ),
                        0
                    )
                ), 0) AS horas_reservadas
            FROM cabinas c
            LEFT JOIN historial_reservas_clientes hrc
                ON hrc.cabina_id = c.id
               AND hrc.estado <> "cancelada"
               AND hrc.fecha_fin > :inicio_filtro
               AND hrc.fecha_reserva < :fin_filtro
            GROUP BY c.id, c.nombre
            ORDER BY c.nombre ASC
        ');
        $stmt->execute([
            'inicio_calc' => $inicioMes->format('Y-m-d H:i:s'),
            'fin_calc' => $finMes->format('Y-m-d H:i:s'),
            'inicio_filtro' => $inicioMes->format('Y-m-d H:i:s'),
            'fin_filtro' => $finMes->format('Y-m-d H:i:s')
        ]);

        $resultado = [];
        foreach ($stmt->fetchAll() as $fila) {
            $porcentaje = 0.0;
            if ($horasMes > 0) {
                $porcentaje = min(100, ((float) $fila['horas_reservadas'] / $horasMes) * 100);
            }

            $resultado[] = [
                'cabina' => $fila['nombre'],
                'porcentaje' => round($porcentaje, 2)
            ];
        }

        return $resultado;
    }
}
