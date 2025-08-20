<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableGaleriaItems extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'album_id'    => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'null'=>true], // opcional
            'tipo'        => ['type'=>'ENUM','constraint'=>['photo','video'],'default'=>'photo'],
            'destacado'   => ['type'=>'TINYINT','constraint'=>1,'unsigned'=>true,'default'=>0], // 1=featured
            'categoria'   => ['type'=>'VARCHAR','constraint'=>60,'null'=>true], // campeonato/entrenamiento/openhouse/...
            'anio'        => ['type'=>'SMALLINT','unsigned'=>true,'null'=>true],
            'titulo'      => ['type'=>'VARCHAR','constraint'=>160,'null'=>true],
            'alt'         => ['type'=>'VARCHAR','constraint'=>160,'null'=>true],
            // Foto
            'src'         => ['type'=>'VARCHAR','constraint'=>255,'null'=>true], // ruta a la imagen (obligatoria si tipo=photo)
            'thumb'       => ['type'=>'VARCHAR','constraint'=>255,'null'=>true], // miniatura optimizada
            // Video
            'video_provider' => ['type'=>'VARCHAR','constraint'=>30,'null'=>true], // youtube|vimeo|html5
            'video_id'       => ['type'=>'VARCHAR','constraint'=>120,'null'=>true], // id del video proveedor
            'video_url'      => ['type'=>'VARCHAR','constraint'=>255,'null'=>true], // url o embed (html5 src)
            'duracion_seg'   => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'null'=>true],
            'orden'          => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'default'=>999],
            'publicado'      => ['type'=>'TINYINT','constraint'=>1,'unsigned'=>true,'default'=>1],
            'created_at'     => ['type'=>'DATETIME','null'=>true],
            'updated_at'     => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['categoria','anio','destacado','publicado','orden']);
        $this->forge->addForeignKey('album_id','galeria_album','id','SET NULL','CASCADE');
        $this->forge->createTable('galeria_items', true, ['ENGINE'=>'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('galeria_items', true);
    }
}
