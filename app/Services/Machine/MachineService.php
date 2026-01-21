<?php

namespace App\Services\Machine;

use App\Repositories\Machine\MachineRepository;
use App\Validation\Machine\MachineValidation;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AppSetup\Machine\VwMachine;
use App\Repositories\DataTableRepository;
use App\Repositories\Workshop\WorkshopRepository;
use Config\Services;
use Config\Database;

class MachineService
{
    protected $db;
    protected $validasi;
    protected $repository;
    protected $workshopRepo;

    public function __construct(MachineRepository $repo)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->repository = $repo;
        $this->workshopRepo = new WorkshopRepository();
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new VwMachine();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'specification', 'workshop_name', 'brand', 'serial_no', 'tonnage_name', 'rate', 'mfg_date', 'purchase_date', 'description'];
            $column_order = [
                'code',
                'name',
                'specification',
                'workshop_name',
                'brand',
                'serial_no',
                'tonnage_name',
                'rate',
                'mfg_date',
                'purchase_date',
                'description'
            ];
            $defaultOrder = array('code' => 'asc');

            $data_table = new DataTableRepository($builder, $column_search, $column_order, $defaultOrder, [], 'deleted_at');

            $result = $data_table->proses($requestedData);

            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    $row->specification,
                    $row->workshop_name,
                    $row->brand,
                    $row->serial_no,
                    $row->tonnage_name,
                    $row->rate,
                    $row->mfg_date,
                    $row->purchase_date,
                    $row->description
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[MachineService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $data)
    {
        try {
            $this->validasi->setRules(MachineValidation::$save);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[MachineService::saveData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $workshop = $data['data_workshop'];
            $code = $data['data_code'];

            $get_workshop = $this->workshopRepo->find($workshop);
            $workshop_name = $get_workshop->name;

            $cek_kode = $this->repository->getCodeByWorkshop($workshop, $code);
            if ($cek_kode) {
                log_message('error', '[MachineService::saveData] Machine code {code} already exist in workshop {workshop}', ['code' => $code, 'workshop' => $workshop]);
                throw new \Exception("Machine code <strong>$code</strong> already exist in workshop <strong>$workshop_name</strong>", ResponseInterface::HTTP_CONFLICT);
            }

            $data = [
                'id' => generate_uuid(),
                'code' => strtoupper(trim($data['data_code'])),
                'name' => ucwords(trim($data['data_name'])),
                'specification' => trim($data['data_spesifikasi']),
                'workshop' => trim($data['data_workshop']),
                'brand' => trim($data['data_brand']),
                'serial_no' => trim($data['serial_no']),
                'tonnage' => ($data['data_tonnage'] ? trim($data['data_tonnage']) : null),
                'rate' => trim($data['data_rate']),
                'mfg_date' => ($data['mfg_date'] ? date('Y-m-d', strtotime($data['mfg_date'])) : null),
                'purchase_date' => ($data['purchase_date'] ? date('Y-m-d', strtotime($data['purchase_date'])) : null),
                'description' => trim($data['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                $php_errormsg = $this->db->error();
                log_message('error', '[MachineService::saveData] Failed to save new machine data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $php_errormsg]);
                throw new \Exception("Failed to save new machine data " . $this->db->error(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[MachineService::saveData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get_data = $this->repository->find($id);
            if (!$get_data) {
                log_message('error', '[MachineService::getData] Machine with id {id} not found', ['id' => $id]);
                throw new \Exception("Machine with id $id not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'token' => enkripsi($get_data->id),
                'code' => $get_data->code,
                'name' => $get_data->name,
                'specification' => $get_data->specification,
                'workshop' => $get_data->workshop,
                'brand' => $get_data->brand,
                'serial_no' => $get_data->serial_no,
                'tonnage' => $get_data->tonnage,
                'rate' => $get_data->rate,
                'mfg_date' => $get_data->mfg_date,
                'purchase_date' => $get_data->purchase_date,
                'description' => $get_data->description
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[MachineService::getData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $data)
    {
        try {
            $this->validasi->setRules(MachineValidation::$update);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[MachineService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi($data['data_token']);

            $get_old_data = $this->repository->find($id);
            if (!$get_old_data) {
                log_message('error', '[MachineService::updateData] Machine with id {id} not found', ['id' => $id]);
                throw new \Exception("Machine with id $id not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            $old_code = $get_old_data->code;
            $old_workshop = $get_old_data->workshop;

            $workshop = $this->workshopRepo->find($data['data_workshop']);

            $nama_workshop = $workshop->name;

            if ($data['data_workshop'] !== $old_workshop || $data['data_code'] !== $old_code) {
                $cek_kode = $this->repository->getCodeByWorkshop($data['data_workshop'], $data['data_code']);
                if ($cek_kode) {
                    log_message('error', '[MachineService::updateData] Machine code {code} already exist in workshop {workshop}', ['code' => $data['data_code'], 'workshop' => $data['data_workshop']]);
                    throw new \Exception("Machine code <strong>{$data['data_code']}</strong> already exist in workshop <strong>{$nama_workshop}</strong>", ResponseInterface::HTTP_CONFLICT);
                }
            }

            $data = [
                'code' => strtoupper(trim($data['data_code'])),
                'name' => ucwords(trim($data['data_name'])),
                'specification' => trim($data['data_spesifikasi']),
                'workshop' => trim($data['data_workshop']),
                'brand' => trim($data['data_brand']),
                'serial_no' => trim($data['serial_no']),
                'tonnage' => ($data['data_tonnage'] ? trim($data['data_tonnage']) : null),
                'rate' => trim($data['data_rate']),
                'mfg_date' => ($data['mfg_date'] ? date('Y-m-d', strtotime($data['mfg_date'])) : null),
                'purchase_date' => ($data['purchase_date'] ? date('Y-m-d', strtotime($data['purchase_date'])) : null),
                'description' => trim($data['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                $php_errormsg = $this->db->error();
                log_message('error', '[MachineService::updateData] Failed to update data : {err} from {ip}', ['err' => $php_errormsg, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to update data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[MachineService::updateData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function  deleteData(string $id)
    {
        try {
            $this->db->transStart();
            $this->repository->delete($id);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                $php_errormsg = $this->db->error();
                log_message('error', '[MachineService::deleteData] Failed to delete data : {err} from {ip}', ['err' => $php_errormsg, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to delete data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[MachineService::deleteData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function prevData(string $code, string $workshop)
    {
        try {
            $prev = $this->repository->getPrevData($code, $workshop);
            if (!$prev) {
                throw new \Exception("You are in the first data", ResponseInterface::HTTP_BAD_REQUEST);
            }

            return [
                'token' => enkripsi($prev->id)
            ];
        } catch (\Exception $e) {
            log_message('error', '[MachineService::prevData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function nextData(string $code, string $workshop)
    {
        try {
            $next = $this->repository->getNextData($code, $workshop);
            if (!$next) {
                throw new \Exception("You are in the last data", ResponseInterface::HTTP_BAD_REQUEST);
            }

            return [
                'token' => enkripsi($next->id)
            ];
        } catch (\Exception $e) {
            log_message('error', '[MachineService::nextData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function massDeleteData(array $data)
    {
        try {
            $this->db->transStart();
            $this->repository->massDelete($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                $php_errormsg = $this->db->error();
                log_message('error', '[MachineService::massDeleteData] Failed to delete data : {err} from {ip}', ['err' => $php_errormsg, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to delete data " . $php_errormsg, ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[MachineService::massDeleteData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            try {
                $fileName = "machine_list" . date("Ymd_his") . '.xlsx';

                $headers = [
                    'Machine No',
                    'Machine Name',
                    'Specification',
                    'Workshop',
                    'Brand',
                    'Serial No',
                    'Tonnage',
                    'Machine Rate',
                    'Mfg Date',
                    'Purchase Date',
                    'Description',
                ];

                $dataCallback = function ($offset, $limit) {
                    $column = 'code, name, specification, workshop_name, brand, serial_no, tonnage_name, rate, mfg_date, purchase_date, description';
                    return $this->repository->chunkedData($offset, $limit, 'code', $column);
                };

                return export_to_excel($fileName, $headers, $dataCallback);
            } catch (\Exception $e) {
                log_message('error', '[MachineService::exportData] Unexpected occured when exported equipement type data by {NIK} from {ip} with error {err} ', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
                throw $e;
            }
        } catch (\Exception $e) {
            log_message('error', '[MachineService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function loadAllData()
    {
        try {
            return $this->repository->all('code', 'ASC');
        } catch (\Exception $e) {
            log_message('error', '[MachineService::loadAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
        }
    }
}
