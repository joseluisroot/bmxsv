<?php

namespace App\Models;

use CodeIgniter\Model;

class RedesSocialesModel extends Model
{
    protected $table            = 'redes_sociales';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'atleta_id',
        'plataforma',
        'url',
        'icono',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $returnType       = 'array';

    public function getByAtletaId($atletaId)
    {
        return $this->where('atleta_id', $atletaId)->findAll();
    }
}