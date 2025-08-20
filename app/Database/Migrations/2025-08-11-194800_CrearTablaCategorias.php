<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearTablaCategorias extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'codigo' => ['type' => 'VARCHAR', 'constraint' => 30],
            'nombre' => ['type' => 'VARCHAR', 'constraint' => 120],
            'orden'  => ['type' => 'INT', 'constraint' => 11, 'default' => 999],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('codigo');
        $this->forge->createTable('categorias', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('categorias', true);
    }
}
