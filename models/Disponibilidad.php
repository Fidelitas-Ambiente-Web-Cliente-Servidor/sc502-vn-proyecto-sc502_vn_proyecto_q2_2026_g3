<?php

require_once __DIR__ . '/../config/database.php';

class Disponibilidad
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function obtenerTodasLasCabinas(): array
    {
        $stmt = $this->db->query("SELECT * FROM cabinas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCabinasDisponibles(): array
    {
        $stmt = $this->db->query("SELECT * FROM cabinas WHERE estado = 'activa'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCalendarioSemana(DateTimeImmutable $lunes, DateTimeImmutable $domingo): array
    {
        $cabinas = $this->db->query("SELECT id, nombre, estado FROM cabinas ORDER BY nombre ASC")
            ->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare("
            SELECT cabina_id, fecha_reserva, fecha_fin
            FROM historial_reservas_clientes
            WHERE estado <> 'cancelada'
              AND fecha_reserva < :fin
              AND fecha_fin > :inicio
        ");
        $stmt->execute([
            'inicio' => $lunes->format('Y-m-d 00:00:00'),
            'fin' => $domingo->format('Y-m-d 23:59:59')
        ]);
        $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $reservasPorCabina = [];
        foreach ($reservas as $reserva) {
            $reservasPorCabina[$reserva['cabina_id']][] = [
                'inicio' => new DateTimeImmutable($reserva['fecha_reserva']),
                'fin' => new DateTimeImmutable($reserva['fecha_fin'])
            ];
        }

        $resultado = [];
        foreach ($cabinas as $cabina) {
            $estados = [];

            for ($i = 0; $i < 7; $i++) {
                if (in_array($cabina['estado'], ['mantenimiento', 'inactiva'], true)) {
                    $estados[] = $cabina['estado'];
                    continue;
                }

                $diaInicio = $lunes->modify("+{$i} day")->setTime(0, 0, 0);
                $diaFin = $diaInicio->setTime(23, 59, 59);

                $ocupada = false;
                foreach ($reservasPorCabina[$cabina['id']] ?? [] as $intervalo) {
                    if ($intervalo['inicio'] < $diaFin && $intervalo['fin'] > $diaInicio) {
                        $ocupada = true;
                        break;
                    }
                }

                $estados[] = $ocupada ? 'ocupada' : 'disponible';
            }

            $resultado[] = [
                'id' => (int) $cabina['id'],
                'nombre' => $cabina['nombre'],
                'estados' => $estados
            ];
        }

        return $resultado;
    }
}