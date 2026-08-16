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
        'modo_hits',
        'configuracion_bicicleta_default_id',
        'auto_close_hit',
        'nodo_inicio_id',
        'nodo_fin_id',
    ];

    protected $useTimestamps = true;
}
