<?php

namespace App\Models;

use CodeIgniter\Model;

class RankingPeriodoModel extends Model
{
    protected $table = 'ranking_periodos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['motivo_id','anio','mes','nombre_publico','metric','sort_direction','publicado'];
    protected $useTimestamps = true;
}