<?php

namespace App\Services\Performance;

use App\Models\HitEntrenamientoModel;
use App\Models\PuntoControlModel;

class AttemptResolverService
{
    protected HitEntrenamientoModel $hitModel;
    protected HitCreatorService $hitCreator;

    public function __construct()
    {
        $this->hitModel = new HitEntrenamientoModel();
        $this->hitCreator = new HitCreatorService();
    }

    public function resolve(array $session, array $athlete, string $timingPoint): array
    {
        $hit = $this->hitModel
            ->where('sesion_entrenamiento_id', $session['id'])
            ->where('atleta_id', $athlete['id'])
            ->whereIn('estado', ['pendiente', 'en_progreso'])
            ->orderBy('numero_hit', 'DESC')
            ->first();

        if ($hit) {
            return ['success' => true, 'hit' => $hit, 'created' => false];
        }

        if (($session['modo_hits'] ?? 'manual') !== 'automatico') {
            return [
                'success' => false,
                'message' => 'No existe un hit abierto y la sesión está en modo manual.',
                'mode' => $session['modo_hits'] ?? 'manual',
            ];
        }

        if (empty($session['nodo_inicio_id'])) {
            return ['success' => false, 'message' => 'La sesión no tiene nodo inicial configurado.'];
        }

        $startNode = (new PuntoControlModel())->find((int) $session['nodo_inicio_id']);

        if (!$startNode || empty($startNode['codigo'])) {
            return ['success' => false, 'message' => 'La sesión no tiene nodo inicial válido.'];
        }

        if ($timingPoint !== $startNode['codigo']) {
            return [
                'success' => false,
                'message' => 'Solo el nodo inicial puede crear un nuevo hit.',
                'expected' => $startNode['codigo'],
                'received' => $timingPoint,
            ];
        }

        $created = $this->hitCreator->createAutomaticHit($session, (int) $athlete['id']);
        if (empty($created['success'])) {
            return $created;
        }

        $hit = $this->hitModel->find((int) $created['hit_id']);

        return ['success' => true, 'hit' => $hit, 'created' => true];
    }
}
