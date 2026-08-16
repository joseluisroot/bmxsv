<?php

namespace App\Services\Performance;

use App\Models\PuntoControlModel;
use App\Models\SesionEntrenamientoModel;

class SessionManagerService
{
    public function listSessions(): array
    {
        $model = new SesionEntrenamientoModel();

        $sessions = $model
            ->orderBy('fecha', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();

        return [
            'success' => true,
            'sessions' => array_map(fn(array $session) => $this->decorateSession($session), $sessions),
        ];
    }

    public function getSession(int $sessionId): array
    {
        $session = (new SesionEntrenamientoModel())->find($sessionId);

        if (!$session) {
            return ['success' => false, 'message' => 'Sesión no encontrada.'];
        }

        return ['success' => true, 'session' => $this->decorateSession($session)];
    }

    public function createSession(array $payload): array
    {
        $validation = $this->validatePayload($payload);
        if (!$validation['success']) {
            return $validation;
        }

        $model = new SesionEntrenamientoModel();
        $now = date('Y-m-d H:i:s');

        if (($payload['estado'] ?? 'borrador') === 'abierta') {
            $this->closeOtherOpenSessions();
        }

        $id = $model->insert([
            'nombre' => trim((string) $payload['nombre']),
            'pista' => $payload['pista'] ?? null,
            'fecha' => $payload['fecha'],
            'coach' => $payload['coach'] ?? null,
            'objetivo' => $payload['objetivo'] ?? null,
            'clima' => $payload['clima'] ?? null,
            'estado_pista' => $payload['estado_pista'] ?? null,
            'notas' => $payload['notas'] ?? null,
            'estado' => $payload['estado'] ?? 'borrador',
            'modo_hits' => $payload['modo_hits'] ?? 'manual',
            'configuracion_bicicleta_default_id' => !empty($payload['configuracion_bicicleta_default_id']) ? (int) $payload['configuracion_bicicleta_default_id'] : null,
            'auto_close_hit' => !empty($payload['auto_close_hit']) ? 1 : 0,
            'nodo_inicio_id' => (int) $payload['nodo_inicio_id'],
            'nodo_fin_id' => (int) $payload['nodo_fin_id'],
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        if (!$id) {
            return ['success' => false, 'message' => 'No se pudo crear la sesión.'];
        }

        return [
            'success' => true,
            'message' => 'Sesión creada correctamente.',
            'session' => $this->decorateSession($model->find((int) $id)),
        ];
    }

    public function updateSession(int $sessionId, array $payload): array
    {
        $model = new SesionEntrenamientoModel();
        $existing = $model->find($sessionId);

        if (!$existing) {
            return ['success' => false, 'message' => 'Sesión no encontrada.'];
        }

        $merged = array_merge($existing, $payload);
        $validation = $this->validatePayload($merged);
        if (!$validation['success']) {
            return $validation;
        }

        if (($merged['estado'] ?? null) === 'abierta') {
            $this->closeOtherOpenSessions($sessionId);
        }

        $model->update($sessionId, [
            'nombre' => trim((string) $merged['nombre']),
            'pista' => $merged['pista'] ?? null,
            'fecha' => $merged['fecha'],
            'coach' => $merged['coach'] ?? null,
            'objetivo' => $merged['objetivo'] ?? null,
            'clima' => $merged['clima'] ?? null,
            'estado_pista' => $merged['estado_pista'] ?? null,
            'notas' => $merged['notas'] ?? null,
            'estado' => $merged['estado'] ?? 'borrador',
            'modo_hits' => $merged['modo_hits'] ?? 'manual',
            'configuracion_bicicleta_default_id' => !empty($merged['configuracion_bicicleta_default_id']) ? (int) $merged['configuracion_bicicleta_default_id'] : null,
            'auto_close_hit' => !empty($merged['auto_close_hit']) ? 1 : 0,
            'nodo_inicio_id' => (int) $merged['nodo_inicio_id'],
            'nodo_fin_id' => (int) $merged['nodo_fin_id'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return [
            'success' => true,
            'message' => 'Sesión actualizada correctamente.',
            'session' => $this->decorateSession($model->find($sessionId)),
        ];
    }

    public function updateStatus(int $sessionId, string $status): array
    {
        if (!in_array($status, ['borrador', 'abierta', 'cerrada'], true)) {
            return ['success' => false, 'message' => 'Estado de sesión inválido.'];
        }

        $model = new SesionEntrenamientoModel();
        $session = $model->find($sessionId);
        if (!$session) {
            return ['success' => false, 'message' => 'Sesión no encontrada.'];
        }

        if ($status === 'abierta') {
            if (empty($session['nodo_inicio_id']) || empty($session['nodo_fin_id'])) {
                return ['success' => false, 'message' => 'Configura nodo de inicio y fin antes de abrir la sesión.'];
            }
            $this->closeOtherOpenSessions($sessionId);
        }

        $model->update($sessionId, [
            'estado' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return [
            'success' => true,
            'message' => "Sesión {$status} correctamente.",
            'session' => $this->decorateSession($model->find($sessionId)),
        ];
    }

    public function timingPoints(): array
    {
        $points = (new PuntoControlModel())
            ->where('activo', 1)
            ->orderBy('orden', 'ASC')
            ->findAll();

        return ['success' => true, 'timing_points' => $points];
    }

    private function validatePayload(array $payload): array
    {
        foreach (['nombre', 'fecha', 'nodo_inicio_id', 'nodo_fin_id'] as $field) {
            if (empty($payload[$field])) {
                return ['success' => false, 'message' => "El campo {$field} es requerido."];
            }
        }

        if (!in_array($payload['modo_hits'] ?? 'manual', ['manual', 'automatico'], true)) {
            return ['success' => false, 'message' => 'modo_hits debe ser manual o automatico.'];
        }

        if (!in_array($payload['estado'] ?? 'borrador', ['borrador', 'abierta', 'cerrada'], true)) {
            return ['success' => false, 'message' => 'Estado de sesión inválido.'];
        }

        $points = new PuntoControlModel();
        $start = $points->find((int) $payload['nodo_inicio_id']);
        $end = $points->find((int) $payload['nodo_fin_id']);

        if (!$start || !$end) {
            return ['success' => false, 'message' => 'Nodo de inicio o fin inválido.'];
        }

        if ((int) $start['orden'] >= (int) $end['orden']) {
            return ['success' => false, 'message' => 'El nodo final debe estar después del nodo inicial.'];
        }

        if (($payload['modo_hits'] ?? 'manual') === 'automatico' && empty($payload['configuracion_bicicleta_default_id'])) {
            return ['success' => false, 'message' => 'En modo automático debes seleccionar una configuración de bicicleta por defecto.'];
        }

        return ['success' => true];
    }

    private function closeOtherOpenSessions(?int $exceptId = null): void
    {
        $model = new SesionEntrenamientoModel();
        $builder = $model->where('estado', 'abierta');
        if ($exceptId !== null) {
            $builder->where('id !=', $exceptId);
        }

        foreach ($builder->findAll() as $session) {
            $model->update((int) $session['id'], ['estado' => 'cerrada']);
        }
    }

    private function decorateSession(array $session): array
    {
        $points = new PuntoControlModel();
        $start = !empty($session['nodo_inicio_id']) ? $points->find((int) $session['nodo_inicio_id']) : null;
        $end = !empty($session['nodo_fin_id']) ? $points->find((int) $session['nodo_fin_id']) : null;

        $session['start_node'] = $start ? ['id' => (int) $start['id'], 'codigo' => $start['codigo'], 'nombre' => $start['nombre']] : null;
        $session['end_node'] = $end ? ['id' => (int) $end['id'], 'codigo' => $end['codigo'], 'nombre' => $end['nombre']] : null;

        return $session;
    }
}
