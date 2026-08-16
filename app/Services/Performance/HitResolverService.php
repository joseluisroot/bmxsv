<?php

namespace App\Services\Performance;

use App\Models\ChipIdentificacionModel;
use App\Models\DispositivoTiempoModel;
use App\Models\HitEntrenamientoModel;
use App\Models\PuntoControlModel;
use App\Models\SesionEntrenamientoModel;
use App\Repositories\Performance\TimingRepository;
use Throwable;

class HitResolverService
{
    protected TimingRepository $timingRepository;
    protected HitCreatorService $hitCreator;
    protected AttemptResolverService $attemptResolver;
    protected ValidatorPipelineService $validatorPipeline;
    protected InvalidEventLoggerService $invalidEventLogger;

    public function __construct()
    {
        $this->timingRepository = new TimingRepository();
        $this->hitCreator = new HitCreatorService();
        $this->attemptResolver = new AttemptResolverService();
        $this->validatorPipeline = new ValidatorPipelineService();
        $this->invalidEventLogger = new InvalidEventLoggerService();
    }

    public function resolveAthleteByChip(string $chipCode): ?array
    {
        $chipModel = new ChipIdentificacionModel();
        $chip = $chipModel
            ->select('chips_identificacion.*, atletas.nombres, atletas.apellidos')
            ->join('atletas', 'atletas.id = chips_identificacion.atleta_id')
            ->where('chips_identificacion.codigo_chip', $chipCode)
            ->where('chips_identificacion.activo', 1)
            ->first();

        if (!$chip) {
            return null;
        }

        return [
            'chip_id' => (int) $chip['id'],
            'chip_code' => $chip['codigo_chip'],
            'athlete' => [
                'id' => (int) $chip['atleta_id'],
                'nombre' => trim(($chip['nombres'] ?? '') . ' ' . ($chip['apellidos'] ?? '')),
            ],
        ];
    }

