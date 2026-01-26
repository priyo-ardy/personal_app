<?php

namespace App\Validation\Location;

class LocationValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Location name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters.',
                'max_length' => '{field} must be less than {param} characters.'
            ]
        ],
        'data_factory' => [
            'label' => 'Factory',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Location token',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'data_name' => [
            'label' => 'Location name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters.',
                'max_length' => '{field} must be less than {param} characters.'
            ]
        ],
        'data_factory' => [
            'label' => 'Factory',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ]
    ];
}
