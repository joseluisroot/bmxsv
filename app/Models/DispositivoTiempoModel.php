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
        'hardware_version',
        'ntp_server',
        'timezone',
        'config_status',
        'clock_status',
        'last_sync_at',
        'clock_offset_us',
        'battery_mv',
        'signal_dbm',
        'health_status',
        'api_status',
        'aat_radio_status',
        'lf_status',
        'pending_events',
        'uptime_seconds',
        'last_event_at',
        'last_config_at',
        'notes',
        'activo',
        'ultima_conexion',
    ];

    protected $useTimestamps = true;
}
