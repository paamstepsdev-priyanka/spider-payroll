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

        $data = [
            'title'               => 'Executive Dashboard',
            'breadcrumb_item'     => 'Dashboard',
            'totalUsers'          => $totalUsers,
            'activeUsers'         => $activeUsers,
            'totalContractors'    => $totalContractors,
            'activeContractors'   => $activeContractors,
            'totalEmployees'      => $totalEmployees,
            'activeEmployees'     => $activeEmployees,
            'totalMonthlyBase'    => $totalMonthlyBase,
            'currentMonthName'    => $currentMonthName,
            'currentYear'         => $currentYear,
            'currentMonth'        => $currentMonth,
            'attStatus'           => $attStatus,
            'salStatus'           => $salStatus,
            'recentContractors'   => $recentContractors,
            'recentEmployees'     => $recentEmployees,
        ];

        return view('dashboard/index', $data);
    }
}
