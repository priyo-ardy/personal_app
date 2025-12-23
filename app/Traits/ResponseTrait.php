<?php

namespace App\Traits;

use Config\Services;

trait ResponseTrait
{
    protected function errorResponse(string $message, int $code, string $view)
    {
        $requst = Services::request();
        $response = Services::response();

        if ($requst->isAJAX()) {
            return $response->setStatusCode($code)->setJSON([
                'status'  => 'error',
                'message' => $message
            ]);
        }

        return view("errors/html/{$view}", ['message' => $message]);
    }
}
