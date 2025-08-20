<?php

namespace App\Models;

use CodeIgniter\Model;

class GaleriaFotoModel extends Model
{
    protected $table = 'galeria_foto';
    protected $primaryKey = 'id';
    protected $allowedFields = ['album_id','archivo','titulo','alt','orden','publicado'];
    protected $useTimestamps = true;
}