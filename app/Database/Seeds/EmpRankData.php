<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmpRankData extends Seeder
{
    public function run()
    {
        $data = [
            [
                "id" => "0ec0cfe2-3498-4e0c-b64f-033115f1e116",
                "code" => "ERK-0001",
                "name" => "Helper",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:04:30+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:04:30+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "4eec6a5f-2902-4978-b33a-e6332acb37ac",
                "code" => "ERK-0002",
                "name" => "Operator",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:04:35+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:04:35+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "3c13a5ed-ebe6-4fd0-a5e0-6df3c469c368",
                "code" => "ERK-0003",
                "name" => "Staff",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:04:40+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:04:40+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "7c4a8bd4-d922-4e1e-9f36-b03ba70603ec",
                "code" => "ERK-0004",
                "name" => "Technician",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:04:45+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:04:45+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "f0ace51e-c3f4-48ee-830d-43df41a2ba06",
                "code" => "ERK-0005",
                "name" => "Engineer",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:04:51+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:04:51+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "70b210db-7c24-4c03-9229-e6bc243f0d30",
                "code" => "ERK-0006",
                "name" => "Leader",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:04:56+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:04:56+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "4dd17510-d0aa-45dd-84bb-c6e29b24a85a",
                "code" => "ERK-0007",
                "name" => "Supervisor",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:05:01+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:05:01+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "4bd48de5-dbea-432d-a4bd-294189619e10",
                "code" => "ERK-0008",
                "name" => "Manager",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:05:08+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:05:08+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "5c6e3c01-b22c-48ca-962e-46a361de3112",
                "code" => "ERK-0009",
                "name" => "General Manager",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:05:14+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:05:14+07",
                "updated_by" => null,
                "deleted_at" => null
            ]
        ];

        $this->db->table('m_employee_rank')->insertBatch($data);
    }
}
