<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RankingPeriodoModel;

class RankingController extends BaseController
{
    public function periodo(int $periodoId)
    {
        $db = \Config\Database::connect();
        $periodoModel = new RankingPeriodoModel();

        $periodo = $periodoModel->find($periodoId);
        if (!$periodo || (int)$periodo['publicado'] !== 1) {
            return $this->response->setStatusCode(404)->setBody('Período no encontrado');
        }

        // Traer detalles agrupados por categoría (mismo criterio que en HomeController)
        $builder = $db->table('ranking_detalles d')
            ->select('c.nombre AS categoria, c.orden AS cat_orden, a.nombres, a.apellidos, a.slug, a.id,
                      d.posicion, d.valor_num, d.valor_ms')
            ->join('categorias c', 'c.id = d.categoria_id')
            ->join('atletas a', 'a.id = d.atleta_id')
            ->where('d.periodo_id', $periodoId);

        if ($periodo['metric'] === 'time_ms') {
            $builder->orderBy('c.orden','ASC')->orderBy('c.nombre','ASC')
                ->orderBy('d.posicion IS NULL, d.posicion','ASC', false)
                ->orderBy('d.valor_ms','ASC');
        } else {
            $dir = $periodo['sort_direction'] ?? 'DESC';
            $builder->orderBy('c.orden','ASC')->orderBy('c.nombre','ASC')
                ->orderBy('d.posicion IS NULL, d.posicion','ASC', false)
                ->orderBy('d.valor_num', $dir);
        }

        $rows = $builder->get()->getResultArray();

        $agrupado = [];
        foreach ($rows as $r) {
            $agrupado[$r['categoria']][] = $r;
        }

        $data = [
            'periodo' => null,//$periodo,
            'agrupado' => null //$agrupado,
        ];

        // Devuelve solo el HTML del contenido
        return view('partials/ranking_content', $data);
    }
}