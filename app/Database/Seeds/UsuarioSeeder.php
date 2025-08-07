<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nombre'     => 'Administrador',
            'email'      => 'admin@bmxsv.com',
            'password'   => password_hash('admin123', PASSWORD_DEFAULT),
            'rol'        => 'admin',
            'activo'     => true,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->table('usuarios')->insert($data);
    }
}
