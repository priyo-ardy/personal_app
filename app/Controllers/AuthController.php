<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\AuthService;
use App\Services\ResetPasswordServices;
use App\Repositories\UserRepository;

class AuthController extends BaseController
{
    protected $authService;
    protected $resetPassword;

    public function __construct()
    {
        $this->authService = new AuthService(new UserRepository());
        $this->resetPassword = new ResetPasswordServices(new UserRepository());
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
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', 'request method not allowed for authorization process : {method} from {ip}', ['method' => $this->request->getMethod()]);
            return $this->errorResponse('request method not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'error_405');
        }

        try {
            $data = $this->request->getPost();
            if ($this->authService->prosesLogin($data)) {
                return pesan(ResponseInterface::HTTP_OK, 'Authorization success');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $this->request->getIPAddress()]);
            return pesan($code, $e->getMessage());
        }
    }

    public function forgotPassword()
    {
        $data = [
            'title' => 'Forgot Password'
        ];

        return view('Auth/forgot-password', $data);
    }

    public function resetPassword()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', 'request method not allowed for authorization process : {method} from {ip}', ['method' => $this->request->getMethod()]);
            return $this->errorResponse('request method not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'error_405');
        }

        try {
            $data = $this->request->getPost();
            if ($this->resetPassword->resetPassword($data)) {
                return pesan(ResponseInterface::HTTP_OK, 'Your password change request has been successfully processed. Please check your inbox or spam folder in your email.');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $this->request->getIPAddress()]);
            return pesan($code, $e->getMessage());
        }
    }

    public function logout()
    {
        session()->destroy();
        helper('cookie');
        delete_cookie('is_user_logged_in'); // Hapus flag cookie
        return redirect()->to(base_url());
    }
}
