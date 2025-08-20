<?php

namespace App\Models;

use CodeIgniter\Model;

class CompetenciaModel extends Model
{
    protected $table      = 'competencias';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre','sede','fecha'];
    protected $useTimestamps = true;
}