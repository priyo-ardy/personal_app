<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        $user_level = $session->get('user_level');

        if (empty($user_level)) {
            return redirect()->to(base_url('login'))->with('error', 'Please login first');
        }

        if (empty($arguments)) {
            return;
        }

        if (!in_array($user_level, $arguments)) {
            return view('errors/html/error_403', ['title' => "Unauthorized Access"]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
