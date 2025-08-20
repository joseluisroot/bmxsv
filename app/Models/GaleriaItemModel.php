<?php

namespace App\Models;

use CodeIgniter\Model;

class GaleriaItemModel extends Model
{
    protected $table = 'galeria_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'album_id','tipo','destacado','categoria','anio','titulo','alt',
        'src','thumb','video_provider','video_id','video_url','duracion_seg',
        'orden','publicado'
    ];
    protected $useTimestamps = true;
}