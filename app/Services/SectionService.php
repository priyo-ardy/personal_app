<?php

namespace App\Services;

use App\Models\AppSetup\Section\SectionModel;
use App\Repositories\DepartmentRepository;
use App\Repositories\SectionRepository;
use App\Validation\SectionValidation;
use App\Repositories\DataTableRepository;
use App\Models\AppSetup\Section\VwSectionModel;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class SectionService
{
    protected $db;
    protected $validasi;
    protected $dataTable;
    protected $deptRepo;
    protected $sectionRepo;

    public function __construct(SectionRepository $sectionRepo)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->deptRepo = new DepartmentRepository();
        $this->sectionRepo = $sectionRepo;
    }

    function loadData()
    {
        return $this->sectionRepo->all('code', 'asc');
    }

    function save(array $data)
    {
        try {
            $this->validasi->setRules(SectionValidation::$save);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', "[SectionService::save] Failed to verify new section data by {NIK} from {ip} : {err}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $error_to_string]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $section_code = $this->sectionRepo->getNewSectionCode();

            $data = [
                'id' => generate_uuid(),
                'code' => $section_code,
                'dept' => $data['data_dept'],
                'name' => ucwords(trim($data['data_name'])),
                'effective_date' => $data['effective_date'],
                'description' => $data['data_remark'],
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->sectionRepo->save($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', '[SectionService::save] Failed to save new section data by {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->error()]);
                throw new \Exception('Failed to save new section data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[SectionService::save] Successfully saved a new section data with new section code : {code} by {NIK} from {ip}', ['code' => $section_code, 'NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);

            return true;
        } catch (\Exception $e) {
            log_message('error', "[SectionService::save] Failed to save new section data by {NIK} from {ip} : {err}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    function get(string $id)
    {
        try {
            $get_data = $this->sectionRepo->getSectionData($id);

            if (!$get_data) {
                log_message('error', '[SectionService::get] Failed to get section data by {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception('Data not found', ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($get_data->id),
                'code' => $get_data->code,
                'dept' => $get_data->dept,
                'name' => $get_data->name,
                'effective_date' => $get_data->effective_date,
                'description' => $get_data->description,
            ];
        } catch (\Exception $e) {
            log_message('error', "[SectionService::get] Failed to get section data by {NIK} from {ip} : {err}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    function update(array $data)
    {
        try {
            $this->validasi->setRules(SectionValidation::$update);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', "[SectionService::update] Failed to update section data by {NIK} from {ip} : {err}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $error_to_string]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id_section = dekripsi($data['data_token']);

            $data = [
                'dept' => $data['data_dept'],
                'name' => ucwords(trim($data['data_name'])),
                'effective_date' => $data['effective_date'],
                'description' => $data['data_remark'],
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->sectionRepo->update($id_section, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', '[SectionService::update] Failed to update section data by NIK {NIK} from {ip}, DB error: {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->error()]);
                throw new \Exception('Failed to update section data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[SectionService::update] Successfully updated section data with section code: {code} by NIK {NIK} from {ip}', ['code' => $data['code'], 'NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);

            return true;
        } catch (\Exception $e) {
            log_message('error', "[SectionService::update] Failed to update section data by {NIK} from {ip} : {err}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    function delete(array $data)
    {
        try {
            $this->db->transStart();
            $this->sectionRepo->massDelete($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', '[SectionService::delete] Failed to delete section data by NIK {NIK} from {ip}, DB error: {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->error()]);
                throw new \Exception('Failed to delete section data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[SectionService::delete] Successfully deleted section data with section code: {code} by NIK {NIK} from {ip}', ['code' => $data['code'], 'NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);

            return true;
        } catch (\Exception $e) {
            log_message('error', "[SectionService::delete] Failed to delete section data by {NIK} from {ip} : {err}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    function loadTable($requestedData)
    {
        try {
            $model = new VwSectionModel();
            $builder = $model->builder();

            $column_search = ['code', 'dept_name', 'name', 'effective_date', 'description'];
            $column_order = [
                '0' => 'code',
                '1' => 'dept_name',
                '2' => 'name',
                '3' => 'effective_date',
                '4' => 'description'
            ];

            $defaultOrder = [
                'code' => 'asc'
            ];

            $dataTable = new DataTableRepository($builder, $column_search, $column_order, $defaultOrder, [], 'deleted_at');

            $result = $dataTable->proses($requestedData);

            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->dept_name,
                    $row->name,
                    date("l, d F Y", strtotime($row->effective_date)),
                    $row->description
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', "[SectionService::loadTable] Failed to load section data by {NIK} from {ip} : {err}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    function exportData()
    {
        try {
            $fileName = "section_list" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Section Code',
                'Section Name',
                'Department Name',
                'Effective Date',
                'Remark'
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, name, dept_name, effective_date, description';
                return $this->sectionRepo->chunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($fileName, $headers, $dataCallback);
        } catch (\Exception $e) {
            log_message('error', '[SectionService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getListByDept(string $dept)
    {
        try {
            $get_data = $this->sectionRepo->getSectionDataByDept($dept);

            if (!$get_data) {
                throw new \Exception('Data not found', ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [];

            foreach ($get_data as $row) {
                $data[] = [
                    'token' => $row->id,
                    'code' => $row->code,
                    'name' => $row->name,
                ];
            }

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[SectionService::getListByDept] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
