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
        'working_day' => [
            'label' => 'Working day',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Working day is required'
            ]
        ],
        'working_hour_type' => [
            'label' => 'Working hour type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Working hour type is required'
            ]
        ],
        'data_overday' => [
            'label' => 'Overday',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Overday is required'
            ]
        ],
        'overtime_type' => [
            'label' => 'Overtime type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Overtime type is required'
            ]
        ],
        'min_overtime' => [
            'label' => 'Min. overtime',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Min. overtime is required',
                'numeric' => 'Min. overtime must be numeric'
            ]
        ],
        'std_in' => [
            'label' => 'Std. clock in',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Std. clock in is required'
            ]
        ],
        'std_out' => [
            'label' => 'Std. clock in',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Std. clock in is required'
            ]
        ],
        'istirahat' => [
            'label' => 'Istirahat',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Istirahat is required',
                'numeric' => 'Istirahat must be numeric'
            ]
        ],
        'jam_kerja' => [
            'label' => 'Total working hour',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Total working hour is required',
                'numeric' => 'Total working hour must be numeric'
            ]
        ],
        'absent_status' => [
            'label' => 'Absent status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Absent status is required'
            ]
        ],
        'effective_date' => [
            'label' => 'Effective date',
            'rules' => 'trim|required|valid_date',
            'errors' => [
                'required' => 'Effective date is required',
                'valid_date' => 'Effective date is invalid'
            ]
        ],
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Data token',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Data token is required'
            ]
        ],
        'data_name' => [
            'label' => 'Shift name',
            'rules' => 'trim|required|min_length[2]|max_length[150]',
            'errors' => []
        ],
        'working_day' => [
            'label' => 'Working day',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Working day is required'
            ]
        ],
        'working_hour_type' => [
            'label' => 'Working hour type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Working hour type is required'
            ]
        ],
        'data_overday' => [
            'label' => 'Overday',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Overday is required'
            ]
        ],
        'overtime_type' => [
            'label' => 'Overtime type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Overtime type is required'
            ]
        ],
        'min_overtime' => [
            'label' => 'Min. overtime',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Min. overtime is required',
                'numeric' => 'Min. overtime must be numeric'
            ]
        ],
        'std_in' => [
            'label' => 'Std. clock in',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Std. clock in is required'
            ]
        ],
        'std_out' => [
            'label' => 'Std. clock in',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Std. clock in is required'
            ]
        ],
        'istirahat' => [
            'label' => 'Istirahat',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Istirahat is required',
                'numeric' => 'Istirahat must be numeric'
            ]
        ],
        'jam_kerja' => [
            'label' => 'Total working hour',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Total working hour is required',
                'numeric' => 'Total working hour must be numeric'
            ]
        ],
        'absent_status' => [
            'label' => 'Absent status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Absent status is required'
            ]
        ],
        'effective_date' => [
            'label' => 'Effective date',
            'rules' => 'trim|required|valid_date',
            'errors' => [
                'required' => 'Effective date is required',
                'valid_date' => 'Effective date is invalid'
            ]
        ],
    ];
}
