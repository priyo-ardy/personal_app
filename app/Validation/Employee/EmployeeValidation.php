<?php

namespace App\Validation\Employee;

class EmployeeValidation
{
    public static $save = [
        'data_category' => [
            'label' => 'Employee category',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee category is required'
            ]
        ],
        'data_nik' => [
            'label' => 'Employee code (NIK)',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee code (NIK) is required'
            ]
        ],
        'data_lokasi' => [
            'label' => 'Working location',
            'rules' => 'required',
            'errors' => [
                'required' => 'Working location is required'
            ]
        ],
        'data_name' => [
            'label' => 'Employee name',
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => 'Employee name is required',
                'min_length' => 'Employee name must be at least 2 characters',
                'max_length' => 'Employee name must not exceed 150 characters'
            ]
        ],
        'data_ktp' => [
            'label' => 'Employee personal ID number',
            'rules' => 'required|numeric|min_length[3]|max_length[16]',
            'errors' => [
                'required' => 'Employee personal ID number is required',
                'numeric' => 'Employee personal ID number must be numeric',
                'min_length' => 'Employee personal ID number must be at least 3 characters',
                'max_length' => 'Employee personal ID number must not exceed 16 characters'
            ]
        ],
        'data_tempat_lahir' => [
            'label' => 'Place of birth',
            'rules' => 'required',
            'errors' => [
                'required' => 'Place of birth is required'
            ]
        ],
        'data_tgl_lahir' => [
            'label' => 'Date of birth',
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => 'Date of birth is required',
                'valid_date' => 'Date of birth is invalid'
            ]
        ],
        'data_gender' => [
            'label' => 'Employee gender',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee gender is required'
            ]
        ],
        'data_agama' => [
            'label' => 'Employee religion',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee religion is required'
            ]
        ],
        'data_email' => [
            'label' => 'Employee personal email',
            'rules' => 'required|valid_email',
            'errors' => [
                'required' => 'Employee personal email is required',
                'valid_email' => 'Employee personal email is invalid'
            ]
        ],
        'data_tlp_1' => [
            'label' => 'Employee primary phone number',
            'rules' => 'required|numeric|min_length[5]|max_length[20]',
            'errors' => [
                'required' => 'Employee primary phone number is required',
                'numeric' => 'Employee primary phone number must be numeric',
                'min_length' => 'Employee primary phone number must be at least 5 characters',
                'max_length' => 'Employee primary phone number must not exceed 20 characters'
            ]
        ],
        'data_join' => [
            'label' => 'Employee join date',
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => 'Employee join date is required',
                'valid_date' => 'Employee join date is invalid'
            ]
        ],
        'data_alamat_ktp' => [
            'label' => 'Employee address based on ID card',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee address based on ID card is required'
            ]
        ],
        'data_provinsi_ktp' => [
            'label' => 'Employee ID card province',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee ID card province is required'
            ]
        ],
        'data_kota_ktp' => [
            'label' => 'Employee ID card city',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee ID card city is required'
            ]
        ],
        'data_alamat_sekarang' => [
            'label' => 'Employee current address',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee current address is required'
            ]
        ],
        'data_provinsi_sekarang' => [
            'label' => 'Employee current province',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee current province is required'
            ]
        ],
        'data_kota_sekarang' => [
            'label' => 'Employee current city',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee current city is required'
            ]
        ],
        'data_alamat_orang_tua' => [
            'label' => 'Employee parent address',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee parent address is required'
            ]
        ],
        'data_provinsi_orang_tua' => [
            'label' => 'Employee parent province',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee parent province is required'
            ]
        ],
        'data_kota_orang_tua' => [
            'label' => 'Employee parent city',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee parent city is required'
            ]
        ],
        'data_nama_emergency' => [
            'label' => 'Employee emergency contact name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => 'Employee emergency contact name is required',
                'min_length' => 'Employee emergency contact name must be at least 3 characters',
                'max_length' => 'Employee emergency contact name must not exceed 150 characters'
            ]
        ],
        'data_relasi_emergency' => [
            'label' => 'Employee emergency contact relationship',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee emergency contact relationship is required'
            ]
        ],
        'data_tlp_emergency' => [
            'label' => 'Employee emergency contact phone number',
            'rules' => 'required|numeric|min_length[5]|max_length[20]',
            'errors' => [
                'required' => 'Employee emergency contact phone number is required',
                'numeric' => 'Employee emergency contact phone number must be numeric',
                'min_length' => 'Employee emergency contact phone number must be at least 5 characters',
                'max_length' => 'Employee emergency contact phone number must not exceed 20 characters'
            ]
        ],
        'data_alamat_emergency' => [
            'label' => 'Employee emergency contact address',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee emergency contact address is required'
            ]
        ],
        'data_jenis_seragam' => [
            'label' => "Uniform type",
            'rules' => 'required',
            'errors' => [
                'required' => "Uniform type is required"
            ]
        ],
        'data_ukuran_seragam' => [
            'label' => "Uniform size",
            'rules' => 'required',
            'errors' => [
                'required' => "Uniform size is required"
            ]
        ],
        'data_ukuran_sepatu' => [
            'label' => "Shoes size",
            'rules' => 'required',
            'errors' => [
                'required' => "Shoes size is required"
            ]
        ],
        'data_aksesoris' => [
            'label' => "Accessories",
            'rules' => 'required',
            'errors' => [
                'required' => "Accessories is required"
            ]
        ]
    ];

    public static $update = [];
}
