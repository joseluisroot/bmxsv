<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DispositivosTiempoSeeder extends Seeder
{
    public function run()
    {
        $puntos = $this->db
            ->table('puntos_control')
            ->get()
            ->getResultArray();

        $map = [];

        foreach ($puntos as $punto) {
            $map[$punto['codigo']] = $punto['id'];
        }

        $data = [
            [
                'codigo_dispositivo' => 'ESP32-GATE',
                'punto_control_id'   => $map['TP01'],
                'tipo_dispositivo'   => 'ESP32',
                'tipo_sensor'        => 'START_GATE',
                'activo'             => 1,
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
            [
                'codigo_dispositivo' => 'ESP32-RAMPA',
                'punto_control_id'   => $map['TP02'],
                'tipo_dispositivo'   => 'ESP32',
                'tipo_sensor'        => 'PHOTOELECTRIC',
                'activo'             => 1,
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
            [
                'codigo_dispositivo' => 'ESP32-CURVA1',
                'punto_control_id'   => $map['TP03'],
                'tipo_dispositivo'   => 'ESP32',
                'tipo_sensor'        => 'PHOTOELECTRIC',
                'activo'             => 1,
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
            [
                'codigo_dispositivo' => 'ESP32-CURVA2',
                'punto_control_id'   => $map['TP04'],
                'tipo_dispositivo'   => 'ESP32',
                'tipo_sensor'        => 'PHOTOELECTRIC',
                'activo'             => 1,
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
            [
                'codigo_dispositivo' => 'ESP32-CURVA3',
                'punto_control_id'   => $map['TP05'],
                'tipo_dispositivo'   => 'ESP32',
                'tipo_sensor'        => 'PHOTOELECTRIC',
                'activo'             => 1,
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
            [
                'codigo_dispositivo' => 'ESP32-META',
                'punto_control_id'   => $map['TP06'],
                'tipo_dispositivo'   => 'ESP32',
                'tipo_sensor'        => 'PHOTOELECTRIC',
                'activo'             => 1,
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ]
        ];

        $this->db->table('dispositivos_tiempo')->insertBatch($data);
    }
}
