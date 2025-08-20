<?php

namespace App\Models;

use CodeIgniter\Model;

class RankingMotivoModel extends Model
{
    protected $table = 'ranking_motivos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['codigo','nombre','descripcion'];
    protected $useTimestamps = true;
}