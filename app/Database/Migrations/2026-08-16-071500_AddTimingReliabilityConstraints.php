<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use RuntimeException;

class AddTimingReliabilityConstraints extends Migration
{
    public function up()
    {
        $this->forge->addColumn('registros_tiempo', [
            'event_id' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
                'after' => 'id',
            ],
        ]);

        $duplicates = $this->db->query('
            SELECT hit_entrenamiento_id, punto_control_id, COUNT(*) AS total
            FROM registros_tiempo
            GROUP BY hit_entrenamiento_id, punto_control_id
            HAVING COUNT(*) > 1
            LIMIT 1
        ')->getRowArray();

        if ($duplicates) {
            throw new RuntimeException(
                'No se puede aplicar la restricción BTPS: existen puntos duplicados en registros_tiempo. ' .
                'Limpie los duplicados antes de ejecutar nuevamente la migración.'
            );
        }

        $this->db->query('ALTER TABLE registros_tiempo ADD UNIQUE KEY uq_registros_tiempo_event_id (event_id)');
        $this->db->query('ALTER TABLE registros_tiempo ADD UNIQUE KEY uq_registros_tiempo_hit_punto (hit_entrenamiento_id, punto_control_id)');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE registros_tiempo DROP INDEX uq_registros_tiempo_event_id');
        $this->db->query('ALTER TABLE registros_tiempo DROP INDEX uq_registros_tiempo_hit_punto');
        $this->forge->dropColumn('registros_tiempo', 'event_id');
    }
}
