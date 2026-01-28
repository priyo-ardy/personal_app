<?php

namespace App\Validation\FamilyRelation;

class FamilyRelationValidation
{
    public static $save = [
        'data_name' => [
            'label' => "Family relation name",
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => "Family relation name is required",
                'min_length' => "Family relation name must be at least 3 characters",
                'max_length' => "Family relation name must be less than 150 characters"
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => "Family relation token",
            'rules' => 'required',
            'errors' => [
                'required' => "Family relation token is required"
            ]
        ],
        'data_name' => [
            'label' => "Family relation name",
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => "Family relation name is required",
                'min_length' => "Family relation name must be at least 3 characters",
                'max_length' => "Family relation name must be less than 150 characters"
            ]
        ]
    ];
}
