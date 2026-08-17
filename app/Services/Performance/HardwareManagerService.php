<?php

namespace App\Services\Performance;

use App\Models\AatAssignmentModel;
use App\Models\AatDeviceModel;
use App\Models\AtletaModel;
use App\Models\ChipIdentificacionModel;
use App\Models\DispositivoTiempoModel;
use App\Models\PuntoControlModel;
use Config\Database;

class HardwareManagerService
{
    public function listAats(): array
    {
        $db = Database::connect();
        $rows = $db->table('aat_devices a')
            ->select('a.*, owner.nombres owner_nombres, owner.apellidos owner_apellidos, aa.id assignment_id, aa.assignment_type, aa.starts_at, aa.ends_at, aa.sesion_entrenamiento_id, ath.id assigned_athlete_id, ath.nombres assigned_nombres, ath.apellidos assigned_apellidos')
            ->join('atletas owner', 'owner.id = a.owner_athlete_id', 'left')
            ->join('aat_assignments aa', 'aa.aat_device_id = a.id AND aa.active = 1', 'left')
            ->join('atletas ath', 'ath.id = aa.atleta_id', 'left')
            ->orderBy('a.uid', 'ASC')->get()->getResultArray();
        return ['success' => true, 'aats' => $rows];
    }

    public function createAat(array $payload): array
    {
        $uid = trim((string)($payload['uid'] ?? ''));
        if ($uid === '') return ['success' => false, 'message' => 'UID es requerido.'];
        $ownership = $payload['ownership_type'] ?? 'club';
        if (!in_array($ownership, ['club', 'athlete'], true)) return ['success' => false, 'message' => 'Tipo de propiedad inválido.'];
        if ($ownership === 'athlete' && empty($payload['owner_athlete_id'])) return ['success' => false, 'message' => 'Selecciona el atleta propietario.'];

        $model = new AatDeviceModel();
        if ($model->where('uid', $uid)->first()) return ['success' => false, 'message' => 'El UID ya existe.'];
        $now = date('Y-m-d H:i:s');
        $id = $model->insert([
            'uid' => $uid,
            'serial_number' => $payload['serial_number'] ?? null,
            'ownership_type' => $ownership,
            'owner_athlete_id' => !empty($payload['owner_athlete_id']) ? (int)$payload['owner_athlete_id'] : null,
            'status' => 'available',
            'firmware_version' => $payload['firmware_version'] ?? null,
            'notes' => $payload['notes'] ?? null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        return ['success' => (bool)$id, 'message' => $id ? 'AAT registrado correctamente.' : 'No se pudo registrar el AAT.', 'aat_id' => $id];
    }

    public function assignAat(int $aatId, array $payload): array
    {
        $device = (new AatDeviceModel())->find($aatId);
        if (!$device) return ['success' => false, 'message' => 'AAT no encontrado.'];
        if (in_array($device['status'], ['maintenance', 'retired'], true)) return ['success' => false, 'message' => 'El AAT no está disponible para asignación.'];
        $athleteId = (int)($payload['athlete_id'] ?? 0);
        if (!$athleteId || !(new AtletaModel())->find($athleteId)) return ['success' => false, 'message' => 'Atleta inválido.'];

        $assignments = new AatAssignmentModel();
        if ($assignments->where('aat_device_id', $aatId)->where('active', 1)->first()) return ['success' => false, 'message' => 'El AAT ya tiene una asignación activa.'];
        $type = $payload['assignment_type'] ?? 'loan';
        if (!in_array($type, ['permanent', 'loan', 'rental'], true)) return ['success' => false, 'message' => 'Tipo de asignación inválido.'];

        $now = date('Y-m-d H:i:s');
        $db = Database::connect();
        $db->transStart();
        $assignmentId = $assignments->insert([
            'aat_device_id' => $aatId,
            'atleta_id' => $athleteId,
            'sesion_entrenamiento_id' => !empty($payload['session_id']) ? (int)$payload['session_id'] : null,
            'assignment_type' => $type,
            'starts_at' => $payload['starts_at'] ?? $now,
            'ends_at' => $payload['ends_at'] ?? null,
            'active' => 1,
            'notes' => $payload['notes'] ?? null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        (new AatDeviceModel())->update($aatId, ['status' => $type === 'permanent' ? 'assigned' : 'loaned', 'updated_at' => $now]);
        $this->syncLegacyChipAssignment((string)$device['uid'], $athleteId, true);
        $db->transComplete();
        return ['success' => $db->transStatus(), 'message' => 'AAT asignado correctamente y habilitado para timing.', 'assignment_id' => $assignmentId];
    }

    public function returnAat(int $aatId): array
    {
        $deviceModel = new AatDeviceModel();
        $device = $deviceModel->find($aatId);
        if (!$device) return ['success' => false, 'message' => 'AAT no encontrado.'];
        $assignmentModel = new AatAssignmentModel();
        $assignment = $assignmentModel->where('aat_device_id', $aatId)->where('active', 1)->first();
        if (!$assignment) return ['success' => false, 'message' => 'El AAT no tiene una asignación activa.'];

        $now = date('Y-m-d H:i:s');
        $db = Database::connect();
        $db->transStart();
        $assignmentModel->update((int)$assignment['id'], ['active' => 0, 'returned_at' => $now, 'updated_at' => $now]);
        $deviceModel->update($aatId, ['status' => 'available', 'updated_at' => $now]);
        $this->syncLegacyChipAssignment((string)$device['uid'], (int)$assignment['atleta_id'], false);
        $db->transComplete();
        return ['success' => $db->transStatus(), 'message' => 'AAT devuelto, disponible y deshabilitado para el atleta anterior.'];
    }

    public function aatHistory(int $aatId): array
    {
        $db = Database::connect();
        $rows = $db->table('aat_assignments aa')
            ->select('aa.*, a.nombres, a.apellidos, s.nombre session_name')
            ->join('atletas a', 'a.id = aa.atleta_id')
            ->join('sesiones_entrenamiento s', 's.id = aa.sesion_entrenamiento_id', 'left')
            ->where('aa.aat_device_id', $aatId)->orderBy('aa.starts_at', 'DESC')->get()->getResultArray();
        return ['success' => true, 'history' => $rows];
    }

    public function resolveAthleteByAat(string $uid): ?array
    {
        $db = Database::connect();
        $row = $db->table('aat_devices d')
            ->select('d.id aat_id, d.uid, aa.atleta_id, a.nombres, a.apellidos')
            ->join('aat_assignments aa', 'aa.aat_device_id = d.id AND aa.active = 1')
            ->join('atletas a', 'a.id = aa.atleta_id')
            ->where('d.uid', $uid)->whereIn('d.status', ['assigned', 'loaned'])->get()->getRowArray();
        return $row ?: null;
    }

    public function listBtns(): array
    {
        $rows = (new DispositivoTiempoModel())
            ->select('dispositivos_tiempo.*, puntos_control.codigo punto_codigo, puntos_control.nombre punto_nombre')
            ->join('puntos_control', 'puntos_control.id = dispositivos_tiempo.punto_control_id')
            ->orderBy('puntos_control.orden', 'ASC')->findAll();
        return ['success' => true, 'devices' => $rows];
    }

    public function saveBtn(?int $id, array $payload): array
    {
        foreach (['codigo_dispositivo', 'punto_control_id'] as $field) if (empty($payload[$field])) return ['success' => false, 'message' => "{$field} es requerido."];
        if (!(new PuntoControlModel())->find((int)$payload['punto_control_id'])) return ['success' => false, 'message' => 'Punto de control inválido.'];
        $networkMode = $payload['network_mode'] ?? 'local';
        if (!in_array($networkMode, ['local', 'cloud', 'auto'], true)) return ['success' => false, 'message' => 'Modo de red inválido.'];

        $model = new DispositivoTiempoModel();
        $now = date('Y-m-d H:i:s');
        $data = [
            'codigo_dispositivo' => trim((string)$payload['codigo_dispositivo']),
            'punto_control_id' => (int)$payload['punto_control_id'],
            'tipo_dispositivo' => $payload['tipo_dispositivo'] ?? 'BTPS_BTN',
            'tipo_sensor' => $payload['tipo_sensor'] ?? 'AAT_LF_RF',
            'network_mode' => $networkMode,
            'endpoint_url' => $payload['endpoint_url'] ?? null,
            'ip_address' => $payload['ip_address'] ?? null,
            'firmware_version' => $payload['firmware_version'] ?? null,
            'hardware_version' => $payload['hardware_version'] ?? null,
            'ntp_server' => $payload['ntp_server'] ?? null,
            'timezone' => $payload['timezone'] ?? 'America/El_Salvador',
            'config_status' => $payload['config_status'] ?? 'configured',
            'last_config_at' => $now,
            'clock_status' => $payload['clock_status'] ?? 'unknown',
            'health_status' => $payload['health_status'] ?? 'unknown',
            'notes' => $payload['notes'] ?? null,
            'activo' => isset($payload['activo']) ? (int)(bool)$payload['activo'] : 1,
        ];

        if ($id) {
            if (!$model->find($id)) return ['success' => false, 'message' => 'BTN no encontrado.'];
            $model->update($id, $data);
        } else {
            if ($model->where('codigo_dispositivo', $data['codigo_dispositivo'])->first()) return ['success' => false, 'message' => 'El código de BTN ya existe.'];
            $id = (int)$model->insert($data);
        }
        return ['success' => true, 'message' => 'BTN guardado correctamente.', 'device_id' => $id];
    }

    public function btnConfiguration(string $deviceCode): array
    {
        $device = (new DispositivoTiempoModel())->where('codigo_dispositivo', $deviceCode)->where('activo', 1)->first();
        if (!$device) return ['success' => false, 'message' => 'BTN no encontrado o inactivo.'];
        return ['success' => true, 'configuration' => [
            'device_code' => $device['codigo_dispositivo'],
            'timing_point_id' => (int)$device['punto_control_id'],
            'network_mode' => $device['network_mode'] ?? 'local',
            'endpoint_url' => $device['endpoint_url'] ?? null,
            'ntp_server' => $device['ntp_server'] ?? null,
            'timezone' => $device['timezone'] ?? 'America/El_Salvador',
            'config_status' => $device['config_status'] ?? 'unconfigured',
        ]];
    }

    public function updateBtnTelemetryByCode(string $deviceCode, array $payload): array
    {
        $model = new DispositivoTiempoModel();
        $device = $model->where('codigo_dispositivo', $deviceCode)->first();
        if (!$device) return ['success' => false, 'message' => 'BTN no encontrado.'];
        $allowed = ['clock_status','last_sync_at','clock_offset_us','battery_mv','signal_dbm','health_status','api_status','aat_radio_status','lf_status','pending_events','uptime_seconds','last_event_at','ip_address','firmware_version','hardware_version'];
        $data = array_intersect_key($payload, array_flip($allowed));
        $data['ultima_conexion'] = date('Y-m-d H:i:s');
        if (!$data) return ['success' => false, 'message' => 'No hay telemetría válida para actualizar.'];
        $model->update((int)$device['id'], $data);
        return ['success' => true, 'message' => 'Telemetría BTN recibida.', 'device_id' => (int)$device['id']];
    }

    public function updateBtnHealth(int $id, array $payload): array
    {
        $model = new DispositivoTiempoModel();
        $device = $model->find($id);
        if (!$device) return ['success' => false, 'message' => 'BTN no encontrado.'];
        return $this->updateBtnTelemetryByCode((string)$device['codigo_dispositivo'], $payload);
    }

    private function syncLegacyChipAssignment(string $uid, int $athleteId, bool $active): void
    {
        $model = new ChipIdentificacionModel();
        $chip = $model->where('codigo_chip', $uid)->first();
        $now = date('Y-m-d H:i:s');
        $data = ['atleta_id' => $athleteId, 'tipo' => 'BTPS_AAT', 'activo' => $active ? 1 : 0, 'notas' => 'Sincronizado automáticamente por BTPS AAT Manager.', 'updated_at' => $now];
        if ($chip) { $model->update((int)$chip['id'], $data); return; }
        $data['codigo_chip'] = $uid;
        $data['created_at'] = $now;
        $model->insert($data);
    }
}
