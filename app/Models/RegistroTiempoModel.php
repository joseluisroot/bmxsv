<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistroTiempoModel extends Model
{
    protected $table = 'registros_tiempo';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'event_id',
        'hit_entrenamiento_id',
        'punto_control_id',
        'dispositivo_tiempo_id',
        'timestamp_ms',
        'payload_raw',
    ];

    protected $useTimestamps = false;
}
