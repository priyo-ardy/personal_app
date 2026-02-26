<?php

namespace App\Services\JobData;

use App\Database\Migrations\VwLatestJobData;
use App\Repositories\JobData\JobDataRepository;
use App\Validation\JobData\JobDataValidation;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;
use App\Traits\KalkulasiTrait;
use App\Models\AppSetup\JobData\LatestJobDataModel;

class JobDataService
{
    use KalkulasiTrait;
    protected $repository;
    protected $db;
    protected $validation;

    public function __construct(JobDataRepository $repo)
    {
        $this->repository = $repo;
        $this->db = Database::connect();
        $this->validation = Services::validation();
    }

    public function loadTable(array $postData)
    {
        try {
            $model = new LatestJobDataModel();
            $builder = $model->builder();

            $column_search = [
                'nik',
                'employee_name',
                'position_name',
                'dept_name',
                'section_name',
                'nbhx_position_name',
                'grade_name',
                'rank_name',
                'category_name',
                'nbhx_category_name',
                'report_to_position',
                'action_name',
                'reason_name',
                'work_relationship',
                'no_contract',
                'remark'
            ];

            $column_order = [
                '0' => 'nik',
                '1' => 'employee_name',
                '2' => 'position_name',
                '3' => 'dept_name',
                '4' => 'section_name',
                '5' => 'nbhx_position_name',
                '6' => 'grade_name',
                '7' => 'rank_name',
                '8' => 'category_name',
                '9' => 'nbhx_category_name',
                '10' => 'hitung_absen',
                '11' => 'hitung_lembur',
                '12' => 'report_to_position',
                '13' => 'action_name',
                '14' => 'reason_name',
                '15' => 'work_relationship',
                '16' => 'no_contract',
                '17' => 'durasi_kontrak',
                '18' => 'tipe_durasi',
                '19' => 'akhir_kontrak',
                '20' => 'remark',
                '21' => 'join_date',
                '22' => 'on_job_position'
            ];

            $default_order = array('nik' => 'asc');

            $dataTable = new DataTableRepository($builder, $column_search, $column_order, $default_order, [], 'deleted_at');

            $result = $dataTable->proses($postData);
            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->job_data_id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->job_data_id) . '`)">' . $row->nik . " - " . $row->employee_name .  '</a>',
                    $row->position_name,
                    $row->dept_name,
                    $row->section_name,
                    $row->nbhx_position_name,
                    $row->grade_name,
                    $row->rank_name,
                    $row->category_name,
                    $row->nbhx_category_name,
                    $row->hitung_absen,
                    $row->hitung_lembur,
                    $row->report_to_position,
                    $row->superior_name,
                    $row->tgl_masuk_kerja,
                    $this->date_duration($row->tgl_masuk_kerja, date('Y-m-d')),
                    $row->on_job_position,
                    $this->date_duration($row->on_job_position, date('Y-m-d')),
                    $row->action_name,
                    $row->reason_name,
                    $row->work_relationship,
                    $row->no_contract,
                    $row->durasi_kontrak,
                    $row->tipe_durasi,
                    $row->akhir_kontrak,
                    $row->remark
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[JobDataService::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function  saveData(array $postData)
    {
        try {
            $this->validation->setRules(JobDataValidation::save());

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[JobDataService::saveData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $remarkValue = "";
            if (!empty(trim($postData['data_remark']))) {
                $remarkValue = trim($postData['data_remark']);
            } elseif (trim($postData['data_relasi']) !== 'Tetap') {
                $remarkValue = $this->kalkulasi_durasi_kontrak(
                    trim($postData['effective_date']),
                    trim($postData['data_durasi']),
                    trim($postData['data_tipe_durasi'])
                );

                $remarkValue = $this->date_duration(trim($postData['effective_date']), $remarkValue);
            }

            $data = [
                'id' => uuid_v7(),
                'employee_id' => trim($postData['data_employee']),
                'action' => trim($postData['data_action']),
                'reason' => trim($postData['data_reason']),
                'position' => trim($postData['data_position']),
                'work_relationship' => trim($postData['data_relasi']),
                'effective_date' => trim($postData['effective_date']),
                'superior' => ($postData['data_superior']) ? trim($postData['data_superior']) : null,
                'no_contract' => trim($postData['data_contract']),
                'durasi_kontrak' => ($postData['data_relasi'] == 'Tetap') ? 0 : trim($postData['data_durasi']),
                'tipe_durasi' => ($postData['data_relasi'] == 'Tetap') ? 'Tahun' : trim($postData['data_tipe_durasi']),
                'akhir_kontrak' => ($postData['data_relasi'] == 'Tetap') ? '9999-12-31' : $this->kalkulasi_durasi_kontrak(trim($postData['effective_date']), trim($postData['data_durasi']), trim($postData['data_tipe_durasi'])),
                'remark' => $remarkValue,
                'status' => '1',
                'created_by' => session()->get('user_name'),
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[JobDataService::saveData] Failed to save data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to save data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[JobDataService::saveData] New job data has been registered to employee {employee} with id {id} from {ip}', ['employee' => $data['employee_id'], 'id' => $data['id'], 'ip' => $_SERVER['REMOTE_ADDR']]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[JobDataService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getJobDataInfo(string $id)
    {
        try {
            $result = $this->repository->find($id);

            if (!$result) {
                log_message('error', '[JobDataService::getJobDataInfo] Job data not found by {NIK} : {id}', ['NIK' => session()->get('user_name'), 'id' => $id]);
                throw new \Exception("Job data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'action' => $result->action,
                'reason' => $result->reason,
                'position' => $result->position,
                'effective_date' => $result->effective_date,
                'work_relationship' => $result->work_relationship,
                'no_contract' => $result->no_contract,
                'durasi_kontrak' => $result->durasi_kontrak,
                'akhir_kontrak' => $result->akhir_kontrak,
                'superior' => $result->superior,
                'remark' => $result->remark,
                'tipe_durasi' => $result->tipe_durasi,
                'position' => $result->position
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[JobDataService::getJobDataInfo] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get_data = $this->repository->find($id);

            if (!$get_data) {
                log_message('error', '[JobDataService::getData] Job data with id {id} not found', ['id' => $id]);
                throw new \Exception("Job data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'token' => enkripsi($get_data->id),
                'employee_token' => $get_data->employee_id,
                'position' => $get_data->position,
                'action' => $get_data->action,
                'reason' => $get_data->reason,
                'work_relationship' => $get_data->work_relationship,
                'effective_date' => $get_data->effective_date,
                'superior' => $get_data->superior,
                'no_contract' => $get_data->no_contract,
                'durasi_kontrak' => $get_data->durasi_kontrak,
                'tipe_durasi' => $get_data->tipe_durasi,
                'akhir_kontrak' => $get_data->akhir_kontrak,
                'remark' => $get_data->remark
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[JobDataService::getData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(JobDataValidation::update());

            if ($this->validation->run($postData) == false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[JobDataService::updateData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $remarkValue = "";
            if (!empty(trim($postData['data_remark']))) {
                $remarkValue = trim($postData['data_remark']);
            } elseif (trim($postData['data_relasi']) !== 'Tetap') {
                $remarkValue = $this->kalkulasi_durasi_kontrak(
                    trim($postData['effective_date']),
                    trim($postData['data_durasi']),
                    trim($postData['data_tipe_durasi'])
                );

                $remarkValue = $this->date_duration(trim($postData['effective_date']), $remarkValue);
            }

            $id = dekripsi(trim($postData['data_token']));

            $data = [
                'action' => trim($postData['data_action']),
                'reason' => trim($postData['data_reason']),
                'position' => trim($postData['data_position']),
                'work_relationship' => trim($postData['data_relasi']),
                'effective_date' => trim($postData['effective_date']),
                'superior' => ($postData['data_superior']) ? trim($postData['data_superior']) : null,
                'no_contract' => trim($postData['data_contract']),
                'durasi_kontrak' => ($postData['data_relasi'] == 'Tetap') ? 0 : trim($postData['data_durasi']),
                'tipe_durasi' => ($postData['data_relasi'] == 'Tetap') ? 'Tahun' : trim($postData['data_tipe_durasi']),
                'akhir_kontrak' => ($postData['data_relasi'] == 'Tetap') ? null : $this->kalkulasi_durasi_kontrak(trim($postData['effective_date']), trim($postData['data_durasi']), trim($postData['data_tipe_durasi'])),
                'remark' => $remarkValue,
                'status' => '1',
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[JobDataService::updateData] Failed to update job data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception("Failed to update job data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[JobDataService::updateData] Job data with id {id} has been updated by {NIK}', ['id' => $id, 'NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[JobDataService::updateData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function deleteData(string $id)
    {
        try {
            $this->db->transStart();
            $this->repository->delete($id);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[JobDataService::deleteData] Failed to delete job data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception("Failed to delete job data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[JobDataService::deleteData] Job data with id {id} has been deleted by {NIK}', ['id' => $id, 'NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[JobDataService::deleteData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function deleteMassData(array $id)
    {
        try {
            $this->db->transStart();
            $this->repository->massDelete($id);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[JobDataService::deleteMassData] Failed to delete job data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception("Failed to delete job data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }


            return true;
        } catch (\Exception $e) {
            log_message('error', '[JobDataService::deleteMassData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = 'latest_job_data_' . date('Y-m-d') . '.xlsx';

            $headers = [
                'NIK',
                'Employee Name',
                'Position',
                'Dept',
                'Dept',
                'NBHX Position',
                'Grade',
                'Rank',
                'Category',
                'NBHX Category',
                'Allow Finger',
                'Allow Overtime',
                'Report To Position',
                'Report To',
                'Join Date',
                'On Job Position',
                'Action',
                'Reason',
                'Work Relationship',
                'Contract No',
                'Contract Duration',
                'Duration Type',
                'End of Contract',
                'Remark',
            ];

            $dataCallBack = function ($offset, $limit) {
                $column = 'nik, employee_name, position_name, dept_name, section_name, nbhx_position_name, grade_name, rank_name, category_name, nbhx_category_name, hitung_absen, hitung_lembur, report_to_position, superior_name, tgl_masuk_kerja, on_job_position, action_name, reason_name, work_relationship, no_contract, durasi_kontrak, tipe_durasi, akhir_kontrak, remark';
                return $this->repository->chunkedData($offset, $limit, 'nik', $column);
            };

            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[JobDataService::exportData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
