<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PeriodTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'UUID',
                'null' => false
            ],
            'tgl_awal' => [
                'type' => 'DATE',
                'null' => false
            ],
            'tgl_akhir' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'user_name' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null
            ],
            'created_by' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true
            ],
            'updated_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null
            ],
            'updated_by' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null
            ]
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->addKey('user_name');
        $this->forge->addKey('tgl_awal');
        $this->forge->addKey('tgl_akhir');

        $this->forge->createTable('m_period', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_period', true);
    }
}
