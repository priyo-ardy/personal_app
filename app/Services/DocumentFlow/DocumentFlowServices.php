<?php

namespace App\Services\DocumentFlow;

use App\Validation\DocumentFlow\DocumentFlowValidation;
use App\Repositories\DocumentFlow\DocumentFlowRepository;
// use App\Models\AppSetup\DocumentFlow\DocumentFlowModel;
// use App\Repositories\DataTableRepository;
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
    protected $flow;

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

    public function getDocumentFlow()
    {
        try {
            $flows = $this->repository->getFlowData();

            $nodes = [];
            $edges = [];
            $recordedNodes = [];

            foreach ($flows as $row) {
                if (empty($row->parent_id)) {
                    if (!in_array($row->child_id, $recordedNodes)) {
                        $nodes[] = [
                            'id' => $row->child_id,
                            'label' => $row->child_name,
                            'color' => '#28a745',
                            'font' => ['color' => '#fff']
                        ];

                        $recordedNodes[] = $row->child_id;
                    }
                } else {
                    if (!in_array($row->parent_id, $recordedNodes)) {
                        $nodes[] = [
                            'id' => $row->parent_id,
                            'label' => $row->parent_name,
                        ];

                        $recordedNodes[] = $row->parent_id;
                    }

                    if (!in_array($row->child_id, $recordedNodes)) {
                        $nodes[] = [
                            'id' => $row->child_id,
                            'label' => $row->child_name,
                        ];

                        $recordedNodes[] = $row->child_id;
                    }

                    $edges[] = [
                        'from' => $row->parent_id,
                        'to' => $row->child_id
                    ];
                }
            }

            $data = [
                'nodes' => json_encode($nodes),
                'edges' => json_encode($edges)
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[DocumentFlowServices::getDocumentFlow] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }
}
