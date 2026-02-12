<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EquipmentTypeData extends Seeder
{
    public function run()
    {
        $data = [
            [
                "id" => "ffa91ac5-2ed1-44f4-8d04-a692d2b6c7fd",
                "code" => "EQT-0001",
                "name" => "Machine Equipment",
                "description" => "",
                "created_at" => "2026-02-12 10:20:46+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:20:46+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "227d28a3-a986-44bc-98e0-729c3463f4e2",
                "code" => "EQT-0002",
                "name" => "Transportation Equipment",
                "description" => "",
                "created_at" => "2026-02-12 10:20:51+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:20:51+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "a44579ba-d733-4264-9322-7592af29632c",
                "code" => "EQT-0003",
                "name" => "Final Inspection",
                "description" => "",
                "created_at" => "2026-02-12 10:20:55+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:20:55+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "f9882017-5cbf-4c71-b95c-a8fe9ed43f0b",
                "code" => "EQT-0004",
                "name" => "Laboratorium Equipment",
                "description" => "",
                "created_at" => "2026-02-12 10:21:02+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:21:02+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "79912353-c494-45df-9ea8-ff9c3634b30e",
                "code" => "EQT-0005",
                "name" => "Electronic Equipment",
                "description" => "",
                "created_at" => "2026-02-12 10:21:08+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:21:08+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "b49631ba-7253-4295-910d-390f77437256",
                "code" => "EQT-0006",
                "name" => "Other Equipment",
                "description" => "",
                "created_at" => "2026-02-12 10:21:20+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:21:20+07",
                "updated_by" => "0092",
                "deleted_at" => null
            ]
        ];

        $this->db->table('m_equipment_type')->insertBatch($data);
    }
}
