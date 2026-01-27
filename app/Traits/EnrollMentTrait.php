<?php

namespace App\Traits;

class EnrollMentTrait
{
    public function generateShiftEnrollement(string $employee_id, string $start_date, array $shift_pattern, int $duration = 30): array
    {
        $totalPattern = count($shift_pattern);
        if ($totalPattern === 0) return [];

        $enrollments = [];
        $startDateTime = new \DateTime($start_date);

        for ($i = 0; $i < $duration; $i++) {
            $currentCountDay = $i % $totalPattern;

            $currentDate = (clone $startDateTime)->modify('+' . $i . ' day');

            $enrollments[] = [
                'employee_id' => $employee_id,
                'date' => $currentDate->format('Y-m-d'),
                'shift_code' => $shift_pattern[$currentCountDay],
                'cycle_day' => $currentCountDay + 1,
            ];
        }

        return $enrollments;
    }

    public function enrollByWorkgroup(array $workgroupId, int $duration = 30)
    {
        $db = \Config\Database::connect();
        $enrollmentModel = new \App\Models\AppSetup\Enrollment\EnrollmentModel();
        $employeeModel = new \App\Models\Employee\EmployeeModel();

        $groups = $db->table('m_workgroup')
            ->whereIn('id', $workgroupId)
            ->get()
            ->getResultObject();

        $groupPatterns = array_column($groups, 'pattern', 'id');
    }
}
