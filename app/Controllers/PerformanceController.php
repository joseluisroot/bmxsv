<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PerformanceController extends BaseController
{
    public function atleta($id)
    {
        return view('Performance/dashboard', [
            'atletaId'  => $id,
            'title'     => 'Dashboard Atleta',
            'pageTitle' => 'Dashboard Atleta',
        ]);
    }

    public function sesionLive($id)
    {
        return view('Performance/session_live', [
            'sessionId' => $id,
            'title'     => 'Sesión en Vivo',
            'pageTitle' => 'Sesión en Vivo',
        ]);
    }

    public function coach($sessionId)
    {
        return view('Performance/coach', [
            'sessionId' => $sessionId,
            'title'     => 'Coach Dashboard',
            'pageTitle' => 'Coach Dashboard',
        ]);
    }

    public function compareHits($hitAId, $hitBId)
    {
        return view('Performance/compare_hits', [
            'hitAId' => $hitAId,
            'hitBId' => $hitBId,
            'title' => 'Comparación de Hits',
            'pageTitle' => 'Comparación de Hits',
        ]);
    }

    public function compareAthletes($athleteAId, $athleteBId)
    {
        return view('Performance/compare_athletes', [
            'athleteAId' => $athleteAId,
            'athleteBId' => $athleteBId,
            'title' => 'Comparación de Atletas',
            'pageTitle' => 'Comparación de Atletas',
        ]);
    }

    public function clubRanking()
    {
        return view('Performance/club_ranking', [
            'title' => 'Ranking del Club',
            'pageTitle' => 'Ranking del Club',
        ]);
    }

    public function sessions()
    {
        return view('Performance/sessions', [
            'title' => 'BTPS Session Manager',
            'pageTitle' => 'BTPS Session Manager',
        ]);
    }

    public function aatManager()
    {
        return view('performance/aat_manager', [
            'title' => 'BTPS AAT Manager',
            'pageTitle' => 'BTPS AAT Manager',
        ]);
    }

    public function btnManager()
    {
        return view('performance/btn_manager', [
            'title' => 'BTPS BTN Manager',
            'pageTitle' => 'BTPS BTN Manager',
        ]);
    }

    public function sessionControl($sessionId)
    {
        return view('Performance/session_control', [
            'sessionId' => $sessionId,
            'title' => 'Control de Sesión',
            'pageTitle' => 'Control de Sesión',
        ]);
    }

    public function sessionSimulator($sessionId)
    {
        return view('Performance/session_simulator', [
            'sessionId' => $sessionId,
            'title' => 'Sensor Simulator',
            'pageTitle' => 'Sensor Simulator',
        ]);
    }
}
