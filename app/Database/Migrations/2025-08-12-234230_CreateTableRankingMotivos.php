<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableRankingMotivos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'codigo'      => ['type'=>'VARCHAR','constraint'=>50],  // ej: RANK_MENSUAL
            'nombre'      => ['type'=>'VARCHAR','constraint'=>150],
            'descripcion' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'created_at'  => ['type'=>'DATETIME','null'=>true],
            'updated_at'  => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('codigo');
        $this->forge->createTable('ranking_motivos', true, ['ENGINE'=>'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('ranking_motivos', true);
    }
}
