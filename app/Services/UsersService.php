<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Validation\UsersValidation;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use Config\Services;
use Config\Database;


class UsersService
{
    protected $db;
    protected $userRepo;
    protected $validasi;
    public function __construct(UserRepository $userRepo)
    {
        $this->db = Database::connect();
        $this->userRepo = $userRepo;
        $this->validasi = Services::validation();
    }

    public function save(array $data)
    {
        $this->validasi->setRules(UsersValidation::$saveUser);

        if ($this->validasi->run($data) === false) {
            $error_to_string = implode("<br>", $this->validasi->getErrors());
            log_message('error', "Failed to verify ew user data : {err}", ['err' => $error_to_string]);
            throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
        }

        $user_id = generate_uuid();
        $user_email = email_hash($data['data_email']);
        $user_phone = phone_hash($data['data_phone']);

        $check_email = $this->userRepo->findByEmail($user_email);
        $check_phone = $this->userRepo->findByPhone($user_phone);

        $duplicate_errors = [];

        if ($check_email) {
            $duplicate_errors[] = "Email <strong class=\"text-danger\">$data[data_email]</strong> already exists";
        }

        if ($check_phone) {
            $duplicate_errors[] = "Phone <strong class=\"text-danger\">$data[data_phone]</strong> already exists";
        }

        if (count($duplicate_errors) > 0) {
            $error_to_string = implode("<br>", $duplicate_errors);
            log_message('error', "Failed to create new user : {user}, with error {err}", ['user' => $data['data_username'], 'err' => $error_to_string]);
            throw new \Exception($error_to_string, ResponseInterface::HTTP_CONFLICT);
        }

        $data = [
            'user_id' => $user_id,
            'user_name' => $data['data_username'],
            'full_name' => ucwords($data['data_fullname']),
            'user_password' => password_hash($data['data_password'], PASSWORD_DEFAULT),
            'user_email' => enkripsi($data['data_email']),
            'user_phone' => enkripsi($data['data_phone']),
            'email_hash' => email_hash($data['data_email']),
            'phone_hash' => phone_hash($data['data_phone']),
            'user_image' => 'default.png',
            'user_status' => 'active',
            'user_level' => $data['data_level'],
            'remark' => $data['data_remark'],
            'login_attempts' => 0,
            'last_login' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => session()->get('user_name'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => null,
            'deleted_at' => null
        ];

        $this->db->transStart();
        $this->userRepo->create($data);
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            log_message('error', "Failed to create new user : {user}, error {err}", ['user' => $data['user_name'], 'err' => $this->db->getLastQuery()]);
            throw new \Exception('Failed to create new user', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        return true;
    }

    function loadTable($requestData)
    {
        $model = new UserModel();
        $builder = $model->builder();

        $column_search = ['user_name', 'full_name', 'email_hash', 'phone_hash', 'user_level', 'last_login', 'login_from', 'remark'];
        $column_order = [
            '0' => 'user_name',
            '1' => 'full_name',
            '2' => 'email_hash',
            '3' => 'phone_hash',
            '4' => 'user_level',
            '5' => 'last_login',
            '6' => 'login_from',
            '7' => 'remark'
        ];

        $defaultOrder = array('user_name' => 'asc');
        $customSearch = [
            'email_hash' => function ($builder, $searchValue) {
                $email_hash = email_hash($searchValue);
                $builder->orWhere('email_hash', $email_hash);
            },
            'phone_hash' => function ($builder, $searchValue) {
                $phone_hash = phone_hash($searchValue);
                $builder->orWhere('phone_hash', $phone_hash);
            }
        ];

        $dataTable = new DataTableRepository($builder, $column_search, $column_order, $defaultOrder, $customSearch);

        $result = $dataTable->proses($requestData);

        $formattedData = [];

        foreach ($result['data'] as $row) {
            $formattedData[] = [
                '<a href="#" onclick="editData(`' . enkripsi($row->user_id) . '`)" class="text-primary fw-bolder text-decoration-none" title="Click to edit">' . $row->user_name . '</a>',
                $row->full_name,
                ($row->user_email) ? dekripsi($row->user_email) : '',
                ($row->user_phone) ? dekripsi($row->user_phone) : '',
                $row->user_level,
                $row->last_login,
                $row->login_from,
                $row->remark,
                ''
            ];
        }

        $result['data'] = $formattedData;

        return $result;
    }

    function exportData()
    {
        $fileName = "user_list" . date("Ymd_his") . 'xlsx';
        $headers = [
            'User Name',
            'Full Name',
            'Email Address',
            'Phone Number',
            'Last Login',
            'last Login From',
            'user_level',
            'user_status',
            'Remark',
        ];

        $dataCallback = function ($offset, $limit) {
            $column = 'user_name, full_name, user_email, user_phone, last_login, login_from, user_level, user_status, remark';
            return $this->userRepo->getChunkedData($offset, $limit, 'user_name', $column);
        };

        return export_decrypted_data($fileName, $headers, ['user_email', 'user_phone'], $dataCallback);
    }

    function getUserData($user_token)
    {
        $user_id = dekripsi($user_token);

        $get_user_by_id = $this->userRepo->findById($user_id);
        if (!$get_user_by_id) {
            throw new \Exception("User not found", ResponseInterface::HTTP_NOT_FOUND);
        }

        $data = [
            'token' => enkripsi($get_user_by_id->user_id),
            'user_name' => $get_user_by_id->user_name,
            'full_name' => $get_user_by_id->full_name,
            'user_email' => dekripsi($get_user_by_id->user_email),
            'user_phone' => dekripsi($get_user_by_id->user_phone),
            'user_level' => $get_user_by_id->user_level,
            'remark' => $get_user_by_id->remark
        ];

        return $data;
    }
}
