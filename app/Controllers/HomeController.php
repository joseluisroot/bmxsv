<?php

namespace App\Controllers;

use App\Models\AtletaModel;
use App\Models\NoticiaModel;

class HomeController extends BaseController
{
    public function index()
    {

        $atletaModel = new AtletaModel();
        $data['title'] = 'Inicio | BMXSV';
        $data['atletas'] = $atletaModel->findAll();

        $noticiaModel = new NoticiaModel();
        $ultimasNoticias = $noticiaModel
            ->orderBy('fecha_publicacion', 'DESC')
            ->limit(3)
            ->find();

        $data['ultimasNoticias'] = $ultimasNoticias;

        $data['calendarScript'] = view('scripts/calendar');

        return view('layouts/head', $data, ['saveData' => true])
            . view('layouts/header', ['saveData' => true])
            . view('home', $data, ['saveData' => true])
            . view('layouts/footer',['saveData' => true]);


    }
}
