<?php

namespace App\Controllers\AppSetup\Users;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\UsersService;
use App\Repositories\UserRepository;
use CodeIgniter\HTTP\Response;

class UsersController extends BaseController
{
    protected $userService;

    public function __construct()
    {
        $this->userService = new UsersService(new UserRepository());
    }
    public function index()
    {
        $data = [
            'title' => "User Management",
            'footer' => [
                '<script src="' . base_url() . 'js/AppSetup/Users/users.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Users/index', $data);
    }

    public function addUser()
    {
        $data = [
            'title' => "Add new user",
            'footer' => [
                '<script src="' . base_url() . 'js/AppSetup/Users/add.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Users/add', $data);
    }

    function saveUser()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', 'request method not allowed for save new user process : {method} from {ip} by {NIK}', ['method' => $this->request->getMethod(), 'ip' => $this->request->getIPAddress(), 'NIK' => session()->get('user_name')]); // simpan log
            throw new \Exception('request method not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->userService->save($data)) {
                return pesan(ResponseInterface::HTTP_OK, 'User saved successfully');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR; // jika tidak ada kode error di exception, kembalikan error 500
            log_message('error', "Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $this->request->getIPAddress()]); // simpan log
            return pesan($code, $e->getMessage());
        }
    }

    function loadTable()
    {
        if ($this->request->isAJAX()) {
            $requestData = $this->request->getPost();

            $output = $this->userService->loadTable($requestData);

            return $this->response->setJSON($output);
        }

        return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
    }

    function exportData()
    {
        try {
            return $this->userService->exportData();
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR; // jika tidak ada kode error di exception, kembalikan error 500
            log_message('error', "Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $this->request->getIPAddress()]); // simpan log
            return pesan($code, $e->getMessage());
        }
    }
    function getUser($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', 'request method not allowed for save new user process : {method} from {ip} by {NIK}', ['method' => $this->request->getMethod(), 'ip' => $this->request->getIPAddress(), 'NIK' => session()->get('user_name')]); // simpan log
            throw new \Exception('request method not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $getData = $this->userService->getUserData($token);

            if ($getData) {
                return pesan(ResponseInterface::HTTP_OK, 'User data found', $getData['token']);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR; // jika tidak ada kode error di exception, kembalikan error 500
            log_message('error', "Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $this->request->getIPAddress()]); // simpan log
            return pesan($code, $e->getMessage());
        }
    }

    function showUser($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', 'request method not allowed for save new user process : {method} from {ip} by {NIK}', ['method' => $this->request->getMethod(), 'ip' => $this->request->getIPAddress(), 'NIK' => session()->get('user_name')]); // simpan log
            throw new \Exception('request method not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $getData = $this->userService->getUserData($token);
            if (!$getData) {
                throw new \Exception('User not found', ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'title' => "Show user " . $getData['full_name'],
                'data' => $getData,
                'footer' => [
                    '<script src="' . base_url('js/AppSetup/Users/show.js') . '"></script>'
                ]
            ];

            return view('AppSetup/Users/show', $data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR; // jika tidak ada kode error di exception, kembalikan error 500
            log_message('error', "Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $this->request->getIPAddress()]); // simpan log
            return pesan($code, $e->getMessage());
        }
    }

    function updateData()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', 'request method not allowed for save new user process : {method} from {ip} by {NIK}', ['method' => $this->request->getMethod(), 'ip' => $this->request->getIPAddress(), 'NIK' => session()->get('user_name')]); // simpan log
            throw new \Exception('request method not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();
            if ($this->userService->update($data)) {
                return pesan(ResponseInterface::HTTP_OK, 'User updated successfully');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR; // jika tidak ada kode error di exception, kembalikan error 500
            log_message('error', "Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $this->request->getIPAddress()]); // simpan log
            return pesan($code, $e->getMessage());
        }
    }

    function disableData()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', 'request method not allowed for save new user process : {method} from {ip} by {NIK}', ['method' => $this->request->getMethod(), 'ip' => $this->request->getIPAddress(), 'NIK' => session()->get('user_name')]); // simpan log
            throw new \Exception('request method not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if ($this->userService->disableData($json_data)) {
                return pesan(ResponseInterface::HTTP_OK, "User disabled successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR; // jika tidak ada kode error di exception, kembalikan error 500
            log_message('error', "Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $this->request->getIPAddress()]); // simpan log
            return pesan($code, $e->getMessage());
        }
    }

    function prevData()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', 'request method not allowed to getting previous user data : {method} from {ip} by {NIK}', ['method' => $this->request->getMethod(), 'ip' => $this->request->getIPAddress(), 'NIK' => session()->get('user_name')]); // simpan log
            throw new \Exception('request method not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            $get_prev_data = $this->userService->prevData($json_data);
            if ($json_data) {
                return pesan(ResponseInterface::HTTP_OK, 'Data Found', $get_prev_data);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR; // jika tidak ada kode error di exception, kembalikan error 500
            log_message('error', "Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $this->request->getIPAddress()]); // simpan log
            return pesan($code, $e->getMessage());
        }
    }

    function nextData()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', 'request method not allowed to getting next user data : {method} from {ip} by {NIK}', ['method' => $this->request->getMethod(), 'ip' => $this->request->getIPAddress(), 'NIK' => session()->get('user_name')]); // simpan log
            throw new \Exception('request method not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            $get_next_data = $this->userService->nextData($json_data);
            if ($json_data) {
                return pesan(ResponseInterface::HTTP_OK, 'Data Found', $get_next_data);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR; // jika tidak ada kode error di exception, kembalikan error 500
            log_message('error', "Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $this->request->getIPAddress()]); // simpan log
            return pesan($code, $e->getMessage());
        }
    }

    function massDelete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', 'request method not allowed to delete mass user data : {method} from {ip} by {NIK}', ['method' => $this->request->getMethod(), 'ip' => $this->request->getIPAddress(), 'NIK' => session()->get('user_name')]); // simpan log
            throw new \Exception('request method not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getJSON(true);

            $get = $this->userService->massDelete($data);
            return pesan(ResponseInterface::HTTP_OK, 'User deleted successfully', $get);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR; // jika tidak ada kode error di exception, kembalikan error 500
            log_message('error', "Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $this->request->getIPAddress()]); // simpan log
            return pesan($code, $e->getMessage());
        }
    }
}
