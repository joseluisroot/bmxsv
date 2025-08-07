<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PadreController extends BaseController
{
    public function index()
    {
        return view('padre/index'); // o como sea tu vista
    }

    public function atletas()
    {
        $usuarioId = session('usuario.id');

        $db = \Config\Database::connect();
        $builder = $db->table('atletas');
        $builder->select('atletas.*');
        $builder->join('atleta_usuario', 'atletas.id = atleta_usuario.atleta_id');
        $builder->where('atleta_usuario.usuario_id', $usuarioId);
        $query = $builder->get();

        $data['atletas'] = $query->getResultArray();

        return view('padre/atletas', $data);
    }

}
