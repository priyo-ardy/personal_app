<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ApqpDocumentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'UUID',
                'null' => false
            ],
            'apqp_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'baris' => [
                'type' => 'INT',
                'null' => false,
                'default' => 1
            ],
            'document_level' => [
                'type' => 'INT',
                'null' => false,
                'default' => 1
            ],
            'document_name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false
            ],
            'uploader' => [
                'type' => 'UUID',
                'null' => false
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
        $this->forge->addKey('apqp_id');
        $this->forge->addKey('document_name');
        $this->forge->addKey('uploader');

        $this->forge->addForeignKey('apqp_id', 'm_apqp_header', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('uploader', 'm_karyawan', 'id', 'RESTRICT', 'RESTRICT');

        $this->forge->createTable('m_apqp_document', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_apqp_document', true);
    }
}
