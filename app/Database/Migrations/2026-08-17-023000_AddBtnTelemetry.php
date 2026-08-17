<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBtnTelemetry extends Migration
{
    public function up()
    {
        $this->forge->addColumn('dispositivos_tiempo', [
            'hardware_version' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'ntp_server' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'timezone' => ['type' => 'VARCHAR', 'constraint' => 64, 'default' => 'America/El_Salvador'],
            'config_status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'unconfigured'],
            'api_status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'unknown'],
            'aat_radio_status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'unknown'],
            'lf_status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'unknown'],
            'pending_events' => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'uptime_seconds' => ['type' => 'BIGINT', 'unsigned' => true, 'default' => 0],
            'last_event_at' => ['type' => 'DATETIME', 'null' => true],
            'last_config_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
    }

    public function down()
    {
        foreach (['hardware_version','ntp_server','timezone','config_status','api_status','aat_radio_status','lf_status','pending_events','uptime_seconds','last_event_at','last_config_at'] as $column) {
            $this->forge->dropColumn('dispositivos_tiempo', $column);
        }
    }
}
