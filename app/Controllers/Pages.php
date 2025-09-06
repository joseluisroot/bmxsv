<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Pages extends BaseController
{
    public function faq()
    {
        $data = [
            'title' => 'Preguntas Frecuentes | BMXSV',
            'descripcion' => 'Horarios, coaches, primera clase gratis, cómo coordinar visita y qué llevar para entrenar BMX Race en El Salvador.',
            'faqScript'   => view('scripts/faq'),
        ];

        return view('layouts/head', $data)
            . view('layouts/header_simple')
            . view('pages/faq', $data)
            . view('layouts/footer');
    }
}