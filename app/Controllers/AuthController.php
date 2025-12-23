<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\AuthService;
use App\Repositories\UserRepository;

class AuthController extends BaseController
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new AuthService(new UserRepository());
    }
    public function index()
    {
        $data = [
            'title' => "User Authorization",
        ];

        return view('Auth/index', $data);
    }

    public function prosesLogin()
    {
        // Cek request method
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', 'request method not allowed for authorization process : {method} from {ip}', ['method' => $this->request->getMethod()]); // simpan log
            return $this->errorResponse('request method not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'error_405'); // tampilkan error
        }

        try {
            // Lempar proses login ke services AuthService
            $this->authService->prosesLogin($this->request->getPost());
        } catch (\Exception $e) {
            // Tampikan error
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR; // jika tidak ada kode error di exception, kembalikan error 500
            log_message('error', "Unexpected error occured : {err}", ['err' => $e->getMessage()]); // simpan log
            return $this->errorResponse($e->getMessage(), $code, 'error_500'); // tampilkan error
        }
    }
}
