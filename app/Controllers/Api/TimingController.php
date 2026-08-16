<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\Performance\TimingEventProcessor;
use App\Services\PerformanceAnalyticsService;

class TimingController extends BaseController
{
    public function pass()
    {
        $request = $this->request->getJSON(true) ?? [];

        $processor = new TimingEventProcessor();
        $result = $processor->process($request);

        return $this->response
            ->setStatusCode($result['status_code'] ?? 200)
            ->setJSON($result);
    }

    public function summary($hitId)
    {
        $analyticsService = new PerformanceAnalyticsService();
        $data = $analyticsService->getHitSummary((int) $hitId);

        if (empty($data['success'])) {
            return $this->response->setStatusCode(404)->setJSON($data);
        }

        return $this->response->setJSON($data);
    }

    public function sessionSummary($sessionId)
    {
        $analyticsService = new PerformanceAnalyticsService();
        $data = $analyticsService->getSessionSummary((int) $sessionId);

        if (empty($data['success'])) {
            return $this->response->setStatusCode(404)->setJSON($data);
        }

        return $this->response->setJSON($data);
    }
}
