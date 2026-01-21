<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwMaterial extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_material");
        $this->db->query("
            CREATE VIEW vw_material AS
            SELECT
                A.id,
                A.code,
                A.name,
                A.specification,
                A.category,
                B.name AS category_name,
                A.cust_part_no,
                A.cust_part_name,
                A.color,
                A.workshop,
                C.name AS workshop_name,
                A.property,
                A.uom,
                D.name AS uom_name,
                A.shift_capacity,
                A.spq,
                A.qty_per_bag,
                A.net_weight,
                A.gross_weight,
                A.cavity,
                A.image,
                A.description,
                A.created_at,
                A.created_by,
                A.updated_at,
                A.updated_by,
                A.deleted_at
            FROM m_material AS A
                LEFT JOIN m_material_category AS B ON A.category = B.id
                LEFT JOIN m_workshop AS C ON A.workshop = C.id
                LEFT JOIN m_uom AS D ON A.uom = D.id
            ORDER BY A.code ASC
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_material");
    }
}
