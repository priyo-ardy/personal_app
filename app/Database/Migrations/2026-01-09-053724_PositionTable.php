<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PositionTable extends Migration
{
    public function up()
    {
        $this->forge->addField(
            [
                'id' => [
                    'type' => "VARCHAR",
                    'constraint' => 50,
                    'null' => false
                ],
                'code' => [
                    'type' => "VARCHAR",
                    'constraint' => 20,
                    'null' => false
                ],
                'name' => [
                    'type' => "VARCHAR",
                    'constraint' => 150,
                    'null' => false
                ],
                'description' => [
                    'type' => "TEXT",
                    'null' => true
                ],
                'nbhx_position' => [
                    'type' => "VARCHAR",
                    'constraint' => 50,
                    'null' => false
                ],
                'dept' => [
                    'type' => "VARCHAR",
                    'constraint' => 50,
                    'null' => false
                ],
                'section' => [
                    'type' => "VARCHAR",
                    'constraint' => 50,
                    'null' => false
                ],
                'report_to' => [
                    'type' => "VARCHAR",
                    'constraint' => 50,
                    'null' => false
                ],
                'grade' => [
                    'type' => "VARCHAR",
                    'constraint' => 50,
                    'null' => false
                ],
                'rank' => [
                    'type' => "VARCHAR",
                    'constraint' => 50,
                    'null' => false
                ],
                'emp_status' => [
                    'type' => "VARCHAR",
                    'constraint' => 10,
                ],
                'category' => [
                    'type' => "VARCHAR",
                    'constraint' => 50,
                    'null' => false
                ],
                'nbhx_category' => [
                    'type' => "VARCHAR",
                    'constraint' => 50,
                    'null' => false
                ],
                'effective_date' => [
                    'type' => "DATE",
                    'null' => false
                ],
                'hitung_absen' => [
                    'type' => "VARCHAR",
                    'constraint' => 1,
                    'null' => false
                ],
                'hitung_lembur' => [
                    'type' => "VARCHAR",
                    'constraint' => 1,
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
            ]
        );

        $this->forge->addKey('id', true, true);
        $this->forge->addKey('code', false, true);
        $this->forge->addKey('effective_date');
        $this->forge->addKey('nbhx_position');
        $this->forge->addKey('dept');
        $this->forge->addKey('section');
        $this->forge->addKey('grade');
        $this->forge->addKey('rank');
        $this->forge->addKey('category');
        $this->forge->addKey('nbhx_category');

        $this->forge->addForeignKey('nbhx_position', 'm_nbhx_position', 'id', '', 'RESTRICT', 'nbhx_position');
        $this->forge->addForeignKey('dept', 'm_department', 'id', '', 'RESTRICT', 'department');
        $this->forge->addForeignKey('section', 'm_section', 'id', '', 'RESTRICT', 'section');
        $this->forge->addForeignKey('grade', 'm_grade', 'id', '', 'RESTRICT', 'grade');
        $this->forge->addForeignKey('rank', 'm_employee_rank', 'id', '', 'RESTRICT', 'rank');
        $this->forge->addForeignKey('category', 'm_employee_category', 'id', '', 'RESTRICT', 'category');
        $this->forge->addForeignKey('nbhx_category', 'm_class_nbhx', 'id', '', 'RESTRICT', 'class_nbhx');

        $this->forge->createTable('m_position', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_position', true);
    }
}
