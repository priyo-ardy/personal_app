<?php

namespace App\Repositories;

use App\Interfaces\SectionInterface;
use App\Models\AppSetup\Section\SectionModel;
use App\Models\AppSetup\Section\VwSectionModel;
use App\Repositories\CrudRepository;
use CodeIgniter\Model;

class SectionRepository extends CrudRepository implements SectionInterface
{
    protected $model;
    protected $viewModel;
    protected $sectionRepo;

    public function __construct()
    {
        $this->model = new SectionModel();
        $this->viewModel = new VwSectionModel();
    }

    public function getNewSectionCode()
    {
        return $this->generateCode('SCT-', 'code', 4);
    }

    public function save(array $data)
    {
        return $this->create($data);
    }

    public function getSectionData(string $id_section)
    {
        return $this->find($id_section);
    }

    public function updateData($id, $data)
    {
        return $this->update($id, $data);
    }

    public function massDelete($section_data)
    {
        return $this->model->update($section_data, ['deleted_at' => date('Y-m-d H:i:sP')]);
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        // $view = new VwSectionModel();
        // return $this->getChunkedData($offset, $limit, $order, $column);
        return $this->viewModel->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }


    function getSectionDataByDept($dept)
    {
        return $this->model->where('dept', $dept)
            ->where('effective_date <=', date("Y-m-d"))
            ->orderBy('code', 'ASC')
            ->findAll();
    }
}
