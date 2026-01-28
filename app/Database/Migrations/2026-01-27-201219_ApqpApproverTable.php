<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ApqpApproverTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false
            ],
            'id_apqp' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false
            ],
            'approver' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false
            ],
            'row_no' => [
                'type' => "INT",
                'null' => false,
                'default' => 1
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
        $this->forge->addKey('id_apqp');
        $this->forge->addKey('approver');
        $this->forge->addForeignKey('id_apqp', 'm_apqp_header', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approver',  'm_karyawan', 'id', 'RESTRICT', 'RESTRICT');

        $this->forge->createTable('m_apqp_approver');
    }

    public function down()
    {
        $this->forge->dropTable('m_apqp_approver', true);
    }
}
