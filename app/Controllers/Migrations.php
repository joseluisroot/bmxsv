<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Migrations extends Controller
{
    public function index()
    {
        $migrate = \Config\Services::migrations();

        try {
            $migrate->latest();
            echo "Migraciones ejecutadas exitosamente.";
        } catch (\Throwable $e) {
            echo "Error al ejecutar migraciones: " . $e->getMessage();
        }
    }
}