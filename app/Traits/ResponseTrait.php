<?php

namespace App\Traits;

use Config\Services;

trait ResponseTrait
{

    protected function exceptionResponse(\Exception $e)
    {
        $httpCode = method_exists($e, 'getHttpcode') ? $e->getCode() : 500;

        $response =  [
            'status' => 'error',
            'code' => $httpCode,
            'message' => $e->getMessage(),
            'data' => method_exists($e, 'getData') ? $e->getTraceAsString() : null
        ];

        if (ENVIRONMENT === 'development') {
            $response['debug'] = [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
        }

        log_message('error', '[{exception}] {message} in {file} : {line}', [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        $response = Services::response();

        return $response->setStatusCode($httpCode)->setJSON($response);
    }

    protected function success($data = null, string $message = 'Success', int $code = 200)
    {
        $response = [
            'status' => 'success',
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];

        return $this->response->setStatusCode($code)->setJSON($response);
    }
}
