<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Reserva.php';

class ReservaController
{
    private Reserva $model;
    private array $estadosValidos = ['activa', 'finalizada', 'cancelada'];

    public function __construct()
    {
        $this->model = new Reserva();
    }

    public function index(): void
    {
        require __DIR__ . '/../views/reservas/index.php';
    }

    public function listar(): void
    {
        echo json_encode([
            'response' => '00',
            'reservas' => $this->model->getAllReservas()
        ]);
    }

    public function datosFormulario(): void
    {
        echo json_encode([
            'response' => '00',
            'clientes' => $this->model->getClientes(),
            'cabinas' => $this->model->getCabinasReservables()
        ]);
    }

    public function crear(): void
    {
        $datos = $this->validar($_POST);

        if ($datos['error']) {
            echo json_encode(['response' => '01', 'message' => $datos['error']]);
            return;
        }

        $validacionEntidad = $this->validarEntidades($datos);
        if ($validacionEntidad['error']) {
            echo json_encode(['response' => '01', 'message' => $validacionEntidad['error']]);
            return;
        }

        if ($this->model->existeTraslapeCabina($datos['cabina_id'], $datos['fecha_reserva'], $datos['fecha_fin'])) {
            echo json_encode([
                'response' => '01',
                'message' => 'La cabina seleccionada ya tiene una reserva activa en ese rango de fechas'
            ]);
            return;
        }

        $reservaId = $this->model->createReserva(
            $datos['cliente_id'],
            $datos['cabina_id'],
            $datos['fecha_reserva'],
            $datos['fecha_fin'],
            $datos['huespedes']
        );

        echo json_encode([
            'response' => '00',
            'message' => 'Reserva registrada exitosamente',
            'reserva' => $this->model->getReservaById($reservaId)
        ]);
    }

    public function actualizar(int $id): void
    {
        $reserva = $this->model->getReservaById($id);
        if (!$reserva) {
            echo json_encode(['response' => '01', 'message' => 'Reserva no encontrada']);
            return;
        }

        if (($reserva['estado'] ?? '') === 'cancelada') {
            echo json_encode(['response' => '01', 'message' => 'No se puede editar una reserva cancelada']);
            return;
        }

        $datos = $this->validar($_POST);
        if ($datos['error']) {
            echo json_encode(['response' => '01', 'message' => $datos['error']]);
            return;
        }

        $validacionEntidad = $this->validarEntidades($datos);
        if ($validacionEntidad['error']) {
            echo json_encode(['response' => '01', 'message' => $validacionEntidad['error']]);
            return;
        }

        if ($this->model->existeTraslapeCabina($datos['cabina_id'], $datos['fecha_reserva'], $datos['fecha_fin'], $id)) {
            echo json_encode([
                'response' => '01',
                'message' => 'La cabina seleccionada ya tiene una reserva activa en ese rango de fechas'
            ]);
            return;
        }

        $this->model->updateReserva(
            $id,
            $datos['cliente_id'],
            $datos['cabina_id'],
            $datos['fecha_reserva'],
            $datos['fecha_fin'],
            $datos['huespedes']
        );

        echo json_encode([
            'response' => '00',
            'message' => 'Reserva actualizada exitosamente',
            'reserva' => $this->model->getReservaById($id)
        ]);
    }

    public function cambiarEstado(int $id): void
    {
        $estado = trim($_POST['estado'] ?? '');
        if (!in_array($estado, $this->estadosValidos, true)) {
            echo json_encode(['response' => '01', 'message' => 'El estado indicado no es válido']);
            return;
        }

        $reserva = $this->model->getReservaById($id);
        if (!$reserva) {
            echo json_encode(['response' => '01', 'message' => 'Reserva no encontrada']);
            return;
        }

        if (($reserva['estado'] ?? '') === $estado) {
            echo json_encode(['response' => '00', 'message' => 'La reserva ya se encuentra en ese estado']);
            return;
        }

        if ($estado === 'finalizada' && (float) ($reserva['pendiente'] ?? 0) > 0) {
            echo json_encode([
                'response' => '01',
                'message' => 'No se puede finalizar una reserva con saldo pendiente'
            ]);
            return;
        }

        $this->model->actualizarEstado($id, $estado);

        echo json_encode([
            'response' => '00',
            'message' => 'Estado de reserva actualizado exitosamente',
            'reserva' => $this->model->getReservaById($id)
        ]);
    }

    private function validar(array $input): array
    {
        $clienteId = (int) ($input['cliente_id'] ?? 0);
        $cabinaId = (int) ($input['cabina_id'] ?? 0);
        $huespedes = (int) ($input['huespedes'] ?? 0);

        $fechaReserva = $this->normalizarFecha($input['fecha_reserva'] ?? '', false);
        $fechaFin = $this->normalizarFecha($input['fecha_fin'] ?? '', true);

        if ($clienteId <= 0) {
            return ['error' => 'Debe seleccionar un cliente'];
        }

        if ($cabinaId <= 0) {
            return ['error' => 'Debe seleccionar una cabina'];
        }

        if (!$fechaReserva || !$fechaFin) {
            return ['error' => 'Debe indicar un rango de fechas válido'];
        }

        if (strtotime($fechaReserva) >= strtotime($fechaFin)) {
            return ['error' => 'La fecha de salida debe ser posterior a la de entrada'];
        }

        if ($huespedes <= 0) {
            return ['error' => 'La cantidad de huéspedes debe ser mayor a cero'];
        }

        return [
            'error' => null,
            'cliente_id' => $clienteId,
            'cabina_id' => $cabinaId,
            'fecha_reserva' => $fechaReserva,
            'fecha_fin' => $fechaFin,
            'huespedes' => $huespedes
        ];
    }

    private function validarEntidades(array $datos): array
    {
        if (!$this->model->existeCliente($datos['cliente_id'])) {
            return ['error' => 'El cliente seleccionado no existe'];
        }

        $cabina = $this->model->getCabinaReservableById($datos['cabina_id']);
        if (!$cabina) {
            return ['error' => 'La cabina seleccionada no está disponible para reservas'];
        }

        if ($datos['huespedes'] > (int) $cabina['capacidad']) {
            return ['error' => 'La cantidad de huéspedes excede la capacidad de la cabina'];
        }

        return ['error' => null];
    }

    private function normalizarFecha(string $valor, bool $esFin): ?string
    {
        $valor = trim($valor);
        if ($valor === '') {
            return null;
        }

        $formatos = ['Y-m-d\\TH:i', 'Y-m-d H:i:s', 'Y-m-d'];
        foreach ($formatos as $formato) {
            $fecha = DateTimeImmutable::createFromFormat($formato, $valor);
            if ($fecha !== false) {
                if ($formato === 'Y-m-d') {
                    $hora = $esFin ? '23:59:59' : '00:00:00';
                    return $fecha->format('Y-m-d') . ' ' . $hora;
                }

                return $fecha->format('Y-m-d H:i:s');
            }
        }

        return null;
    }
}
