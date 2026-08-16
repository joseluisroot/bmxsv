<?php

namespace App\Services\Performance;

class TimingEventProcessor
{
    protected HitResolverService $hitResolver;

    public function __construct(?HitResolverService $hitResolver = null)
    {
        $this->hitResolver = $hitResolver ?? new HitResolverService();
    }

    public function process(array $payload): array
    {
        if (empty($payload)) {
            return [
                'success' => false,
                'status_code' => 400,
                'message' => 'Payload vacío o inválido.',
            ];
        }

        foreach (['device_code', 'timing_point_code', 'timestamp_ms'] as $field) {
            if (!isset($payload[$field]) || $payload[$field] === '') {
                return [
                    'success' => false,
                    'status_code' => 400,
                    'message' => "{$field} es requerido.",
                ];
            }
        }

        $hasHitId = !empty($payload['hit_entrenamiento_id']);
        $hasChipCode = !empty($payload['chip_code']);

        if (!$hasHitId && !$hasChipCode) {
            return [
                'success' => false,
                'status_code' => 400,
                'message' => 'Debe enviar hit_entrenamiento_id o chip_code.',
            ];
        }

        if ($hasHitId) {
            return $this->hitResolver->registerPassByHit($payload);
        }

        return $this->hitResolver->registerPassByChip($payload);
    }
}
