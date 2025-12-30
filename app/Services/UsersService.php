<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Validation\UsersValidation;
use CodeIgniter\HTTP\ResponseInterface;
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
            log_message('error', "Failed to create new user : {user}", ['user' => $data['user_name']]);
            throw new \Exception('Failed to create new user', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        return true;
    }
}
