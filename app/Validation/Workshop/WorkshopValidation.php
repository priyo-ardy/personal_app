<?php

namespace App\Validation\Workshop;

class WorkshopValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Workshop name',
            'rules' => 'required|min_length[3]|max_length[150]|is_unique[m_workshop.name]',
            'errors' => [
                'is_unique' => '{field} already exists',
                'required' => '{field} is required',
                'min_length' => '{field} must be at least {param} characters in length',
                'max_length' => '{field} must not exceed {param} characters in length'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Workhop Token',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required'
            ]
        ],
        'data_name' => [
            'label' => 'Workshop name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least {param} characters in length',
                'max_length' => '{field} must not exceed {param} characters in length'
            ]
        ]
    ];
}
