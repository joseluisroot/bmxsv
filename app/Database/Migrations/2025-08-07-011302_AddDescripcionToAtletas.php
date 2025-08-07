<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDescripcionToAtletas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('atletas', [
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'hobbies' // Opcional: lo ubica después del campo hobbies
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('atletas', 'descripcion');
    }
}
