<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AtletaUsuarioSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'usuario_id' => 1, // Asegúrate que este ID exista
                'atleta_id'  => 1, // Asegúrate que este ID exista
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'usuario_id' => 1,
                'atleta_id'  => 2,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('atleta_usuario')->insertBatch($data);
    }
}
