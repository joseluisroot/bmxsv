<?php

namespace app\Services\Performance;

use App\Repositories\Performance\TimingRepository;

class SequenceValidatorService
{
    protected TimingRepository $timingRepository;

    protected array $fullTrackSequence = [
        'TP01' => 'TP02',
        'TP02' => 'TP03',
        'TP03' => 'TP04',
        'TP04' => 'TP05',
        'TP05' => 'TP06',
        'TP06' => null,
    ];

    public function __construct()
    {
        $this->timingRepository = new TimingRepository();
    }

    public function validate(int $hitId, string $incomingPoint): array
    {
        $lastEvent = $this->timingRepository->getLastEventByHit($hitId);

        if (!$lastEvent) {
            if ($incomingPoint !== 'TP01') {
                return [
                    'success' => false,
                    'message' => 'La secuencia debe iniciar en TP01.',
                    'expected' => 'TP01',
                    'received' => $incomingPoint,
                ];
            }

            return ['success' => true];
        }

        $lastPointCode = $lastEvent['codigo'] ?? $lastEvent['punto_codigo'] ?? null;

        if (!$lastPointCode && isset($lastEvent['punto_control_id'])) {
            $lastPointCode = $this->resolvePointCode((int) $lastEvent['punto_control_id']);
        }

        if (!$lastPointCode) {
            return [
                'success' => false,
                'message' => 'No se pudo resolver el último punto registrado.',
            ];
        }

        $expected = $this->fullTrackSequence[$lastPointCode] ?? null;

        if ($expected === null) {
            return [
                'success' => false,
                'message' => 'El hit ya llegó al último punto de la secuencia.',
                'expected' => null,
                'received' => $incomingPoint,
            ];
        }

        if ($incomingPoint !== $expected) {
            return [
                'success' => false,
                'message' => "Secuencia inválida. Se esperaba {$expected}.",
                'expected' => $expected,
                'received' => $incomingPoint,
                'last_point' => $lastPointCode,
            ];
        }

        return ['success' => true];
    }

    private function resolvePointCode(int $pointId): ?string
    {
        $model = new \App\Models\PuntoControlModel();

        $point = $model->find($pointId);

        return $point['codigo'] ?? null;
    }
}