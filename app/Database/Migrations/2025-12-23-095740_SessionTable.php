<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SessionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => "VARCHAR",
                'constraint' => 128,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci',
            ],
            'ip_address' => [
                'type' => "VARCHAR",
                'constraint' => 45,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci',
            ],
            'timestamp' => [
                'type' => "TIMESTAMP",
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci',
            ],
            'data' => [
                'type' => "BLOB",
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci',
            ],
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->addKey('ip_address');
        $this->forge->addKey('timestamp');

        $this->forge->createTable('ci_sessions', true);
    }

    public function down()
    {
        $this->forge->dropTable('ci_sessions', true);
    }
}
