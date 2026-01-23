<?php

namespace App\Services\Customer;

use App\Repositories\Customer\CustomerRepository;
use App\Repositories\DataTableRepository;
use App\Models\AppSetup\Customer\CustomerModel;
use App\Models\AppSetup\Customer\VwCustomerModel;
use App\Validation\Customer\CustomerValidation;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;
use PHPUnit\TextUI\XmlConfiguration\Validator;

class CustomerService
{
    protected $db;
    protected $validation;
    protected $repository;

    public function __construct(CustomerRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function loadTable($requestedData)
    {
        try {
            $model = new VwCustomerModel();
            $builder = $model->builder();

            $column_search = [
                'code',
                'category_name',
                'name',
                'address',
                'email_hash',
                'phone_hash',
                'contact_person',
                'contact_person_email_hash',
                'contact_person_phone_hash',
                'description'
            ];

            $column_order = [
                'code',
                'category_name',
                'name',
                'address',
                'email_hash',
                'phone_hash',
                'contact_person',
                'contact_person_email_hash',
                'contact_person_phone_hash',
                'description'
            ];

            $default_order = array('code' => 'asc');

            $customSearch = [
                'phone_hash' => function ($builder, $searchValue) {
                    $phone_hash = phone_hash($searchValue);
                    $builder->orWhere('phone_hash', $phone_hash);
                },
                'email_hash' => function ($builder, $searchValue) {
                    $email_hash = email_hash($searchValue);
                    $builder->orWhere('email_hash', $email_hash);
                },
                'contact_person_email_hash' => function ($builder, $searchValue) {
                    $cp_phone = phone_hash($searchValue);
                    $builder->orWhere('contact_person_email_hash', $cp_phone);
                },
                'contact_person_phone_hash' => function ($builder, $searchValue) {
                    $cp_email = email_hash($searchValue);
                    $builder->orWhere('contact_person_phone_hash', $cp_email);
                }
            ];

            $data_table = new DataTableRepository($builder, $column_search, $column_order, $default_order, $customSearch, 'deleted_at');

            $result = $data_table->proses($requestedData);
            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->category_name,
                    $row->name,
                    $row->address,
                    ($row->email) ? sensor_email(dekripsi($row->email)) : null,
                    ($row->phone) ? sensor_phone(dekripsi($row->phone)) : null,
                    $row->contact_person,
                    ($row->contact_person_email) ? sensor_email(dekripsi($row->contact_person_email)) : null,
                    ($row->contact_person_phone) ? sensor_phone(dekripsi($row->contact_person_phone)) : null,
                    $row->description
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[CustomerService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(CustomerValidation::$save);

            if ($this->validation->run($postData) == false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[CustomerService::saveData] Validation error occured : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to save new customer data, validation error with message <br> $error_to_string", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = generate_uuid();
            $code = $this->repository->generateCode('CUST-', 'code', 6);

            $data = [
                'id' => $id,
                'code' => $code,
                'name' => ucwords(trim($postData['data_name'])),
                'address' => trim($postData['data_address']),
                'category' => $postData['data_category'],
                'email' => ($postData['data_email']) ? enkripsi($postData['data_email']) : null,
                'email_hash' => ($postData['data_email']) ? email_hash($postData['data_email']) : null,
                'phone' => ($postData['data_phone']) ? enkripsi($postData['data_phone']) : null,
                'phone_hash' => ($postData['data_phone']) ? phone_hash($postData['data_phone']) : null,
                'contact_person' => $postData['data_contact'] ?? null,
                'contact_person_email' => ($postData['email_contact']) ? enkripsi($postData['email_contact']) : null,
                'contact_person_email_hash' => ($postData['email_contact']) ? email_hash($postData['email_contact']) : null,
                'contact_person_phone' => ($postData['phone_contact']) ? enkripsi($postData['phone_contact']) : null,
                'contact_person_phone_hash' => ($postData['phone_contact']) ? phone_hash($postData['phone_contact']) : null,
                'description' => ($postData['data_remark']) ? $postData['data_remark'] : null,
                'created_by' => session()->get('user_name'),
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[CustomerService::saveData] Failed to save data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to save data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            log_message('error', '[CustomerService::saveData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $data = $this->repository->find($id);
            if (!$data) {
                log_message('error', '[CustomerService::getData] Data not found : {id} from {ip}', ['id' => $id, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Data not found', ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($data->id),
                'code' => $data->code,
                'name' => $data->name,
                'address' => $data->address,
                'category' => $data->category,
                'email' => ($data->email) ? dekripsi($data->email) : null,
                'phone' => ($data->phone) ? dekripsi($data->phone) : null,
                'contact_person' => $data->contact_person,
                'contact_person_email' => ($data->contact_person_email) ? dekripsi($data->contact_person_email) : null,
                'contact_person_phone' => ($data->contact_person_phone) ? dekripsi($data->contact_person_phone) : null,
                'description' => $data->description,
            ];
        } catch (\Exception $e) {
            log_message('error', '[CustomerService::getData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(CustomerValidation::$update);

            if ($this->validation->run($postData) == false) {
                $error_to_string = implode("\n", $this->validation->getErrors());
                log_message('error', '[CustomerService::updateData] Validation error occured : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string);
            }

            $id = dekripsi($postData['data_token']);

            $data = [
                'name' => ucwords(trim($postData['data_name'])),
                'address' => trim($postData['data_address']),
                'category' => $postData['data_category'],
                'email' => ($postData['data_email']) ? enkripsi($postData['data_email']) : null,
                'email_hash' => ($postData['data_email']) ? email_hash($postData['data_email']) : null,
                'phone' => ($postData['data_phone']) ? enkripsi($postData['data_phone']) : null,
                'phone_hash' => ($postData['data_phone']) ? phone_hash($postData['data_phone']) : null,
                'contact_person' => ($postData['data_contact']) ? $postData['data_contact'] : null,
                'contact_person_email' => ($postData['email_contact']) ? enkripsi($postData['email_contact']) : null,
                'contact_person_email_hash' => ($postData['email_contact']) ? email_hash($postData['email_contact']) : null,
                'contact_person_phone' => ($postData['phone_contact']) ? enkripsi($postData['phone_contact']) : null,
                'contact_person_phone_hash' => ($postData['phone_contact']) ? phone_hash($postData['phone_contact']) : null,
                'description' => ($postData['data_remark']) ? $postData['data_remark'] : null,
                'updated_by' => session()->get('user_name'),
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[CustomerService::updateData] Failed to update data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to update data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[CustomerService::updateData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[CustomerService::deleteData] Failed to delete data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to delete data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[CustomerService::deleteData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function massDelete(array $postData)
    {
        try {
            $this->db->transStart();
            $this->repository->massDelete($postData);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[CustomerService::massDelete] Failed to delete data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to delete data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[CustomerService::massDelete] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = 'customer_list_' . date('Y-m-d_H-i-s') . '.xlsx';

            $headers = [
                'code',
                'name',
                'address',
                'category',
                'email',
                'phone',
                'contact_person',
                'contact_person_email',
                'contact_person_phone',
                'description',
            ];

            $dataCallBack = function ($limit, $offset) {
                $column = '
                    code,
                    name,
                    address,
                    category_name,
                    email,
                    phone,
                    contact_person,
                    contact_person_email,
                    contact_person_phone,
                    description,
                ';

                return $this->repository->chunkedData($offset, $limit, 'code', $column);
            };

            return export_decrypted_data($file_name, $headers, ['email', 'phone', 'contact_person_email', 'contact_person_phone'], $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[CustomerService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            $data = $this->repository->all('code', 'ASC');

            if (!$data) {
                log_message('error', '[CustomerService::getAllData] Data not found from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Data not found', ResponseInterface::HTTP_NOT_FOUND);
            }

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[CustomerService::getAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                'token' => enkripsi($prev->id),
            ];
        } catch (\Exception $e) {
            log_message('error', '[CustomerService::prevData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                'token' => enkripsi($next->id),
            ];
        } catch (\Exception $e) {
            log_message('error', '[CustomerService::nextData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
