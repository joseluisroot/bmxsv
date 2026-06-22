<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PerformanceController extends BaseController
{
    public function atleta($id)
    {
        return view('performance/dashboard', [
            'atletaId'  => $id,
            'title'     => 'Dashboard Atleta',
            'pageTitle' => 'Dashboard Atleta',
        ]);
    }

    public function sesionLive($id)
    {
        return view('performance/session_live', [
            'sessionId' => $id,
            'title'     => 'Sesión en Vivo',
            'pageTitle' => 'Sesión en Vivo',
        ]);
    }

    public function coach($sessionId)
    {
        return view('performance/coach', [
            'sessionId' => $sessionId,
            'title'     => 'Coach Dashboard',
            'pageTitle' => 'Coach Dashboard',
        ]);
    }

    public function compareHits($hitAId, $hitBId)
    {
        return view('performance/compare_hits', [
            'hitAId' => $hitAId,
            'hitBId' => $hitBId,
            'title' => 'Comparación de Hits',
            'pageTitle' => 'Comparación de Hits',
        ]);
    }

    public function compareAthletes($athleteAId, $athleteBId)
    {
        return view('performance/compare_athletes', [
            'athleteAId' => $athleteAId,
            'athleteBId' => $athleteBId,
            'title' => 'Comparación de Atletas',
            'pageTitle' => 'Comparación de Atletas',
        ]);
    }


}
