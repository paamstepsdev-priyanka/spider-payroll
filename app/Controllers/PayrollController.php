<?php

namespace App\Controllers;

use App\Models\MonthlyPayrollStatusModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class PayrollController extends BaseController
{
    protected MonthlyPayrollStatusModel $payrollStatusModel;

    public function __construct()
    {
        $this->payrollStatusModel = new MonthlyPayrollStatusModel();
    }

    /**
     * Payroll Processing Status Dashboard landing page.
     */
    public function index()
    {
        $currentYear  = (int) date('Y');
        $currentMonth = (int) date('n');

        // Determine default current Financial Year start year (April to March)
        $defaultFyStart = ($currentMonth >= 4) ? $currentYear : ($currentYear - 1);

        // Read requested FY / year parameter
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

        // Fetch DB statuses for date range (Apr 1 of start year to Mar 31 of end year)
        $dbStatuses = $this->payrollStatusModel->getStatusesByDateRange(
            sprintf('%04d-04-01', $fyStartYear),
            sprintf('%04d-03-31', $fyEndYear)
        );

        // Generate 12 Financial Year months data (Apr .. Mar)
        $months                  = [];
        $attendanceCompletedCount = 0;
        $salaryProcessedCount     = 0;
        $inProgressCount          = 0;
        $payslipsGeneratedCount   = 0;

        $alertMonth               = null;
        $monthsBehind             = 0;

        for ($i = 0; $i < 12; $i++) {
            if ($i < 9) {
                $mNum  = $i + 4; // Apr=4, May=5, ..., Dec=12
                $mYear = $fyStartYear;
            } else {
                $mNum  = $i - 8; // Jan=1, Feb=2, Mar=3
                $mYear = $fyEndYear;
            }

            $monthDate      = sprintf('%04d-%02d-01', $mYear, $mNum);
            $monthTimestamp = mktime(0, 0, 0, $mNum, 1, $mYear);
            $fullMonthName  = date('F', $monthTimestamp);
            $shortMonthName = strtoupper(date('M', $monthTimestamp));
            $opensTextDate  = '1 ' . date('M Y', $monthTimestamp);

            // Determine if future month (locked) / current / past
            $isCurrent = ($mYear === $currentYear && $mNum === $currentMonth);
            $isFuture  = false;
            $isPast    = false;

            if ($mYear > $currentYear) {
                $isFuture = true;
            } elseif ($mYear < $currentYear) {
                $isPast = true;
            } else { // $mYear === $currentYear
                if ($mNum > $currentMonth) {
                    $isFuture = true;
                } elseif ($mNum < $currentMonth) {
                    $isPast = true;
                }
            }

            // DB Record or Default Status
            $record     = $dbStatuses[$monthDate] ?? null;
            $attStatus  = $record['attendance_status']   ?? 'not_started';
            $salStatus  = $record['salary_status']       ?? 'pending';
            $disbStatus = $record['disbursement_status'] ?? 'pending';

            // Status counts for Summary Cards
            if ($attStatus === 'frozen') {
                $attendanceCompletedCount++;
            }

            if ($salStatus === 'frozen') {
                $salaryProcessedCount++;
            }

            if ($disbStatus === 'completed' || $salStatus === 'frozen') {
                $payslipsGeneratedCount++;
            }

            $isFullyFrozen = ($attStatus === 'frozen' && $salStatus === 'frozen');
            $isNotStarted  = ($attStatus === 'not_started' && $salStatus === 'pending');

            if (!$isFullyFrozen && ($attStatus === 'in_progress' || $salStatus === 'in_progress' || $attStatus === 'frozen' || !$isNotStarted || $isCurrent)) {
                $inProgressCount++;
            }

            // Determine timeline status category
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

        // Available financial years for selector
        $availableFys = [];
        $minStart = min($defaultFyStart - 2, $fyStartYear - 1);
        $maxStart = max($defaultFyStart + 2, $fyStartYear + 1);
        for ($y = $minStart; $y <= $maxStart; $y++) {
            $yEnd = $y + 1;
            $availableFys[$y] = "FY {$y}-" . substr((string) $yEnd, -2);
        }

        $data = [
            'title'                      => 'Payroll Processing Status',
            'subtitle'                   => 'Track attendance recording and salary processing for each month for Nisha Roadway Pvt Ltd. (Switch company from the navbar.)',
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
     * Placeholder screen for selected month's payroll flow.
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

        // Lock check for future month
        if ($year > $currentYear || ($year === $currentYear && $month > $currentMonth)) {
            return redirect()->to(site_url('payroll?year=' . $year))
                ->with('error', 'The selected payroll month is locked.');
        }

        $monthName = date('F Y', mktime(0, 0, 0, $month, 1, $year));
        $shortName = strtoupper(date('M', mktime(0, 0, 0, $month, 1, $year)));

        $data = [
            'title'           => "Payroll — {$monthName}",
            'subtitle'        => 'Detailed 3-step payroll flow will be implemented next',
            'breadcrumb_item' => "Payroll ({$shortName} {$year})",
            'year'            => $year,
            'month'           => $month,
            'monthName'       => $monthName,
        ];

        return view('payroll/month_placeholder', $data);
    }
}
