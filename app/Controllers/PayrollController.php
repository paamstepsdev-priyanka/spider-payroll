<?php

namespace App\Controllers;

use App\Models\MonthlyPayrollStatusModel;
use App\Models\MonthlyAttendanceModel;
use App\Models\CalculatedSalaryModel;
use App\Models\EmployeeModel;
use App\Models\ContractorModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class PayrollController extends BaseController
{
    protected MonthlyPayrollStatusModel $payrollStatusModel;
    protected MonthlyAttendanceModel $attendanceModel;
    protected CalculatedSalaryModel $salaryModel;
    protected EmployeeModel $employeeModel;
    protected ContractorModel $contractorModel;

    public function __construct()
    {
        $this->payrollStatusModel = new MonthlyPayrollStatusModel();
        $this->attendanceModel    = new MonthlyAttendanceModel();
        $this->salaryModel        = new CalculatedSalaryModel();
        $this->employeeModel      = new EmployeeModel();
        $this->contractorModel    = new ContractorModel();
    }

    /**
     * Payroll Processing Status Dashboard landing page.
     */
    public function index()
    {
        $currentYear  = (int) date('Y');
        $currentMonth = (int) date('n');

        // Default FY start year (April to March)
        $defaultFyStart = ($currentMonth >= 4) ? $currentYear : ($currentYear - 1);

        $param = $this->request->getGet('fy') ?? $this->request->getGet('year');
        if ($param) {
            if (preg_match('/^(\d{4})/', (string) $param, $matches)) {
                $fyStartYear = (int) $matches[1];
            } else {
                $fyStartYear = $defaultFyStart;
            }
        } else {
            $fyStartYear = $defaultFyStart;
        }

        $fyEndYear       = $fyStartYear + 1;
        $fyLabel         = "FY {$fyStartYear}-" . substr((string) $fyEndYear, -2);
        $fyDateRangeText = "APR {$fyStartYear} - MAR {$fyEndYear}";

        $dbStatuses = $this->payrollStatusModel->getStatusesByDateRange(
            sprintf('%04d-04-01', $fyStartYear),
            sprintf('%04d-03-31', $fyEndYear)
        );

        $months                   = [];
        $attendanceCompletedCount = 0;
        $salaryProcessedCount     = 0;
        $inProgressCount          = 0;
        $payslipsGeneratedCount   = 0;

        $alertMonth               = null;
        $monthsBehind             = 0;

        for ($i = 0; $i < 12; $i++) {
            if ($i < 9) {
                $mNum  = $i + 4; // Apr=4 .. Dec=12
                $mYear = $fyStartYear;
            } else {
                $mNum  = $i - 8; // Jan=1 .. Mar=3
                $mYear = $fyEndYear;
            }

            $monthDate      = sprintf('%04d-%02d-01', $mYear, $mNum);
            $monthTimestamp = mktime(0, 0, 0, $mNum, 1, $mYear);
            $fullMonthName  = date('F', $monthTimestamp);
            $shortMonthName = strtoupper(date('M', $monthTimestamp));
            $opensTextDate  = '1 ' . date('M Y', $monthTimestamp);

            $isCurrent = ($mYear === $currentYear && $mNum === $currentMonth);
            $isFuture  = false;
            $isPast    = false;

            if ($mYear > $currentYear) {
                $isFuture = true;
            } elseif ($mYear < $currentYear) {
                $isPast = true;
            } else {
                if ($mNum > $currentMonth) {
                    $isFuture = true;
                } elseif ($mNum < $currentMonth) {
                    $isPast = true;
                }
            }

            $record     = $dbStatuses[$monthDate] ?? null;
            $attStatus  = $record['attendance_status']   ?? 'draft';
            $salStatus  = $record['salary_status']       ?? 'draft';
            $disbStatus = $record['disbursement_status'] ?? 'pending';

            if (in_array($attStatus, ['freeze', 'frozen'])) {
                $attendanceCompletedCount++;
            }

            if (in_array($salStatus, ['freeze', 'frozen'])) {
                $salaryProcessedCount++;
                $payslipsGeneratedCount++;
            }

            $isFullyFrozen = (in_array($attStatus, ['freeze', 'frozen']) && in_array($salStatus, ['freeze', 'frozen']));

            if (!$isFullyFrozen && ($attStatus === 'in_progress' || $attStatus === 'draft' || $salStatus === 'draft' || $isCurrent)) {
                $inProgressCount++;
            }

            if ($isFuture) {
                $statusCategory = 'locked';
            } elseif ($isFullyFrozen) {
                $statusCategory = 'closed';
            } elseif ($isPast && !$isFullyFrozen) {
                $monthsBehind++;
                if ($alertMonth === null) {
                    $statusCategory = 'action_needed';
                    $alertMonth = [
                        'name'        => $fullMonthName,
                        'year'        => $mYear,
                        'number'      => $mNum,
                        'short_name'  => $shortMonthName,
                        'url'         => site_url("payroll/month/{$mYear}/{$mNum}"),
                        'att_status'  => $attStatus,
                        'sal_status'  => $salStatus,
                    ];
                } else {
                    $statusCategory = 'incomplete';
                }
            } elseif ($isCurrent) {
                $statusCategory = 'current';
                if ($alertMonth === null && !$isFullyFrozen) {
                    $alertMonth = [
                        'name'        => $fullMonthName,
                        'year'        => $mYear,
                        'number'      => $mNum,
                        'short_name'  => $shortMonthName,
                        'url'         => site_url("payroll/month/{$mYear}/{$mNum}"),
                        'att_status'  => $attStatus,
                        'sal_status'  => $salStatus,
                    ];
                }
            } else {
                $statusCategory = 'incomplete';
            }

            $months[] = [
                'number'              => $mNum,
                'year'                => $mYear,
                'name'                => $fullMonthName,
                'short_name'          => $shortMonthName,
                'display_title'       => strtoupper(date('M Y', $monthTimestamp)),
                'month_date'          => $monthDate,
                'is_locked'           => $isFuture,
                'is_current'          => $isCurrent,
                'is_past'             => $isPast,
                'status_category'     => $statusCategory,
                'attendance_status'   => $attStatus,
                'salary_status'       => $salStatus,
                'disbursement_status' => $disbStatus,
                'opens_text'          => 'Opens ' . $opensTextDate,
                'url'                 => site_url("payroll/month/{$mYear}/{$mNum}"),
            ];
        }

        $availableFys = [];
        $minStart = min($defaultFyStart - 2, $fyStartYear - 1);
        $maxStart = max($defaultFyStart + 2, $fyStartYear + 1);
        for ($y = $minStart; $y <= $maxStart; $y++) {
            $yEnd = $y + 1;
            $availableFys[$y] = "FY {$y}-" . substr((string) $yEnd, -2);
        }


        $data = [
            'title'                      => 'Payroll Processing Status',
            'breadcrumb_item'            => 'Payroll',
            'fyStartYear'                => $fyStartYear,
            'fyEndYear'                  => $fyEndYear,
            'fyLabel'                    => $fyLabel,
            'fyDateRangeText'            => $fyDateRangeText,
            'availableFys'               => $availableFys,
            'prevFyStart'                => $fyStartYear - 1,
            'nextFyStart'                => $fyStartYear + 1,
            'currentYear'                => $currentYear,
            'currentMonth'               => $currentMonth,
            'months'                     => $months,
            'attendanceCompletedCount'   => $attendanceCompletedCount,
            'salaryProcessedCount'       => $salaryProcessedCount,
            'inProgressCount'            => $inProgressCount,
            'payslipsGeneratedCount'     => $payslipsGeneratedCount,
            'alertMonth'                 => $alertMonth,
            'monthsBehind'               => $monthsBehind,
        ];

        return view('payroll/dashboard', $data);
    }

    /**
     * Monthly Payroll Processing Page (3-Step Workflow).
     */
    public function month($year = null, $month = null)
    {
        $year  = (int) $year;
        $month = (int) $month;

        if ($year < 2000 || $month < 1 || $month > 12) {
            throw PageNotFoundException::forPageNotFound('Invalid year or month requested.');
        }

        $currentYear  = (int) date('Y');
        $currentMonth = (int) date('n');

        // Prevent accessing future locked months
        if ($year > $currentYear || ($year === $currentYear && $month > $currentMonth)) {
            return redirect()->to(site_url('payroll?fy=' . $year))
                ->with('error', 'The selected payroll month is locked.');
        }

        $monthDate       = sprintf('%04d-%02d-01', $year, $month);
        $daysInMonth     = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $monthTimestamp  = mktime(0, 0, 0, $month, 1, $year);
        $monthName       = date('F Y', $monthTimestamp);
        $shortMonthName  = strtoupper(date('M', $monthTimestamp));

        // Get or initialize month status
        $statusRecord = $this->payrollStatusModel->getOrCreateStatus($monthDate);

        // Fetch contractors list
        $contractors = $this->contractorModel->where('status', 'active')->orderBy('contractor_name', 'ASC')->findAll();

        // Fetch employees with contractor details
        $employees = $this->employeeModel
            ->select('employees.*, contractors.contractor_name')
            ->join('contractors', 'contractors.contractor_id = employees.contractor_id', 'left')
            ->where('employees.status', 'active')
            ->orderBy('employees.employee_id', 'ASC')
            ->findAll();

        // Fetch existing attendance records
        $dbAttendance = $this->attendanceModel->getAttendanceByMonth($monthDate);

        // Fetch existing calculated salary records
        $dbSalary = $this->salaryModel->getCalculatedSalaryByMonth($monthDate);

        $attendanceRows = [];
        $filledCount    = 0;

        foreach ($employees as $emp) {
            $empId = $emp['employee_id'];
            $att   = $dbAttendance[$empId] ?? null;

            if ($att) {
                $attendedDays     = (isset($att['attended_days']) && $att['attended_days'] !== null && $att['attended_days'] !== '') ? (float) $att['attended_days'] : '';
                $leaveDays        = (float) ($att['leave_days'] ?? 0);
                $leaveNotDeducted = (float) ($att['leave_not_deducted'] ?? 0);
                $netDays          = (float) ($att['net_days_payable'] ?? 0);
                if ($attendedDays !== '') {
                    $filledCount++;
                }
            } else {
                $attendedDays     = ''; // Default blank for manual admin entry
                $leaveDays        = 0.0;
                $leaveNotDeducted = 0.0;
                $netDays          = 0.0;
            }

            $baseSalary = (float) $emp['monthly_base_salary'];
            $calcSalary = round(($baseSalary / $daysInMonth) * $netDays, 2);

            $salRecord  = $dbSalary[$empId] ?? null;
            if ($salRecord && isset($salRecord['calculated_salary'])) {
                $calcSalary = (float) $salRecord['calculated_salary'];
            }

            $attendanceRows[] = [
                'employee_id'         => $empId,
                'employee_name'       => $emp['employee_name'],
                'biometric_code'      => $emp['biometric_code'],
                'designation'         => $emp['designation'] ?? 'Employee',
                'contractor_id'       => $emp['contractor_id'],
                'contractor_name'     => $emp['contractor_name'] ?? 'Direct / No Contractor',
                'monthly_base_salary' => $baseSalary,
                'total_month_days'    => $daysInMonth,
                'attended_days'       => $attendedDays,
                'leave_days'          => $leaveDays,
                'leave_not_deducted'  => $leaveNotDeducted,
                'net_days_payable'    => $netDays,
                'calculated_salary'   => $calcSalary,
                'remarks'             => $salRecord['remarks'] ?? ($att['remarks'] ?? ''),
                'status'              => $emp['status'],
            ];
        }

        $totalEmployees = count($employees);
        $pendingCount   = max(0, $totalEmployees - $filledCount);

        // Step 2 Summaries
        $totalPayrollBudget       = 0.0;
        $totalFrozenAttendanceDays = 0.0;
        $totalNetPayableDays      = 0.0;

        foreach ($attendanceRows as $row) {
            $totalPayrollBudget        += $row['calculated_salary'];
            $totalFrozenAttendanceDays += $row['net_days_payable'];
            $totalNetPayableDays       += $row['net_days_payable'];
        }

        // Step 3 Contractor Payout Summaries
        $contractorPayouts = [];
        foreach ($contractors as $c) {
            $cId = $c['contractor_id'];
            $cEmps = array_filter($attendanceRows, fn($r) => (int)$r['contractor_id'] === (int)$cId);
            $empCount = count($cEmps);
            $payoutAmount = array_sum(array_column($cEmps, 'calculated_salary'));

            $contractorPayouts[] = [
                'contractor_id'          => $cId,
                'contractor_name'        => $c['contractor_name'],
                'phone_number'           => $c['phone_number'],
                'bank_account_number'    => $c['bank_account_number'],
                'ifsc_code'              => $c['ifsc_code'],
                'bank_name'              => $c['bank_name'] ?? 'N/A',
                'associated_employees'   => $empCount,
                'total_payout'           => $payoutAmount,
            ];
        }

        // Direct / No Contractor employees summary row
        $directEmps = array_filter($attendanceRows, fn($r) => empty($r['contractor_id']));
        if (!empty($directEmps)) {
            $contractorPayouts[] = [
                'contractor_id'          => 0,
                'contractor_name'        => 'Direct / No Contractor',
                'phone_number'           => '-',
                'bank_account_number'    => '-',
                'ifsc_code'              => '-',
                'bank_name'              => '-',
                'associated_employees'   => count($directEmps),
                'total_payout'           => array_sum(array_column($directEmps, 'calculated_salary')),
            ];
        }

        $data = [
            'title'                      => "Payroll Processing — {$monthName}",
            'subtitle'                   => "Monthly Payroll Processing · {$monthName}",
            'breadcrumb_item'            => "Payroll ({$shortMonthName} {$year})",
            'year'                       => $year,
            'month'                      => $month,
            'monthDate'                  => $monthDate,
            'monthName'                  => $monthName,
            'daysInMonth'                => $daysInMonth,
            'statusRecord'               => $statusRecord,
            'contractors'                => $contractors,
            'attendanceRows'             => $attendanceRows,
            'totalEmployees'             => $totalEmployees,
            'filledCount'                => $filledCount,
            'pendingCount'               => $pendingCount,
            'totalPayrollBudget'         => $totalPayrollBudget,
            'totalFrozenAttendanceDays'  => $totalFrozenAttendanceDays,
            'totalNetPayableDays'        => $totalNetPayableDays,
            'contractorPayouts'          => $contractorPayouts,
        ];

        return view('payroll/month_process', $data);
    }

    /**
     * AJAX: Save draft attendance inputs.
     */
    public function saveAttendance()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $monthDate = $this->request->getPost('month_date');
        $rows      = $this->request->getPost('attendance');

        if (!$monthDate || !is_array($rows)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid or missing data.']);
        }

        $status = $this->payrollStatusModel->getOrCreateStatus($monthDate);
        if (in_array($status['attendance_status'], ['freeze', 'frozen'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Attendance for this month is frozen/locked and cannot be edited.',
            ]);
        }

        foreach ($rows as $empId => $data) {
            $totalDays    = (float) ($data['total_month_days'] ?? 30);
            $attendedDays = isset($data['attended_days']) ? (float) $data['attended_days'] : (isset($data['leave_days']) ? max(0, $totalDays - (float)$data['leave_days']) : $totalDays);
            $attendedDays = max(0, min($totalDays, $attendedDays));
            $leaveDays    = max(0, $totalDays - $attendedDays);
            $netDays      = $attendedDays;

            $existing = $this->attendanceModel
                ->where('employee_id', $empId)
                ->where('month_date', $monthDate)
                ->first();

            $recordData = [
                'employee_id'        => $empId,
                'month_date'         => $monthDate,
                'total_month_days'   => $totalDays,
                'attended_days'      => $attendedDays,
                'leave_days'         => $leaveDays,
                'leave_not_deducted' => 0,
                'net_days_payable'   => $netDays,
                'remarks'            => $data['remarks'] ?? null,
            ];

            if ($existing) {
                $this->attendanceModel->update($existing['attendance_id'], $recordData);
            } else {
                $this->attendanceModel->insert($recordData);
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Draft attendance saved successfully.',
        ]);
    }

    /**
     * AJAX: Quick fill attendance for all or contractor-filtered employees.
     */
    public function quickFillAttendance()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $monthDate    = $this->request->getPost('month_date');
        $contractorId = $this->request->getPost('contractor_id');
        $daysInMonth  = (int) $this->request->getPost('days_in_month');

        if (!$monthDate || !$daysInMonth) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid parameters.']);
        }

        $status = $this->payrollStatusModel->getOrCreateStatus($monthDate);
        if (in_array($status['attendance_status'], ['freeze', 'frozen'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Attendance for this month is frozen/locked and cannot be edited.',
            ]);
        }

        $builder = $this->employeeModel->where('status', 'active');
        if (!empty($contractorId)) {
            $builder->where('contractor_id', $contractorId);
        }
        $employees = $builder->findAll();

        foreach ($employees as $emp) {
            $empId = $emp['employee_id'];
            $existing = $this->attendanceModel
                ->where('employee_id', $empId)
                ->where('month_date', $monthDate)
                ->first();

            $recordData = [
                'employee_id'        => $empId,
                'month_date'         => $monthDate,
                'total_month_days'   => $daysInMonth,
                'attended_days'      => $daysInMonth,
                'leave_days'         => 0,
                'leave_not_deducted' => 0,
                'net_days_payable'   => $daysInMonth,
            ];

            if ($existing) {
                $this->attendanceModel->update($existing['attendance_id'], $recordData);
            } else {
                $this->attendanceModel->insert($recordData);
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Quick Fill completed for ' . count($employees) . ' employees.',
        ]);
    }

    /**
     * AJAX: Freeze and complete attendance.
     */
    public function freezeAttendance()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $monthDate = $this->request->getPost('month_date');
        if (!$monthDate) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing month date parameter.']);
        }

        $status = $this->payrollStatusModel->getOrCreateStatus($monthDate);
        if (in_array($status['attendance_status'], ['freeze', 'frozen'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Attendance is already completed & frozen.']);
        }

        // Freeze attendance status
        $this->payrollStatusModel->update($status['id'], [
            'attendance_status'    => 'freeze',
            'attendance_frozen_at' => date('Y-m-d H:i:s'),
            'salary_status'        => 'draft',
        ]);

        // Auto calculate and seed default calculated_salary records if not already populated
        $monthTimestamp = strtotime($monthDate);
        $monthNum       = (int) date('n', $monthTimestamp);
        $yearNum        = (int) date('Y', $monthTimestamp);
        $daysInMonth    = cal_days_in_month(CAL_GREGORIAN, $monthNum, $yearNum);

        $employees    = $this->employeeModel->where('status', 'active')->findAll();
        $dbAttendance = $this->attendanceModel->getAttendanceByMonth($monthDate);

        foreach ($employees as $emp) {
            $empId      = $emp['employee_id'];
            $cId        = $emp['contractor_id'] ?? 0;
            $att        = $dbAttendance[$empId] ?? null;
            $netDays    = $att ? (float) $att['net_days_payable'] : (float) $daysInMonth;
            $baseSalary = (float) $emp['monthly_base_salary'];
            $calcSalary = round(($baseSalary / $daysInMonth) * $netDays, 2);

            $existingSalary = $this->salaryModel
                ->where('employee_id', $empId)
                ->where('month_date', $monthDate)
                ->first();

            $salaryData = [
                'employee_id'         => $empId,
                'contractor_id'       => $cId,
                'month_date'          => $monthDate,
                'monthly_base_salary' => $baseSalary,
                'net_days_payable'    => $netDays,
                'calculated_salary'   => $calcSalary,
                'remarks'             => $att['remarks'] ?? null,
            ];

            if ($existingSalary) {
                $this->salaryModel->update($existingSalary['id'], $salaryData);
            } else {
                $this->salaryModel->insert($salaryData);
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Attendance has been frozen & completed! Step 2 Salary Computation is now unlocked.',
        ]);
    }

    /**
     * AJAX: Save draft salary computations.
     */
    public function saveSalary()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $monthDate = $this->request->getPost('month_date');
        $rows      = $this->request->getPost('salaries');

        if (!$monthDate || !is_array($rows)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid parameters.']);
        }

        $status = $this->payrollStatusModel->getOrCreateStatus($monthDate);

        if (!in_array($status['attendance_status'], ['freeze', 'frozen'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot modify salary before Attendance is frozen.']);
        }

        if (in_array($status['salary_status'], ['freeze', 'frozen'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Salary is frozen/approved and cannot be edited.']);
        }

        foreach ($rows as $empId => $data) {
            $calcSalary = round((float) ($data['calculated_salary'] ?? 0), 2);
            $existing = $this->salaryModel
                ->where('employee_id', $empId)
                ->where('month_date', $monthDate)
                ->first();

            if ($existing) {
                $this->salaryModel->update($existing['id'], [
                    'calculated_salary' => $calcSalary,
                    'remarks'           => $data['remarks'] ?? null,
                ]);
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Draft salary computation saved successfully.',
        ]);
    }

    /**
     * AJAX: Freeze and approve salary.
     */
    public function approveSalary()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $monthDate = $this->request->getPost('month_date');
        if (!$monthDate) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing month date parameter.']);
        }

        $status = $this->payrollStatusModel->getOrCreateStatus($monthDate);

        if (!in_array($status['attendance_status'], ['freeze', 'frozen'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Step 1 Attendance must be frozen before approving salary.']);
        }

        if (in_array($status['salary_status'], ['freeze', 'frozen'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Salary is already frozen & approved.']);
        }

        // Freeze salary status, but keep disbursement_status as 'pending' until actual disbursement
        $this->payrollStatusModel->update($status['id'], [
            'salary_status'    => 'freeze',
            'salary_frozen_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Salary computation has been frozen & approved! Step 3 Payslip & NEFT Export is now unlocked.',
        ]);
    }

    /**
     * Download NEFT Excel Sheet (CSV format).
     */
    public function exportNeft($year = null, $month = null)
    {
        $year  = (int) $year;
        $month = (int) $month;
        $monthDate = sprintf('%04d-%02d-01', $year, $month);

        $status = $this->payrollStatusModel->where('month_date', $monthDate)->first();
        if (!$status || !in_array($status['salary_status'], ['freeze', 'frozen', 'approved', 'completed'])) {
            return redirect()->to(site_url("payroll/month/{$year}/{$month}"))
                ->with('error', 'NEFT Export requires Step 2 Salary Computation to be approved first.');
        }

        $contractorId = $this->request->getGet('contractor_id');

        $query = $this->salaryModel
            ->select('calculated_salary.*, employees.employee_name, employees.bank_account_number, employees.ifsc_code, employees.bank_name, contractors.contractor_name')
            ->join('employees', 'employees.employee_id = calculated_salary.employee_id')
            ->join('contractors', 'contractors.contractor_id = calculated_salary.contractor_id', 'left')
            ->where('calculated_salary.month_date', $monthDate);

        if ($contractorId !== null && $contractorId !== '') {
            if ((int)$contractorId === 0) {
                $query->where('(calculated_salary.contractor_id IS NULL OR calculated_salary.contractor_id = 0)');
            } else {
                $query->where('calculated_salary.contractor_id', (int)$contractorId);
            }
        }

        $salaries = $query->findAll();

        $contractorSuffix = ($contractorId !== null && $contractorId !== '') ? "_Contractor_" . $contractorId : "";
        $filename = "NEFT_Payout" . $contractorSuffix . "_" . date('M_Y', strtotime($monthDate)) . ".csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Sr No', 'Contractor', 'Employee Name', 'Bank Name', 'Account Number', 'IFSC Code', 'Net Payable Days', 'Payout Amount']);

        $sr = 1;
        foreach ($salaries as $row) {
            fputcsv($output, [
                $sr++,
                $row['contractor_name'] ?? 'Direct',
                $row['employee_name'],
                $row['bank_name'] ?? 'N/A',
                $row['bank_account_number'] ?? 'N/A',
                $row['ifsc_code'] ?? 'N/A',
                $row['net_days_payable'],
                number_format((float) $row['calculated_salary'], 2, '.', ''),
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Download Salary Slips Summary (CSV format).
     */
    public function exportSlips($year = null, $month = null)
    {
        $year  = (int) $year;
        $month = (int) $month;
        $monthDate = sprintf('%04d-%02d-01', $year, $month);

        $status = $this->payrollStatusModel->where('month_date', $monthDate)->first();
        if (!$status || !in_array($status['salary_status'], ['freeze', 'frozen', 'approved', 'completed'])) {
            return redirect()->to(site_url("payroll/month/{$year}/{$month}"))
                ->with('error', 'Payslips Export requires Step 2 Salary Computation to be approved first.');
        }

        $salaries = $this->salaryModel
            ->select('calculated_salary.*, employees.employee_name, employees.designation, contractors.contractor_name')
            ->join('employees', 'employees.employee_id = calculated_salary.employee_id')
            ->join('contractors', 'contractors.contractor_id = calculated_salary.contractor_id', 'left')
            ->where('calculated_salary.month_date', $monthDate)
            ->findAll();

        $filename = "Salary_Slips_Summary_" . date('M_Y', strtotime($monthDate)) . ".csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Employee ID', 'Employee Name', 'Designation', 'Contractor', 'Base Salary', 'Net Payable Days', 'Calculated Net Salary', 'Status']);

        foreach ($salaries as $row) {
            fputcsv($output, [
                $row['employee_id'],
                $row['employee_name'],
                $row['designation'] ?? 'Staff',
                $row['contractor_name'] ?? 'Direct',
                number_format((float) $row['monthly_base_salary'], 2, '.', ''),
                $row['net_days_payable'],
                number_format((float) $row['calculated_salary'], 2, '.', ''),
                'APPROVED',
            ]);
        }

        fclose($output);
        exit;
    }
}
