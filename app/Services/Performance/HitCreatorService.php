<?php

namespace App\Services\Performance;

use App\Models\HitEntrenamientoModel;

class HitCreatorService
{
    protected HitEntrenamientoModel $hitModel;
    public function __construct()
    {
        $this->hitModel = new HitEntrenamientoModel();
    }
    public function createAutomaticHit(array $session, int $athleteId): array
    {
        $configurationId = $session['configuracion_bicicleta_default_id'] ?? null;

        if (empty($configurationId)) {
            return [
                'success' => false,
                'message' => 'La sesión no tiene configuración de bicicleta por defecto.',
            ];
        }



        $lastHit = $this->hitModel
            ->where('sesion_entrenamiento_id', (int) $session['id'])
            ->where('atleta_id', $athleteId)
            ->orderBy('numero_hit', 'DESC')
            ->first();

        $nextHitNumber = $lastHit
            ? ((int) $lastHit['numero_hit']) + 1
            : 1;

        $hitId = $this->hitModel->insert([
            'sesion_entrenamiento_id' => (int) $session['id'],
            'atleta_id' => $athleteId,
            'configuracion_bicicleta_id' => (int) $configurationId,
            'numero_hit' => $nextHitNumber,
            'tipo_hit' => 'entrenamiento',
            'estado' => 'pendiente',
            'notas_coach' => 'Hit creado automáticamente por BTPS',
            'sensacion_atleta' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if (!$hitId) {
            return [
                'success' => false,
                'message' => 'No se pudo crear el hit automáticamente.',
            ];
        }

        return [
            'success' => true,
            'hit_id' => (int) $hitId,
            'numero_hit' => $nextHitNumber,
            'session_id' => (int) $session['id'],
            'athlete_id' => $athleteId,
            'configuration_id' => (int) $configurationId,
        ];
    }
}