<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Seeders extends Controller
{
    public function index()
    {
        echo "Por favor especifica el seeder: /seeders/nombre";
    }

    public function run($seederName)
    {
        try {
            $seeder = \Config\Services::seeder();
            $seeder->call($seederName);
            echo "Seeder ejecutado exitosamente: {$seederName}";
        } catch (\Throwable $e) {
            echo "Error al ejecutar el seeder: " . $e->getMessage();
        }
    }
}
