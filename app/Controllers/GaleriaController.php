<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GaleriaAlbumModel;
use App\Models\GaleriaFotoModel;
use App\Models\GaleriaItemModel;

class GaleriaController extends BaseController
{
    public function index()
    {
        $categoria = strtolower((string)$this->request->getGet('categoria'));
        $anio      = (string)$this->request->getGet('anio');
        $onlyFeat  = (string)$this->request->getGet('destacados') === '1';

        $model = new GaleriaItemModel();

        $builder = $model->where('publicado', 1);
        if (!empty($categoria) && $categoria !== 'todos') $builder->where('categoria', $categoria);
        if (!empty($anio) && $anio !== 'todos')           $builder->where('anio', (int)$anio);
        if ($onlyFeat)                                     $builder->where('destacado', 1);

        // Orden: destacados primero, luego orden/reciente
        $builder->orderBy('destacado','DESC')
            ->orderBy('orden','ASC')
            ->orderBy('id','DESC');

        // Paginación
        $perPage = 9;
        $items = $builder->paginate($perPage);
        $pager = $model->pager;

        // Para generar filtros dinámicos (si quieres)
        $db = \Config\Database::connect();
        $categorias = array_map(fn($r)=>$r['categoria'],
            $db->table('galeria_items')->select('DISTINCT categoria', false)
                ->where('publicado',1)->where('categoria IS NOT NULL', null, false)
                ->orderBy('categoria','ASC')->get()->getResultArray()
        );
        $anios = array_map(fn($r)=>$r['anio'],
            $db->table('galeria_items')->select('DISTINCT anio', false)
                ->where('publicado',1)->where('anio IS NOT NULL', null, false)
                ->orderBy('anio','DESC')->get()->getResultArray()
        );

        return view('galeria/index', [
            'items'      => $items,
            'pager'      => $pager,
            'categorias' => $categorias,   // si quieres poblar botones dinámicos
            'anios'      => $anios,
            'filtros'    => ['categoria'=>$categoria ?: 'todos', 'anio'=>$anio ?: 'todos', 'destacados'=>$onlyFeat ? 1 : 0],
        ]);
    }

    public function album(string $slug)
    {
        $albumModel = new GaleriaAlbumModel();
        $fotoModel  = new GaleriaFotoModel();

        $album = $albumModel->where(['slug'=>$slug,'publicado'=>1])->first();
        if (!$album) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $fotos = $fotoModel->where(['album_id'=>$album['id'],'publicado'=>1])
            ->orderBy('orden','ASC')->orderBy('id','ASC')->findAll(100);

        return view('galeria/album', ['album'=>$album, 'fotos'=>$fotos]);
    }
}