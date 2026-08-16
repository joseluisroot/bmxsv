<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTimingInvalidEventsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'hit_entrenamiento_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],

            'device_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'timing_point_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'chip_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'timestamp_ms' => [
                'type' => 'BIGINT',
                'null' => true,
            ],

            'event_source' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'API',
            ],

            'validator' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
            ],

            'severity' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'ERROR',
            ],

            'message' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            'payload_raw' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],

            'details_raw' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addKey('hit_entrenamiento_id');
        $this->forge->addKey('device_code');
        $this->forge->addKey('timing_point_code');
        $this->forge->addKey('validator');
        $this->forge->addKey('event_source');
        $this->forge->addKey('severity');

        $this->forge->addForeignKey(
            'hit_entrenamiento_id',
            'hits_entrenamiento',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->forge->createTable('timing_invalid_events');
    }

    public function down()
    {
        $this->forge->dropTable('timing_invalid_events');
    }
}
