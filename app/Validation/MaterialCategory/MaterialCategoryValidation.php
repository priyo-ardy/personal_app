<?php

namespace App\Validation\MaterialCategory;


class MaterialCategoryValidation
{
    public static $save = [
        'data_name' => [
            'label' => "Material category name",
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least {param} characters in length',
                'max_length' => '{field} must not exceed {param} characters in length'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => "Material category token",
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required'
            ]
        ],
        'data_name' => [
            'label' => "Material category name",
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least {param} characters in length',
                'max_length' => '{field} must not exceed {param} characters in length'
            ]
        ]
    ];
}
