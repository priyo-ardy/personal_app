<?php

namespace App\Validation;


class EmployeeGradeValidation
{
    public static $save = [
        'data_name' => [
            'label' => "Employee grade name",
            'rules' => 'trim|required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => "The {field} is required",
                'min_length' => "The {field} must be at least 3 characters long",
                'max_length' => "The {field} must be less than 150 characters long"
            ]
        ],
        'effective_date' => [
            'label' => "Effective date",
            'rules' => 'trim|required|date',
            'errors' => [
                'required' => "The {field} is required",
                'date' => "The {field} must be a valid date"
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => "Employee grade token",
            'rules' => 'trim|required',
            'errors' => [
                'required' => "The {field} is required"
            ]
        ],
        'data_name' => [
            'label' => "Employee grade name",
            'rules' => 'trim|required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => "The {field} is required",
                'min_length' => "The {field} must be at least 3 characters long",
                'max_length' => "The {field} must be less than 150 characters long"
            ]
        ],
        'effective_date' => [
            'label' => "Effective date",
            'rules' => 'trim|required|date',
            'errors' => [
                'required' => "The {field} is required",
                'date' => "The {field} must be a valid date"
            ]
        ]
    ];
}
