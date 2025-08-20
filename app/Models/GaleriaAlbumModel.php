<?php

namespace App\Models;

use CodeIgniter\Model;

class GaleriaAlbumModel extends Model
{
    protected $table = 'galeria_album';
    protected $primaryKey = 'id';
    protected $allowedFields = ['slug','titulo','descripcion','categoria','fecha_evento','anio','portada','publicado'];
    protected $useTimestamps = true;
}