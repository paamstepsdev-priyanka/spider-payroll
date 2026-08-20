<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ContractorModel;
use App\Models\EmployeeModel;
use App\Models\MonthlyPayrollStatusModel;
use App\Models\CalculatedSalaryModel;

class DashboardController extends BaseController
{
    protected UserModel $userModel;
    protected ContractorModel $contractorModel;
    protected EmployeeModel $employeeModel;
    protected MonthlyPayrollStatusModel $payrollStatusModel;
    protected CalculatedSalaryModel $salaryModel;

    public function __construct()
    {
        $this->userModel          = new UserModel();
        $this->contractorModel    = new ContractorModel();
        $this->employeeModel      = new EmployeeModel();
        $this->payrollStatusModel = new MonthlyPayrollStatusModel();
        $this->salaryModel        = new CalculatedSalaryModel();
    }

    public function index()
    {
        // System metrics
        $totalUsers        = $this->userModel->countAllResults();
        $activeUsers       = $this->userModel->where('status', 'active')->countAllResults();

        $totalContractors  = $this->contractorModel->countAllResults();
        $activeContractors = $this->contractorModel->where('status', 'active')->countAllResults();

        $totalEmployees    = $this->employeeModel->countAllResults();
        $activeEmployees   = $this->employeeModel->where('status', 'active')->countAllResults();

        // Calculate monthly base payroll liability
        $activeEmps        = $this->employeeModel->where('status', 'active')->findAll();
        $totalMonthlyBase  = 0.0;
        foreach ($activeEmps as $emp) {
            $totalMonthlyBase += (float) ($emp['monthly_base_salary'] ?? 0);
        }

        // Current Month Payroll Status
        $currentYear  = (int) date('Y');
        $currentMonth = (int) date('n');
        $currentMonthDate = sprintf('%04d-%02d-01', $currentYear, $currentMonth);
        $currentMonthName = date('F Y');

        $statusRecord = $this->payrollStatusModel->where('month_date', $currentMonthDate)->first();
        $attStatus    = $statusRecord['attendance_status'] ?? 'draft';
        $salStatus    = $statusRecord['salary_status'] ?? 'draft';

        // Recent Contractors
        $recentContractors = $this->contractorModel
            ->orderBy('contractor_id', 'DESC')
            ->findAll(5);

        // Recent Employees
        $recentEmployees = $this->employeeModel
            ->select('employees.*, contractors.contractor_name')
            ->join('contractors', 'contractors.contractor_id = employees.contractor_id', 'left')
            ->orderBy('employees.employee_id', 'DESC')
            ->findAll(5);

        // Upcoming Birthdays (Week & Month)
        $upcomingBirthdaysWeek  = $this->getUpcomingBirthdays('week');
        $upcomingBirthdaysMonth = $this->getUpcomingBirthdays('month');

        $data = [
            'title'                  => 'Executive Dashboard',
            'breadcrumb_item'        => 'Dashboard',
            'totalUsers'             => $totalUsers,
            'activeUsers'            => $activeUsers,
            'totalContractors'       => $totalContractors,
            'activeContractors'      => $activeContractors,
            'totalEmployees'         => $totalEmployees,
            'activeEmployees'        => $activeEmployees,
            'totalMonthlyBase'       => $totalMonthlyBase,
            'currentMonthName'       => $currentMonthName,
            'currentYear'            => $currentYear,
            'currentMonth'           => $currentMonth,
            'attStatus'              => $attStatus,
            'salStatus'              => $salStatus,
            'recentContractors'      => $recentContractors,
            'recentEmployees'        => $recentEmployees,
            'upcomingBirthdaysWeek'  => $upcomingBirthdaysWeek,
            'upcomingBirthdaysMonth' => $upcomingBirthdaysMonth,
        ];

        return view('dashboard/index', $data);
    }

    /**
     * Fetch active employees with upcoming birthdays for the given period ('week' or 'month')
     */
    private function getUpcomingBirthdays(string $period = 'week'): array
    {
        $employees = $this->employeeModel
            ->select('employees.*, contractors.contractor_name')
            ->join('contractors', 'contractors.contractor_id = employees.contractor_id', 'left')
            ->where('employees.status', 'active')
            ->where('employees.date_of_birth IS NOT NULL')
            ->where('employees.date_of_birth !=', '0000-00-00')
            ->where('employees.date_of_birth !=', '')
            ->findAll();

        $todayStr    = date('Y-m-d');
        $todayTs     = strtotime($todayStr);
        $currentYear = (int) date('Y');

        // Current calendar week (Monday to Sunday)
        $w         = (int) date('w'); // 0 (Sun) to 6 (Sat)
        $dayOfWeek = ($w === 0) ? 7 : $w;
        $mondayTs  = strtotime("-" . ($dayOfWeek - 1) . " days", $todayTs);
        $sundayTs  = strtotime("+" . (7 - $dayOfWeek) . " days", $todayTs);

        $mondayThisWeek = date('Y-m-d', $mondayTs);
        $sundayThisWeek = date('Y-m-d', $sundayTs);

        $currentMonth = date('m');

        $birthdays = [];

        foreach ($employees as $emp) {
            $dob = $emp['date_of_birth'];
            if (!$dob || $dob === '0000-00-00') {
                continue;
            }

            $dobTs = strtotime($dob);
            if (!$dobTs) {
                continue;
            }

            $bMonth = date('m', $dobTs);
            $bDay   = date('d', $dobTs);
            $bYear  = (int) date('Y', $dobTs);

            // Birthday occurrence in the current year
            $bdayThisYearStr = sprintf('%04d-%02d-%02d', $currentYear, $bMonth, $bDay);
            $bdayThisYearTs  = strtotime($bdayThisYearStr);

            if ($period === 'week') {
                if ($bdayThisYearStr < $mondayThisWeek || $bdayThisYearStr > $sundayThisWeek) {
                    continue;
                }
            } elseif ($period === 'month') {
                if ($bMonth !== $currentMonth) {
                    continue;
                }
            }

            $ageTurning = $currentYear - $bYear;
            $diffDays   = (int) round(($bdayThisYearTs - $todayTs) / 86400);

            if ($diffDays === 0) {
                $badgeText  = 'Today! 🎉';
                $badgeClass = 'bg-danger text-white';
            } elseif ($diffDays === 1) {
                $badgeText  = 'Tomorrow';
                $badgeClass = 'bg-warning text-dark';
            } elseif ($diffDays > 1) {
                $badgeText  = "In {$diffDays} days";
                $badgeClass = 'bg-success text-white';
            } elseif ($diffDays === -1) {
                $badgeText  = 'Yesterday';
                $badgeClass = 'bg-secondary text-white';
            } else {
                $badgeText  = date('d M', $dobTs);
                $badgeClass = 'bg-light text-dark border';
            }

            $emp['bday_formatted'] = date('d M', $dobTs);
            $emp['bday_full_date'] = $bdayThisYearStr;
            $emp['age_turning']   = $ageTurning;
            $emp['diff_days']     = $diffDays;
            $emp['badge_text']    = $badgeText;
            $emp['badge_class']   = $badgeClass;

            $birthdays[] = $emp;
        }

        usort($birthdays, function ($a, $b) {
            return strcmp($a['bday_full_date'], $b['bday_full_date']);
        });

        return $birthdays;
    }
}
