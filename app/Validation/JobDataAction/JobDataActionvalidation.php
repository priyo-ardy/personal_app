<?php


namespace App\Validation\JobDataAction;


class JobDataActionvalidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Job data action name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} cannot exceed {param} characters in length.'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Job data action token',
            'rules' => 'required',
            'errors' => ['required' => '{field} is required.']
        ],
        'data_name' => [
            'label' => 'Job data action name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} cannot exceed {param} characters in length.'
            ]
        ]
    ];
}
