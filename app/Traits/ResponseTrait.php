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

        log_message('error', '{message} in {file} : {line}', [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        $response = Services::response();

        return $response->setStatusCode($httpCode)->setJSON($response);
    }

    protected function success(int $code = 200, string $message = 'Success', $data = null)
    {
        $response = [
            'status' => 'success',
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];

        return $this->response->setStatusCode($code)->setJSON($response);
    }

    protected function setTimestamptzInsert(array $data)
    {
        $now = date('Y-m-d H:i:sP'); // Output: 2025-12-31 20:00:00+07:00

        $data['data'][$this->createdField] = $now;
        $data['data'][$this->updatedField] = $now;

        return $data;
    }

    protected function setTimestamptzUpdate(array $data)
    {
        $data['data'][$this->updatedField] = date('Y-m-d H:i:sP');
        return $data;
    }
}
