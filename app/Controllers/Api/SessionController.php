<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\Performance\SessionManagerService;

class SessionController extends BaseController
{
    protected SessionManagerService $service;

    public function __construct()
    {
        $this->service = new SessionManagerService();
    }

    public function index()
    {
        return $this->response->setJSON($this->service->listSessions());
    }

    public function show($sessionId)
    {
        $data = $this->service->getSession((int) $sessionId);
        return $this->response
            ->setStatusCode(!empty($data['success']) ? 200 : 404)
            ->setJSON($data);
    }

    public function create()
    {
        $payload = $this->request->getJSON(true) ?? [];
        $data = $this->service->createSession($payload);

        return $this->response
            ->setStatusCode(!empty($data['success']) ? 201 : 400)
            ->setJSON($data);
    }

    public function update($sessionId)
    {
        $payload = $this->request->getJSON(true) ?? [];
        $data = $this->service->updateSession((int) $sessionId, $payload);

        return $this->response
            ->setStatusCode(!empty($data['success']) ? 200 : 400)
            ->setJSON($data);
    }

    public function status($sessionId)
    {
        $payload = $this->request->getJSON(true) ?? [];
        $data = $this->service->updateStatus((int) $sessionId, (string) ($payload['estado'] ?? ''));

        return $this->response
            ->setStatusCode(!empty($data['success']) ? 200 : 400)
            ->setJSON($data);
    }

    public function timingPoints()
    {
        return $this->response->setJSON($this->service->timingPoints());
    }
}
