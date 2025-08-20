<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableRankingPeriodos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'motivo_id'      => ['type'=>'INT','constraint'=>11,'unsigned'=>true],
            'anio'           => ['type'=>'SMALLINT','unsigned'=>true],   // 2025
            'mes'            => ['type'=>'TINYINT','unsigned'=>true],    // 1..12
            'nombre_publico' => ['type'=>'VARCHAR','constraint'=>150,'null'=>true], // ej: "Agosto 2025"
            'metric'         => ['type'=>'VARCHAR','constraint'=>30],     // 'points' | 'time_ms' | 'custom'
            'sort_direction' => ['type'=>'ENUM','constraint'=>['ASC','DESC'],'default'=>'DESC'], // puntos=DESC, time=ASC
            'publicado'      => ['type'=>'TINYINT','constraint'=>1,'unsigned'=>true,'default'=>1],
            'created_at'     => ['type'=>'DATETIME','null'=>true],
            'updated_at'     => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['motivo_id','anio','mes']);
        $this->forge->addForeignKey('motivo_id','ranking_motivos','id','CASCADE','CASCADE');
        $this->forge->createTable('ranking_periodos', true, ['ENGINE'=>'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('ranking_periodos', true);
    }
}
