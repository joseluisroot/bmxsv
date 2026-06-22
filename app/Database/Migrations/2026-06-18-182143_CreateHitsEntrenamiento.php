<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHitsEntrenamiento extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'sesion_entrenamiento_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'atleta_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'configuracion_bicicleta_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'numero_hit' => [
                'type' => 'INT',
            ],
            'tipo_hit' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'entrenamiento',
            ],
            'estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'pendiente',
            ],
            'notas_coach' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'sensacion_atleta' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('sesion_entrenamiento_id');
        $this->forge->addKey('atleta_id');
        $this->forge->addKey('configuracion_bicicleta_id');
        $this->forge->addForeignKey('sesion_entrenamiento_id', 'sesiones_entrenamiento', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('atleta_id', 'atletas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('configuracion_bicicleta_id', 'configuraciones_bicicleta', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hits_entrenamiento');
    }

    public function down()
    {
        $this->forge->dropTable('hits_entrenamiento');
    }
}
