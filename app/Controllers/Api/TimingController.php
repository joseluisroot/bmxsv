<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\DispositivoTiempoModel;
use App\Models\HitEntrenamientoModel;
use App\Models\PuntoControlModel;
use App\Models\RegistroTiempoModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\PerformanceAnalyticsService;


class TimingController extends BaseController
{
    public function pass()
    {
        $request = $this->request->getJSON(true);

        if (!$request) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON([
                    'success' => false,
                    'message' => 'Payload JSON inválido.',
                ]);
        }

        $required = [
            'device_code',
            'timing_point_code',
            'hit_entrenamiento_id',
            'timestamp_ms',
        ];

        foreach ($required as $field) {
            if (!isset($request[$field]) || $request[$field] === '') {
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                    ->setJSON([
                        'success' => false,
                        'message' => "El campo {$field} es requerido.",
                    ]);
            }
        }

        $dispositivoModel = new DispositivoTiempoModel();
        $puntoModel       = new PuntoControlModel();
        $registroModel    = new RegistroTiempoModel();

        $punto = $puntoModel
            ->where('codigo', $request['timing_point_code'])
            ->where('activo', 1)
            ->first();

        if (!$punto) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON([
                    'success' => false,
                    'message' => 'Punto de control no encontrado o inactivo.',
                ]);
        }

        $dispositivo = $dispositivoModel
            ->where('codigo_dispositivo', $request['device_code'])
            ->where('activo', 1)
            ->first();

        if (!$dispositivo) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON([
                    'success' => false,
                    'message' => 'Dispositivo no encontrado o inactivo.',
                ]);
        }

        if ((int) $dispositivo['punto_control_id'] !== (int) $punto['id']) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON([
                    'success' => false,
                    'message' => 'El dispositivo no pertenece al punto de control enviado.',
                ]);
        }

        $hitModel = new HitEntrenamientoModel();

        $hit = $hitModel->find((int) $request['hit_entrenamiento_id']);

        if (!$hit) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON([
                    'success' => false,
                    'message' => 'El hit de entrenamiento no existe.',
                ]);
        }

        $payloadRaw = [
            'device_code'       => $request['device_code'],
            'timing_point_code' => $request['timing_point_code'],
            'raw_data'          => $request['raw_data'] ?? null,
            'received_at'       => date('Y-m-d H:i:s'),
        ];

        $registroId = $registroModel->insert([
            'hit_entrenamiento_id'  => (int) $request['hit_entrenamiento_id'],
            'punto_control_id'      => (int) $punto['id'],
            'dispositivo_tiempo_id' => (int) $dispositivo['id'],
            'timestamp_ms'          => (int) $request['timestamp_ms'],
            'payload_raw'           => json_encode($payloadRaw),
            'created_at'            => date('Y-m-d H:i:s'),
        ]);

        $dispositivoModel->update($dispositivo['id'], [
            'ultima_conexion' => date('Y-m-d H:i:s'),
        ]);

        $hitUpdated = $hitModel->find((int) $request['hit_entrenamiento_id']);

        $currentPosition = [
            'point_code'   => $punto['codigo'],
            'point_name'   => $punto['nombre'],
            'timestamp_ms' => (int) $request['timestamp_ms'],
        ];

        return $this->response
            ->setStatusCode(ResponseInterface::HTTP_CREATED)
            ->setJSON([
                'success' => true,
                'message' => 'Registro de tiempo guardado correctamente.',
                'data' => [
                    'registro_id' => $registroId,
                    'hit_entrenamiento_id' => (int) $request['hit_entrenamiento_id'],
                    'session_id' => (int) $hitUpdated['sesion_entrenamiento_id'],
                    'athlete_id' => (int) $hitUpdated['atleta_id'],
                    'punto_control' => $punto['codigo'],
                    'dispositivo' => $dispositivo['codigo_dispositivo'],
                    'timestamp_ms' => (int) $request['timestamp_ms'],
                    'current_position' => $currentPosition,
                ],
            ]);
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
