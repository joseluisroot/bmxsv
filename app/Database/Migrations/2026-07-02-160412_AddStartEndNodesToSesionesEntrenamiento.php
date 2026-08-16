<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStartEndNodesToSesionesEntrenamiento extends Migration
{
    public function up()
    {
        $this->forge->addColumn('sesiones_entrenamiento', [
            'nodo_inicio_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'auto_close_hit',
            ],
            'nodo_fin_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'nodo_inicio_id',
            ],
        ]);

        $this->db->query("
            ALTER TABLE sesiones_entrenamiento
            ADD CONSTRAINT fk_sesion_nodo_inicio
            FOREIGN KEY (nodo_inicio_id)
            REFERENCES puntos_control(id)
            ON DELETE SET NULL
            ON UPDATE CASCADE
        ");

        $this->db->query("
            ALTER TABLE sesiones_entrenamiento
            ADD CONSTRAINT fk_sesion_nodo_fin
            FOREIGN KEY (nodo_fin_id)
            REFERENCES puntos_control(id)
            ON DELETE SET NULL
            ON UPDATE CASCADE
        ");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE sesiones_entrenamiento DROP FOREIGN KEY fk_sesion_nodo_inicio");
        $this->db->query("ALTER TABLE sesiones_entrenamiento DROP FOREIGN KEY fk_sesion_nodo_fin");

        $this->forge->dropColumn('sesiones_entrenamiento', 'nodo_inicio_id');
        $this->forge->dropColumn('sesiones_entrenamiento', 'nodo_fin_id');
    }
}
