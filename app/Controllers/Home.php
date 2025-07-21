<?php

namespace App\Controllers;

use App\Models\AtletaModel;

class Home extends BaseController
{
    public function index()
    {

        $atletaModel = new AtletaModel();
        $data['title'] = 'Inicio | BMXSV';
        $data['atletas'] = $atletaModel->findAll();

        $data['calendarScript'] = view('scripts/calendar');

        return view('layouts/head', $data)
            . view('layouts/header')
            . view('home', $data)
            . view('layouts/footer');
    }
}
