<?php

namespace app\Services\Performance;

use App\Models\DispositivoTiempoModel;
use App\Models\PuntoControlModel;

class DeviceValidatorService
{
    public function validate(
        string $deviceCode,
        string $timingPointCode
    ): array {
        $puntoModel = new PuntoControlModel();
        $dispositivoModel = new DispositivoTiempoModel();

        $punto = $puntoModel
            ->where('codigo', $timingPointCode)
            ->where('activo', 1)
            ->first();

        if (!$punto) {
            return [
                'success' => false,
                'message' => 'Punto de control no encontrado o inactivo.',
            ];
        }

        $dispositivo = $dispositivoModel
            ->where('codigo_dispositivo', $deviceCode)
            ->where('activo', 1)
            ->first();

        if (!$dispositivo) {
            return [
                'success' => false,
                'message' => 'Dispositivo no encontrado o inactivo.',
            ];
        }

        if ((int) $dispositivo['punto_control_id'] !== (int) $punto['id']) {
            return [
                'success' => false,
                'message' => 'El dispositivo no pertenece al punto de control enviado.',
            ];
        }

        return [
            'success' => true,
            'punto' => $punto,
            'dispositivo' => $dispositivo,
        ];
    }
}