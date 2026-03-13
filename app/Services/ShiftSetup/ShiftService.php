<?php

namespace App\Services\ShiftSetup;

use App\Repositories\ShiftSetup\ShiftRepository;
use App\Validation\ShiftSetup\ShiftValidation;
use App\Models\AppSetup\ShiftSetup\VwShiftModel;
use App\Repositories\DataTableRepository;
use Ramsey\Uuid\Uuid;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;
use App\Traits\KalkulasiTrait;

class ShiftService
{
    use KalkulasiTrait;
    protected $repository;
    protected $db;
    protected $validation;

    public function __construct(ShiftRepository $repo)
    {
        $this->repository = $repo;
        $this->db = Database::connect();
        $this->validation = Services::validation();
    }

    public function loadTable(array $postData)
    {
        try {
            $model = new VwShiftModel();
            $builder = $model->builder();

            $column_search = [];
            $column_order = [];
            $default_order = array('code' => 'asc');

            $data_table = new DataTableRepository($builder, $column_search, $column_order, $default_order, [], 'deleted_at');

            $result = $data_table->proses($postData);
            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    $row->working_day . " Working day",
                    date("H:i", strtotime($row->std_in)),
                    date("H:i", strtotime($row->std_out)),
                    $row->break . " Minutes",
                    $row->nama_overday,
                    $row->working_type_name,
                    $row->working_hour . " Hour(s)",
                    $row->min_overtime . " Hour",
                    $row->auto_overtime_name,
                    $row->overtime_name,
                    $row->overtime_type_name,
                    date("H:i", strtotime($row->overtime_in)),
                    date("H:i", strtotime($row->overtime_out)),
                    $row->overtime_rate,
                    $row->overtime_index,
                    $row->absence_name,
                    $row->remark,
                    $row->effective_date
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[ShiftService::loadTable] Error : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData, $rate)
    {
        try {
            $this->validation->setRules(ShiftValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[ShiftService::saveData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Validation failed : <br>" . $error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $jam_masuk = trim($postData['std_in']);
            $jam_pulang = trim($postData['std_out']);
            $istirahat = trim($postData['istirahat']);

            $jam_kerja = $this->kalkulasi_jam_kerja($jam_masuk, $jam_pulang, $istirahat);

            $overtime_type = trim($postData['overtime_type']);
            $lembur_mulai = trim($postData['lembur_mulai']);
            $lebur_selesai = trim($postData['lembur_selesai']);
            $working_hour_type = trim($postData['working_hour_type']);
            $min_overtime = $postData['min_overtime'];

            $auto_overtime = $this->kalkulasi_overtime($overtime_type, $lembur_mulai, $lebur_selesai, $working_hour_type, $min_overtime, $rate);

            // Penentuan early in, late in, early out, late out
            $time_in = $this->kalkulasi_early_late_in_out(trim($postData['std_in']));
            $late_out = $this->kalkulasi_early_late_in_out(trim($postData['std_out']));

            // Otomatis hitung lembur
            $auto_lembur = false;
            if (isset($postData['otomatis_hitung_lembur'])) {
                if ($postData['otomatis_hitung_lembur'] == 1) {
                    $auto_lembur = true;
                }
            }


            $data = [
                'id' => Uuid::uuid7()->toString(),
                'code' => $this->repository->generateCode('SFT-', 'code', 6),
                'name' => ucwords(trim($postData['data_name'])),
                'working_day' => trim($postData['working_day']),
                'early_in' => $time_in['early'],
                'std_in' => trim($postData['std_in']),
                'late_in' => $time_in['late'],
                'early_out' => $late_out['early'],
                'std_out' => trim($postData['std_out']),
                'late_out' => $late_out['late'],
                'break' => trim($postData['istirahat']),
                'overday' => $postData['data_overday'],
                'working_hour_type' => trim($postData['working_hour_type']),
                'working_hour' => $jam_kerja,
                'min_overtime' => ($postData['min_overtime']) ? trim($postData['min_overtime']) : 1,
                'auto_overtime' => $auto_lembur,
                'default_overtime' => trim($postData['overtime_type']),
                'overtime_type' => ($postData['auto_overtime_type']) ? trim($postData['auto_overtime_type']) : null,
                'overtime_in' => ($postData['lembur_mulai']) ? trim($postData['lembur_mulai']) : "00:00:00",
                'overtime_out' => ($postData['lembur_selesai']) ? trim($postData['lembur_selesai']) : "00:00:00",
                'overtime_break' => $auto_overtime['lama_istirahat'],
                'overtime_rate' => $auto_overtime['rate_lembur'],
                'overtime_index' => $auto_overtime['lama_lembur'],
                'x15' => $auto_overtime['x15'],
                'x20' => $auto_overtime['x20'],
                'x30' => $auto_overtime['x30'],
                'x40' => $auto_overtime['x40'],
                'default_absence_status' => trim($postData['absent_status']),
                'remark' => trim($postData['data_remark']),
                'effective_date' => trim($postData['effective_date']),
                'created_by' => session()->get('user_name'),
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[ShiftService::saveData] Failed to save shift data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->getLastQuery()]);
                throw new \Exception("Failed to save new shift data " . $this->db->error()['message'], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[ShiftService::saveData] Shift data was saved by {NIK}', ['NIK' => session()->get('user_name')]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[ShiftService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get = $this->repository->find($id);

            if (!$get) {
                log_message('error', '[ShiftService::getData] Shift data with id {id} not found', ['id' => $id]);
                throw new \Exception("Shift data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'token' => enkripsi($get->id),
                'code' => $get->code,
                'name' => $get->name,
                'working_day' => $get->working_day,
                'early_in' => $get->early_in,
                'std_in' => date("H:i", strtotime($get->std_in)),
                'late_in' => $get->late_in,
                'early_out' => $get->early_out,
                'std_out' => date("H:i", strtotime($get->std_out)),
                'late_out' => $get->late_out,
                'break' => $get->break,
                'overday' => $get->overday,
                'working_hour_type' => $get->working_hour_type,
                'working_hour' => $get->working_hour,
                'min_overtime' => $get->min_overtime,
                'auto_overtime' => $get->auto_overtime,
                'default_overtime' => $get->default_overtime,
                'overtime_type' => $get->overtime_type,
                'overtime_in' => date("H:i", strtotime($get->overtime_in)),
                'overtime_out' => date("H:i", strtotime($get->overtime_out)),
                'overtime_break' => $get->overtime_break,
                'overtime_rate' => $get->overtime_rate,
                'overtime_index' => $get->overtime_index,
                'x15' => $get->x15,
                'x20' => $get->x20,
                'x30' => $get->x30,
                'x40' => $get->x40,
                'default_absence_status' => $get->default_absence_status,
                'remark' => $get->remark,
                'effective_date' => $get->effective_date,
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[ShiftService::getData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData, $rate)
    {
        try {
            $id = dekripsi(trim($postData['data_token']));

            $get = $this->repository->find($id);

            if (!$get) {
                log_message('error', '[ShiftService::updateData] Shift data with id {id} not found', ['id' => $id]);
                throw new \Exception("Shift data not found", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $this->validation->setRules(ShiftValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[ShiftService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $jam_masuk = trim($postData['std_in']);
            $jam_pulang = trim($postData['std_out']);
            $istirahat = trim($postData['istirahat']);

            $jam_kerja = $this->kalkulasi_jam_kerja($jam_masuk, $jam_pulang, $istirahat);

            $overtime_type = trim($postData['overtime_type']);
            $lembur_mulai = trim($postData['lembur_mulai']);
            $lebur_selesai = trim($postData['lembur_selesai']);
            $working_hour_type = trim($postData['working_hour_type']);
            $min_overtime = $postData['min_overtime'];

            $auto_overtime = $this->kalkulasi_overtime($overtime_type, $lembur_mulai, $lebur_selesai, $working_hour_type, $min_overtime, $rate);

            // Penentuan early in, late in, early out, late out
            $time_in = $this->kalkulasi_early_late_in_out(trim($postData['std_in']));
            $late_out = $this->kalkulasi_early_late_in_out(trim($postData['std_out']));

            // Otomatis hitung lembur
            $auto_lembur = false;
            if (isset($postData['otomatis_hitung_lembur'])) {
                if ($postData['otomatis_hitung_lembur'] == 1) {
                    $auto_lembur = true;
                }
            }

            $data = [
                'name' => ucwords(trim($postData['data_name'])),
                'working_day' => trim($postData['working_day']),
                'early_in' => $time_in['early'],
                'std_in' => trim($postData['std_in']),
                'late_in' => $time_in['late'],
                'early_out' => $late_out['early'],
                'std_out' => trim($postData['std_out']),
                'late_out' => $late_out['late'],
                'break' => trim($postData['istirahat']),
                'overday' => $postData['data_overday'],
                'working_hour_type' => trim($postData['working_hour_type']),
                'working_hour' => $jam_kerja,
                'min_overtime' => ($postData['min_overtime']) ? trim($postData['min_overtime']) : 1,
                'auto_overtime' => $auto_lembur,
                'default_overtime' => trim($postData['overtime_type']),
                'overtime_type' => ($postData['auto_overtime_type']) ? trim($postData['auto_overtime_type']) : null,
                'overtime_in' => ($postData['lembur_mulai']) ? trim($postData['lembur_mulai']) : "00:00:00",
                'overtime_out' => ($postData['lembur_selesai']) ? trim($postData['lembur_selesai']) : "00:00:00",
                'overtime_break' => $auto_overtime['lama_istirahat'],
                'overtime_rate' => $auto_overtime['rate_lembur'],
                'overtime_index' => $auto_overtime['lama_lembur'],
                'x15' => $auto_overtime['x15'],
                'x20' => $auto_overtime['x20'],
                'x30' => $auto_overtime['x30'],
                'x40' => $auto_overtime['x40'],
                'default_absence_status' => trim($postData['absent_status']),
                'remark' => trim($postData['data_remark']),
                'effective_date' => trim($postData['effective_date']),
                'created_by' => session()->get('user_name'),
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[ShiftService::updateData] Transaction failed : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Transaction failed", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[ShiftService::updateData] Data updated successfully, data : {data}', ['data' => json_encode($data)]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[ShiftService::updateData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[ShiftService::deleteData] Transaction failed : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Transaction failed", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[ShiftService::deleteData] Data deleted successfully, id : {id}', ['id' => $id]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[ShiftService::deleteData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[ShiftService::massDelete] Transaction failed : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Transaction failed", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[ShiftService::massDelete] Data deleted successfully, id : {id}', ['id' => json_encode($id)]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[ShiftService::massDelete] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = "shift_list_" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Shift Code',
                'Shift Name',
                'Working Day',
                'Std. Clock ',
                'Std. Clock Out',
                'Break',
                'Overday',
                'Working Hour Type',
                'Standart Working Hour',
                'Min. Overtime',
                'Auto Overtime',
                'Default Overtime',
                'Overtime Type',
                'Overtime Start',
                'Overtime Finish',
                'Rate',
                'Index',
                'Default Absence Status',
                'Remark',
                'Effective Date'
            ];

            $dataCallBack = function ($offset, $limit) {
                $columns = [
                    'code',
                    'name',
                    'working_day',
                    'std_in',
                    'std_out',
                    'break',
                    'nama_overday',
                    'working_type_name',
                    'working_hour',
                    'min_overtime',
                    'auto_overtime_name',
                    'overtime_name',
                    'overtime_type_name',
                    'overtime_in',
                    'overtime_out',
                    'overtime_rate',
                    'overtime_index',
                    'absence_name',
                    'remark',
                    'effective_date'
                ];
                return $this->repository->chunkedData($offset, $limit, 'code', $columns);
            };

            return export_decrypted_data($file_name, $headers, ['email', 'phone', 'contact_person_email', 'contact_person_phone'], $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[ShiftService::exportData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function prevData(string $code)
    {
        try {
            $get_prev_data = $this->repository->prevData('code', $code);
            if (!$get_prev_data) {
                throw new \Exception("You are in the first data", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'token' => enkripsi($get_prev_data->id),
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[ShiftService::prevData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function nextData(string $data)
    {
        try {
            $get_next_data = $this->repository->nextData('code', $data);
            if (!$get_next_data) {
                throw new \Exception("You are in the last data", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'token' => enkripsi($get_next_data->id),
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[ShiftService::nextData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            $get = $this->repository->all('name', 'asc');

            return $get;
        } catch (\Exception $e) {
            log_message('error', '[ShiftService::getAllData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
