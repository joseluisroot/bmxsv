<?php

namespace App\Controllers;

use App\Models\NoticiaModel;

class NoticiasController extends BaseController
{
    protected $noticiaModel;

    public function __construct()
    {
        $this->noticiaModel = new NoticiaModel();
    }

    public function index()
    {
        $perPage = 9; // cámbialo a 6 si lo prefieres
        $noticias = $this->noticiaModel
            ->orderBy('fecha_publicacion', 'DESC')
            ->paginate($perPage);

        $data = [
            'title'        => 'Noticias | BMXSV',
            'descripcion'  => 'Todas las noticias y novedades del BMX en El Salvador.',
            'noticias'     => $noticias,
            'pager'        => $this->noticiaModel->pager,
        ];

        return view('layouts/head', $data)
            . view('layouts/header_simple')
            . view('noticias/index', $data)
            . view('layouts/footer');
    }

    public function detalle($slug)
    {

        helper('text');

        $noticia = $this->noticiaModel->obtenerPorSlug($slug);

        if (!$noticia) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Noticia no encontrada");
        }

        $relacionadas = $this->noticiaModel
            ->where('slug !=', $slug)
            ->orderBy('fecha_publicacion', 'DESC')
            ->limit(3)
            ->find();

        $data = [
            'title' => $noticia['titulo'],
            'noticia' => $noticia,
            'relacionadas' => $relacionadas
        ];

        return view('layouts/head', $data)
            . view('layouts/header_simple')
            . view('noticias/detalle', $data)
            . view('layouts/footer');
    }

    public function ultimasNoticias($limite = 3)
    {
        return $this->noticiaModel
            ->where('publicado', true)
            ->orderBy('fecha_publicacion', 'DESC')
            ->findAll($limite);
    }
}