<?php

namespace App\Controllers;

use App\Models\AtletaModel;
use App\Models\CompetenciaModel;
use App\Models\GaleriaItemModel;
use App\Models\NoticiaModel;
use App\Models\ResultadoModel;
use App\Models\RankingMotivoModel;
use App\Models\RankingPeriodoModel;

class HomeController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Atletas
        $atletaModel = new AtletaModel();
        $data['title'] = 'Inicio | BMXSV';
        $data['atletas'] = $atletaModel->findAll();

        // Noticias
        $noticiaModel = new NoticiaModel();
        $data['ultimasNoticias'] = $noticiaModel
            ->orderBy('fecha_publicacion', 'DESC')
            ->limit(3)
            ->find();

        // === Resultados Recientes (última competencia) ===
        $competenciaModel = new CompetenciaModel();
        $ultima = $competenciaModel
            ->orderBy('fecha', 'DESC')
            ->first();

        $data['ultimaCompetencia'] = $ultima;

        $data['ganadoresPorCategoria'] = [];
        if ($ultima) {
            $resultadoModel = new ResultadoModel();

            // Trae ganador por categoría (posicion = 1) ordenado por categorias.orden, categorias.nombre
            $builder = $db->table('resultados r')
                ->select('c.nombre AS categoria, a.nombres, a.apellidos, a.slug, a.id, r.tiempo_ms')
                ->join('categorias c', 'c.id = r.categoria_id')
                ->join('atletas a', 'a.id = r.atleta_id')
                ->where('r.competencia_id', $ultima['id'])
                ->where('r.posicion', 1)
                ->orderBy('c.orden', 'ASC')
                ->orderBy('c.nombre', 'ASC');

            $data['ganadoresPorCategoria'] = $builder->get()->getResultArray();
        }

        $data['calendarScript'] = view('scripts/calendar');
        $data['rankingScript'] = view('scripts/ranking');

        // === Ranking Mensual (último publicado) ===
        $motivoModel  = new RankingMotivoModel();
        $periodoModel = new RankingPeriodoModel();

        $motivo = $motivoModel->where('codigo', 'RANK_MENSUAL')->first();

        $data['rankingPeriodo'] = null;
        $data['rankingDatos']   = [];   // agrupado por categoría
        $data['rankingTabs']    = [];   // últimos N periodos para tabs si quieres

        if ($motivo) {
            // último período publicado
            $periodo = $periodoModel->where('motivo_id', $motivo['id'])
                ->where('publicado', 1)
                ->orderBy('anio', 'DESC')->orderBy('mes', 'DESC')
                ->first();

            if ($periodo) {
                $data['rankingPeriodo'] = $periodo;

                // Traer detalles con joins
                // Orden principal: por posicion si existe; si no, por valor según metric/sort
                $builder = $db->table('ranking_detalles d')
                    ->select('c.nombre AS categoria, c.orden AS cat_orden, a.nombres, a.apellidos, a.slug, a.id,
                      d.posicion, d.valor_num, d.valor_ms')
                    ->join('categorias c', 'c.id = d.categoria_id')
                    ->join('atletas a', 'a.id = d.atleta_id')
                    ->where('d.periodo_id', $periodo['id']);

                // Aplica ordenamiento
                if (!empty($periodo['metric']) && $periodo['metric'] === 'time_ms') {
                    $builder->orderBy('c.orden', 'ASC')->orderBy('c.nombre', 'ASC');
                    // Si no hay "posicion", ordena por valor_ms ASC
                    $builder->orderBy('d.posicion IS NULL, d.posicion', 'ASC', false)
                        ->orderBy('d.valor_ms', 'ASC');
                } else {
                    // points u otros numéricos
                    $dir = $periodo['sort_direction'] ?? 'DESC';
                    $builder->orderBy('c.orden', 'ASC')->orderBy('c.nombre', 'ASC');
                    $builder->orderBy('d.posicion IS NULL, d.posicion', 'ASC', false)
                        ->orderBy('d.valor_num', $dir);
                }

                $rows = $builder->get()->getResultArray();

                // Agrupar por categoría
                $agrupado = [];
                foreach ($rows as $r) {
                    $cat = $r['categoria'];
                    if (!isset($agrupado[$cat])) $agrupado[$cat] = [];
                    $agrupado[$cat][] = $r;
                }

                $data['rankingDatos'] = $agrupado;

                // (Opcional) tabs últimos 6 periodos publicados
                $tabs = $periodoModel->where('motivo_id', $motivo['id'])
                    ->where('publicado', 1)
                    ->orderBy('anio', 'DESC')->orderBy('mes', 'DESC')
                    ->findAll(6);
                $data['rankingTabs'] = $tabs;
            }
        }

        // Galeria
        $galeriaModel = new GaleriaItemModel();
        $data['galeriaDestacados'] = $galeriaModel
            ->where('publicado', 1)
            ->where('destacado', 1)
            ->orderBy('orden', 'ASC')
            ->orderBy('id', 'DESC')
            ->findAll(12);


        return view('layouts/head', $data)
            . view('layouts/header')
            . view('home', $data)
            . view('layouts/footer');
    }
}
