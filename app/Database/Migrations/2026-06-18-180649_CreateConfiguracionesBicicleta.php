<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConfiguracionesBicicleta extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'bicicleta_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'plato' => [
                'type' => 'INT',
            ],
            'pinon' => [
                'type' => 'INT',
                'null' => true,
            ],
            'biela_mm' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'presion_llanta_delantera' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'presion_llanta_trasera' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'posicion_manubrio' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'posicion_asiento' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
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
        $this->forge->addKey('bicicleta_id');
        $this->forge->addKey('plato');
        $this->forge->addForeignKey('bicicleta_id', 'bicicletas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('configuraciones_bicicleta');
    }

    public function down()
    {
        $this->forge->dropTable('configuraciones_bicicleta');
    }
}
