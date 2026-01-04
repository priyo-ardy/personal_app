<?php

namespace App\Validation;

class DepartmentValidation
{
    public static $save = [
        'data_name' => [
            'label' => "Department name",
            'rules' => 'trim|required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => 'The {filed} is required',
                'min_length' => 'The minimum of {label} is {param} character',
                'max_length' => 'The {filed} cannot exceed than {param} character'
            ]
        ],
        'effective_date' => [
            'label' => "Effective date",
            'rules' => 'trim|required|valid_date',
            'errors' => [
                'required' => 'The {filed} is required',
                'valid_date' => 'The {filed} is invalid date format'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Department name',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'The {filed} is required'
            ]
        ],
        'data_name' => [
            'label' => "Department name",
            'rules' => 'trim|required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => 'The {filed} is required',
                'min_length' => 'The minimum of {label} is {param} character',
                'max_length' => 'The {filed} cannot exceed than {param} character'
            ]
        ],
        'effective_date' => [
            'label' => "Effective date",
            'rules' => 'trim|required|valid_date',
            'errors' => [
                'required' => 'The {filed} is required',
                'valid_date' => 'The {filed} is invalid date format'
            ]
        ]
    ];
}
