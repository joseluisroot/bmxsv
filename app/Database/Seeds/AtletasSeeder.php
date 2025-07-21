<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AtletasSeeder extends Seeder
{
    public function run()
    {
        $atletas = [
            [
                'nombres'      => 'Luis',
                'apellidos'    => 'Martínez',
                'slug'         => 'luis-martinez',
                'categoria'    => 'Junior',
                'club'         => 'San Salvador',
                'edad'         => 16,
                'anios_bmx'    => 3,
                'palmares'     => "Campeón Nacional 2024 - Categoría Junior\n1er lugar - Carrera San Salvador (julio 2025)\n2do lugar - Carrera Santa Ana (mayo 2025)\n3er lugar - Carrera Ahuachapán (abril 2025)",
                'estilo'       => 'Luis es reconocido por su explosividad en la salida y gran capacidad de control en curvas cerradas.',
                'equipamiento' => "Bicicleta: GT Speed Series Pro XL\nCasco: Troy Lee Designs D4\nGuantes: 100%\nUniforme: Oficial del Club San Salvador",
                'hobbies'      => 'Motocross, tocar batería, enseñar a nuevos atletas jóvenes del club',
                'foto'         => null,
            ],
            [
                'nombres'      => 'Diego',
                'apellidos'    => 'Ramos',
                'slug'         => 'diego-ramos',
                'categoria'    => 'Junior',
                'club'         => 'Santa Ana',
                'edad'         => 17,
                'anios_bmx'    => 4,
                'palmares'     => "2do lugar - Carrera Nacional (junio 2025)\nCampeón Departamental 2023",
                'estilo'       => 'Especialista en pistas técnicas y manejo en lluvia.',
                'equipamiento' => "Bicicleta: Redline Flight\nCasco: Fox Rampage\nGuantes: Troy Lee Designs",
                'hobbies'      => 'Ciclismo de montaña, videojuegos, fotografía deportiva',
                'foto'         => null,
            ],
            [
                'nombres'      => 'Kevin',
                'apellidos'    => 'López',
                'slug'         => 'kevin-lopez',
                'categoria'    => 'Junior',
                'club'         => 'La Libertad',
                'edad'         => 15,
                'anios_bmx'    => 2,
                'palmares'     => "Campeón Nacional 2023 - Categoría Juvenil",
                'estilo'       => 'Velocidad pura en rectas y excelente reacción en salidas.',
                'equipamiento' => "Bicicleta: Haro Race Lite\nCasco: Bell Full-9\nUniforme: Personalizado con los colores del club",
                'hobbies'      => 'Atletismo, mecánica de bicicletas, surf',
                'foto'         => null,
            ],
        ];

        $this->db->table('atletas')->insertBatch($atletas);
    }
}
