<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegistrosTiempo extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'hit_entrenamiento_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'punto_control_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'dispositivo_tiempo_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'timestamp_ms' => [
                'type' => 'BIGINT',
            ],
            'payload_raw' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('hit_entrenamiento_id');
        $this->forge->addKey('punto_control_id');
        $this->forge->addKey('dispositivo_tiempo_id');
        $this->forge->addKey('timestamp_ms');
        $this->forge->addForeignKey('hit_entrenamiento_id', 'hits_entrenamiento', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('punto_control_id', 'puntos_control', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('dispositivo_tiempo_id', 'dispositivos_tiempo', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('registros_tiempo');
    }

    public function down()
    {
        $this->forge->dropTable('registros_tiempo');
    }
}
