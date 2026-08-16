<?php

namespace App\Models;

use CodeIgniter\Model;

class ChipIdentificacionModel extends Model
{
    protected $table = 'chips_identificacion';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'atleta_id',
        'codigo_chip',
        'tipo',
        'activo',
        'notas',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = false;
}
