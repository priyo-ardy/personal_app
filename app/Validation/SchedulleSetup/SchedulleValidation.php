<?php

namespace App\Validation\SchedulleSetup;

class SchedulleValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Schedulle name',
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least {param} characters in length',
                'max_length' => '{field} must not exceed {param} characters in length',
            ]
        ],
        'data_hari' => [
            'label' => 'Total day period',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => '{field} is required',
                'numeric' => '{field} must be numeric',
            ]
        ],
        'effective_date' => [
            'label' => 'Effective date',
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => '{field} is required',
                'valid_date' => '{field} must be valid date',
            ]
        ],
        'shift.*' => [
            'label' => 'Shift',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required',
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Schedulle token',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required',
            ]
        ],
        'data_name' => [
            'label' => 'Schedulle name',
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least {param} characters in length',
                'max_length' => '{field} must not exceed {param} characters in length',
            ]
        ],
        'data_hari' => [
            'label' => 'Total day period',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => '{field} is required',
                'numeric' => '{field} must be numeric',
            ]
        ],
        'effective_date' => [
            'label' => 'Effective date',
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => '{field} is required',
                'valid_date' => '{field} must be valid date',
            ]
        ],
        'shift.*' => [
            'label' => 'Shift',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required',
            ]
        ]
    ];
}
