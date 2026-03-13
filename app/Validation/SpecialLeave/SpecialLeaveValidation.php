<?php

namespace App\Validation\SpecialLeave;

class SpecialLeaveValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Special leave name',
            'rules' => 'trim|required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} must not exceed {param} characters in length.'
            ]
        ],
        'data_hari' => [
            'label' => 'Leave granted (day)',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => '{field} is required.',
                'numeric' => '{field} must be a number.'
            ]
        ],
        'data_dokumen' => [
            'label' => 'Supporting document',
            'rules' => 'trim|required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'data_upload_dokumen' => [
            'label' => 'Upload supporting document',
            'rules' => 'trim|required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Special leave token',
            'rules' => 'trim|required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'data_name' => [
            'label' => 'Special leave name',
            'rules' => 'trim|required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} must not exceed {param} characters in length.'
            ]
        ],
        'data_hari' => [
            'label' => 'Leave granted (day)',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => '{field} is required.',
                'numeric' => '{field} must be a number.'
            ]
        ],
        'data_dokumen' => [
            'label' => 'Supporting document',
            'rules' => 'trim|required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'data_upload_dokumen' => [
            'label' => 'Upload supporting document',
            'rules' => 'trim|required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
    ];
}
