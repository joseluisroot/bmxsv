<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class JuegosController extends BaseController
{
    public function focusNumbers()
    {
        $data = [
            'title' => 'Focus Numbers | BMXSV',
            'descripcion' => 'Encuentra y marca números en orden. Juego de concentración BMXSV.',
        ];
        return view('juegos/focus_numbers', $data);
    }
}
