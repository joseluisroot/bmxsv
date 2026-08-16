<?php

namespace App\Services\Performance;

use App\Models\ChipIdentificacionModel;
use App\Models\DispositivoTiempoModel;
use App\Models\HitEntrenamientoModel;
use App\Models\PuntoControlModel;
use App\Models\SesionEntrenamientoModel;
use App\Repositories\Performance\TimingRepository;
use App\Services\Performance\SequenceValidatorService;
use App\Services\Performance\TimestampValidatorService;
use App\Services\Performance\DebounceValidatorService;
use App\Services\Performance\ValidatorPipelineService;
use App\Services\Performance\InvalidEventLoggerService;

class HitResolverService
{
    protected TimingRepository $timingRepository;
    protected HitCreatorService $hitCreator;
    protected AttemptResolverService $attemptResolver;
    protected SequenceValidatorService $sequenceValidator;
    protected TimestampValidatorService $timestampValidator;
    protected DebounceValidatorService $debounceValidator;
    protected ValidatorPipelineService $validatorPipeline;
    protected InvalidEventLoggerService $invalidEventLogger;

    public function __construct()
    {
        $this->timingRepository = new TimingRepository();
        $this->hitCreator = new HitCreatorService();
        $this->attemptResolver = new AttemptResolverService();
        $this->sequenceValidator = new SequenceValidatorService();
        $this->timestampValidator = new TimestampValidatorService();
        $this->debounceValidator = new DebounceValidatorService();
        $this->validatorPipeline = new ValidatorPipelineService();
        $this->invalidEventLogger = new InvalidEventLoggerService();
    }
    public function resolveAthleteByChip(string $chipCode): ?array
    {
        $chipModel = new ChipIdentificacionModel();

        $chip = $chipModel
            ->select('
                chips_identificacion.*,
                atletas.nombres,
                atletas.apellidos
            ')
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
        $sessionModel = new SesionEntrenamientoModel();

        return $sessionModel
            ->where('estado', 'abierta')
            ->orderBy('fecha', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function resolveOpenHit(int $sessionId, int $athleteId): ?array
    {
        $hitModel = new HitEntrenamientoModel();

        return $hitModel
            ->where('sesion_entrenamiento_id', $sessionId)
            ->where('atleta_id', $athleteId)
            ->whereIn('estado', ['pendiente', 'en_progreso'])
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function registerPassByHit(array $payload): array
    {
        $required = [
            'device_code',
            'timing_point_code',
            'hit_entrenamiento_id',
            'timestamp_ms',
        ];

        foreach ($required as $field) {
            if (!isset($payload[$field]) || $payload[$field] === '') {
                return [
                    'success' => false,
                    'status_code' => 400,
                    'message' => "El campo {$field} es requerido.",
                ];
            }
        }

        $hitModel         = new HitEntrenamientoModel();
        $dispositivoModel = new DispositivoTiempoModel();

        $hit = $hitModel->find((int)$payload['hit_entrenamiento_id']);

        if (!$hit) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'El hit de entrenamiento no existe.',
            ];
        }

        if ($hit['estado'] === 'completado') {
            return [
                'success' => false,
                'status_code' => 400,
                'message' => 'Este hit ya está completado. Crea un nuevo hit para una nueva pasada.',
            ];
        }

        $validation = $this->validatorPipeline->validate([
            'hit_id' => (int) $payload['hit_entrenamiento_id'],
            'device_code' => $payload['device_code'],
            'timing_point_code' => $payload['timing_point_code'],
            'timestamp_ms' => (int) $payload['timestamp_ms'],
        ]);

        if (!$validation['success']) {

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

        $punto = $validation['punto'];
        $dispositivo = $validation['dispositivo'];

        if (
            $this->timingRepository->existsTimingPoint(
                (int)$payload['hit_entrenamiento_id'],
                (int)$punto['id']
            )
        ) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'Este punto de control ya fue registrado para este hit.',
            ];
        }

        $payloadRaw = [
            'device_code'       => $payload['device_code'],
            'timing_point_code' => $payload['timing_point_code'],
            'raw_data'          => $payload['raw_data'] ?? null,
            'received_at'       => date('Y-m-d H:i:s'),
        ];

        $registroId = $this->timingRepository->save([
            'hit_entrenamiento_id'  => (int)$payload['hit_entrenamiento_id'],
            'punto_control_id'      => (int)$punto['id'],
            'dispositivo_tiempo_id' => (int)$dispositivo['id'],
            'timestamp_ms'          => (int)$payload['timestamp_ms'],
            'payload_raw'           => json_encode($payloadRaw),
            'created_at'            => date('Y-m-d H:i:s'),
        ]);

        $dispositivoModel->update($dispositivo['id'], [
            'ultima_conexion' => date('Y-m-d H:i:s'),
        ]);

        if ($hit['estado'] === 'pendiente') {
            $hitModel->update((int)$payload['hit_entrenamiento_id'], [
                'estado' => 'en_progreso',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $sessionModel = new SesionEntrenamientoModel();
        $puntoControlModel = new PuntoControlModel();

        $session = $sessionModel->find($hit['sesion_entrenamiento_id']);

        $endNode = $puntoControlModel->find($session['nodo_fin_id']);

        if ($punto['codigo'] === $endNode['codigo']) {
            $hitModel->update((int)$payload['hit_entrenamiento_id'], [
                'estado' => 'completado',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return [
            'success' => true,
            'status_code' => 201,
            'message' => 'Registro de tiempo guardado correctamente.',
            'data' => [
                'registro_id' => $registroId,
                'hit_entrenamiento_id' => (int)$payload['hit_entrenamiento_id'],
                'punto_control' => $punto['codigo'],
                'dispositivo' => $dispositivo['codigo_dispositivo'],
                'timestamp_ms' => (int)$payload['timestamp_ms'],
                'hit_estado' => $punto['codigo'] === 'TP06' ? 'completado' : 'en_progreso',
            ],
        ];
    }

    public function registerPassByChip(array $payload): array
    {
        $required = [
            'device_code',
            'timing_point_code',
            'chip_code',
            'timestamp_ms',
        ];

        foreach ($required as $field) {
            if (!isset($payload[$field]) || $payload[$field] === '') {
                return [
                    'success' => false,
                    'status_code' => 400,
                    'message' => "El campo {$field} es requerido.",
                ];
            }
        }

        $chipData = $this->resolveAthleteByChip($payload['chip_code']);

        if (!$chipData) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'Chip no encontrado o inactivo.',
            ];
        }

        $session = $this->resolveActiveSession();

        if (!$session) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'No hay sesión activa abierta.',
            ];
        }

        $attempt = $this->attemptResolver->resolve(
            $session,
            $chipData['athlete'],
            $payload['timing_point_code']
        );

        if (!$attempt['success']) {
            return [
                'success' => false,
                'status_code' => 400,
                'message' => $attempt['message'],
                'details' => $attempt,
            ];
        }

        $payload['hit_entrenamiento_id'] = $attempt['hit']['id'];

        $result = $this->registerPassByHit($payload);

        if (!empty($result['success'])) {
            $result['data']['chip_code'] = $payload['chip_code'];
            $result['data']['athlete'] = $chipData['athlete'];
            $result['data']['session_id'] = (int) $session['id'];
        }

        return $result;
    }
}