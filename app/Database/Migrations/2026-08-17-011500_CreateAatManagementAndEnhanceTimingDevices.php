<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAatManagementAndEnhanceTimingDevices extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'uid' => ['type' => 'VARCHAR', 'constraint' => 100],
            'serial_number' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'ownership_type' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'club'],
            'owner_athlete_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'available'],
            'firmware_version' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'battery_mv' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'last_seen_at' => ['type' => 'DATETIME', 'null' => true],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('uid');
        $this->forge->addUniqueKey('serial_number');
        $this->forge->addKey('owner_athlete_id');
        $this->forge->addForeignKey('owner_athlete_id', 'atletas', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('aat_devices');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'aat_device_id' => ['type' => 'INT', 'unsigned' => true],
            'atleta_id' => ['type' => 'INT', 'unsigned' => true],
            'sesion_entrenamiento_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'assignment_type' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'loan'],
            'starts_at' => ['type' => 'DATETIME'],
            'ends_at' => ['type' => 'DATETIME', 'null' => true],
            'returned_at' => ['type' => 'DATETIME', 'null' => true],
            'active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('aat_device_id');
        $this->forge->addKey('atleta_id');
        $this->forge->addKey('sesion_entrenamiento_id');
        $this->forge->addKey(['aat_device_id', 'active']);
        $this->forge->addForeignKey('aat_device_id', 'aat_devices', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('atleta_id', 'atletas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('sesion_entrenamiento_id', 'sesiones_entrenamiento', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('aat_assignments');

        $this->forge->addColumn('dispositivos_tiempo', [
            'network_mode' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'local', 'after' => 'tipo_sensor'],
            'endpoint_url' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'network_mode'],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true, 'after' => 'endpoint_url'],
            'firmware_version' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'ip_address'],
            'clock_status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'unknown', 'after' => 'firmware_version'],
            'last_sync_at' => ['type' => 'DATETIME', 'null' => true, 'after' => 'clock_status'],
            'clock_offset_us' => ['type' => 'INT', 'null' => true, 'after' => 'last_sync_at'],
            'battery_mv' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'clock_offset_us'],
            'signal_dbm' => ['type' => 'INT', 'null' => true, 'after' => 'battery_mv'],
            'health_status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'unknown', 'after' => 'signal_dbm'],
            'notes' => ['type' => 'TEXT', 'null' => true, 'after' => 'health_status'],
        ]);
    }

    public function down()
    {
        foreach ([
            'network_mode', 'endpoint_url', 'ip_address', 'firmware_version', 'clock_status',
            'last_sync_at', 'clock_offset_us', 'battery_mv', 'signal_dbm', 'health_status', 'notes',
        ] as $column) {
            $this->forge->dropColumn('dispositivos_tiempo', $column);
        }

        $this->forge->dropTable('aat_assignments', true);
        $this->forge->dropTable('aat_devices', true);
    }
}
