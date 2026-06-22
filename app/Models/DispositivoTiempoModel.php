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
        'activo',
        'ultima_conexion',
    ];

    protected $useTimestamps = true;
}
