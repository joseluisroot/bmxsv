<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDispositivosTiempo extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'codigo_dispositivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'punto_control_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'tipo_dispositivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'ESP32',
            ],
            'tipo_sensor' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'activo' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'ultima_conexion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('codigo_dispositivo');
        $this->forge->addKey('punto_control_id');
        $this->forge->addForeignKey('punto_control_id', 'puntos_control', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dispositivos_tiempo');
    }

    public function down()
    {
        $this->forge->dropTable('dispositivos_tiempo');
    }
}
