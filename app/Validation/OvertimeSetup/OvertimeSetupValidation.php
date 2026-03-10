<?php

namespace App\Validation\OvertimeSetup;

class OvertimeSetupValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Overtime name',
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least 2 characters',
                'max_length' => '{field} must be at most 150 characters'
            ]
        ],
        'data_type' => [
            'label' => 'Type of Working Hour',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required'
            ]
        ],
        'data_rate' => [
            'label' => 'Rate Row',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => '{field} is required',
                'numeric' => '{field} must be numeric'
            ]
        ],
        'rate.*' => [
            'label' => 'Rate',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => '{field} is required',
                'numeric' => '{field} must be numeric'
            ]
        ]
    ];

    public static $update_rate = [
        'rate_token' => [
            'label' => 'Rate token',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required'
            ]
        ],
        'rate.*' => [
            'label' => 'Rate',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => '{field} is required',
                'numeric' => '{field} must be numeric'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Overtime token',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required'
            ]
        ],
        'data_name' => [
            'label' => 'Overtime name',
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least 2 characters',
                'max_length' => '{field} must be at most 150 characters'
            ]
        ],
        'data_type' => [
            'label' => 'Type of Working Hour',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required'
            ]
        ]
    ];
}
