<?php

namespace App\Validation\EducationDegree;

class EducationDegreeValidation
{
    public static $save = [
        'data_name' => [
            'label' => "Education degree name",
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => "Education degree name is required",
                'min_length' => "Education degree name must be at least 2 characters",
                'max_length' => "Education degree name must be less than 150 characters"
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => "Education degree token",
            'rules' => 'required',
            'errors' => [
                'required' => "Education degree token is required"
            ]
        ],
        'data_name' => [
            'label' => "Education degree name",
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => "Education degree name is required",
                'min_length' => "Education degree name must be at least 2 characters",
                'max_length' => "Education degree name must be less than 150 characters"
            ]
        ]
    ];
}
