<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableGaleriaAlbum extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'slug'          => ['type'=>'VARCHAR','constraint'=>160],
            'titulo'        => ['type'=>'VARCHAR','constraint'=>160],
            'descripcion'   => ['type'=>'TEXT','null'=>true],
            'categoria'     => ['type'=>'VARCHAR','constraint'=>80,'null'=>true], // p.ej. "Campeonato", "Entrenos"
            'fecha_evento'  => ['type'=>'DATE','null'=>true],
            'anio'          => ['type'=>'SMALLINT','unsigned'=>true,'null'=>true], // para filtrar rápido
            'portada'       => ['type'=>'VARCHAR','constraint'=>255,'null'=>true], // ruta a imagen portada
            'publicado'     => ['type'=>'TINYINT','constraint'=>1,'unsigned'=>true,'default'=>1],
            'created_at'    => ['type'=>'DATETIME','null'=>true],
            'updated_at'    => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey(['anio','categoria','publicado']);
        $this->forge->createTable('galeria_album', true, ['ENGINE'=>'InnoDB']);
    }
    public function down()
    {
        $this->forge->dropTable('galeria_album', true);
    }
}
