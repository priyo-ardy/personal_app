<?php

namespace App\Services\Supplier;

use App\Repositories\Supplier\SupplierRepository;
use App\Models\AppSetup\Supplier\SupplierModel;
use App\Validation\Supplier\SupplierValidation;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class SupplierService
{
    protected $repository;
    protected $db;
    protected $validation;

    public function __construct(SupplierRepository $repo)
    {
        $this->repository = $repo;
        $this->db = Database::connect();
        $this->validation = Services::validation();
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new SupplierModel();
            $builder = $model->builder();

            $column_search = [
                'code',
                'name',
                'address',
                'phone',
                'email',
                'contact_person',
                'contact_person_email_hash',
                'contact_person_phone_hash',
                'npwp_no',
                'npwp_hash',
                'bank_name',
                'bank_account_no_hash',
                'bank_account_name',
                'description',
            ];

            $column_order = [
                '0' => 'code',
                '1' => 'name',
                '2' => 'address',
                '3' => 'phone',
                '4' => 'email',
                '5' => 'contact_person',
                '6' => 'contact_person_email_hash',
                '7' => 'contact_person_phone_hash',
                '8' => 'npwp_no',
                '9' => 'npwp_hash',
                '10' => 'bank_name',
                '11' => 'bank_account_no_hash',
                '12' => 'bank_account_name',
                '13' => 'description',
            ];

            $default_order = array('code' => 'asc');

            $custom_search = [
                'phone_hash' => function ($builder, $searchValue) {
                    $phone_hash = phone_hash($searchValue);
                    $builder->like('phone_hash', $phone_hash);
                },
                'email_hash' => function ($builder, $searchValue) {
                    $email_hash = email_hash($searchValue);
                    $builder->like('email_hash', $email_hash);
                },
                'contact_person_email_hash' => function ($builder, $searchValue) {
                    $contact_person_email_hash = email_hash($searchValue);
                    $builder->like('contact_person_email_hash', $contact_person_email_hash);
                },
                'contact_person_phone_hash' => function ($builder, $searchValue) {
                    $contact_person_phone_hash = phone_hash($searchValue);
                    $builder->like('contact_person_phone_hash', $contact_person_phone_hash);
                },
                'npwp_hash' => function ($builder, $searchValue) {
                    $npwp_hash = npwp_hash($searchValue);
                    $builder->like('npwp_hash', $npwp_hash);
                },
                'bank_account_no_hash' => function ($builder, $searchValue) {
                    $bank_account_no_hash = bank_account_no_hash($searchValue);
                    $builder->like('bank_account_no_hash', $bank_account_no_hash);
                }
            ];

            $data_table = new DataTableRepository($builder, $column_search, $column_order, $default_order, $custom_search, 'deleted_at');

            $result = $data_table->proses($requestedData);
            $formatData = [];

            foreach ($result['data'] as $row) {
                $formatData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    $row->address,
                    ($row->phone) ? dekripsi($row->phone) : null,
                    ($row->email) ? dekripsi($row->email) : null,
                    $row->contact_person,
                    ($row->contact_person_email) ? dekripsi($row->contact_person_email) : null,
                    ($row->contact_person_phone) ? dekripsi($row->contact_person_phone) : null,
                    ($row->npwp_no) ? dekripsi($row->npwp_no) : null,
                    $row->bank_name,
                    ($row->bank_account_no) ? dekripsi($row->bank_account_no) : '',
                    $row->bank_account_name,
                    $row->description,
                ];
            }

            $result['data'] = $formatData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[SupplierService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(SupplierValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[SupplierService::saveData] Validation error : {err} from {ip}', ['err' => implode("<br>", $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Validation error : " . $error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = uuid_v7();
            $code = $this->repository->generateCode('SUP-', 'code', 6);

            $data = [
                'id' => $id,
                'code' => $code,
                'name' => ucwords(trim($postData['data_name'])),
                'address' => trim($postData['data_address']),
                'phone' => ($postData['data_phone']) ? enkripsi($postData['data_phone']) : null,
                'phone_hash' => ($postData['data_phone']) ? phone_hash($postData['data_phone']) : null,
                'email' => ($postData['data_email']) ? enkripsi($postData['data_email']) : null,
                'email_hash' => ($postData['data_email']) ? email_hash($postData['data_email']) : null,
                'contact_person' => ucwords(trim($postData['data_contact_person'])),
                'contact_person_email' => ($postData['data_contact_person_email']) ? enkripsi($postData['data_contact_person_email']) : null,
                'contact_person_email_hash' => ($postData['data_contact_person_email']) ? email_hash($postData['data_contact_person_email']) : null,
                'contact_person_phone' => ($postData['data_contact_person_phone']) ? enkripsi($postData['data_contact_person_phone']) : null,
                'contact_person_phone_hash' => ($postData['data_contact_person_phone']) ? phone_hash($postData['data_contact_person_phone']) : null,
                'npwp_no' => ($postData['data_npwp_no']) ? enkripsi($postData['data_npwp_no']) : null,
                'npwp_hash' => ($postData['data_npwp_no']) ? npwp_hash($postData['data_npwp_no']) : null,
                'bank_name' => trim($postData['data_bank_name']),
                'bank_account_no' => ($postData['data_bank_account_no']) ? enkripsi($postData['data_bank_account_no']) : null,
                'bank_account_no_hash' => ($postData['data_bank_account_no']) ? bank_account_no_hash($postData['data_bank_account_no']) : null,
                'bank_account_name' => trim($postData['data_bank_account_name']),
                'description' => trim($postData['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[SupplierService::saveData] Failed to save data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to save data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[SupplierService::saveData] Data saved successfully : {id} from {ip}', ['id' => $id, 'ip' => $_SERVER['REMOTE_ADDR']]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[SupplierService::saveData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get_data = $this->repository->find($id);

            if (!$get_data) {
                log_message('error', '[SupplierService::getData] Data not found : {id} from {ip}', ['id' => $id, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Data not found', ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($get_data->id),
                'code' => $get_data->code,
                'name' => $get_data->name,
                'address' => $get_data->address,
                'phone' => ($get_data->phone) ? dekripsi($get_data->phone) : null,
                'email' => ($get_data->email) ? dekripsi($get_data->email) : null,
                'contact_person' => $get_data->contact_person,
                'contact_person_email' => ($get_data->contact_person_email) ? dekripsi($get_data->contact_person_email) : null,
                'contact_person_phone' => ($get_data->contact_person_phone) ? dekripsi($get_data->contact_person_phone) : null,
                'npwp_no' => ($get_data->npwp_no) ? dekripsi($get_data->npwp_no) : null,
                'bank_name' => $get_data->bank_name,
                'bank_account_no' => ($get_data->bank_account_no) ? dekripsi($get_data->bank_account_no) : null,
                'bank_account_name' => $get_data->bank_account_name,
                'description' => $get_data->description
            ];
        } catch (\Exception $e) {
            log_message('error', '[SupplierService::getData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(SupplierValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_sting = implode("<br>", $this->validation->getErrors());
                log_message('error', '[SupplierService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_sting, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_sting);
            }

            $id = dekripsi(trim($postData['data_token']));

            $data = [
                'name' => ucwords(trim($postData['data_name'])),
                'address' => trim($postData['data_address']),
                'phone' => ($postData['data_phone']) ? enkripsi($postData['data_phone']) : null,
                'phone_hash' => ($postData['data_phone']) ? phone_hash($postData['data_phone']) : null,
                'email' => ($postData['data_email']) ? enkripsi($postData['data_email']) : null,
                'email_hash' => ($postData['data_email']) ? email_hash($postData['data_email']) : null,
                'contact_person' => ucwords(trim($postData['data_contact_person'])),
                'contact_person_email' => ($postData['data_contact_person_email']) ? enkripsi($postData['data_contact_person_email']) : null,
                'contact_person_email_hash' => ($postData['data_contact_person_email']) ? email_hash($postData['data_contact_person_email']) : null,
                'contact_person_phone' => ($postData['data_contact_person_phone']) ? enkripsi($postData['data_contact_person_phone']) : null,
                'contact_person_phone_hash' => ($postData['data_contact_person_phone']) ? phone_hash($postData['data_contact_person_phone']) : null,
                'npwp_no' => ($postData['data_npwp_no']) ? enkripsi($postData['data_npwp_no']) : null,
                'npwp_hash' => ($postData['data_npwp_no']) ? npwp_hash($postData['data_npwp_no']) : null,
                'bank_name' => trim($postData['data_bank_name']),
                'bank_account_no' => ($postData['data_bank_account_no']) ? enkripsi($postData['data_bank_account_no']) : null,
                'bank_account_no_hash' => ($postData['data_bank_account_no']) ? bank_account_no_hash($postData['data_bank_account_no']) : null,
                'bank_account_name' => trim($postData['data_bank_account_name']),
                'description' => trim($postData['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[SupplierService::updateData] Failed to update data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($this->db->error()['message']);
            }

            log_message('info', '[SupplierService::updateData] Supplier with id {id} has been updated', ['id' => $id]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[SupplierService::updateData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[SupplierService::deleteData] Failed to delete data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($this->db->error()['message']);
            }

            log_message('info', '[SupplierService::deleteData] Supplier with id {id} has been deleted', ['id' => $id]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[SupplierService::deleteData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[SupplierService::massDelete] Failed to delete data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($this->db->error()['message']);
            }

            log_message('info', '[SupplierService::massDelete] Supplier with total {total} has been deleted', ['total' => count($id)]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[SupplierService::massDelete] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = "supplier_list_" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Supplier Code',
                'Supplier Name',
                'Address',
                'Email',
                'Phone',
                'Contact Person',
                'Contact Person Email',
                'Contact Person Phone',
                'NPWP No',
                'Bank Name',
                'Bank Account No',
                'Bank Account Name',
                'Remark'
            ];

            $dataCallBack = function ($offset, $limit) {
                $column = 'code, name, address, phone, email,contact_person, contact_person_email, contact_person_phone, npwp_no, bank_name, bank_account_no, bank_account_name, description';
                return $this->repository->getChunkedData($offset, $limit, 'code', $column);
            };

            return export_decrypted_data($file_name, $headers, ['email', 'phone', 'contact_person_email', 'contact_person_phone', 'npwp_no', 'bank_account_no'], $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[SupplierService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function prevData(string $code)
    {
        try {
            $prev = $this->repository->prevData('code', $code);
            if (!$prev) {
                throw new \Exception('You are in the first data', ResponseInterface::HTTP_BAD_REQUEST);
            }

            return [
                'token' => enkripsi($prev->id)
            ];
        } catch (\Exception $e) {
            log_message('error', '[SupplierService::prevData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function nextData(string $code)
    {
        try {
            $next = $this->repository->nextData('code', $code);
            if (!$next) {
                throw new \Exception('You are in the last data', ResponseInterface::HTTP_BAD_REQUEST);
            }

            return [
                'token' => enkripsi($next->id)
            ];
        } catch (\Exception $e) {
            log_message('error', '[SupplierService::nextData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            return $this->repository->all('code', 'asc');
        } catch (\Exception $e) {
            log_message('error', '[SupplierService::getAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
