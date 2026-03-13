<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SpecialLeaveTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'UUID',
                'null' => false
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false
            ],
            'jml_hari' => [
                'type' => 'INT',
                'null' => false,
                'default' => 1
            ],
            'dokumen' => [
                'type' => 'TEXT',
                'null' => false
            ],
            'upload_dokumen' => [
                'type' => 'boolean',
                'null' => false,
                'default' => false
            ],
            'remark' => [
                'type' => 'TEXT',
                'null' => true
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
        $this->forge->addKey('code');
        $this->forge->addKey('name');

        $this->forge->createTable('m_cuti_khusus', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_cuti_khusus', true);
    }
}
