<?php

namespace App\Models;

use CodeIgniter\Model;

class AtletaGaleriaModel extends Model
{
    protected $table = 'atleta_galeria';
    protected $primaryKey = 'id';
    protected $allowedFields = ['atleta_id', 'imagen', 'descripcion'];
}