<?php

namespace App\Models;

use CodeIgniter\Model;

class RankingDetalleModel extends Model
{
    protected $table = 'ranking_detalles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['periodo_id','categoria_id','atleta_id','posicion','valor_num','valor_ms','notas'];
    protected $useTimestamps = true;
}