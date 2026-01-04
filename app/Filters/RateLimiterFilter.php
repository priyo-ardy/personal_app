<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class RateLimiterFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        $throttler = Services::throttler();


        $limit = $arguments[0] ?? 60;
        $seconds = $arguments[1] ?? 60;


        if ($session->has('user_name')) {
            $identifier = 'user_' . $session->get('user_name');
        } else {
            $identifier = 'ip_' . $request->getIPAddress();
        }

        $path = $request->getUri()->getPath();
        $rawKey = $identifier . '_' . $path;
        $key = md5('rate_limit_' . $rawKey);

        if ($throttler->check($key, $limit, $seconds) === false) {
            // return service('response')->setStatusCode(ResponseInterface::HTTP_TOO_MANY_REQUESTS);
            return service('response')->setStatusCode(ResponseInterface::HTTP_TOO_MANY_REQUESTS)->setJSON(['status' => 'error', 'message' => 'Too many requests.']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // throw new \Exception('Not implemented');
    }
}
