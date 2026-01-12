<?php

namespace App\Validation;

class PositionValidation
{
    public static $save = [
        'data_name' => [
            'label' => "Position Name",
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} must not exceed {param} characters in length.'
            ]
        ],
        'nbhx_position' => [
            'label' => "NBHX Position Category",
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'data_dept' => [
            'label' => "Department",
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'data_section' => [
            'label' => "Section",
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'data_report_to' => [
            'label' => "Report to Position",
            'rules' => 'trim',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'data_grade' => [
            'label' => "Position Grade",
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'data_rank' => [
            'label' => "Position Rank",
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'data_status' => [
            'label' => "Position Status",
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'data_category' => [
            'label' => "Position Category",
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'nbhx_category' => [
            'label' => "NBHX Category",
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'effective_date' => [
            'label' => "Effective Date",
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => '{field} is required.',
                'valid_date' => '{field} is invalid.'
            ]
        ]
    ];
}
