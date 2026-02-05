<?php

namespace App\Validation\Employee;

class EducationValidation
{
    public static $save = [
        'data_token' => [
            'label' => 'Employee token',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee token is required'
            ]
        ],
        'data_degree.*' => [
            'label' => 'Degree',
            'rules' => 'required',
            'errors' => [
                'required' => 'Degree is required'
            ]
        ],
        'data_sekolah.*' => [
            'label' => 'School name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => 'School name is required',
                'min_length' => 'School name must be at least 3 characters',
                'max_length' => 'School name must not exceed 150 characters'
            ]
        ],
        'data_jurusan.*' => [
            'label' => 'Major',
            'rules' => 'required|min_length[1]|max_length[150]',
            'errors' => [
                'required' => 'Major is required',
                'min_length' => 'Major must be at least 3 characters',
                'max_length' => 'Major must not exceed 150 characters'
            ]
        ],
        'data_tahun_lulus.*' => [
            'label' => 'Year of graduation',
            'rules' => 'required|numeric|min_length[3]|max_length[6]',
            'errors' => [
                'required' => 'Year of graduation is required',
                'numeric' => 'Year of graduation must be a number',
                'min_length' => 'Year of graduation must be at least 3 characters',
                'max_length' => 'Year of graduation must not exceed 6 characters'
            ]
        ],
    ];

    public static $update = [];
}
