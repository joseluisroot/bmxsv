<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PuntosControlSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'codigo'      => 'TP01',
                'nombre'      => 'Gate / Salida',
                'descripcion' => 'Salida desde el partidor',
                'orden'       => 1,
                'activo'      => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'codigo'      => 'TP02',
                'nombre'      => 'Fin del Partidor',
                'descripcion' => 'Primera lectura al bajar del partidor',
                'orden'       => 2,
                'activo'      => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'codigo'      => 'TP03',
                'nombre'      => 'Salida Curva 1',
                'descripcion' => 'Entrada a la segunda línea',
                'orden'       => 3,
                'activo'      => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'codigo'      => 'TP04',
                'nombre'      => 'Salida Curva 2',
                'descripcion' => 'Entrada a la tercera línea',
                'orden'       => 4,
                'activo'      => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'codigo'      => 'TP05',
                'nombre'      => 'Salida Curva 3',
                'descripcion' => 'Entrada a la última línea',
                'orden'       => 5,
                'activo'      => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'codigo'      => 'TP06',
                'nombre'      => 'Meta',
                'descripcion' => 'Línea final de llegada',
                'orden'       => 6,
                'activo'      => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('puntos_control')->insertBatch($data);
    }
}
