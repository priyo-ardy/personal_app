<?php

namespace App\Validation\Machine;

class MachineValidation
{
    public static $save = [
        'data_code' => [
            'label' => 'Machine number',
            'rules' => 'required|is_unique[machine.code]|min_length[2]|max_length[20]',
            'errors' => [
                'required' => '{field} is required',
                'is_unique' => '{field} must be unique',
                'min_length' => '{field} must be at least 2 characters',
                'max_length' => '{field} must be at most 20 characters'
            ]
        ],
        'data_name' => [
            'label' => 'Machine name',
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least 2 characters',
                'max_length' => '{field} must be at most 150 characters'
            ]
        ],
        'data_workshop' => [
            'label' => 'Workshop',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required'
            ]
        ]
    ];


    public static $update = [
        'data_token' => [
            'label' => 'Machine token',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required'
            ]
        ],
        'data_code' => [
            'label' => 'Machine number',
            'rules' => 'required|min_length[2]|max_length[20]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least 2 characters',
                'max_length' => '{field} must be at most 20 characters'
            ]
        ],
        'data_name' => [
            'label' => 'Machine name',
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least 2 characters',
                'max_length' => '{field} must be at most 150 characters'
            ]
        ],
        'data_workshop' => [
            'label' => 'Workshop',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required'
            ]
        ]
    ];
}
