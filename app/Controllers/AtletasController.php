<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AtletaGaleriaModel;
use App\Models\AtletaModel;
use App\Models\RedesSocialesModel;

class AtletasController extends BaseController
{

    public function perfil($slug)
    {
        $model = new AtletaModel();
        $redesModel = new RedesSocialesModel();
        $galeriaModel = new AtletaGaleriaModel();

        $atleta = $model->where('slug', $slug)->first();

        if (!$atleta) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Atleta no encontrado.");
        }

        $redes = $redesModel->getByAtletaId($atleta['id']);
        $galeria = $galeriaModel->where('atleta_id', $atleta['id'])->findAll();

        $data['title'] = 'Perfil de Atleta | ' . $atleta['nombres'] . ' ' . $atleta['apellidos'];
        $data['atleta'] = $atleta;
        $data['redes_sociales'] = $redes;
        $data['galeria'] = $galeria;

        return view('layouts/head', $data)
            . view('layouts/header_simple')
            . view('atletas/perfil', $data)
            . view('layouts/footer');
    }
}