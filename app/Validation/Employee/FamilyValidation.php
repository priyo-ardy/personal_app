<?php

namespace App\Validation\Employee;

class FamilyValidation
{
    public static $save = [
        'data_token' => [
            'label' => 'Employee token',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee token is required'
            ]
        ],
        'data_relation.*' => [
            'label' => 'Employee family relation',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee family relation is required'
            ]
        ],
        'data_name.*' => [
            'label' => 'Family member name',
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => 'Family member name is required',
                'min_length' => 'Family member name must be at least 2 characters',
                'max_length' => 'Family member name must not exceed 150 characters'
            ]
        ],
        'data_ocupation.*' => [
            'label' => 'Family member occupation',
            'rules' => 'required',
            'errors' => [
                'required' => 'Family member occupation is required'
            ]
        ]
    ];
}
