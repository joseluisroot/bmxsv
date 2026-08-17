<?php

namespace App\Models;

use CodeIgniter\Model;

class DispositivoTiempoModel extends Model
{
    protected $table = 'dispositivos_tiempo';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'codigo_dispositivo',
        'punto_control_id',
        'tipo_dispositivo',
        'tipo_sensor',
        'network_mode',
        'endpoint_url',
        'ip_address',
        'firmware_version',
        'clock_status',
        'last_sync_at',
        'clock_offset_us',
        'battery_mv',
        'signal_dbm',
        'health_status',
        'notes',
        'activo',
        'ultima_conexion',
    ];

    protected $useTimestamps = true;
}
