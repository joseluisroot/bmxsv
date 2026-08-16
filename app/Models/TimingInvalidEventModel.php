<?php

namespace App\Models;

use CodeIgniter\Model;

class TimingInvalidEventModel extends Model
{
    protected $table = 'timing_invalid_events';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'hit_entrenamiento_id',
        'device_code',
        'timing_point_code',
        'chip_code',
        'timestamp_ms',
        'event_source',
        'validator',
        'severity',
        'message',
        'payload_raw',
        'details_raw',
        'created_at',
    ];

    protected $useTimestamps = false;
}
