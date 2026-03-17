<?php

namespace App\Services\DocumentFlow;

use App\Validation\DocumentFlow\DocumentFlowValidation;
use App\Repositories\DocumentFlow\DocumentFlowRepository;
use App\Models\AppSetup\DocumentFlow\DocumentFlowModel;
use App\Repositories\DataTableRepository;
use App\Repositories\ApqpSetup\ApqpDocumentRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Config\Database;
use Config\Services;

class DocumentFlowServices
{
    protected $repository;
    protected $db;
    protected $validation;
    protected $document;

    public function __construct(DocumentFlowRepository $repo)
    {
        $this->repository = $repo;
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->document = new ApqpDocumentRepository();
    }

    public function loadTable(array $postData)
    {
        try {
        } catch (\Exception $e) {
            log_message('error', '[DocumentFlowServices::loadTable] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getParent()
    {
        try {
            $get_data = $this->repository->getParentData();

            return $get_data;
        } catch (\Exception $e) {
            log_message('error', '[DocumentFlowServices::getParent] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getDocumentList()
    {
        try {
            $get_data = $this->document->getDocumentList();

            return $get_data;
        } catch (\Exception $e) {
            log_message('error', '[DocumentFlowServices::getDocumentList] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(DocumentFlowValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[DocumentFlowServices::saveData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $parents = (isset($postData['parent_id'])) ? $postData['parent_id'] : null;
            $child = $postData['child_id'];

            $data = [];

            if (empty($parents) || (count($parents) == 1 && $parents[0] == "")) {
                $data[] = [
                    'id'           => Uuid::uuid7()->toString(),
                    'parent_id'    => null,
                    'child_id'     => $child,
                    'is_mandatory' => true,
                    'created_by'   => session()->get('user_name')
                ];
            } else {
                // JIKA ADA ISI: Lakukan looping
                foreach ($parents as $parent) {
                    // Lewati jika pilihan kosong (biasanya placeholder "-- Choose --") 
                    // atau jika parent sama dengan child
                    if (empty($parent) || $parent === $child) continue;

                    $data[] = [
                        'id'           => Uuid::uuid7()->toString(),
                        'parent_id'    => $parent,
                        'child_id'     => $child,
                        'is_mandatory' => true,
                        'created_by'   => session()->get('user_name')
                    ];
                }
            }


            $this->db->transStart();
            $this->repository->massSave($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[DocumentFlowServices::saveData] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->error()]);
                throw new \Exception('Transaction error occured', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[DocumentFlowServices::saveData] Data saved successfully');
            return true;
        } catch (\Exception $e) {
            log_message('error', '[DocumentFlowServices::saveData] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get = $this->repository->find($id);

            if (!$get) {
                log_message('error', '[DocumentFlowServices::getData] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->error()['message']]);
                throw new \Exception('Transaction error occured', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            $data = [
                'token' => enkripsi($get->id),
                'level' => $get->level,
                'parent_id' => $get->parent_id,
                'document_id' => $get->document_id,
                'has_child' => $get->has_child,
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[DocumentFlowServices::getData] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(DocumentFlowValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[DocumentFlowServices::update] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi($postData['data_token']);

            $data = [
                'level' => trim($postData['data_level']),
                'parent_id' => trim($postData['data_parent']),
                'document_id' => trim($postData['data_document']),
                'has_child' => trim($postData['data_has_child']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[DocumentFlowServices::update] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->error()]);
                throw new \Exception('Transaction error occured', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[DocumentFlowServices::update] Data updated successfully, id : {id}', ['id' => $id]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[DocumentFlowServices::updateData] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteData(array $id)
    {
        $this->db->transStart();
        $this->repository->massDelete($id);
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            log_message('error', '[DocumentFlowServices::deleteData] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->error()]);
            throw new \Exception('Transaction error occured', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        log_message('info', '[DocumentFlowServices::deleteData] Data deleted successfully, id : {id}', ['id' => implode(', ', $id)]);

        return true;
    }

    public function exportData() {}

    public function getAllData()
    {
        try {
            $get = $this->repository->all('id', 'asc');

            return $get;
        } catch (\Exception $e) {
            log_message('error', '[DocumentFlowServices::getAllData] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getDataByParent(string $parent)
    {
        try {
            $get = $this->repository->getFlowByParent($parent);

            if (!$get) {
                log_message('error', '[DocumentFlowServices::getDataByParent] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->error()['message']]);
                throw new \Exception('Transaction error occured', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $get;
        } catch (\Exception $e) {
            log_message('error', '[DocumentFlowServices::getDataByParent] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }
}
