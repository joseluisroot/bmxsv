<?php

namespace App\Models;

use CodeIgniter\Model;

class BicicletaModel extends Model
{
    protected $table = 'bicicletas';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'atleta_id',
        'marca',
        'modelo',
        'talla',
        'numero_serie',
        'notas',
    ];

    protected $useTimestamps = true;
}
