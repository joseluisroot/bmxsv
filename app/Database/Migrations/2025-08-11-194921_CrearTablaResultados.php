<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearTablaResultados extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'competencia_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'categoria_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'atleta_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tiempo_ms'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'posicion'       => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true, 'null' => true],
            'ronda'          => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['competencia_id', 'categoria_id']);
        $this->forge->addForeignKey('competencia_id', 'competencias', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('categoria_id', 'categorias', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('atleta_id', 'atletas', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('resultados', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('resultados');
    }
}
