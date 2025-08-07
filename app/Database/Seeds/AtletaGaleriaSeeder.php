<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AtletaGaleriaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'atleta_id'  => 1,
                'imagen'     => 'galeria1.jpg',
                'descripcion'=> 'Participación en campeonato nacional',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'atleta_id'  => 1,
                'imagen'     => 'galeria2.jpg',
                'descripcion'=> 'Entrenamiento con el equipo',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'atleta_id'  => 2,
                'imagen'     => 'galeria3.jpg',
                'descripcion'=> 'Podio en competencia junior',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('atleta_galeria')->insertBatch($data);
    }
}
