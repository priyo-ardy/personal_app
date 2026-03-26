<?php

namespace App\Repositories\DocumentFlow;

use  App\Models\AppSetup\DocumentFlow\DocumentFlowModel;
use App\Repositories\CrudRepository;
use CodeIgniter\Model;
use Config\Database;

class DocumentFlowRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new DocumentFlowModel();
    }

    public function getFlowByParent(string $parent_id)
    {
        return $this->model->where('parent_id', $parent_id)->orderBy('level', 'ASC')->findAll();
    }

    public function getParentData()
    {
        return $this->model->select('level')
            ->distinct()
            ->orderBy('level', 'ASC')
            ->get()
            ->getResultObject();
    }

    public function getDocumentList()
    {
        $db = Database::connect();

        $query = "SELECT vw_apqp_document.id, vw_apqp_document.document_name FROM vw_apqp_document WHERE id NOT IN (SELECT document_id FROM m_document_flow)";

        return $db->query($query)->getResultObject();
    }

    public function getFlowData()
    {
        $query = "
            SELECT 
                A.id, 
                A.parent_id, 
                B.document_name AS parent_name, 
                A.child_id,
                C.document_name AS child_name,
                A.is_mandatory,
                A.created_at,
                A.created_by,
                A.updated_at,
                A.updated_by,
                A.deleted_at
            FROM m_document_flow AS A 
                LEFT JOIN m_apqp_document AS B ON A.parent_id = B.id
                LEFT JOIN m_apqp_document AS C ON A.child_id = C.id
            ORDER BY A.id ASC
        ";

        return $this->model->query($query)->getResultObject();
    }
}
