<?php

namespace App\Models;

use CodeIgniter\Model;

class ResultadoModel extends Model
{
    protected $table      = 'resultados';
    protected $primaryKey = 'id';
    protected $allowedFields = ['competencia_id','categoria_id','atleta_id','tiempo_ms','posicion','ronda'];
    protected $useTimestamps = true;
}