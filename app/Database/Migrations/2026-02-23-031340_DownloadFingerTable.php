<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DownloadFingerTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'UUID',
                'null' => false
            ],
            'nik' => [
                'type' => 'CHAR',
                'constraint' => '5',
                'null' => false,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'type' => [
                'type' => 'CHAR',
                'constraint' => '1',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null
            ],
            'updated_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null
            ]
        ]);
    }

    public function down()
    {
        //
    }
}
