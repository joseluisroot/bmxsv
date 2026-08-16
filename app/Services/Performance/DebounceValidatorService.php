<?php

namespace App\Services\Performance;

use App\Repositories\Performance\TimingRepository;

class DebounceValidatorService
{
    protected TimingRepository $timingRepository;
    protected int $debounceMilliseconds = 500;

    public function __construct()
    {
        $this->timingRepository = new TimingRepository();
    }

    public function validate(int $hitId, int $incomingTimestamp): array
    {
        $lastEvent = $this->timingRepository->getLastEventByHit($hitId);

        if (!$lastEvent) {
            return ['success' => true];
        }

        $lastTimestamp = (int) $lastEvent['timestamp_ms'];
        $diff = abs($incomingTimestamp - $lastTimestamp);

        if ($diff <= $this->debounceMilliseconds) {
            return [
                'success' => false,
                'message' => 'Evento rechazado por posible rebote del sensor.',
                'last_timestamp' => $lastTimestamp,
                'received' => $incomingTimestamp,
                'diff_ms' => $diff,
                'debounce_ms' => $this->debounceMilliseconds,
            ];
        }

        return ['success' => true];
    }
}
