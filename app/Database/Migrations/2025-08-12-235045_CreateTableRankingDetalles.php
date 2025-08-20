<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableRankingDetalles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'periodo_id'  => ['type'=>'INT','constraint'=>11,'unsigned'=>true],
            'categoria_id'=> ['type'=>'INT','constraint'=>11,'unsigned'=>true],
            'atleta_id'   => ['type'=>'INT','constraint'=>11,'unsigned'=>true],
            'posicion'    => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'null'=>true], // opcional: guarda el top ordenado
            'valor_num'   => ['type'=>'DECIMAL','constraint'=>'10,3','null'=>true], // para 'points' o métricas numéricas
            'valor_ms'    => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'null'=>true], // para 'time_ms'
            'notas'       => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'created_at'  => ['type'=>'DATETIME','null'=>true],
            'updated_at'  => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['periodo_id','categoria_id']);
        $this->forge->addForeignKey('periodo_id','ranking_periodos','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('categoria_id','categorias','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('atleta_id','atletas','id','CASCADE','CASCADE');
        $this->forge->createTable('ranking_detalles', true, ['ENGINE'=>'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('ranking_detalles', true);
    }
}
