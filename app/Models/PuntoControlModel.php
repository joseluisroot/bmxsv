<?php

namespace App\Models;

use CodeIgniter\Model;

class PuntoControlModel extends Model
{
    protected $table = 'puntos_control';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'codigo',
        'nombre',
        'descripcion',
        'orden',
        'activo',
    ];

    protected $useTimestamps = true;
}
