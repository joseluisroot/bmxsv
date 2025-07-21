<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAtletasTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nombres'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'apellidos'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug'           => ['type' => 'VARCHAR', 'constraint' => 150, 'unique' => true],
            'categoria'      => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'club'           => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'edad'           => ['type' => 'INT', 'null' => true],
            'anios_bmx'      => ['type' => 'INT', 'null' => true],
            'palmares'       => ['type' => 'TEXT', 'null' => true],
            'estilo'         => ['type' => 'TEXT', 'null' => true],
            'equipamiento'   => ['type' => 'TEXT', 'null' => true],
            'hobbies'        => ['type' => 'TEXT', 'null' => true],
            'foto'           => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('atletas');
    }

    public function down()
    {
        $this->forge->dropTable('atletas');
    }
}
