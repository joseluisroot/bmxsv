<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablaGaleriaFoto extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'album_id'   => ['type'=>'INT','constraint'=>11,'unsigned'=>true],
            'archivo'    => ['type'=>'VARCHAR','constraint'=>255], // ruta /uploads/galeria/...
            'titulo'     => ['type'=>'VARCHAR','constraint'=>160,'null'=>true],
            'alt'        => ['type'=>'VARCHAR','constraint'=>160,'null'=>true],
            'orden'      => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'default'=>999],
            'publicado'  => ['type'=>'TINYINT','constraint'=>1,'unsigned'=>true,'default'=>1],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['album_id','publicado','orden']);
        $this->forge->addForeignKey('album_id','galeria_album','id','CASCADE','CASCADE');
        $this->forge->createTable('galeria_foto', true, ['ENGINE'=>'InnoDB']);
    }
    public function down()
    {
        $this->forge->dropTable('galeria_foto', true);
    }
}
