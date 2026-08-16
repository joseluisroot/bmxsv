<?php

namespace App\Services\Performance;

use App\Repositories\Performance\TimingRepository;

class TimestampValidatorService
{
    protected TimingRepository $timingRepository;

    protected int $maxGapMilliseconds = 300000; // 5 minutos

    public function __construct()
    {
        $this->timingRepository = new TimingRepository();
    }

    public function validate(
        int $hitId,
        int $incomingTimestamp
    ): array {

        $lastEvent = $this->timingRepository
            ->getLastEventByHit($hitId);

        if (!$lastEvent) {
            return [
                'success' => true
            ];
        }

        $lastTimestamp = (int)$lastEvent['timestamp_ms'];

        if ($incomingTimestamp < $lastTimestamp) {
            return [
                'success' => false,
                'message' => 'El timestamp recibido es menor al último registrado.',
                'last_timestamp' => $lastTimestamp,
                'received' => $incomingTimestamp,
            ];
        }

        if ($incomingTimestamp === $lastTimestamp) {
            return [
                'success' => false,
                'message' => 'El timestamp ya fue registrado.',
            ];
        }

        if (($incomingTimestamp - $lastTimestamp) > $this->maxGapMilliseconds) {
            return [
                'success' => false,
                'message' => 'El tiempo entre eventos supera el máximo permitido.',
            ];
        }

        return [
            'success' => true
        ];
    }
}