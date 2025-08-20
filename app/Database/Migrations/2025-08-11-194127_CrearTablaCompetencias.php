<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearTablaCompetencias extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nombre'     => ['type' => 'VARCHAR', 'constraint' => 200],
            'sede'       => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'fecha'      => ['type' => 'DATE', 'null' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('competencias', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('competencias', true);
    }
}
