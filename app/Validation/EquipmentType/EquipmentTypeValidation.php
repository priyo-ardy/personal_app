<?php

namespace App\Validation\EquipmentType;

class EquipmentTypeValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Equipment type name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => 'The {field} must be at least {param} characters',
                'max_length' => 'The {field} must be at most {param} characters'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Equipment type token',
            'rules' => 'required',
            'errors' => [
                'required' => "The {field} is required"
            ]
        ],
        'data_name' => [
            'label' => 'Equipment type name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => 'The {field} must be at least {param} characters',
                'max_length' => 'The {field} must be at most {param} characters'
            ]
        ]
    ];
}
