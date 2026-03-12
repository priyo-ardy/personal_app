<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MasterShiftTable extends Migration
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
            'working_day' => [
                'type' => 'INT',
                'null' => false
            ],
            'early_in' => [
                'type' => 'TIME',
                'null' => false,
                'default' => '00:00:00'
            ],
            'std_in' => [
                'type' => 'TIME',
                'null' => false,
                'default' => '00:00:00'
            ],
            'late_in' => [
                'type' => 'TIME',
                'null' => false,
                'default' => '00:00:00'
            ],
            'early_out' => [
                'type' => 'TIME',
                'null' => false,
                'default' => '00:00:00'
            ],
            'std_out' => [
                'type' => 'TIME',
                'null' => false,
                'default' => '00:00:00'
            ],
            'late_out' => [
                'type' => 'TIME',
                'null' => false,
                'default' => '00:00:00'
            ],
            'break' => [
                'type' => 'INT',
                'null' => false,
                'default' => '0'
            ],
            'overday' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => false
            ],
            'working_hour_type' => [
                'type' => 'CHAR',
                'constraint' => 3,
                'null' => false
            ],
            'working_hour' => [
                'type' => 'NUMERIC',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0
            ],
            'min_overtime' => [
                'type' => 'INT',
                'null' => false,
                'default' => 0
            ],
            'auto_overtime' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => false
            ],
            'default_overtime' => [
                'type' => 'UUID',
                'null' => false
            ],
            'overtime_type' => [
                'type' => 'VARCHAR',
                'constraint' => 1,
                'null' => true
            ],
            'overtime_in' => [
                'type' => 'TIME',
                'null' => false,
                'default' => '00:00:00'
            ],
            'overtime_out' => [
                'type' => 'TIME',
                'null' => false,
                'default' => '00:00:00'
            ],
            'overtime_break' => [
                'type' => 'INT',
                'null' => false,
                'default' => '0'
            ],
            'overtime_rate' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0
            ],
            'overtime_index' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0
            ],
            'x15' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0
            ],
            'x20' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0
            ],
            'x30' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0
            ],
            'x40' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0
            ],
            'default_absence_status' => [
                'type' => 'UUID',
                'null' => false
            ],
            'remark' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'effective_date' => [
                'type' => 'DATE',
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
        $this->forge->addKey('code');

        $this->forge->addForeignKey('default_overtime', 'm_overtime', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('default_absence_status', 'm_absence_status', 'id', 'RESTRICT', 'RESTRICT');

        $this->forge->createTable('m_shift', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_shift', true);
    }
}
