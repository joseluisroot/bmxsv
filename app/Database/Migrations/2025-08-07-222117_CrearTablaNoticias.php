<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearTablaNoticias extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'titulo' => ['type' => 'VARCHAR', 'constraint' => 200],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 200, 'unique' => true],
            'resumen' => ['type' => 'TEXT', 'null' => true],
            'contenido' => ['type' => 'LONGTEXT'],
            'imagen_destacada' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'autor' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => 'Redacción BMXSV'],
            'fecha_publicacion' => ['type' => 'TIMESTAMP', 'null' => false, 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),],
            'etiquetas' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('noticias');
    }

    public function down()
    {
        $this->forge->dropTable('noticias');
    }
}
