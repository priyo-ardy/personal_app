<?php

namespace App\Validation\JobData;

class JobDataValidation
{
    public static function checkStatusWajib($value, array $data, ?string &$error = null, string $label = 'Field'): bool
    {
        $statusWajib = ['Kontrak', 'Magang', 'PKL', 'Harian'];
        $hubungan    = $data['data_hubungan_kerja'] ?? '';

        if (in_array($hubungan, $statusWajib) && ($value === '' || $value === null)) {
            $error = "{$label} is required for non-permanent employees.";
            return false;
        }
        return true;
    }

    public static function save(): array
    {
        return [
            'data_employee' => [
                'label'  => 'Employee',
                'rules'  => 'required',
                'errors' => ['required' => '{field} is required.']
            ],
            'data_action' => [
                'label'  => 'Action',
                'rules'  => 'required',
                'errors' => ['required' => '{field} is required.']
            ],
            'data_reason' => [
                'label'  => 'Reason',
                'rules'  => 'required',
                'errors' => ['required' => '{field} is required.']
            ],
            'data_position' => [
                'label'  => 'Position',
                'rules'  => 'required',
                'errors' => ['required' => '{field} is required.']
            ],
            'data_relasi' => [
                'label'  => 'Work relationship',
                'rules'  => 'required',
                'errors' => ['required' => '{field} is required.']
            ],
            'effective_date' => [
                'label'  => 'Effective date',
                'rules'  => 'required|valid_date',
                'errors' => [
                    'required'   => '{field} is required.',
                    'valid_date' => '{field} format is invalid.'
                ]
            ],
            'data_contract' => [
                'label'  => 'Contract No.',
                'rules'  => 'required|min_length[1]|max_length[50]',
                'errors' => [
                    'required'   => '{field} is required.',
                    'min_length' => '{field} must be at least {param} characters.',
                    'max_length' => '{field} cannot exceed {param} characters.'
                ]
            ],
            // Bagian Kondisional di bawah ini menggunakan 'required_if'
            'data_durasi' => [
                'label'  => 'Contract duration',
                'rules'  => [
                    'permit_empty',
                    'numeric',
                    static fn($v, $d, &$e) => self::checkStatusWajib($v, $d, $e, 'Contract duration')
                ],
            ],
            'data_tipe_durasi' => [
                'label'  => 'Contract duration type',
                'rules'  => [
                    'permit_empty',
                    static fn($v, $d, &$e) => self::checkStatusWajib($v, $d, $e, 'Contract duration type')
                ],
            ],
            'data_akhir_kontrak' => [
                'label'  => 'End of contract',
                'rules'  => [
                    'permit_empty',
                    'valid_date',
                    static fn($v, $d, &$e) => self::checkStatusWajib($v, $d, $e, 'End of contract')
                ],
            ]
        ];
    }
    // public static $save = [
    //     'data_employee' => [
    //         'label' => 'Employee',
    //         'rules' => 'required',
    //         'errors' => [
    //             'required' => '{field} is required'
    //         ]
    //     ],
    //     'data_action' => [
    //         'label' => 'Job data action',
    //         'rules' => 'required',
    //         'errors' => [
    //             'required' => '{field} is required'
    //         ]
    //     ],
    //     'data_reason' => [
    //         'label' => 'Job data reason',
    //         'rules' => 'required',
    //         'errors' => [
    //             'required' => '{field} is required'
    //         ]
    //     ],
    //     'data_position' => [
    //         'label' => 'Registered position',
    //         'rules' => 'required',
    //         'errors' => [
    //             'required' => '{field} is required'
    //         ]
    //     ],
    //     'data_relasi' => [
    //         'label' => 'Work relationship',
    //         'rules' => 'required',
    //         'errors' => [
    //             'required' => '{field} is required'
    //         ]
    //     ],
    //     'effective_date' => [
    //         'label' => 'Effective date',
    //         'rules' => 'required|valid_date',
    //         'errors' => [
    //             'required' => '{field} is required',
    //             'valid_date' => '{field} is not valid'
    //         ]
    //     ],
    //     'data_superior' => [
    //         'label' => 'Direct superior',
    //         'rules' => 'permit_empty|required',
    //         'errors' => [
    //             'required' => '{field} is required'
    //         ]
    //     ],
    //     'data_contract' => [
    //         'label' => 'Contract No.',
    //         'rules' => 'trim|required|min_length[2]|max_length[50]',
    //         'errors' => [
    //             'required' => '{field} is required',
    //             'min_length' => '{field} must be at least {param} characters long',
    //             'max_length' => '{field} must not exceed {param} characters long'
    //         ]
    //     ],
    //     'data_durasi' => [
    //         'label' => 'Contract duration',
    //         'rules' => 'trim|required|numeric|min_length[1]|max_length[3]',
    //         'errors' => [
    //             'required' => '{field} is required',
    //             'min_length' => '{field} must be at least {param} characters long',
    //             'max_length' => '{field} must not exceed {param} characters long'
    //         ]
    //     ],
    //     'data_tipe_durasi' => [
    //         'label' => 'Contract duration type',
    //         'rules' => 'required',
    //         'errors' => [
    //             'required' => '{field} is required'
    //         ]
    //     ],
    //     'data_akhir_kontrak' => [
    //         'label' => 'Contract expired date',
    //         'rules' => 'required|valid_date',
    //         'errors' => [
    //             'required' => '{field} is required',
    //             'valid_date' => '{field} is not valid'
    //         ]
    //     ],
    // ];
}
