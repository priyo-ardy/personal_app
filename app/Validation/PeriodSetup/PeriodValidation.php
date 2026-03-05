<?php

namespace App\Validation\PeriodSetup;

class PeriodValidation
{
    public static $save = [
        'data_user_name' => [
            'label' => 'User name',
            'rules' => 'required',
            'errors' => ['required' => '{field} is required.'],
        ],
        'data_tgl_awal' => [
            'label' => 'Start date',
            'rules' => 'required|valid_date',
            'errors' => ['required' => '{field} is required.', 'valid_date' => 'Invalid date format.'],
        ],
        'data_tgl_akhir' => [
            'label' => 'End date',
            'rules' => 'required|valid_date',
            'errors' => ['required' => '{field} is required.', 'valid_date' => 'Invalid date format.'],
        ],
    ];

    public static $update = [
        'data_user_name' => [
            'label' => 'User name',
            'rules' => 'required',
            'errors' => ['required' => '{field} is required.'],
        ],
        'data_tgl_awal' => [
            'label' => 'Start date',
            'rules' => 'required|valid_date',
            'errors' => ['required' => '{field} is required.', 'valid_date' => 'Invalid date format.'],
        ],
        'data_tgl_akhir' => [
            'label' => 'End date',
            'rules' => 'required|valid_date',
            'errors' => ['required' => '{field} is required.', 'valid_date' => 'Invalid date format.'],
        ],
    ];
}
