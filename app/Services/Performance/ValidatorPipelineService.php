<?php

namespace App\Services\Performance;

class ValidatorPipelineService
{
    protected SequenceValidatorService $sequenceValidator;
    protected TimestampValidatorService $timestampValidator;
    protected DebounceValidatorService $debounceValidator;
    protected DeviceValidatorService $deviceValidator;

    public function __construct()
    {
        $this->sequenceValidator = new SequenceValidatorService();
        $this->timestampValidator = new TimestampValidatorService();
        $this->debounceValidator = new DebounceValidatorService();
        $this->deviceValidator = new DeviceValidatorService();
    }

    public function validate(array $context): array
    {

        $required = [
            'hit_id',
            'device_code',
            'timing_point_code',
            'timestamp_ms',
        ];

        foreach ($required as $field) {
            if (!isset($context[$field]) || $context[$field] === '') {
                return $this->fail('ValidatorPipelineService', [
                    'success' => false,
                    'message' => "Contexto inválido. Falta {$field}.",
                ]);
            }
        }

        $hitId = (int) $context['hit_id'];
        $timingPointCode = (string) $context['timing_point_code'];
        $timestampMs = (int) $context['timestamp_ms'];

        $deviceValidation = $this->deviceValidator->validate(
            (string) $context['device_code'],
            $timingPointCode
        );

        if (empty($deviceValidation['success'])) {
            return $this->fail(
                'DeviceValidatorService',
                $deviceValidation
            );
        }

        $sequenceValidation = $this->sequenceValidator->validate(
            $hitId,
            $timingPointCode
        );

        if (empty($sequenceValidation['success'])) {
            return $this->fail(
                'SequenceValidatorService',
                $sequenceValidation
            );
        }

        $timestampValidation = $this->timestampValidator->validate(
            $hitId,
            $timestampMs
        );

        if (empty($timestampValidation['success'])) {
            return $this->fail(
                'TimestampValidatorService',
                $timestampValidation
            );
        }

        $debounceValidation = $this->debounceValidator->validate(
            $hitId,
            $timestampMs
        );

        if (empty($debounceValidation['success'])) {
            return $this->fail(
                'DebounceValidatorService',
                $debounceValidation
            );
        }

        return [
            'success' => true,
            'punto' => $deviceValidation['punto'],
            'dispositivo' => $deviceValidation['dispositivo'],
        ];
    }

    private function fail(string $validator, array $result): array
    {
        return [
            'success' => false,
            'validator' => $validator,
            'message' => $result['message'] ?? 'Evento rechazado.',
            'details' => $result,
        ];
    }
}