<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearTablaAtletaGaleria extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'atleta_id'   => ['type' => 'INT', 'unsigned' => true],
            'imagen'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'titulo'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'descripcion' => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('atleta_id', 'atletas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('atleta_galeria');
    }

    public function down()
    {
        $this->forge->dropTable('atleta_galeria');
    }
}
