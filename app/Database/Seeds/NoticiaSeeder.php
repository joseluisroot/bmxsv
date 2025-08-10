<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class NoticiaSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('es_ES');
        $noticias = [];

        for ($i = 1; $i <= 15; $i++) {
            $titulo = $faker->sentence(6);
            $slug = url_title($titulo, '-', true);
            $noticias[] = [
                'titulo'            => $titulo,
                'slug'              => $slug,
                'resumen'       => $faker->text(120),
                'contenido'         => $faker->paragraphs(5, true),
                'imagen_destacada'            => "https://picsum.photos/600/300?random=$i",
                'fecha_publicacion' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d H:i:s'),
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('noticias')->insertBatch($noticias);
    }
}
