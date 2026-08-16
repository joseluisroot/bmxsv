<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfiguracionBicicletaModel extends Model
{
    protected $table = 'configuraciones_bicicleta';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'bicicleta_id',
        'plato',
        'pinon',
        'biela_mm',
        'presion_llanta_delantera',
        'presion_llanta_trasera',
        'posicion_manubrio',
        'posicion_asiento',
        'notas',
    ];

    protected $useTimestamps = true;
}
