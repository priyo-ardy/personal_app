<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SupplierTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
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
            'address' => [
                'type' => "TEXT",
                'null' => true
            ],
            'phone' => [
                'type' => "TEXT",
                'null' => true
            ],
            'phone_hash' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true
            ],
            'email' => [
                'type' => "TEXT",
                'null' => true
            ],
            'email_hash' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true
            ],
            'contact_person' => [
                'type' => "VARCHAR",
                'constraint' => 150,
                'null' => true
            ],
            'contact_person_email' => [
                'type' => "TEXT",
                'null' => true
            ],
            'contact_person_email_hash' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true
            ],
            'contact_person_phone' => [
                'type' => "TEXT",
                'null' => true
            ],
            'contact_person_phone_hash' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true
            ],
            'npwp_no' => [
                'type' => "TEXT",
                'null' => true
            ],
            'npwp_hash' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true
            ],
            'bank_name' => [
                'type' => "VARCHAR",
                'constraint' => 150,
                'null' => true
            ],
            'bank_account_no' => [
                'type' => "TEXT",
                'null' => true
            ],
            'bank_account_no_hash' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true
            ],
            'bank_account_name' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true
            ],
            'description' => [
                'type' => "TEXT",
                'null' => true
            ],
            'created_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true
            ],
            'created_by' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true
            ],
            'updated_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true
            ],
            'updated_by' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true
            ]
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->addKey('code', false, true);
        $this->forge->addKey('name');

        $this->forge->createTable('m_supplier', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_supplier', true);
    }
}
