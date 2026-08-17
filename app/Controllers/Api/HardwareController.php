<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\Performance\HardwareManagerService;

class HardwareController extends BaseController
{
    private function service(): HardwareManagerService
    {
        return new HardwareManagerService();
    }

    public function aats()
    {
        return $this->response->setJSON($this->service()->listAats());
    }

    public function createAat()
    {
        $data = $this->service()->createAat($this->request->getJSON(true) ?? []);
        return $this->response->setStatusCode($data['success'] ? 201 : 400)->setJSON($data);
    }

    public function assignAat($aatId)
    {
        $data = $this->service()->assignAat((int)$aatId, $this->request->getJSON(true) ?? []);
        return $this->response->setStatusCode($data['success'] ? 200 : 400)->setJSON($data);
    }

    public function returnAat($aatId)
    {
        $data = $this->service()->returnAat((int)$aatId);
        return $this->response->setStatusCode($data['success'] ? 200 : 400)->setJSON($data);
    }

    public function aatHistory($aatId)
    {
        return $this->response->setJSON($this->service()->aatHistory((int)$aatId));
    }

    public function btns()
    {
        return $this->response->setJSON($this->service()->listBtns());
    }

    public function saveBtn($id = null)
    {
        $data = $this->service()->saveBtn($id !== null ? (int)$id : null, $this->request->getJSON(true) ?? []);
        return $this->response->setStatusCode($data['success'] ? ($id ? 200 : 201) : 400)->setJSON($data);
    }

    public function btnHealth($id)
    {
        $data = $this->service()->updateBtnHealth((int)$id, $this->request->getJSON(true) ?? []);
        return $this->response->setStatusCode($data['success'] ? 200 : 400)->setJSON($data);
    }

    public function btnConfiguration($deviceCode)
    {
        $data = $this->service()->btnConfiguration((string)$deviceCode);
        return $this->response->setStatusCode($data['success'] ? 200 : 404)->setJSON($data);
    }

    public function btnTelemetry($deviceCode)
    {
        $data = $this->service()->updateBtnTelemetryByCode((string)$deviceCode, $this->request->getJSON(true) ?? []);
        return $this->response->setStatusCode($data['success'] ? 200 : 404)->setJSON($data);
    }
}
