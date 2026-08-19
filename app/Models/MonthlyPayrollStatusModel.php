<?php

namespace App\Models;

use CodeIgniter\Model;

class MonthlyPayrollStatusModel extends Model
{
    protected $table            = 'monthly_payroll_status';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'month_date',
        'attendance_status',
        'attendance_frozen_at',
        'salary_status',
        'salary_frozen_at',
        'disbursement_status',
        'disbursement_completed_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Fetch status records for a given year indexed by month number (1..12).
     */
    public function getStatusesByYear(int $year): array
    {
        $startDate = sprintf('%04d-01-01', $year);
        $endDate   = sprintf('%04d-12-31', $year);

        $records = $this->where('month_date >=', $startDate)
                        ->where('month_date <=', $endDate)
                        ->findAll();

        $byMonth = [];
        foreach ($records as $row) {
            $monthNum = (int) date('n', strtotime($row['month_date']));
            $byMonth[$monthNum] = $row;
        }

        return $byMonth;
    }

    /**
     * Fetch status records for a given date range indexed by month_date string.
     */
    public function getStatusesByDateRange(string $startDate, string $endDate): array
    {
        $records = $this->where('month_date >=', $startDate)
                        ->where('month_date <=', $endDate)
                        ->findAll();

        $byDate = [];
        foreach ($records as $row) {
            $byDate[$row['month_date']] = $row;
        }

        return $byDate;
    }
}
