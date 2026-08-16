<?php

namespace App\Models;

use CodeIgniter\Model;

class HitEntrenamientoModel extends Model
{
    protected $table = 'hits_entrenamiento';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'sesion_entrenamiento_id',
        'atleta_id',
        'configuracion_bicicleta_id',
        'numero_hit',
        'tipo_hit',
        'estado',
        'notas_coach',
        'sensacion_atleta',
    ];

    protected $useTimestamps = true;
}
