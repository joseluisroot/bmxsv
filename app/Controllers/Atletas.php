<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AtletaModel;
use App\Models\RedesSocialesModel;

class Atletas extends BaseController
{
    public function perfil($slug)
    {
        $model = new AtletaModel();
        $redesModel  = new RedesSocialesModel();

        $atleta = $model->where('slug', $slug)->first();

        if (!$atleta) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Atleta no encontrado.");
        }

        $redes = $redesModel->getByAtletaId($atleta['id']);

        $data['title'] = 'Perfil de Atleta | ' . $atleta['nombres'] . ' ' . $atleta['apellidos'];
        $data['atleta'] = $atleta;
        $data['redes_sociales'] = $redes;

        return view('layouts/head', $data)
            . view('layouts/header_simple')
            . view('atletas/perfil', $data)
            . view('layouts/footer');
    }
}