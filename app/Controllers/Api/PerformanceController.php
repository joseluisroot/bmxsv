<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\HitEntrenamientoModel;
use App\Models\RegistroTiempoModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\PerformanceAnalyticsService;

class PerformanceController extends BaseController
{
    public function setupComparison($athleteId)
    {
        $analyticsService = new PerformanceAnalyticsService();

        $data = $analyticsService
            ->getSetupComparison((int)$athleteId);

        if (empty($data['success'])) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON($data);
        }

        return $this->response->setJSON($data);
    }


    public function bestHits($athleteId)
    {
        $analyticsService = new PerformanceAnalyticsService();

        $data = $analyticsService->getBestHits((int)$athleteId);

        if (empty($data['success'])) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON($data);
        }

        return $this->response->setJSON($data);
    }

    public function history($athleteId)
    {
        $analyticsService = new PerformanceAnalyticsService();

        $data = $analyticsService->getAthleteHistory((int)$athleteId);

        if (empty($data['success'])) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON($data);
        }

        return $this->response->setJSON($data);
    }

    public function dashboard($athleteId)
    {



        $analyticsService = new PerformanceAnalyticsService();

        $data = $analyticsService->getAthleteDashboard((int)$athleteId);

        if (empty($data['success'])) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON($data);
        }

        return $this->response->setJSON($data);
    }

    public function sessionRanking($sessionId)
    {
        $analyticsService = new PerformanceAnalyticsService();

        $data = $analyticsService->getSessionRanking((int)$sessionId);

        if (empty($data['success'])) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON($data);
        }

        return $this->response->setJSON($data);
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
            return isset($item['Performance'][$metricKey]) && $item['Performance'][$metricKey] !== null;
        });

        usort($ranking, function ($a, $b) use ($metricKey) {
            return $a['Performance'][$metricKey] <=> $b['Performance'][$metricKey];
        });

        $position = 1;

        return array_map(function ($item) use (&$position, $metricKey) {
            return [
                'position' => $position++,
                'hit_id' => $item['hit_id'],
                'numero_hit' => $item['numero_hit'],
                'athlete' => $item['athlete'],
                'bike_setup' => $item['bike_setup'],
                'seconds' => $item['Performance'][$metricKey],
            ];
        }, array_values($ranking));
    }

    public function fullDashboard($athleteId)
    {
        $analyticsService = new PerformanceAnalyticsService();

        $data = $analyticsService->getFullDashboard((int)$athleteId);

        return $this->response->setJSON($data);
    }

    public function coachDashboard($sessionId)
    {
        $analyticsService = new PerformanceAnalyticsService();

        $data = $analyticsService->getCoachDashboard((int)$sessionId);

        if (empty($data['success'])) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON($data);
        }

        return $this->response->setJSON($data);
    }

    public function liveSessionStream($sessionId)
    {
        set_time_limit(0);
        ignore_user_abort(true);

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache, no-transform');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        $analyticsService = new PerformanceAnalyticsService();

        $lastPayloadHash = null;

        echo "event: connected\n";
        echo "data: " . json_encode([
                'status' => 'connected',
                'session_id' => (int) $sessionId,
                'time' => date('H:i:s'),
            ]) . "\n\n";

        flush();

        $maxLoops = 30; // 30 ciclos x 2 segundos = 1 minuto

        for ($i = 0; $i < $maxLoops; $i++) {
            if (connection_aborted()) {
                break;
            }

            $data = $analyticsService->getCoachDashboard((int) $sessionId);

            $payloadHash = md5(json_encode($data));

            if ($payloadHash !== $lastPayloadHash) {
                echo "event: coach.dashboard\n";
                echo "data: " . json_encode($data) . "\n\n";

                $lastPayloadHash = $payloadHash;

                flush();
            } else {
                echo ": heartbeat " . date('H:i:s') . "\n\n";
                flush();
            }

            sleep(2);
        }

        echo "event: close\n";
        echo "data: " . json_encode([
                'status' => 'closed',
                'reason' => 'stream_timeout',
                'time' => date('H:i:s'),
            ]) . "\n\n";

        flush();
        exit;
    }

    public function compareHits($hitAId, $hitBId)
    {
        $analyticsService = new PerformanceAnalyticsService();

        $data = $analyticsService->compareHits(
            (int) $hitAId,
            (int) $hitBId
        );

        if (empty($data['success'])) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON($data);
        }

        return $this->response->setJSON($data);
    }

    public function compareAthletes(
        $athleteAId,
        $athleteBId
    )
    {
        $analyticsService = new PerformanceAnalyticsService();

        $data = $analyticsService->compareAthletes(
            (int)$athleteAId,
            (int)$athleteBId
        );

        if (empty($data['success'])) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON($data);
        }

        return $this->response->setJSON($data);
    }

    public function clubRanking()
    {
        $analyticsService = new PerformanceAnalyticsService();

        $data = $analyticsService->getClubRanking();

        if (empty($data['success'])) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON($data);
        }

        return $this->response->setJSON($data);
    }

    public function athleteProgress($athleteId)
    {
        $analyticsService = new PerformanceAnalyticsService();

        $data = $analyticsService->getAthleteProgress((int) $athleteId);

        if (empty($data['success'])) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON($data);
        }

        return $this->response->setJSON($data);
    }

    public function activeSession()
    {
        $analyticsService = new PerformanceAnalyticsService();

        $data = $analyticsService->getActiveSession();

        if (empty($data['success'])) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON($data);
        }

        return $this->response->setJSON($data);
    }

    public function updateSessionStatus($sessionId)
    {
        $request = $this->request->getJSON(true);

        if (empty($request['estado'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'El campo estado es requerido.',
            ]);
        }

        $analyticsService = new PerformanceAnalyticsService();

        $data = $analyticsService->updateSessionStatus(
            (int) $sessionId,
            $request['estado']
        );

        if (empty($data['success'])) {
            return $this->response->setStatusCode(400)->setJSON($data);
        }

        return $this->response->setJSON($data);
    }

    public function createSessionHit($sessionId)
    {
        $request = $this->request->getJSON(true);

        if (
            empty($request['athlete_id']) ||
            empty($request['configuration_id'])
        ) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => 'athlete_id y configuration_id son requeridos.',
                ]);
        }

        $analyticsService = new PerformanceAnalyticsService();

        $data = $analyticsService->createSessionHit(
            (int)$sessionId,
            (int)$request['athlete_id'],
            (int)$request['configuration_id'],
            $request['notas_coach'] ?? null,
            $request['sensacion_atleta'] ?? null
        );

        if (!$data['success']) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON($data);
        }

        return $this->response->setJSON($data);
    }


    public function sessionHits($sessionId)
    {
        $analyticsService = new PerformanceAnalyticsService();

        $data = $analyticsService->getSessionHits((int) $sessionId);

        if (empty($data['success'])) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON($data);
        }

        return $this->response->setJSON($data);
    }

    public function athletes()
    {

        $service = new PerformanceAnalyticsService();

        return $this->response->setJSON(
            $service->getAthletes()
        );
    }

    public function configurations()
    {
        $service = new PerformanceAnalyticsService();

        return $this->response->setJSON(
            $service->getConfigurations()
        );
    }
}
