<?php

namespace App\Models;

use CodeIgniter\Model;

class AatDeviceModel extends Model
{
    protected $table = 'aat_devices';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'uid',
        'serial_number',
        'ownership_type',
        'owner_athlete_id',
        'status',
        'firmware_version',
        'battery_mv',
        'last_seen_at',
        'notes',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = false;
}
