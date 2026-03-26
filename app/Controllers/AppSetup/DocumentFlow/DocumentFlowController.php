<?php

namespace App\Controllers\AppSetup\DocumentFlow;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\DocumentFlow\DocumentFlowServices;
use App\Repositories\DocumentFlow\DocumentFlowRepository;
use App\Services\ApqpSetup\ApqpHeaderService;
use App\Repositories\ApqpSetup\ApqpHeaderRepository;

class DocumentFlowController extends BaseController
{
    protected $document;
    protected $apqp;

    public function __construct()
    {
        $this->document = new DocumentFlowServices(new DocumentFlowRepository());
        $this->apqp = new ApqpHeaderService(new ApqpHeaderRepository());
    }

    public function index()
    {
        $data = [
            'title' => "Document Flow Management",
            'documents' => $this->document->getDocumentList(),
            'footer' => [
                '<script src="https://gw.alipayobjects.com/os/lib/antv/g6/4.8.24/dist/g6.min.js"></script>',
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/DocumentFlow/document_flow.js' . '"></script>',
            ]
        ];

        return view('AppSetup/DocumentFlow/index', $data);
    }

    public function getParentList()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[DocumentFlowController::getParentList] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Method not allowed']);
            throw new \Exception('Request method not allowed', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $get = $this->document->getParent();

            return pesan(ResponseInterface::HTTP_OK, 'Success', $get);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[DocumentFlowController::getParentList] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function getDocumentList()
    {
        try {
            $get = $this->document->getDocumentList();

            return pesan(ResponseInterface::HTTP_OK, 'Success', $get);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[DocumentFlowController::getDocumentList] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[DocumentFlowController::save] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Method not allowed']);
            throw new \Exception('Request method not allowed', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $postData = $this->request->getPost();

            $this->document->saveData($postData);

            return pesan(ResponseInterface::HTTP_OK, "Data saved successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[DocumentFlowController::save] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function getFlow()
    {
        try {
            $get = $this->document->getDocumentFlow();

            return pesan(ResponseInterface::HTTP_OK, 'Success', $get);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[DocumentFlowController::getFlow] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
