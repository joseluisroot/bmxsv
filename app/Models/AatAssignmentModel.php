<?php

namespace App\Models;

use CodeIgniter\Model;

class AatAssignmentModel extends Model
{
    protected $table = 'aat_assignments';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'aat_device_id',
        'atleta_id',
        'sesion_entrenamiento_id',
        'assignment_type',
        'starts_at',
        'ends_at',
        'returned_at',
        'active',
        'notes',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = false;
}
