<?php

namespace App\Services\SchedulleSetup;

use App\Repositories\SchedulleSetup\SchedulleRepository;
use App\Validation\SchedulleSetup\SchedulleValidation;
use App\Repositories\DataTableRepository;
use App\Models\AppSetup\SchedulleSetup\SchedulleModel;
use App\Services\ShiftSetup\ShiftService;
use App\Repositories\ShiftSetup\ShiftRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Config\Services;
use Config\Database;

class SchedulleService
{
    protected $repository;
    protected $db;
    protected $validation;
    protected $shift;

    public function __construct(SchedulleRepository $repository)
    {
        $this->repository = $repository;
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->shift = new ShiftService(new ShiftRepository());
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(SchedulleValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[SchedulleService::saveData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $shifts = $postData['shift'];
            $shift = [];

            for ($i = 0; $i < count($shifts); $i++) {
                $shift[] = $shifts[$i];
            }

            $data = [
                'id' => Uuid::uuid7()->toString(),
                'code' => $this->repository->generateCode('SH-', 'code', 6),
                'name' => trim($postData['data_name']),
                'total_day' => count($shift),
                'effective_date' => trim($postData['effective_date']),
                'remark' => trim($postData['data_remark']),
                'shift' => json_encode($shift),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[SchedulleService::saveData] Failed to save data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to save data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[SchedulleService::saveData] Data saved successfully, id : {id}', ['id' => $data['id']]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[SchedulleService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get = $this->repository->find($id);

            if (!$get) {
                log_message('error', '[SchedulleService::getData] Schedulle data not found with id {id} from {ip}', ['id' => $id, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Schedulle data not found', ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'token' => enkripsi($get->id),
                'code' => $get->code,
                'name' => $get->name,
                'total_day' => $get->total_day,
                'shift' => $get->shift,
                'effective_date' => $get->effective_date,
                'remark' => $get->remark
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[SchedulleService::getData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getShiftData($id)
    {
        try {
            $get_schedulle = $this->repository->find($id);

            if (!$get_schedulle) {
                log_message('error', '[SchedulleService::getShiftData] Schedulle data not found with id {id} from {ip}', ['id' => $id, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Schedulle data not found', ResponseInterface::HTTP_NOT_FOUND);
            }

            $shift_list = [];

            $shifts = json_decode($get_schedulle->shift, true);

            foreach ($shifts as $row) {
                $shift = $this->shift->getData($row);

                $shift_list[] = [
                    'shift_token' => dekripsi($shift['token']),
                    'shift_name' => $shift['name'],
                ];
            }


            return $shift_list;
        } catch (\Exception $e) {
            log_message('error', '[SchedulleService::getShiftData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function loadTable(array $postData)
    {
        try {
            $model = new SchedulleModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'total_day', 'effective_date', 'remark'];
            $column_order = ['code', 'name', 'total_day', 'effective_date', 'remark'];
            $default_order = array('code' => 'asc');

            $dataTable = new DataTableRepository($builder, $column_search, $column_order, $default_order, [], 'deleted_at');

            $result = $dataTable->proses($postData);
            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    $row->total_day . " Days",
                    $row->effective_date,
                    '<a href="#" class="btn btn-sm rounded-0 btn-primary col-12" onclick="showShift(`' . enkripsi($row->id) . '`)">Show Shift</a>',
                    $row->remark
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[SchedulleService::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(SchedulleValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[SchedulleService::updateData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $token = trim($postData['data_token']);
            $id = dekripsi($token);

            $shifts = $postData['shift'];
            $shift = [];

            for ($i = 0; $i < count($shifts); $i++) {
                $shift[] = $shifts[$i];
            }

            $data = [
                'name' => trim($postData['data_name']),
                'total_day' => count($shift),
                'effective_date' => trim($postData['effective_date']),
                'remark' => trim($postData['data_remark']),
                'shift' => json_encode($shift),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[SchedulleService::updateData] Failed to update data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to update data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[SchedulleService::updateData] Successfully update data with id {id} from {ip}', ['id' => $id, 'ip' => $_SERVER['REMOTE_ADDR']]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[SchedulleService::updateData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[SchedulleService::deleteData] Failed to delete data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to delete data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[SchedulleService::deleteData] Successfully delete data with id {id} from {ip}', ['id' => $id, 'ip' => $_SERVER['REMOTE_ADDR']]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[SchedulleService::deleteData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function prevData($code)
    {
        try {
            $data = $this->repository->prevData('code', $code);

            if (!$data) {
                throw new \Exception('You are at the first data', ResponseInterface::HTTP_NOT_FOUND);
            }

            return enkripsi($data['id']);
        } catch (\Exception $e) {
            log_message('error', '[SchedulleService::prevData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function nextData($code)
    {
        try {
            $get = $this->repository->nextData('code', $code);

            if (!$get) {
                throw new \Exception('You are at the last data', ResponseInterface::HTTP_NOT_FOUND);
            }

            return enkripsi($get->id);
        } catch (\Exception $e) {
            log_message('error', '[SchedulleService::nextData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function massDelete(array $id)
    {
        try {
            $this->db->transStart();
            $this->repository->massDelete($id);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[SchedulleService::massDelete] Failed to delete data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to delete data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[SchedulleService::massDelete] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = "schedule_list" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Code',
                'Name',
                'Total Day Period',
                'Shift',
                'Effective Date',
                'Remark'
            ];

            $dataCallBack = function ($limit, $offset) {
                $columns = ['code', 'name', 'total_day', 'shift', 'effective_date', 'remark'];

                return $this->repository->chunkedData($offset, $limit, 'code', $columns);
            };

            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[SchedulleService::exportData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
