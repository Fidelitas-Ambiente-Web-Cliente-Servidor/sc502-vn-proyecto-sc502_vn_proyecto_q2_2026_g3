<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Pago.php';

class PagoController
{
    private Pago $model;
    private array $metodosValidos = ['sinpe', 'transferencia', 'efectivo', 'tarjeta'];

    public function __construct()
    {
        $this->model = new Pago();
    }

    public function index(): void
    {
        require __DIR__ . '/../views/pagos/index.php';
    }

    public function listarReservasPendientes(): void
    {
        echo json_encode([
            'response' => '00',
            'reservas' => $this->model->getReservasConPendiente()
        ]);
    }

    public function listarPagos(): void
    {
        echo json_encode([
            'response' => '00',
            'pagos' => $this->model->getAllPagos()
        ]);
    }

    public function crear(): void
    {
        $datos = $this->validar($_POST);

        if ($datos['error']) {
            echo json_encode(['response' => '01', 'message' => $datos['error']]);
            return;
        }

        $reserva = $this->model->getReservaById($datos['reserva_id']);
        if (!$reserva) {
            echo json_encode(['response' => '01', 'message' => 'La reserva seleccionada no existe']);
            return;
        }

        $montoPendiente = (float) $reserva['pendiente'];
        if ($montoPendiente <= 0) {
            echo json_encode(['response' => '01', 'message' => 'La reserva seleccionada ya está pagada']);
            return;
        }

        if ($datos['monto'] > $montoPendiente) {
            echo json_encode([
                'response' => '01',
                'message' => 'El monto excede el pendiente de la reserva'
            ]);
            return;
        }

        $pagoId = $this->model->createPago(
            $datos['reserva_id'],
            $datos['monto'],
            $datos['metodo'],
            $datos['comprobante']
        );

        $pago = $this->model->getPagoById($pagoId);

        echo json_encode([
            'response' => '00',
            'message' => 'Pago registrado exitosamente',
            'pago' => $pago
        ]);
    }

    private function validar(array $input): array
    {
        $reservaId = (int) ($input['reserva_id'] ?? 0);
        $monto = (float) ($input['monto'] ?? 0);
        $metodo = trim($input['metodo'] ?? '');
        $comprobante = trim($input['comprobante'] ?? '');

        if ($reservaId <= 0) {
            return ['error' => 'Debe seleccionar una reserva'];
        }

        if ($monto <= 0) {
            return ['error' => 'El monto debe ser mayor a cero'];
        }

        if (!in_array($metodo, $this->metodosValidos, true)) {
            return ['error' => 'El método de pago no es válido'];
        }

        return [
            'error' => null,
            'reserva_id' => $reservaId,
            'monto' => round($monto, 2),
            'metodo' => $metodo,
            'comprobante' => $comprobante !== '' ? $comprobante : null
        ];
    }
}
