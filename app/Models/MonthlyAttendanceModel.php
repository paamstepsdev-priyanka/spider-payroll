<?php

namespace App\Models;

use CodeIgniter\Model;

class MonthlyAttendanceModel extends Model
{
    protected $table            = 'monthly_attendance';
    protected $primaryKey       = 'attendance_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'employee_id',
        'month_date',
        'total_month_days',
        'attended_days',
        'leave_days',
        'leave_not_deducted',
        'net_days_payable',
        'remarks',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get monthly attendance indexed by employee_id for a given month_date
     */
    public function getAttendanceByMonth(string $monthDate): array
    {
        $records = $this->where('month_date', $monthDate)->findAll();
        $indexed = [];
        foreach ($records as $row) {
            $indexed[$row['employee_id']] = $row;
        }
        return $indexed;
    }
}
