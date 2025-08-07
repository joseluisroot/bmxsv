<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearTablaAtletaUsuario extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'usuario_id' => ['type' => 'INT', 'unsigned' => true],
            'atleta_id'  => ['type' => 'INT', 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('atleta_id', 'atletas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('atleta_usuario');
    }

    public function down()
    {
        $this->forge->dropTable('atleta_usuario');
    }
}
