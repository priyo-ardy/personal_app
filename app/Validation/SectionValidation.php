<?php

namespace App\Validation;

class SectionValidation
{
    public static $save = [
        'data_dept' => [
            'label' => 'Departemen',
            'rules' => 'required',
            'errors' => [
                'required' => 'The {field} is required'
            ]
        ],
        'data_name' => [
            'label' => 'Section name',
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => 'The {field} is required',
                'min_length' => 'The {field} must be at least {param} characters',
                'max_length' => 'The {field} must be at most {param} characters'
            ]
        ],
        'effective_date' => [
            'label' => 'Effective date',
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => 'The {field} is required',
                'valid_date' => 'The {field} must have a valid date format'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Section token',
            'rules' => 'required',
            'errors' => [
                'required' => 'The {field} is required'
            ]
        ],
        'data_dept' => [
            'label' => 'Departemen',
            'rules' => 'required',
            'errors' => [
                'required' => 'The {field} is required'
            ]
        ],
        'data_name' => [
            'label' => 'Section name',
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => 'The {field} is required',
                'min_length' => 'The {field} must be at least {param} characters',
                'max_length' => 'The {field} must be at most {param} characters'
            ]
        ],
        'effective_date' => [
            'label' => 'Effective date',
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => 'The {field} is required',
                'valid_date' => 'The {field} must have a valid date format'
            ]
        ]
    ];
}
