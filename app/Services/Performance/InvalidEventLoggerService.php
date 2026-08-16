<?php

namespace app\Services\Performance;

use App\Models\TimingInvalidEventModel;

class InvalidEventLoggerService
{
    protected TimingInvalidEventModel $model;

    public function __construct()
    {
        $this->model = new TimingInvalidEventModel();
    }

    public function log(array $payload, array $validation): int
    {
        $this->model->insert([
            'hit_entrenamiento_id' => $payload['hit_entrenamiento_id'] ?? null,
            'device_code'          => $payload['device_code'] ?? null,
            'timing_point_code'    => $payload['timing_point_code'] ?? null,
            'chip_code'            => $payload['chip_code'] ?? null,
            'timestamp_ms'         => $payload['timestamp_ms'] ?? null,
            'event_source'         => $payload['event_source'] ?? 'API',
            'validator'            => $validation['validator'] ?? null,
            'severity'             => $validation['severity'] ?? 'ERROR',
            'message'              => $validation['message'] ?? null,
            'payload_raw'          => json_encode($payload),
            'details_raw'          => json_encode($validation['details'] ?? $validation),
            'created_at'           => date('Y-m-d H:i:s'),
        ]);

        return (int) $this->model->getInsertID();
    }
}