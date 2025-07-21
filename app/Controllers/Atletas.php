<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AtletaModel;

class Atletas extends BaseController
{
    public function perfil($slug)
    {
        $model = new AtletaModel();
        $atleta = $model->where('slug', $slug)->first();

        if (!$atleta) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Atleta no encontrado.");
        }

        $data['title'] = 'Perfil de Atleta | ' . $atleta['nombres'] . ' ' . $atleta['apellidos'];
        $data['atleta'] = $atleta;

        return view('layouts/head', $data)
            . view('layouts/header_simple')
            . view('atletas/perfil', $data)
            . view('layouts/footer');
    }
}