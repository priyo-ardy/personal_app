<?php

namespace App\Validation\ProcessRoute;

class ProcessRouteValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Process route name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} cannot exceed {param} characters in length.'
            ]
        ],
        'data_route' => [
            'label' => 'Process route',
            'rules' => 'required|min_length[3]|max_length[255]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} cannot exceed {param} characters in length.'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Process route token',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'data_name' => [
            'label' => 'Process route name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} cannot exceed {param} characters in length.'
            ]
        ],
        'data_route' => [
            'label' => 'Process route',
            'rules' => 'required|min_length[3]|max_length[255]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} cannot exceed {param} characters in length.'
            ]
        ]
    ];
}
