<?php

namespace App\Services\Performance;

use App\Models\PuntoControlModel;
use App\Repositories\Performance\TimingRepository;

class SequenceValidatorService
{
    protected TimingRepository $timingRepository;
    protected PuntoControlModel $pointModel;

    public function __construct()
    {
        $this->timingRepository = new TimingRepository();
        $this->pointModel = new PuntoControlModel();
    }

    public function validate(int $hitId, string $incomingPoint, ?array $session = null): array
    {
        $sequence = $this->resolveSequence($session);

        if (empty($sequence['success'])) {
            return $sequence;
        }

        $points = $sequence['points'];
        $startCode = $points[0]['codigo'];
        $endCode = $points[count($points) - 1]['codigo'];

        $lastEvent = $this->timingRepository->getLastEventByHit($hitId);

        if (!$lastEvent) {
            if ($incomingPoint !== $startCode) {
                return [
                    'success' => false,
                    'message' => "La secuencia debe iniciar en {$startCode}.",
                    'expected' => $startCode,
                    'received' => $incomingPoint,
                ];
            }

            return ['success' => true, 'start' => $startCode, 'end' => $endCode];
        }

        $lastPointCode = $lastEvent['codigo'] ?? null;
        $codes = array_column($points, 'codigo');
        $lastIndex = array_search($lastPointCode, $codes, true);

        if ($lastIndex === false) {
            return [
                'success' => false,
                'message' => 'El último punto registrado no pertenece a la secuencia configurada para la sesión.',
                'last_point' => $lastPointCode,
            ];
        }

        if ($lastPointCode === $endCode) {
            return [
                'success' => false,
                'message' => 'El hit ya llegó al último punto configurado.',
                'expected' => null,
                'received' => $incomingPoint,
            ];
        }

        $expected = $codes[$lastIndex + 1] ?? null;

        if ($incomingPoint !== $expected) {
            return [
                'success' => false,
                'message' => "Secuencia inválida. Se esperaba {$expected}.",
                'expected' => $expected,
                'received' => $incomingPoint,
                'last_point' => $lastPointCode,
            ];
        }

        return ['success' => true, 'start' => $startCode, 'end' => $endCode];
    }

    private function resolveSequence(?array $session): array
    {
        $startId = (int) ($session['nodo_inicio_id'] ?? 0);
        $endId = (int) ($session['nodo_fin_id'] ?? 0);

        if ($startId <= 0 || $endId <= 0) {
            return [
                'success' => false,
                'message' => 'La sesión debe tener nodo de inicio y nodo de fin configurados.',
            ];
        }

        $start = $this->pointModel->find($startId);
        $end = $this->pointModel->find($endId);

        if (!$start || !$end || empty($start['codigo']) || empty($end['codigo'])) {
            return [
                'success' => false,
                'message' => 'La configuración de nodos de la sesión es inválida.',
            ];
        }

        if ((int) $start['orden'] > (int) $end['orden']) {
            return [
                'success' => false,
                'message' => 'El nodo inicial no puede estar después del nodo final.',
            ];
        }

        $points = $this->pointModel
            ->where('activo', 1)
            ->where('orden >=', (int) $start['orden'])
            ->where('orden <=', (int) $end['orden'])
            ->orderBy('orden', 'ASC')
            ->findAll();

        if (empty($points)) {
            return [
                'success' => false,
                'message' => 'No se pudo construir la secuencia de puntos para la sesión.',
            ];
        }

        return ['success' => true, 'points' => $points];
    }
}
