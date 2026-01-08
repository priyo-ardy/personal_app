<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DepartmentData extends Seeder
{
    public function run()
    {
        $data = [
            [
                "id" => "bc37c241-955c-4c8b-a7d9-36f65f2b203b",
                "code" => "DPT-0001",
                "name" => "BOD",
                "description" => "经理室 GMs Office",
                "effective_date" => "2020-01-01",
                "created_at" => "2026-01-08 16:14:01+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:15:35+07",
                "updated_by" => "0092",
                "deleted_at" => null
            ],
            [
                "id" => "70b198d1-8c23-458e-b106-f9e9bad7eebf",
                "code" => "DPT-0002",
                "name" => "HRGA",
                "description" => "人事部 HR Dept.",
                "effective_date" => "2020-01-01",
                "created_at" => "2026-01-08 16:14:06+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:15:42+07",
                "updated_by" => "0092",
                "deleted_at" => null
            ],
            [
                "id" => "edd72a9e-2349-4040-b9b1-4aa6897699d4",
                "code" => "DPT-0003",
                "name" => "Manufacturing",
                "description" => "制造部 Manufacturing Dept.",
                "effective_date" => "2020-01-01",
                "created_at" => "2026-01-08 16:14:11+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:15:50+07",
                "updated_by" => "0092",
                "deleted_at" => null
            ],
            [
                "id" => "a08c803f-d22d-429f-976a-aa6f4b6dd85e",
                "code" => "DPT-0004",
                "name" => "Finance & Accounting",
                "description" => "财务部 Finance Dept.",
                "effective_date" => "2020-01-01",
                "created_at" => "2026-01-08 16:14:16+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:15:56+07",
                "updated_by" => "0092",
                "deleted_at" => null
            ],
            [
                "id" => "f8df2fd7-1bd9-410d-8848-214fda29c7bb",
                "code" => "DPT-0005",
                "name" => "Quality Assurance",
                "description" => "质保部 Quality Dept",
                "effective_date" => "2020-01-01",
                "created_at" => "2026-01-08 16:14:21+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:16:05+07",
                "updated_by" => "0092",
                "deleted_at" => null
            ],
            [
                "id" => "0056a8db-a632-4289-8200-fd47a50b3076",
                "code" => "DPT-0006",
                "name" => "Research & Development",
                "description" => "开发部 R&D Dept.",
                "effective_date" => "2020-01-01",
                "created_at" => "2026-01-08 16:14:27+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:16:11+07",
                "updated_by" => "0092",
                "deleted_at" => null
            ],
            [
                "id" => "24215ee0-9c9c-4c04-8fa1-3d5b4b726252",
                "code" => "DPT-0007",
                "name" => "Quality Management System",
                "description" => "",
                "effective_date" => "2020-01-01",
                "created_at" => "2026-01-08 16:14:34+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:14:34+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "c32a3031-b567-4644-b062-4f5be998dbdc",
                "code" => "DPT-0008",
                "name" => "Logistic",
                "description" => "物流部 Logistic Dept.",
                "effective_date" => "2020-01-01",
                "created_at" => "2026-01-08 16:14:39+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:16:18+07",
                "updated_by" => "0092",
                "deleted_at" => null
            ],
            [
                "id" => "a9d32223-ee13-4fda-9ff8-6be7a2fbadc7",
                "code" => "DPT-0009",
                "name" => "Business & Sales",
                "description" => "商务部 Business Dept.",
                "effective_date" => "2020-01-01",
                "created_at" => "2026-01-08 16:14:45+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:16:27+07",
                "updated_by" => "0092",
                "deleted_at" => null
            ],
            [
                "id" => "2478edec-af5c-45bc-8310-a53cbd46fb30",
                "code" => "DPT-0010",
                "name" => "Purchasing",
                "description" => "",
                "effective_date" => "2020-01-01",
                "created_at" => "2026-01-08 16:15:05+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:15:05+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "dbd2fcb6-1fa7-42ac-acef-b68783717cc6",
                "code" => "DPT-0011",
                "name" => "System Operation",
                "description" => "",
                "effective_date" => "2020-01-01",
                "created_at" => "2026-01-08 16:15:12+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:15:12+07",
                "updated_by" => null,
                "deleted_at" => null
            ]
        ];

        $this->db->table('m_department')->insertBatch($data);
    }
}
