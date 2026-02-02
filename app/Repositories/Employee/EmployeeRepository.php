<?php

namespace App\Repositories\Employee;

use App\Models\MasterData\Employee\EmployeeModel;

use App\Repositories\CrudRepository;

class EmployeeRepository extends CrudRepository
{
    public function __construct()
    {
        $this->model = new EmployeeModel();
    }

    public function getNewNik(string $category)
    {
        $lastData = $this->model->where("RIGHT(nik, 1) = '$category'")
            ->orderBy('nik', 'DESC')
            ->limit(1)
            ->first();

        if ($lastData) {
            $lastCodeString = $lastData->nik;
            $lastNumber = substr($lastCodeString, strlen($category));
            $nextNumber = (int) $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $paddedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        return $category . $paddedNumber;
    }
}
