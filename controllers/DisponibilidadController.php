<?php
class DisponibilidadController {
    public function index()
    {
        $cabinas = $this->getCabinas();

        require __DIR__ . '/../views/disponibilidad/index.php';
    }

    private function getCabinas()
    {
        // Simulación de datos de base de datos
        return [
            ['id' => 1, 'nombre' => 'Cabina Vista al Mar',    'capacidad' => 4, 'disponible' => true],
            ['id' => 2, 'nombre' => 'Cabina Bosque Tropical', 'capacidad' => 2, 'disponible' => false],
            ['id' => 3, 'nombre' => 'Cabina Familiar',        'capacidad' => 6, 'disponible' => true],
            ['id' => 4, 'nombre' => 'Cabina Rústica',         'capacidad' => 3, 'disponible' => false],
            ['id' => 5, 'nombre' => 'Cabina Premium',         'capacidad' => 5, 'disponible' => true],
        ];
    }
}
