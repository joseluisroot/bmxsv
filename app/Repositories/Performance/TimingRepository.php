<?php

namespace App\Repositories\Performance;

use App\Models\RegistroTiempoModel;

class TimingRepository
{
    protected RegistroTiempoModel $registroModel;

    public function __construct()
    {
        $this->registroModel = new RegistroTiempoModel();
    }

    public function save(array $data): int
    {
        $this->registroModel->insert($data);
        return (int) $this->registroModel->getInsertID();
    }

    public function findByEventId(string $eventId): ?array
    {
        return $this->registroModel->where('event_id', $eventId)->first();
    }

    public function getLastEventByHit(int $hitId): ?array
    {
        return $this->registroModel
            ->select('registros_tiempo.*, puntos_control.codigo AS codigo')
            ->join('puntos_control', 'puntos_control.id = registros_tiempo.punto_control_id')
            ->where('hit_entrenamiento_id', $hitId)
            ->orderBy('timestamp_ms', 'DESC')
            ->first();
    }

    public function getEventsByHit(int $hitId): array
    {
        return $this->registroModel
            ->where('hit_entrenamiento_id', $hitId)
            ->orderBy('timestamp_ms')
            ->findAll();
    }

    public function existsTimingPoint(int $hitId, int $timingPointId): bool
    {
        return $this->registroModel
                ->where('hit_entrenamiento_id', $hitId)
                ->where('punto_control_id', $timingPointId)
                ->countAllResults() > 0;
    }
}
