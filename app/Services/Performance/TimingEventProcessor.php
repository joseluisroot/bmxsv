<?php

namespace App\Services\Performance;

class TimingEventProcessor
{
    protected HitResolverService $hitResolver;

    public function __construct()
    {
        $this->hitResolver = new HitResolverService();
    }

    public function process(array $payload): array
    {
        if (empty($payload)) {
            return [
                'success' => false,
                'message' => 'Payload vacío o inválido.',
            ];
        }

        if (empty($payload['device_code'])) {
            return [
                'success' => false,
                'message' => 'device_code es requerido.',
            ];
        }

        if (empty($payload['timing_point_code'])) {
            return [
                'success' => false,
                'message' => 'timing_point_code es requerido.',
            ];
        }

        if (empty($payload['timestamp_ms'])) {
            return [
                'success' => false,
                'message' => 'timestamp_ms es requerido.',
            ];
        }

        $hasHitId = !empty($payload['hit_entrenamiento_id']);
        $hasChipCode = !empty($payload['chip_code']);

        if ($hasHitId) {
            return $this->hitResolver->registerPassByHit($payload);
        }

        return $this->hitResolver->registerPassByChip($payload);

       /* if (!$hasHitId && !$hasChipCode) {
            return [
                'success' => false,
                'message' => 'Debe enviar hit_entrenamiento_id o chip_code.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Evento recibido correctamente.',
            'mode' => $hasChipCode ? 'chip' : 'hit',
            'payload' => $payload,
        ];*/
    }
}