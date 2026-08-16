<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChipsIdentificacionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'atleta_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'codigo_chip' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'default' => 'RFID_UHF',
            ],
            'activo' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'notas' => [
                'type' => 'TEXT',
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
        $this->forge->addUniqueKey('codigo_chip');
        $this->forge->addForeignKey('atleta_id', 'atletas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('chips_identificacion');
    }

    public function down()
    {
        $this->forge->dropTable('chips_identificacion');
    }
}
