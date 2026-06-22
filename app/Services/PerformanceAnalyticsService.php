<?php

namespace App\Services;

use App\Models\HitEntrenamientoModel;
use App\Models\RegistroTiempoModel;

class PerformanceAnalyticsService
{
    public function buildPerformanceMetrics(array $splits, ?float $totalSeconds): array
    {
        $metrics = [
            'gate' => null,
            'first_straight' => null,
            'middle_track' => null,
            'final_straight' => null,
            'total' => $totalSeconds,
        ];

        foreach ($splits as $split) {
            $key = $split['from'] . '_' . $split['to'];

            switch ($key) {
                case 'TP01_TP02':
                    $metrics['gate'] = $split['seconds'];
                    break;

                case 'TP02_TP03':
                    $metrics['first_straight'] = $split['seconds'];
                    break;

                case 'TP03_TP04':
                case 'TP04_TP05':
                    $metrics['middle_track'] = ($metrics['middle_track'] ?? 0) + $split['seconds'];
                    break;

                case 'TP05_TP06':
                    $metrics['final_straight'] = $split['seconds'];
                    break;
            }
        }

        if ($metrics['middle_track'] !== null) {
            $metrics['middle_track'] = round($metrics['middle_track'], 3);
        }

        return $metrics;
    }

    public function getSessionRanking(int $sessionId): array
    {
        $hitModel = new HitEntrenamientoModel();

        $hits = $hitModel
            ->select('
            hits_entrenamiento.id,
            hits_entrenamiento.numero_hit,
            hits_entrenamiento.atleta_id,
            atletas.nombres AS atleta_nombre,
            configuraciones_bicicleta.plato,
            configuraciones_bicicleta.pinon,
            sesiones_entrenamiento.nombre AS sesion_nombre,
            sesiones_entrenamiento.fecha AS sesion_fecha
        ')
            ->join('atletas', 'atletas.id = hits_entrenamiento.atleta_id')
            ->join('configuraciones_bicicleta', 'configuraciones_bicicleta.id = hits_entrenamiento.configuracion_bicicleta_id')
            ->join('sesiones_entrenamiento', 'sesiones_entrenamiento.id = hits_entrenamiento.sesion_entrenamiento_id')
            ->where('hits_entrenamiento.sesion_entrenamiento_id', $sessionId)
            ->findAll();

        if (empty($hits)) {
            return [
                'success' => false,
                'message' => 'No hay hits registrados para esta sesión.',
            ];
        }

        $items = [];

        foreach ($hits as $hit) {
            $analysis = $this->buildHitAnalysis((int) $hit['id']);

            if (!$analysis || $analysis['total_seconds'] === null) {
                continue;
            }

            $items[] = [
                'hit_id' => (int) $hit['id'],
                'numero_hit' => (int) $hit['numero_hit'],
                'athlete' => [
                    'id' => (int) $hit['atleta_id'],
                    'nombre' => $hit['atleta_nombre'],
                ],
                'bike_setup' => [
                    'plato' => (int) $hit['plato'],
                    'pinon' => $hit['pinon'] !== null ? (int) $hit['pinon'] : null,
                ],
                'total_seconds' => $analysis['total_seconds'],
                'performance' => $analysis['performance'],
            ];
        }

        if (empty($items)) {
            return [
                'success' => false,
                'message' => 'No hay hits con registros suficientes para generar ranking.',
            ];
        }

        return [
            'success' => true,
            'session' => [
                'id' => (int) $sessionId,
                'nombre' => $hits[0]['sesion_nombre'],
                'fecha' => $hits[0]['sesion_fecha'],
            ],
            'hits_count' => count($items),
            'ranking' => [
                'total' => $this->buildMetricRanking($items, 'total_seconds'),
                'gate' => $this->buildNestedMetricRanking($items, 'gate'),
                'first_straight' => $this->buildNestedMetricRanking($items, 'first_straight'),
                'middle_track' => $this->buildNestedMetricRanking($items, 'middle_track'),
                'final_straight' => $this->buildNestedMetricRanking($items, 'final_straight'),
            ],
            'hits' => $items,
        ];
    }

    private function buildMetricRanking(array $items, string $metricKey): array
    {
        $ranking = array_filter($items, function ($item) use ($metricKey) {
            return isset($item[$metricKey]) && $item[$metricKey] !== null;
        });

        usort($ranking, function ($a, $b) use ($metricKey) {
            return $a[$metricKey] <=> $b[$metricKey];
        });

        $position = 1;

        return array_map(function ($item) use (&$position, $metricKey) {
            return [
                'position' => $position++,
                'hit_id' => $item['hit_id'],
                'numero_hit' => $item['numero_hit'],
                'athlete' => $item['athlete'],
                'bike_setup' => $item['bike_setup'],
                'seconds' => $item[$metricKey],
            ];
        }, array_values($ranking));
    }

    private function buildNestedMetricRanking(array $items, string $metricKey): array
    {
        $ranking = array_filter($items, function ($item) use ($metricKey) {
            return isset($item['performance'][$metricKey]) && $item['performance'][$metricKey] !== null;
        });

        usort($ranking, function ($a, $b) use ($metricKey) {
            return $a['performance'][$metricKey] <=> $b['performance'][$metricKey];
        });

        $position = 1;

        return array_map(function ($item) use (&$position, $metricKey) {
            return [
                'position' => $position++,
                'hit_id' => $item['hit_id'],
                'numero_hit' => $item['numero_hit'],
                'athlete' => $item['athlete'],
                'bike_setup' => $item['bike_setup'],
                'seconds' => $item['performance'][$metricKey],
            ];
        }, array_values($ranking));
    }

    public function getSessionSummary(int $sessionId): array
    {
        $hitModel = new HitEntrenamientoModel();

        $hits = $hitModel
            ->select('
            hits_entrenamiento.*,
            atletas.nombres AS atleta_nombre,
            configuraciones_bicicleta.plato,
            configuraciones_bicicleta.pinon,
            bicicletas.marca,
            bicicletas.modelo,
            sesiones_entrenamiento.nombre AS sesion_nombre,
            sesiones_entrenamiento.fecha AS sesion_fecha
        ')
            ->join('atletas', 'atletas.id = hits_entrenamiento.atleta_id')
            ->join('configuraciones_bicicleta', 'configuraciones_bicicleta.id = hits_entrenamiento.configuracion_bicicleta_id')
            ->join('bicicletas', 'bicicletas.id = configuraciones_bicicleta.bicicleta_id')
            ->join('sesiones_entrenamiento', 'sesiones_entrenamiento.id = hits_entrenamiento.sesion_entrenamiento_id')
            ->where('hits_entrenamiento.sesion_entrenamiento_id', $sessionId)
            ->orderBy('hits_entrenamiento.numero_hit', 'ASC')
            ->findAll();

        if (empty($hits)) {
            return [
                'success' => false,
                'message' => 'No hay hits registrados para esta sesión.',
            ];
        }

        $result = [];

        foreach ($hits as $hit) {
            $analysis = $this->buildHitAnalysis((int) $hit['id']);

            $result[] = [
                'hit_id' => (int) $hit['id'],
                'numero_hit' => (int) $hit['numero_hit'],
                'athlete' => [
                    'id' => (int) $hit['atleta_id'],
                    'nombre' => $hit['atleta_nombre'],
                ],
                'bike_setup' => [
                    'bicicleta' => trim(($hit['marca'] ?? '') . ' ' . ($hit['modelo'] ?? '')),
                    'plato' => (int) $hit['plato'],
                    'pinon' => $hit['pinon'] !== null ? (int) $hit['pinon'] : null,
                ],
                'total_seconds' => $analysis['total_seconds'] ?? null,
                'performance' => $analysis['performance'] ?? [
                        'gate' => null,
                        'first_straight' => null,
                        'middle_track' => null,
                        'final_straight' => null,
                        'total' => null,
                    ],
                'records_count' => isset($analysis['records']) ? count($analysis['records']) : 0,
                'records' => $analysis['records'] ?? [],
                'splits' => $analysis['splits'] ?? [],
            ];
        }

        return [
            'success' => true,
            'session' => [
                'id' => $sessionId,
                'nombre' => $hits[0]['sesion_nombre'],
                'fecha' => $hits[0]['sesion_fecha'],
            ],
            'hits_count' => count($result),
            'hits' => $result,
        ];
    }

    public function getHitSummary(int $hitId): array
    {
        $hitModel = new HitEntrenamientoModel();

        $hit = $hitModel
            ->select('
            hits_entrenamiento.*,
            atletas.nombres AS atleta_nombre,
            configuraciones_bicicleta.plato,
            configuraciones_bicicleta.pinon,
            bicicletas.marca,
            bicicletas.modelo,
            sesiones_entrenamiento.nombre AS sesion_nombre,
            sesiones_entrenamiento.fecha AS sesion_fecha
        ')
            ->join('atletas', 'atletas.id = hits_entrenamiento.atleta_id')
            ->join('configuraciones_bicicleta', 'configuraciones_bicicleta.id = hits_entrenamiento.configuracion_bicicleta_id')
            ->join('bicicletas', 'bicicletas.id = configuraciones_bicicleta.bicicleta_id')
            ->join('sesiones_entrenamiento', 'sesiones_entrenamiento.id = hits_entrenamiento.sesion_entrenamiento_id')
            ->where('hits_entrenamiento.id', $hitId)
            ->first();

        if (!$hit) {
            return [
                'success' => false,
                'message' => 'Hit de entrenamiento no encontrado.',
            ];
        }

        $analysis = $this->buildHitAnalysis($hitId);

        if (!$analysis) {
            return [
                'success' => false,
                'message' => 'No hay registros de tiempo para este hit.',
            ];
        }

        return [
            'success' => true,
            'hit_id' => $hitId,
            'athlete' => [
                'id' => (int) $hit['atleta_id'],
                'nombre' => $hit['atleta_nombre'],
            ],
            'bike_setup' => [
                'bicicleta' => trim(($hit['marca'] ?? '') . ' ' . ($hit['modelo'] ?? '')),
                'plato' => (int) $hit['plato'],
                'pinon' => $hit['pinon'] !== null ? (int) $hit['pinon'] : null,
            ],
            'session' => [
                'id' => (int) $hit['sesion_entrenamiento_id'],
                'nombre' => $hit['sesion_nombre'],
                'fecha' => $hit['sesion_fecha'],
            ],
            'total_seconds' => $analysis['total_seconds'],
            'records_count' => count($analysis['records']),
            'records' => $analysis['records'],
            'splits' => $analysis['splits'],
            'performance' => $analysis['performance'],
        ];
    }

    public function getSetupComparison(int $athleteId): array
    {
        $hitModel = new HitEntrenamientoModel();

        $hits = $hitModel
            ->select('
            hits_entrenamiento.id,
            hits_entrenamiento.numero_hit,
            hits_entrenamiento.atleta_id,
            configuraciones_bicicleta.id AS configuracion_id,
            configuraciones_bicicleta.plato,
            configuraciones_bicicleta.pinon
        ')
            ->join(
                'configuraciones_bicicleta',
                'configuraciones_bicicleta.id = hits_entrenamiento.configuracion_bicicleta_id'
            )
            ->where('hits_entrenamiento.atleta_id', $athleteId)
            ->findAll();

        if (empty($hits)) {
            return [
                'success' => false,
                'message' => 'No hay hits registrados para este atleta.',
            ];
        }

        $grouped = [];

        foreach ($hits as $hit) {

            $analysis = $this->buildHitAnalysis((int) $hit['id']);

            if (!$analysis || $analysis['total_seconds'] === null) {
                continue;
            }

            $totalSeconds = $analysis['total_seconds'];
            $performance  = $analysis['performance'];

            $key =
                'plato_' .
                $hit['plato'] .
                '_pinon_' .
                ($hit['pinon'] ?? 'null');

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'plato' => (int) $hit['plato'],
                    'pinon' => $hit['pinon'] !== null ? (int) $hit['pinon'] : null,
                    'hits_count' => 0,
                    'best_time' => null,
                    'worst_time' => null,
                    'average_time' => 0,
                    'times' => [],
                    'performance' => [
                        'gate' => [],
                        'first_straight' => [],
                        'middle_track' => [],
                        'final_straight' => [],
                        'total' => [],
                    ],
                ];
            }

            $grouped[$key]['hits_count']++;
            $grouped[$key]['times'][] = $totalSeconds;

            foreach ($performance as $metricKey => $metricValue) {
                if ($metricValue !== null) {
                    $grouped[$key]['performance'][$metricKey][] = $metricValue;
                }
            }

            if (
                $grouped[$key]['best_time'] === null ||
                $totalSeconds < $grouped[$key]['best_time']
            ) {
                $grouped[$key]['best_time'] = $totalSeconds;
            }

            if (
                $grouped[$key]['worst_time'] === null ||
                $totalSeconds > $grouped[$key]['worst_time']
            ) {
                $grouped[$key]['worst_time'] = $totalSeconds;
            }
        }

        foreach ($grouped as $key => $setup) {

            $grouped[$key]['average_time'] = round(
                array_sum($setup['times']) / count($setup['times']),
                3
            );

            $performanceAverage = [];

            foreach ($setup['performance'] as $metricKey => $values) {

                $performanceAverage[$metricKey] =
                    count($values) > 0
                        ? round(array_sum($values) / count($values), 3)
                        : null;
            }

            $grouped[$key]['performance_average'] =
                $performanceAverage;
        }

        $grouped = array_values($grouped);

        usort($grouped, function ($a, $b) {
            return $a['average_time'] <=> $b['average_time'];
        });

        return [
            'success' => true,
            'athlete_id' => $athleteId,
            'best_setup' => $grouped[0] ?? null,
            'setups' => $grouped,
        ];
    }

    public function getBestHits(int $athleteId): array
    {
        $hitModel = new HitEntrenamientoModel();

        $hits = $hitModel
            ->select('
            hits_entrenamiento.id,
            hits_entrenamiento.numero_hit,
            hits_entrenamiento.atleta_id,
            hits_entrenamiento.created_at,
            configuraciones_bicicleta.plato,
            configuraciones_bicicleta.pinon,
            sesiones_entrenamiento.nombre AS sesion_nombre,
            sesiones_entrenamiento.fecha AS sesion_fecha
        ')
            ->join('configuraciones_bicicleta', 'configuraciones_bicicleta.id = hits_entrenamiento.configuracion_bicicleta_id')
            ->join('sesiones_entrenamiento', 'sesiones_entrenamiento.id = hits_entrenamiento.sesion_entrenamiento_id')
            ->where('hits_entrenamiento.atleta_id', $athleteId)
            ->findAll();

        if (empty($hits)) {
            return [
                'success' => false,
                'message' => 'No hay hits registrados para este atleta.',
            ];
        }

        $result = [];

        foreach ($hits as $hit) {
            $summary = $this->getHitSummary((int) $hit['id']);

            if (empty($summary['success'])) {
                continue;
            }

            $result[] = [
                'hit_id' => (int) $hit['id'],
                'numero_hit' => (int) $hit['numero_hit'],
                'session' => [
                    'nombre' => $hit['sesion_nombre'],
                    'fecha' => $hit['sesion_fecha'],
                ],
                'bike_setup' => [
                    'plato' => (int) $hit['plato'],
                    'pinon' => $hit['pinon'] !== null ? (int) $hit['pinon'] : null,
                ],
                'total_seconds' => $summary['total_seconds'],
                'performance' => $summary['performance'],
            ];
        }

        if (empty($result)) {
            return [
                'success' => false,
                'message' => 'No hay hits con registros suficientes para generar ranking.',
            ];
        }

        usort($result, function ($a, $b) {
            return $a['total_seconds'] <=> $b['total_seconds'];
        });

        return [
            'success' => true,
            'athlete_id' => $athleteId,
            'best_hit' => $result[0],
            'hits_count' => count($result),
            'hits' => $result,
        ];
    }

    public function getAthleteHistory(int $athleteId): array
    {
        $hitModel = new HitEntrenamientoModel();

        $hits = $hitModel
            ->select('
            hits_entrenamiento.id,
            hits_entrenamiento.numero_hit,
            hits_entrenamiento.atleta_id,
            configuraciones_bicicleta.plato,
            configuraciones_bicicleta.pinon,
            sesiones_entrenamiento.id AS sesion_id,
            sesiones_entrenamiento.nombre AS sesion_nombre,
            sesiones_entrenamiento.fecha AS sesion_fecha
        ')
            ->join('configuraciones_bicicleta', 'configuraciones_bicicleta.id = hits_entrenamiento.configuracion_bicicleta_id')
            ->join('sesiones_entrenamiento', 'sesiones_entrenamiento.id = hits_entrenamiento.sesion_entrenamiento_id')
            ->where('hits_entrenamiento.atleta_id', $athleteId)
            ->orderBy('sesiones_entrenamiento.fecha', 'ASC')
            ->orderBy('hits_entrenamiento.numero_hit', 'ASC')
            ->findAll();

        if (empty($hits)) {
            return [
                'success' => false,
                'message' => 'No hay historial para este atleta.',
            ];
        }

        $history = [];

        foreach ($hits as $hit) {
            $summary = $this->getHitSummary((int) $hit['id']);

            if (empty($summary['success'])) {
                continue;
            }

            $history[] = [
                'date' => $hit['sesion_fecha'],
                'session' => [
                    'id' => (int) $hit['sesion_id'],
                    'nombre' => $hit['sesion_nombre'],
                ],
                'hit_id' => (int) $hit['id'],
                'numero_hit' => (int) $hit['numero_hit'],
                'bike_setup' => [
                    'plato' => (int) $hit['plato'],
                    'pinon' => $hit['pinon'] !== null ? (int) $hit['pinon'] : null,
                ],
                'total_seconds' => $summary['total_seconds'],
                'performance' => $summary['performance'],
            ];
        }

        if (empty($history)) {
            return [
                'success' => false,
                'message' => 'No hay registros suficientes para construir el historial.',
            ];
        }

        return [
            'success' => true,
            'athlete_id' => $athleteId,
            'records_count' => count($history),
            'history' => $history,
        ];
    }

    public function getAthleteDashboard(int $athleteId): array
    {
        $historyData = $this->getAthleteHistory($athleteId);

        if (empty($historyData['success']) || empty($historyData['history'])) {
            return [
                'success' => false,
                'message' => 'No hay datos suficientes para generar dashboard.',
            ];
        }

        $history = $historyData['history'];

        usort($history, function ($a, $b) {
            return $a['total_seconds'] <=> $b['total_seconds'];
        });

        $bestHit = $history[0];

        $totalTimes = array_column($history, 'total_seconds');

        $averageTime = round(
            array_sum($totalTimes) / count($totalTimes),
            3
        );

        usort($history, function ($a, $b) {
            return strtotime($b['date']) <=> strtotime($a['date']);
        });

        $lastHit = $history[0];

        return [
            'success' => true,
            'athlete_id' => $athleteId,
            'summary' => [
                'valid_hits' => count($history),
                'average_time' => $averageTime,
                'best_time' => $bestHit['total_seconds'],
                'last_time' => $lastHit['total_seconds'],
            ],
            'best_hit' => $bestHit,
            'last_hit' => $lastHit,
        ];
    }

    public function getFullDashboard(int $athleteId): array
    {
        $dashboardData = $this->getAthleteDashboard($athleteId);
        $setupData     = $this->getSetupComparison($athleteId);
        $bestHitsData  = $this->getBestHits($athleteId);
        $historyData   = $this->getAthleteHistory($athleteId);

        return [
            'success' => true,
            'athlete_id' => $athleteId,
            'dashboard' => !empty($dashboardData['success']) ? $dashboardData : null,
            'setup_comparison' => !empty($setupData['success']) ? $setupData : null,
            'best_hits' => !empty($bestHitsData['success']) ? $bestHitsData : null,
            'history' => !empty($historyData['success']) ? $historyData : null,
            'generated_at' => date('Y-m-d H:i:s'),
        ];
    }

    public function getCoachDashboard(int $sessionId): array
    {
        $summaryData = $this->getSessionSummary($sessionId);
        $rankingData = $this->getSessionRanking($sessionId);

        if (
            empty($summaryData['success']) &&
            empty($rankingData['success'])
        ) {
            return [
                'success' => false,
                'message' => 'No hay información disponible para esta sesión.',
            ];
        }

        $athletes = [];
        $trackStatus = [];

        foreach (($summaryData['hits'] ?? []) as $hit) {
            $athleteId = $hit['athlete']['id'];

            if (!isset($athletes[$athleteId])) {
                $athletes[$athleteId] = [
                    'id' => $athleteId,
                    'nombre' => $hit['athlete']['nombre'],
                    'hits' => 0,
                    'best_time' => null,
                    'last_time' => null,
                ];
            }

            $athletes[$athleteId]['hits']++;

            if ($hit['total_seconds'] !== null) {
                if (
                    $athletes[$athleteId]['best_time'] === null ||
                    $hit['total_seconds'] < $athletes[$athleteId]['best_time']
                ) {
                    $athletes[$athleteId]['best_time'] = $hit['total_seconds'];
                }

                $athletes[$athleteId]['last_time'] = $hit['total_seconds'];
            }

            $lastPoint = null;

            if (!empty($hit['records'])) {
                $lastRecord = end($hit['records']);

                $lastPoint = [
                    'point_code' => $lastRecord['codigo'] ?? null,
                    'point_name' => $lastRecord['nombre'] ?? null,
                    'timestamp_ms' => $lastRecord['timestamp_ms'] ?? null,
                ];
            }

            $trackStatus[$hit['hit_id']] = [
                'hit_id' => $hit['hit_id'],
                'numero_hit' => $hit['numero_hit'],
                'athlete' => $hit['athlete'],
                'bike_setup' => $hit['bike_setup'],
                'current_position' => $lastPoint,
                'total_seconds' => $hit['total_seconds'],
            ];
        }

        return [
            'success' => true,
            'generated_at' => date('Y-m-d H:i:s'),
            'session' => $summaryData['session'] ?? null,
            'summary' => [
                'hits_count' => $summaryData['hits_count'] ?? 0,
                'athletes_count' => count($athletes),
            ],
            'ranking' => $rankingData['ranking'] ?? [],
            'athletes' => array_values($athletes),
            'track_status' => array_values($trackStatus),
            'hits' => $summaryData['hits'] ?? [],
        ];
    }

    private function getHitRecords(int $hitId): array
    {
        $registroModel = new RegistroTiempoModel();

        return $registroModel
            ->select('registros_tiempo.*, puntos_control.codigo, puntos_control.nombre, puntos_control.orden')
            ->join('puntos_control', 'puntos_control.id = registros_tiempo.punto_control_id')
            ->where('registros_tiempo.hit_entrenamiento_id', $hitId)
            ->orderBy('puntos_control.orden', 'ASC')
            ->findAll();
    }

    private function buildSplits(array $records): array
    {
        $splits = [];

        for ($i = 1; $i < count($records); $i++) {
            $from = $records[$i - 1];
            $to   = $records[$i];

            $seconds = ((int) $to['timestamp_ms'] - (int) $from['timestamp_ms']) / 1000;

            $splits[] = [
                'from'    => $from['codigo'],
                'to'      => $to['codigo'],
                'section' => ($from['nombre'] ?? $from['codigo']) . ' → ' . ($to['nombre'] ?? $to['codigo']),
                'seconds' => round($seconds, 3),
            ];
        }

        return $splits;
    }

    private function calculateTotalTime(array $records): ?float
    {
        if (count($records) < 2) {
            return null;
        }

        $first = $records[0];
        $last  = $records[count($records) - 1];

        return round(
            ((int) $last['timestamp_ms'] - (int) $first['timestamp_ms']) / 1000,
            3
        );
    }

    private function buildHitAnalysis(int $hitId): ?array
    {
        $records = $this->getHitRecords($hitId);

        if (empty($records)) {
            return null;
        }

        $splits = $this->buildSplits($records);
        $totalSeconds = $this->calculateTotalTime($records);
        $performance = $this->buildPerformanceMetrics($splits, $totalSeconds);

        return [
            'records' => $records,
            'splits' => $splits,
            'total_seconds' => $totalSeconds,
            'performance' => $performance,
        ];
    }

    public function compareHits(int $hitAId, int $hitBId): array
    {
        $hitA = $this->getHitSummary($hitAId);
        $hitB = $this->getHitSummary($hitBId);

        if (empty($hitA['success']) || empty($hitB['success'])) {
            return [
                'success' => false,
                'message' => 'Uno de los hits no existe o no tiene registros suficientes.',
            ];
        }

        $metrics = [
            'gate' => 'Gate / Salida',
            'first_straight' => 'Primera recta',
            'middle_track' => 'Curvas / Parte media',
            'final_straight' => 'Sprint final',
            'total' => 'Tiempo total',
        ];

        $comparison = [];
        $largestGain = null;
        $largestLoss = null;

        foreach ($metrics as $key => $label) {
            $a = $hitA['performance'][$key] ?? null;
            $b = $hitB['performance'][$key] ?? null;

            if ($a === null || $b === null) {
                continue;
            }

            $difference = round($b - $a, 3);

            $item = [
                'metric' => $key,
                'label' => $label,
                'hit_a' => $a,
                'hit_b' => $b,
                'difference' => $difference,
                'status' => $difference < 0 ? 'improved' : ($difference > 0 ? 'worse' : 'same'),
            ];

            $comparison[] = $item;

            if ($difference < 0) {
                if ($largestGain === null || $difference < $largestGain['difference']) {
                    $largestGain = $item;
                }
            }

            if ($difference > 0) {
                if ($largestLoss === null || $difference > $largestLoss['difference']) {
                    $largestLoss = $item;
                }
            }
        }

        return [
            'success' => true,
            'hit_a' => $hitA,
            'hit_b' => $hitB,
            'summary' => [
                'total_difference' => round(
                    ($hitB['total_seconds'] ?? 0) - ($hitA['total_seconds'] ?? 0),
                    3
                ),
                'largest_gain' => $largestGain,
                'largest_loss' => $largestLoss,
            ],
            'comparison' => $comparison,
        ];
    }

    public function compareAthletes(int $athleteAId, int $athleteBId): array
    {
        $dashboardA = $this->getAthleteDashboard($athleteAId);
        $dashboardB = $this->getAthleteDashboard($athleteBId);

        if (empty($dashboardA['success']) || empty($dashboardB['success'])) {
            return [
                'success' => false,
                'message' => 'No existen datos suficientes para comparar atletas.',
            ];
        }

        $historyA = $this->getAthleteHistory($athleteAId);
        $historyB = $this->getAthleteHistory($athleteBId);

        $bestHitsA = $this->getBestHits($athleteAId);
        $bestHitsB = $this->getBestHits($athleteBId);

        $performanceA =
            $this->calculateAthletePerformanceAverage(
                $athleteAId
            );

        $performanceB =
            $this->calculateAthletePerformanceAverage(
                $athleteBId
            );

        $insightsA =
            $this->generateAthleteInsights(
                $performanceA
            );

        $insightsB =
            $this->generateAthleteInsights(
                $performanceB
            );

        $athleteAName = $dashboardA['best_hit']['athlete']['nombres'] ?? 'Atleta ' . $athleteAId;
        $athleteBName = $dashboardB['best_hit']['athlete']['nombres'] ?? 'Atleta ' . $athleteBId;

        return [
            'success' => true,

            'athlete_a' => [
                'id' => $athleteAId,
                'nombre' => $athleteAName,
                'dashboard' => $dashboardA,
                'history' => $historyA,
                'best_hits' => $bestHitsA,
                'insights' => $insightsA,
            ],

            'athlete_b' => [
                'id' => $athleteBId,
                'nombre' => $athleteBName,
                'dashboard' => $dashboardB,
                'history' => $historyB,
                'best_hits' => $bestHitsB,
                'insights' => $insightsB,
            ],

            'comparison' => [
                'best_time' => [
                    'a' => $dashboardA['summary']['best_time'],
                    'b' => $dashboardB['summary']['best_time'],
                ],
                'average_time' => [
                    'a' => $dashboardA['summary']['average_time'],
                    'b' => $dashboardB['summary']['average_time'],
                ],
                'valid_hits' => [
                    'a' => $dashboardA['summary']['valid_hits'],
                    'b' => $dashboardB['summary']['valid_hits'],
                ],
                'performance_average' => [
                    'gate' => [
                        'a' => $performanceA['gate'] ?? null,
                        'b' => $performanceB['gate'] ?? null,
                    ],
                    'first_straight' => [
                        'a' => $performanceA['first_straight'] ?? null,
                        'b' => $performanceB['first_straight'] ?? null,
                    ],
                    'middle_track' => [
                        'a' => $performanceA['middle_track'] ?? null,
                        'b' => $performanceB['middle_track'] ?? null,
                    ],
                    'final_straight' => [
                        'a' => $performanceA['final_straight'] ?? null,
                        'b' => $performanceB['final_straight'] ?? null,
                    ],
                ],
            ],


        ];
    }

    private function calculateAthletePerformanceAverage(int $athleteId): array
    {
        $history = $this->getAthleteHistory($athleteId);

        if (empty($history['success'])) {
            return [];
        }

        $metrics = [
            'gate' => [],
            'first_straight' => [],
            'middle_track' => [],
            'final_straight' => [],
            'total' => [],
        ];

        foreach ($history['history'] as $record) {

            foreach ($metrics as $metric => $values) {

                $value = $record['performance'][$metric] ?? null;

                if ($value !== null) {
                    $metrics[$metric][] = $value;
                }
            }
        }

        $result = [];

        foreach ($metrics as $metric => $values) {

            $result[$metric] =
                count($values) > 0
                    ? round(array_sum($values) / count($values), 3)
                    : null;
        }

        return $result;
    }

    private function generateAthleteInsights(array $performance): array
    {
        $insights = [];

        $metrics = [
            'gate' => 'Salida',
            'first_straight' => 'Primera recta',
            'middle_track' => 'Curvas',
            'final_straight' => 'Sprint final',
        ];

        $validMetrics = [];

        foreach ($metrics as $key => $label) {

            if (
                isset($performance[$key]) &&
                $performance[$key] !== null
            ) {
                $validMetrics[$key] = $performance[$key];
            }
        }

        if (count($validMetrics) < 2) {
            return [];
        }

        asort($validMetrics);

        $bestKey = array_key_first($validMetrics);
        $worstKey = array_key_last($validMetrics);

        $insights[] = [
            'type' => 'strength',
            'title' => 'Fortaleza principal',
            'message' => $metrics[$bestKey],
        ];

        $insights[] = [
            'type' => 'improvement',
            'title' => 'Área de mejora',
            'message' => $metrics[$worstKey],
        ];

        return $insights;
    }

}