    public function resolveActiveSession(): ?array
    {
        return (new SesionEntrenamientoModel())
            ->where('estado', 'abierta')
            ->orderBy('fecha', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function resolveOpenHit(int $sessionId, int $athleteId): ?array
    {
        return (new HitEntrenamientoModel())
            ->where('sesion_entrenamiento_id', $sessionId)
            ->where('atleta_id', $athleteId)
            ->whereIn('estado', ['pendiente', 'en_progreso'])
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function registerPassByHit(array $payload): array
    {
        foreach (['device_code', 'timing_point_code', 'hit_entrenamiento_id', 'timestamp_ms'] as $field) {
            if (!isset($payload[$field]) || $payload[$field] === '') {
                return ['success' => false, 'status_code' => 400, 'message' => "El campo {$field} es requerido."];
            }
        }

        $hitModel = new HitEntrenamientoModel();
        $sessionModel = new SesionEntrenamientoModel();
        $deviceModel = new DispositivoTiempoModel();
        $pointModel = new PuntoControlModel();

        $hitId = (int) $payload['hit_entrenamiento_id'];
        $hit = $hitModel->find($hitId);

        if (!$hit) {
            return ['success' => false, 'status_code' => 404, 'message' => 'El hit de entrenamiento no existe.'];
        }

        if ($hit['estado'] === 'completado') {
            return ['success' => false, 'status_code' => 409, 'message' => 'Este hit ya está completado. Crea un nuevo hit para una nueva pasada.'];
        }

        $session = $sessionModel->find((int) $hit['sesion_entrenamiento_id']);
        if (!$session || ($session['estado'] ?? null) !== 'abierta') {
            return ['success' => false, 'status_code' => 409, 'message' => 'La sesión asociada al hit no está abierta.'];
        }

        if (empty($session['nodo_inicio_id']) || empty($session['nodo_fin_id'])) {
            return ['success' => false, 'status_code' => 409, 'message' => 'La sesión no tiene nodos de inicio y fin configurados.'];
        }

        $endNode = $pointModel->find((int) $session['nodo_fin_id']);
        if (!$endNode || empty($endNode['codigo'])) {
            return ['success' => false, 'status_code' => 409, 'message' => 'El nodo final configurado para la sesión es inválido.'];
        }

        $eventId = $this->resolveEventId($payload);
        $existingEvent = $this->timingRepository->findByEventId($eventId);
        if ($existingEvent) {
            return [
                'success' => true,
                'status_code' => 200,
                'message' => 'Evento ya procesado anteriormente.',
                'idempotent_replay' => true,
                'data' => [
                    'registro_id' => (int) $existingEvent['id'],
                    'event_id' => $eventId,
                    'hit_entrenamiento_id' => $hitId,
                ],
            ];
        }

        $validation = $this->validatorPipeline->validate([
            'hit_id' => $hitId,
            'device_code' => $payload['device_code'],
            'timing_point_code' => $payload['timing_point_code'],
            'timestamp_ms' => (int) $payload['timestamp_ms'],
            'session' => $session,
        ]);

        if (empty($validation['success'])) {
            $payload['event_id'] = $eventId;
            $invalidEventId = $this->invalidEventLogger->log($payload, $validation);
            return [
                'success' => false,
                'status_code' => 409,
                'message' => $validation['message'],
                'validator' => $validation['validator'] ?? null,
                'invalid_event_id' => $invalidEventId,
                'details' => $validation['details'] ?? [],
            ];
        }

        $point = $validation['punto'];
        $device = $validation['dispositivo'];

        if ($this->timingRepository->existsTimingPoint($hitId, (int) $point['id'])) {
            return ['success' => false, 'status_code' => 409, 'message' => 'Este punto de control ya fue registrado para este hit.'];
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $payloadRaw = [
                'event_id' => $eventId,
                'device_code' => $payload['device_code'],
                'timing_point_code' => $payload['timing_point_code'],
                'chip_code' => $payload['chip_code'] ?? null,
                'raw_data' => $payload['raw_data'] ?? null,
                'received_at' => date('Y-m-d H:i:s'),
            ];

            $registroId = $this->timingRepository->save([
                'event_id' => $eventId,
                'hit_entrenamiento_id' => $hitId,
                'punto_control_id' => (int) $point['id'],
                'dispositivo_tiempo_id' => (int) $device['id'],
                'timestamp_ms' => (int) $payload['timestamp_ms'],
                'payload_raw' => json_encode($payloadRaw),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $deviceModel->update((int) $device['id'], ['ultima_conexion' => date('Y-m-d H:i:s')]);

            $newState = $point['codigo'] === $endNode['codigo'] ? 'completado' : 'en_progreso';
            $hitModel->update($hitId, [
                'estado' => $newState,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            if (!$db->transStatus()) {
                throw new \RuntimeException('La transacción de timing no pudo completarse.');
            }

            $db->transCommit();

            return [
                'success' => true,
                'status_code' => 201,
                'message' => 'Registro de tiempo guardado correctamente.',
                'data' => [
                    'registro_id' => $registroId,
                    'event_id' => $eventId,
                    'hit_entrenamiento_id' => $hitId,
                    'punto_control' => $point['codigo'],
                    'dispositivo' => $device['codigo_dispositivo'],
                    'timestamp_ms' => (int) $payload['timestamp_ms'],
                    'hit_estado' => $newState,
                ],
            ];
        } catch (Throwable $e) {
            $db->transRollback();

            $existingEvent = $this->timingRepository->findByEventId($eventId);
            if ($existingEvent) {
                return [
                    'success' => true,
                    'status_code' => 200,
                    'message' => 'Evento ya procesado anteriormente.',
                    'idempotent_replay' => true,
                    'data' => ['registro_id' => (int) $existingEvent['id'], 'event_id' => $eventId, 'hit_entrenamiento_id' => $hitId],
                ];
            }

            return ['success' => false, 'status_code' => 500, 'message' => 'No se pudo persistir el evento de timing de forma segura.'];
        }
    }

    public function registerPassByChip(array $payload): array
    {
        foreach (['device_code', 'timing_point_code', 'chip_code', 'timestamp_ms'] as $field) {
            if (!isset($payload[$field]) || $payload[$field] === '') {
                return ['success' => false, 'status_code' => 400, 'message' => "El campo {$field} es requerido."];
            }
        }

        $chipData = $this->resolveAthleteByChip((string) $payload['chip_code']);
        if (!$chipData) {
            return ['success' => false, 'status_code' => 404, 'message' => 'Chip no encontrado o inactivo.'];
        }

        $session = $this->resolveActiveSession();
        if (!$session) {
            return ['success' => false, 'status_code' => 404, 'message' => 'No hay sesión activa abierta.'];
        }

        $attempt = $this->attemptResolver->resolve($session, $chipData['athlete'], (string) $payload['timing_point_code']);
        if (empty($attempt['success'])) {
            return ['success' => false, 'status_code' => 400, 'message' => $attempt['message'], 'details' => $attempt];
        }

        $payload['hit_entrenamiento_id'] = $attempt['hit']['id'];
        $result = $this->registerPassByHit($payload);

        if (!empty($result['success'])) {
            $result['data']['chip_code'] = $payload['chip_code'];
            $result['data']['athlete'] = $chipData['athlete'];
            $result['data']['session_id'] = (int) $session['id'];
            $result['data']['hit_created_automatically'] = !empty($attempt['created']);
        }

        return $result;
    }

    private function resolveEventId(array $payload): string
    {
        if (!empty($payload['event_id'])) {
            return substr((string) $payload['event_id'], 0, 64);
        }

        return hash('sha256', implode('|', [
            (string) ($payload['device_code'] ?? ''),
            (string) ($payload['chip_code'] ?? ''),
            (string) ($payload['hit_entrenamiento_id'] ?? ''),
            (string) ($payload['timing_point_code'] ?? ''),
            (string) ($payload['timestamp_ms'] ?? ''),
        ]));
    }
}
