<?php

namespace App\Models;

use CodeIgniter\Model;

class AtletaModel extends Model
{
    protected $table = 'atletas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nombres', 'apellidos', 'slug', 'categoria', 'club', 'edad', 'anios_bmx',
        'palmares', 'estilo', 'equipamiento', 'hobbies', 'foto'
    ];
}