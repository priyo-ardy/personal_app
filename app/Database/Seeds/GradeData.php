<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GradeData extends Seeder
{
    public function run()
    {
        $data = [
            [
                "id" => "413f2ee3-24f8-43d8-b464-70f71980c3c9",
                "code" => "EGR-0001",
                "name" => "Non Skill Worker",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 15:57:24+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 15:57:24+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "751341cf-4260-414f-a226-bcc6c4464479",
                "code" => "EGR-0002",
                "name" => "Basic Skill Worker",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 15:57:30+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 15:57:30+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "97f5a22f-6423-4d54-ad23-cff766707d6b",
                "code" => "EGR-0003",
                "name" => "Skilled Worker",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 15:57:37+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 15:57:37+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "0a53100b-54ab-4663-b599-740fb5b305a9",
                "code" => "EGR-0004",
                "name" => "Supervisor / Specialist",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 15:57:42+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 15:57:42+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "c10e517f-2798-499f-a6de-edf1944d8090",
                "code" => "EGR-0005",
                "name" => "Manager",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 15:57:48+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 15:57:48+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "570b9c81-65b0-4fca-b38d-0421c78096a5",
                "code" => "EGR-0006",
                "name" => "General Manager",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 15:57:54+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 15:57:54+07",
                "updated_by" => null,
                "deleted_at" => null
            ]
        ];

        $this->db->table('m_grade')->insertBatch($data);
    }
}
