<?php

namespace App\Models;

use CodeIgniter\Model;

class SesionEntrenamientoModel extends Model
{
    protected $table = 'sesiones_entrenamiento';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre',
        'pista',
        'fecha',
        'coach',
        'objetivo',
        'clima',
        'estado_pista',
        'notas',
        'estado',
    ];

    protected $useTimestamps = true;
}
