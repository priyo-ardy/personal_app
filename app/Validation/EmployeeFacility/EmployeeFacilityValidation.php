<?php

namespace App\Validation\EmployeeFacility;

class EmployeeFacilityValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Employee facility name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => 'Employee facility name is required',
                'min_length' => 'Employee facility name must be at least 3 characters',
                'max_length' => 'Employee facility name must not exceed 150 characters'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Employee facility token',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee facility token is required'
            ]
        ],
        'data_name' => [
            'label' => 'Employee facility name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => 'Employee facility name is required',
                'min_length' => 'Employee facility name must be at least 3 characters',
                'max_length' => 'Employee facility name must not exceed 150 characters'
            ]
        ]
    ];
}
