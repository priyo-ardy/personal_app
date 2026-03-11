<?php

namespace App\Validation\ShiftSetup;

class ShiftValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Shift name',
            'rules' => 'trim|required|min_length[2]|max_length[150]',
            'errors' => []
        ],
        'working_day',

    ];

    public static $update = [];
}
