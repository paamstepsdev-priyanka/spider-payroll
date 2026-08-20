<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
$attFrozen = in_array($statusRecord['attendance_status'] ?? '', ['freeze', 'frozen', 'locked', 'completed']);
$salFrozen = in_array($statusRecord['salary_status'] ?? '', ['freeze', 'frozen', 'locked', 'completed']);
?>

<!-- Standardized Page Header Row -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between pb-3 mb-4 border-bottom gap-3">
  <div>
    <h3 class="fw-bold text-dark mb-1 d-flex align-items-center flex-wrap gap-2">
      Monthly Payroll Processing
      <span class="badge text-white fs-6 fw-semibold rounded-pill px-3 py-1 shadow-sm" style="background-color: #047857;"><?= esc($monthName) ?></span>
    </h3>
    <p class="text-body-secondary mb-0 small">Record employee attendance, calculate monthly salary breakdowns, and export payslips or NEFT files.</p>
  </div>
  <div>
    <a href="<?= site_url('payroll?fy=' . $year) ?>" class="btn btn-dark btn-sm fw-bold d-inline-flex align-items-center gap-2 px-3 py-2 shadow-sm rounded-2">
      <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
  </div>
</div>

<!-- 3-Step Workflow Header Cards -->
<div class="row g-3 mb-4">

  <!-- STEP 1 -->
  <div class="col-12 col-md-4">
    <div class="card h-100 border-0 rounded-4 p-3 shadow-sm payroll-step-card
            <?= $attFrozen ? 'step-completed' : 'step-active' ?>">

      <div class="d-flex align-items-center gap-3">

        <div class="step-icon rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5">
          <?= $attFrozen ? '✓' : '1' ?>
        </div>

        <div>
          <div class="fw-bold text-dark mb-1">
            Step 1: Attendance Register
          </div>

          <?php if ($attFrozen): ?>
            <span class="badge rounded-pill status-badge status-success text-white">
              Completed & Frozen
            </span>
          <?php else: ?>
            <span class="badge rounded-pill status-badge status-primary text-white">
              Active / In Progress
            </span>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>


  <!-- STEP 2 -->
  <div class="col-12 col-md-4">
    <div class="card h-100 border-0 rounded-4 p-3 shadow-sm payroll-step-card
            <?= $salFrozen ? 'step-completed' : ($attFrozen ? 'step-unlocked' : 'step-locked') ?>">

      <div class="d-flex align-items-center gap-3">

        <div class="step-icon rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5">
          <?= $salFrozen ? '✓' : '2' ?>
        </div>

        <div>
          <div class="fw-bold text-dark mb-1">
            Step 2: Salary Computation
          </div>

          <?php if ($salFrozen): ?>

            <span class="badge rounded-pill status-badge status-success text-white">
              Approved & Locked
            </span>

          <?php elseif ($attFrozen): ?>

            <span class="badge rounded-pill status-badge status-warning text-white">
              Active / Unlocked
            </span>

          <?php else: ?>

            <span class="badge rounded-pill status-badge status-danger text-white">
              🔒 Locked (Complete Step 1)
            </span>

          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>


  <!-- STEP 3 -->
  <div class="col-12 col-md-4">
    <div class="card h-100 border-0 rounded-4 p-3 shadow-sm payroll-step-card
            <?= $salFrozen ? 'step-completed' : 'step-locked' ?>">

      <div class="d-flex align-items-center gap-3">

        <div class="step-icon rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5">
          <?= $salFrozen ? '✓' : '3' ?>
        </div>

        <div>
          <div class="fw-bold text-dark mb-1">
            Step 3: Payslip & NEFT Export
          </div>

          <?php if ($salFrozen): ?>

            <span class="badge rounded-pill status-badge status-success text-white">
              Generated & Ready
            </span>

          <?php else: ?>

            <span class="badge rounded-pill status-badge status-danger text-white">
              🔒 Locked (Approve Step 2)
            </span>

          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>

</div>

<!-- Workflow Nav Tabs -->
<ul class="nav nav-tabs" id="payrollWorkflowTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active fw-bold" id="tab-step1" data-coreui-toggle="tab" data-coreui-target="#step1-pane" type="button" role="tab">
      1. Attendance Register
      <?php if ($attFrozen): ?>
        <i class="bi bi-check-circle-fill text-success ms-1 fs-6"></i>
      <?php endif; ?>
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link fw-bold <?= !$attFrozen ? 'disabled' : '' ?>" id="tab-step2" data-coreui-toggle="tab" data-coreui-target="#step2-pane" type="button" role="tab">
      2. Salary Computation
      <?php if (!$attFrozen): ?>
        <i class="bi bi-lock-fill text-warning ms-1"></i>
      <?php elseif ($salFrozen): ?>
        <i class="bi bi-check-circle-fill text-success ms-1 fs-6"></i>
      <?php endif; ?>
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link fw-bold <?= !$salFrozen ? 'disabled' : '' ?>" id="tab-step3" data-coreui-toggle="tab" data-coreui-target="#step3-pane" type="button" role="tab">
      3. Payslip & NEFT Export
      <?php if (!$salFrozen): ?>
        <i class="bi bi-lock-fill text-warning ms-1"></i>
      <?php else: ?>
        <i class="bi bi-check-circle-fill text-success ms-1 fs-6"></i>
      <?php endif; ?>
    </button>
  </li>
</ul>

<!-- Tab Contents Container (Seamless Zero Gap) -->
<div class="tab-content border rounded-bottom-3 p-4 bg-white shadow-sm mb-4" id="payrollWorkflowTabsContent" style="border-color: #94a3b8 !important;">

  <!-- ========================================== -->
  <!-- STEP 1: ATTENDANCE REGISTER -->
  <!-- ========================================== -->
  <div class="tab-pane fade show active" id="step1-pane" role="tabpanel">

    <!-- Top Controls Row -->
    <div class="card border shadow-sm rounded-3 p-3 bg-white mb-4">
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <!-- Contractor Filter -->
        <?php
        $contractorEmpCounts = [];
        foreach ($attendanceRows as $r) {
            $cId = (int) ($r['contractor_id'] ?? 0);
            if ($cId > 0) {
                $contractorEmpCounts[$cId] = ($contractorEmpCounts[$cId] ?? 0) + 1;
            }
        }
        ?>
        <div class="d-flex align-items-center gap-2">
          <label for="contractorFilter" class="form-label fw-bold mb-0 text-nowrap">Filter by Contractor:</label>
          <select id="contractorFilter" class="form-select form-select-sm" style="min-width: 260px;">
            <option value="">All Contractors</option>
            <?php foreach ($contractors as $c): ?>
              <?php 
              $cId = (int) $c['contractor_id'];
              $empCount = $contractorEmpCounts[$cId] ?? 0;
              if ($empCount > 0): 
              ?>
                <option value="<?= esc($cId) ?>">
                  <?= esc($c['contractor_name']) ?> - <?= $empCount ?> <?= $empCount === 1 ? 'Employee' : 'Employees' ?>
                </option>
              <?php endif; ?>
            <?php endforeach; ?>
          </select>
          <span class="badge bg-secondary-subtle text-secondary border fw-medium px-2 py-1" id="employeeCountBadge">
            <?= esc($totalEmployees) ?> employees
          </span>
        </div>

        <!-- Attendance Progress -->
        <div class="flex-grow-1 mx-md-4" style="max-width: 380px;">
          <div class="d-flex justify-content-between small fw-semibold mb-1">
            <span class="text-body-secondary">Attendance Progress</span>
            <span id="progressText" class="text-primary fw-bold">
              <?= esc($filledCount) ?> / <?= esc($totalEmployees) ?> Filled &middot; <?= esc($pendingCount) ?> Pending
            </span>
          </div>
          <div class="progress" style="height: 8px;">
            <?php
            $pct = $totalEmployees > 0 ? round(($filledCount / $totalEmployees) * 100) : 0;
            ?>
            <div id="progressBar" class="progress-bar bg-primary" role="progressbar" style="width: <?= $pct ?>%;" aria-valuenow="<?= $pct ?>" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Attendance Action Buttons & Table Card -->
    <div class="card border shadow-sm rounded-3 bg-white p-3">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pb-3 mb-3 border-bottom gap-2">
        <div>
          <h5 class="fw-bold mb-0 text-dark">Employee Attendance Sheet</h5>
          <span class="small text-body-secondary">Total days in <?= esc($monthName) ?>: <strong><?= esc($daysInMonth) ?> Days</strong></span>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex flex-wrap gap-2">
          <?php if (!$attFrozen): ?>
            <button type="button" class="btn btn-outline-primary btn-sm rounded-2 shadow-sm" id="btnImportExcel">
              Import Attendance Excel
            </button>
            <button type="button" class="btn btn-outline-warning btn-sm rounded-2 shadow-sm fw-semibold" id="btnSaveAttendanceDraft">
              💾 Save Draft Attendance
            </button>
            <button type="button" class="btn btn-success btn-sm rounded-2 shadow-sm fw-semibold text-white" id="btnFreezeAttendance">
              🔒 Freeze & Complete Attendance
            </button>
          <?php else: ?>
            <button type="button" class="btn btn-success btn-sm rounded-2 shadow-sm fw-semibold text-white" disabled>
              🔒 Attendance Completed & Locked
            </button>
          <?php endif; ?>
        </div>
      </div>

      <!-- Employee Attendance Table -->
      <form id="attendanceForm">
        <input type="hidden" name="month_date" value="<?= esc($monthDate) ?>">
        <div class="table-responsive">
          <table class="table table-hover align-middle border mb-0" id="attendanceTable">
            <thead class="table-light small text-uppercase">
              <tr>
                <th style="width: 50px;">#</th>
                <th>Employee Name</th>
                <th>Contractor</th>
                <th>Biometric Code</th>
                <th class="text-center" style="width: 140px;">Total Month Days</th>
                <th class="text-center" style="width: 140px;">Attended Days</th>
                <th class="text-center" style="width: 140px;">Net Days Payable</th>
              </tr>
            </thead>
            <tbody>
              <?php $sr = 1; ?>
              <?php foreach ($attendanceRows as $row): ?>
                <?php
                $initial = strtoupper(substr($row['employee_name'], 0, 1));
                ?>
                <tr class="attendance-row" data-contractor-id="<?= esc($row['contractor_id']) ?>" data-biometric-code="<?= esc(strtoupper(trim($row['biometric_code'] ?? ''))) ?>">
                  <td class="fw-semibold text-body-secondary"><?= $sr++ ?></td>
                  <td>
                    <span class="fw-semibold text-dark" style="font-size: 13px;"><?= esc($row['employee_name']) ?></span>
                  </td>
                  <td>
                    <span class="contractor-pill">
                      <?= esc($row['contractor_name']) ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge text-bg-light border text-dark fw-semibold">
                      <?= esc($row['biometric_code'] ?? 'N/A') ?>
                    </span>
                  </td>
                  <td class="text-center fw-bold text-dark">
                    <?= esc($daysInMonth) ?>
                    <input type="hidden" name="attendance[<?= $row['employee_id'] ?>][total_month_days]" value="<?= $daysInMonth ?>">
                  </td>
                  <td>
                    <input type="number" step="0.5" min="0" max="<?= $daysInMonth ?>"
                      class="form-control form-control-sm text-center input-attended"
                      name="attendance[<?= $row['employee_id'] ?>][attended_days]"
                      value="<?= esc($row['attended_days']) ?>"
                      data-emp-id="<?= $row['employee_id'] ?>"
                      <?= $attFrozen ? 'readonly disabled' : '' ?>>
                  </td>
                  <td class="text-center">
                    <span class="badge text-bg-primary fs-6 px-3 py-2 net-days-display" id="net-days-<?= $row['employee_id'] ?>">
                      <?= esc($row['net_days_payable']) ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </form>
    </div>
  </div>

  <!-- ========================================== -->
  <!-- STEP 2: SALARY COMPUTATION -->
  <!-- ========================================== -->
  <!-- ========================================== -->
  <!-- STEP 2: SALARY COMPUTATION -->
  <!-- ========================================== -->

  <div class="tab-pane fade" id="step2-pane" role="tabpanel">

    <?php if (!$attFrozen): ?>

      <!-- LOCKED STATE -->
      <div class="salary-locked-card text-center">

        <div class="salary-lock-icon">
          🔒
        </div>

        <h4 class="fw-bold mb-2">
          Step 2 is Locked
        </h4>

        <p class="mb-0">
          Please complete and
          <strong>Freeze Attendance (Step 1)</strong>
          before computing salary.
        </p>

      </div>

    <?php else: ?>
      <!-- ========================================== -->
      <!-- SALARY COMPUTATION SHEET -->
      <!-- ========================================== -->

      <div class="salary-sheet-card">

        <!-- Header -->
        <div class="salary-sheet-header">

          <div class="salary-sheet-title">

            <div class="salary-sheet-icon">
              ₹
            </div>

            <div>
              <h5 class="mb-1">
                Salary Computation Sheet
              </h5>

              <div class="salary-formula">
                Formula:
                <strong>
                  (Monthly Base Salary /
                  <?= esc($daysInMonth) ?>)
                  × Net Days Payable
                </strong>
              </div>
            </div>

          </div>


          <!-- Actions -->
          <div class="salary-actions">
            <?php if (!$salFrozen): ?>
              <button
                type="button"
                class="btn salary-btn salary-btn-draft"
                id="btnSaveSalaryDraft">
                <span>💾</span>
                Save Draft Salary
              </button>

              <button
                type="button"
                class="btn salary-btn salary-btn-approve"
                id="btnApproveSalary">
                <span>🔒</span>
                Freeze & Approve Salary
              </button>
            <?php else: ?>
              <button
                type="button"
                class="btn salary-btn salary-btn-approve"
                disabled>
                <span>🔒</span>
                Salary Approved & Locked
              </button>
            <?php endif; ?>
          </div>

        </div>


        <!-- Formula Info -->
        <div class="salary-info-bar">

          <div>
            <span class="info-dot"></span>
            Salary is calculated automatically based on frozen attendance.
          </div>

          <div class="formula-chip">
            Base Salary ÷ <?= esc($daysInMonth) ?> × Net Days
          </div>

        </div>


        <!-- Form -->
        <form id="salaryForm">

          <input
            type="hidden"
            name="month_date"
            value="<?= esc($monthDate) ?>">

          <div class="salary-table-wrapper">

            <table class="table salary-table align-middle mb-0">

              <thead>

                <tr>

                  <th class="col-number">#</th>

                  <th>
                    Employee
                  </th>

                  <th>
                    Contractor
                  </th>

                  <th class="text-end">
                    Monthly Base Salary
                  </th>

                  <th class="text-center">
                    Net Days
                  </th>

                  <th class="text-end">
                    Calculated Salary
                  </th>

                  <th>
                    Remarks
                  </th>

                </tr>

              </thead>


              <tbody>

                <?php $sSr = 1; ?>

                <?php foreach ($attendanceRows as $row): ?>

                  <tr>

                    <!-- Number -->
                    <td>
                      <span class="salary-row-number">
                        <?= $sSr++ ?>
                      </span>
                    </td>


                    <!-- Employee -->
                    <td>
                      <div class="employee-name fw-semibold text-dark" style="font-size: 13px;">
                        <?= esc($row['employee_name']) ?>
                      </div>
                    </td>


                    <!-- Contractor -->
                    <td>

                      <span class="contractor-pill">
                        <?= esc($row['contractor_name']) ?>
                      </span>

                    </td>


                    <!-- Base Salary -->
                    <td class="text-end">

                      <span class="base-salary">
                        ₹ <?= number_format($row['monthly_base_salary'], 2) ?>
                      </span>

                    </td>


                    <!-- Net Days -->
                    <td class="text-center">

                      <span class="net-days-pill">
                        <?= esc($row['net_days_payable']) ?>
                      </span>

                    </td>


                    <!-- Calculated Salary -->
                    <td class="text-end">

                      <div class="salary-input-wrapper">

                        <span>₹</span>

                        <input
                          type="number"
                          step="0.01"
                          class="salary-amount-input"
                          name="salaries[<?= $row['employee_id'] ?>][calculated_salary]"
                          value="<?= number_format($row['calculated_salary'], 2, '.', '') ?>"
                          <?= $salFrozen ? 'readonly disabled' : '' ?>>

                      </div>

                    </td>


                    <!-- Remarks -->
                    <td>

                      <input
                        type="text"
                        class="remarks-input"
                        name="salaries[<?= $row['employee_id'] ?>][remarks]"
                        value="<?= esc($row['remarks']) ?>"
                        <?= $salFrozen ? 'readonly disabled' : '' ?>
                        placeholder="Optional note">

                    </td>

                  </tr>

                <?php endforeach; ?>

              </tbody>

            </table>

          </div>

        </form>

      </div>

    <?php endif; ?>

  </div>

  <!-- ========================================== -->
  <!-- STEP 3: PAYSLIP & NEFT EXPORT -->
  <!-- ========================================== -->
  <div class="tab-pane fade" id="step3-pane" role="tabpanel">
    <?php if (!$salFrozen): ?>
      <div class="card border border-warning shadow-sm rounded-3 p-5 text-center bg-white">
        <div class="mb-3 fs-1 text-warning">🔒</div>
        <h4 class="fw-bold text-dark mb-2">Step 3 is Locked</h4>
        <p class="text-body-secondary mb-0">Please complete and <strong>Freeze & Approve Salary (Step 2)</strong> to access payslips and NEFT disbursement cards.</p>
      </div>
    <?php else: ?>
      <!-- Export Actions Row -->
      <div class="card border shadow-sm rounded-3 p-3 bg-white mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
          <div>
            <h5 class="fw-bold text-dark mb-0">Payslip & NEFT Disbursement Export</h5>
            <span class="small text-body-secondary">Export disbursement sheets and salary slips for <?= esc($monthName) ?></span>
          </div>
          <div class="d-flex flex-wrap gap-2">
            <a href="<?= site_url("payroll/export-neft/{$year}/{$month}") ?>" class="btn btn-primary btn-sm rounded-2 shadow-sm fw-semibold">
              📥 Download NEFT Excel Sheet
            </a>
            <a href="<?= site_url("payroll/export-slips/{$year}/{$month}") ?>" class="btn btn-outline-success btn-sm rounded-2 shadow-sm fw-semibold">
              📄 Export Salary Slips
            </a>
          </div>
        </div>
      </div>

      <!-- Contractor Payout Table -->
      <div class="card border shadow-sm rounded-3 bg-white mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
          <h6 class="fw-bold text-dark mb-0">Contractor-wise Payout Summaries</h6>
          <span class="badge text-bg-light border text-dark fw-normal"><?= count($contractorPayouts) ?> Contractors</span>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-3" style="width: 50px;">#</th>
                <th>Contractor Name</th>
                <th>Bank Name</th>
                <th>Account Number</th>
                <th>IFSC Code</th>
                <th class="text-center">Staff Count</th>
                <th class="text-end">Total Payout</th>
                <th class="text-center" style="width: 150px;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($contractorPayouts)): ?>
                <tr>
                  <td colspan="8" class="text-center py-4 text-body-secondary">No contractor payout records found.</td>
                </tr>
              <?php else: ?>
                <?php $cpSr = 1; foreach ($contractorPayouts as $cp): ?>
                  <tr>
                    <td class="ps-3 fw-semibold text-body-secondary" style="font-size: 13px;"><?= $cpSr++ ?></td>
                    <td>
                      <span class="fw-semibold text-dark" style="font-size: 13px;"><?= esc($cp['contractor_name']) ?></span>
                    </td>
                    <td>
                      <span class="text-body-secondary" style="font-size: 13px;"><?= esc($cp['bank_name']) ?></span>
                    </td>
                    <td>
                      <span class="fw-semibold text-dark" style="font-size: 13px;"><?= esc($cp['bank_account_number']) ?></span>
                    </td>
                    <td>
                      <span class="badge text-bg-light border text-dark fw-semibold"><?= esc($cp['ifsc_code']) ?></span>
                    </td>
                    <td class="text-center">
                      <span class="badge text-bg-primary rounded-pill"><?= esc($cp['associated_employees']) ?> Staff</span>
                    </td>
                    <td class="text-end">
                      <span class="fw-bold text-success" style="font-size: 13px;">₹ <?= number_format($cp['total_payout'], 2) ?></span>
                    </td>
                    <td class="text-center">
                      <a href="<?= site_url("payroll/export-neft/{$year}/{$month}?contractor_id={$cp['contractor_id']}") ?>" class="btn btn-outline-success btn-sm fw-medium shadow-sm py-1 px-2" style="font-size: 12px;" title="Download Excel for <?= esc($cp['contractor_name']) ?>">
                        <i class="bi bi-file-earmark-excel me-1"></i> Excel Sheet
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
  </div>

</div>

<!-- Import Attendance Excel Modal -->
<div class="modal fade" id="excelImportModal" tabindex="-1" aria-labelledby="excelImportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white py-3">
        <h5 class="modal-title fs-6 fw-bold" id="excelImportModalLabel">
          <i class="bi bi-file-earmark-excel me-2"></i>Import Attendance Excel
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="alert alert-info border-0 bg-info-subtle text-info-emphasis small rounded-3 mb-3">
          <div class="fw-semibold mb-1"><i class="bi bi-info-circle me-1"></i> Biometric Code Import Rules:</div>
          <ul class="mb-0 ps-3">
            <li><strong>Column 1:</strong> Employee Name</li>
            <li><strong>Column 2:</strong> Biometric Code (Used for database matching)</li>
            <li><strong>Column 3:</strong> Attended Days</li>
            <li class="mt-1 text-primary-emphasis">✓ Only existing database employees with matching Biometric Codes are updated.</li>
            <li class="text-secondary">✓ Extra / unmatched Excel rows are automatically ignored.</li>
            <li class="text-secondary">✓ Existing database employees not in Excel remain unchanged.</li>
          </ul>
        </div>

        <div class="mb-3">
          <label for="excelFileInput" class="form-label small fw-semibold text-secondary">Select Excel / CSV File</label>
          <input type="file" class="form-control" id="excelFileInput" accept=".xlsx, .xls, .csv">
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
          <button type="button" class="btn btn-sm btn-outline-secondary" id="btnDownloadSampleExcel">
            <i class="bi bi-download me-1"></i> Sample Template
          </button>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" data-coreui-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-sm btn-primary" id="btnProcessExcelImport">
              <i class="bi bi-upload me-1"></i> Upload & Import
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript Integration -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const daysInMonth = <?= (int) $daysInMonth ?>;
    const monthDate = "<?= esc($monthDate) ?>";

    // 1. Contractor Filter Change
    $('#contractorFilter').on('change', function() {
      const contractorId = $(this).val();
      let visibleCount = 0;
      let filledCount = 0;

      $('.attendance-row').each(function() {
        const rowContractorId = $(this).data('contractor-id');
        if (contractorId === '' || String(rowContractorId) === String(contractorId)) {
          $(this).show();
          visibleCount++;
          const attVal = $(this).find('.input-attended').val();
          if (attVal !== undefined && attVal !== '') {
            filledCount++;
          }
        } else {
          $(this).hide();
        }
      });

      $('#employeeCountBadge').text(visibleCount + ' employees');
      const pendingCount = Math.max(0, visibleCount - filledCount);
      $('#progressText').html(filledCount + ' / ' + visibleCount + ' Filled &middot; ' + pendingCount + ' Pending');
      const pct = visibleCount > 0 ? Math.round((filledCount / visibleCount) * 100) : 0;
      $('#progressBar').css('width', pct + '%').attr('aria-valuenow', pct);
    });

    // 2. Real-time Net Days calculation on attended days input change
    $(document).on('input change', '.input-attended', function() {
      const empId = $(this).data('emp-id');
      const rawVal = $(this).val();
      const val = rawVal !== undefined && rawVal !== null ? rawVal.trim() : '';
      const attendedDays = val !== '' ? parseFloat(val) : 0;

      let netDays = attendedDays;
      if (netDays < 0) netDays = 0;
      if (netDays > daysInMonth) netDays = daysInMonth;

      $('#net-days-' + empId).text(val !== '' ? netDays.toFixed(1) : '0');

      // Update progress bar counter in real-time
      let visibleCount = 0;
      let filledCount = 0;
      $('.attendance-row:visible').each(function() {
        visibleCount++;
        const attVal = $(this).find('.input-attended').val();
        if (attVal !== undefined && attVal !== null && attVal.trim() !== '') {
          filledCount++;
        }
      });
      const pendingCount = Math.max(0, visibleCount - filledCount);
      $('#progressText').html(filledCount + ' / ' + visibleCount + ' Filled &middot; ' + pendingCount + ' Pending');
      const pct = visibleCount > 0 ? Math.round((filledCount / visibleCount) * 100) : 0;
      $('#progressBar').css('width', pct + '%').attr('aria-valuenow', pct);
    });

    // Toast Notification Utility
    function showToast(icon, title, text) {
      if (typeof window.showToast === 'function') {
        window.showToast(icon, text, title);
      } else if (typeof Swal !== 'undefined' && Swal.mixin) {
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3500,
          timerProgressBar: true,
          background: icon === 'success' ? '#25c974' : (icon === 'error' ? '#ef4444' : '#f59e0b'),
          color: '#ffffff',
          iconColor: '#ffffff'
        });
        Toast.fire({
          icon: icon,
          title: title,
          text: text
        });
      } else {
        alert(text);
      }
    }

    // 3. Open Import Excel Modal
    $('#btnImportExcel').on('click', function() {
      $('#excelFileInput').val('');
      const modalEl = document.getElementById('excelImportModal');
      if (typeof coreui !== 'undefined' && coreui.Modal) {
        coreui.Modal.getOrCreateInstance(modalEl).show();
      } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
      } else {
        $('#excelImportModal').modal('show');
      }
    });

    // Download Sample Template
    $('#btnDownloadSampleExcel').on('click', function() {
      let csvContent = "Employee Name,Biometric Code,Attended Days\n";
      $('.attendance-row').each(function() {
        const name = $(this).find('.fw-bold.text-dark').text().trim().replace(/,/g, '');
        const code = $(this).data('biometric-code') || '';
        const att = $(this).find('.input-attended').val() || daysInMonth;
        csvContent += `"${name}","${code}",${att}\n`;
      });

      const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const link = document.createElement("a");
      link.setAttribute("href", url);
      link.setAttribute("download", "Attendance_Import_Template.csv");
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    });

    // Process Excel Import
    $('#btnProcessExcelImport').on('click', function() {
      const fileInput = document.getElementById('excelFileInput');
      if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
        showToast('error', 'No File Selected', 'Please select an Excel or CSV file to import.');
        return;
      }

      const file = fileInput.files[0];
      const reader = new FileReader();

      reader.onload = function(e) {
        try {
          const data = new Uint8Array(e.target.result);
          const workbook = XLSX.read(data, { type: 'array' });
          const firstSheetName = workbook.SheetNames[0];
          const worksheet = workbook.Sheets[firstSheetName];
          const jsonRows = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

          if (!jsonRows || jsonRows.length === 0) {
            showToast('error', 'Empty File', 'The uploaded file contains no readable data.');
            return;
          }

          let bioColIndex = 1;
          let attColIndex = 2;

          // Header detection if present
          if (jsonRows.length > 0) {
            const firstRow = jsonRows[0];
            firstRow.forEach((colHeader, idx) => {
              if (colHeader && typeof colHeader === 'string') {
                const h = colHeader.toLowerCase().trim();
                if (h.includes('biometric') || h.includes('bio') || h.includes('code')) {
                  bioColIndex = idx;
                }
                if (h.includes('attended') || h.includes('days') || h.includes('present') || h.includes('attendance')) {
                  attColIndex = idx;
                }
              }
            });
          }

          let updatedCount = 0;
          let ignoredCount = 0;

          for (let i = 0; i < jsonRows.length; i++) {
            const row = jsonRows[i];
            if (!row || row.length === 0) continue;

            const bioVal = row[bioColIndex];
            const attVal = row[attColIndex];

            if (bioVal === undefined || bioVal === null || bioVal === '') continue;

            const bioCode = String(bioVal).trim().toUpperCase();
            if (bioCode.toLowerCase().includes('biometric')) continue; // Skip header row

            const attendedDays = (attVal !== undefined && attVal !== null && attVal !== '' && !isNaN(attVal)) ? parseFloat(attVal) : null;

            if (attendedDays !== null && attendedDays >= 0) {
              const $targetRow = $('.attendance-row').filter(function() {
                return String($(this).data('biometric-code')).trim().toUpperCase() === bioCode;
              });

              if ($targetRow.length) {
                const $input = $targetRow.find('.input-attended');
                if ($input.length) {
                  $input.val(attendedDays).trigger('input');
                  updatedCount++;
                }
              } else {
                // Biometric Code not in database -> Completely ignore
                ignoredCount++;
              }
            }
          }

          const modalEl = document.getElementById('excelImportModal');
          if (typeof coreui !== 'undefined' && coreui.Modal) {
            coreui.Modal.getInstance(modalEl)?.hide();
          } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getInstance(modalEl)?.hide();
          } else {
            $('#excelImportModal').modal('hide');
          }

          if (updatedCount > 0) {
            let msg = 'Imported attendance for ' + updatedCount + ' matched employee(s).';
            if (ignoredCount > 0) {
              msg += ' (' + ignoredCount + ' unmatched Excel row(s) ignored)';
            }
            showToast('success', 'Import Complete', msg);
          } else {
            showToast('warning', 'No Match Found', 'No matching biometric codes found in database. Unmatched rows were ignored.');
          }

        } catch (err) {
          console.error(err);
          showToast('error', 'File Parse Error', 'Failed to parse file. Please ensure it is a valid .xlsx, .xls, or .csv file.');
        }
      };

      reader.readAsArrayBuffer(file);
    });

    // 4. Save Draft Attendance
    $('#btnSaveAttendanceDraft').on('click', function() {
      const btn = $(this);
      btn.prop('disabled', true).html('Saving...');

      $.ajax({
        url: '<?= site_url("payroll/save-attendance") ?>',
        method: 'POST',
        data: $('#attendanceForm').serialize(),
        dataType: 'json',
        success: function(res) {
          btn.prop('disabled', false).html('💾 Save Draft Attendance');
          if (res.status === 'success') {
            showToast('success', 'Draft Saved', res.message);
          } else {
            showToast('error', 'Action Failed', res.message);
          }
        },
        error: function() {
          btn.prop('disabled', false).html('💾 Save Draft Attendance');
          showToast('error', 'Error', 'Failed to save attendance draft.');
        }
      });
    });

    // 5. Freeze & Complete Attendance
    $('#btnFreezeAttendance').on('click', function() {
      const btn = $(this);
      const monthNameStr = "<?= esc($monthName) ?>";

      Swal.fire({
        title: 'Freeze & Complete Attendance?',
        html: `
          <div class="mb-3 text-start small text-secondary">
            Freezing attendance will lock Step 1 and enable Step 2 Salary Computation for <strong>${monthNameStr}</strong>.
          </div>
          <div class="mb-2 text-start">
            <label for="freezeConfirmInput" class="form-label small fw-bold text-dark mb-1">
              Type <span class="text-danger fw-bold">FREEZE</span> to confirm:
            </label>
            <input type="text" id="freezeConfirmInput" class="form-control text-center fw-bold" placeholder="Type FREEZE to confirm" autocomplete="off">
          </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Freeze Attendance',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
          const val = ($('#freezeConfirmInput').val() || '').trim();
          if (val.toUpperCase() !== 'FREEZE') {
            Swal.showValidationMessage('Please type "FREEZE" to confirm.');
            return false;
          }
          return true;
        }
      }).then((result) => {
        if (result.isConfirmed) {
          btn.prop('disabled', true).html('Freezing...');

          // First save draft then freeze
          $.ajax({
            url: '<?= site_url("payroll/save-attendance") ?>',
            method: 'POST',
            data: $('#attendanceForm').serialize(),
            dataType: 'json',
            success: function() {
              $.ajax({
                url: '<?= site_url("payroll/freeze-attendance") ?>',
                method: 'POST',
                data: {
                  month_date: monthDate
                },
                dataType: 'json',
                success: function(res) {
                  btn.prop('disabled', true).html('🔒 Attendance Completed & Locked');
                  if (res.status === 'success') {
                    showToast('success', 'Attendance Frozen', res.message);
                    setTimeout(() => location.reload(), 1200);
                  } else {
                    showToast('error', 'Action Failed', res.message);
                    btn.prop('disabled', false).html('🔒 Freeze & Complete Attendance');
                  }
                },
                error: function() {
                  btn.prop('disabled', false).html('🔒 Freeze & Complete Attendance');
                  showToast('error', 'Error', 'Server error while freezing attendance.');
                }
              });
            },
            error: function() {
              btn.prop('disabled', false).html('🔒 Freeze & Complete Attendance');
              showToast('error', 'Error', 'Failed to save draft attendance before freezing.');
            }
          });
        }
      });
    });

    // 6. Save Draft Salary
    $('#btnSaveSalaryDraft').on('click', function() {
      const btn = $(this);
      btn.prop('disabled', true).html('Saving...');

      $.ajax({
        url: '<?= site_url("payroll/save-salary") ?>',
        method: 'POST',
        data: $('#salaryForm').serialize(),
        dataType: 'json',
        success: function(res) {
          btn.prop('disabled', false).html('💾 Save Draft Salary');
          if (res.status === 'success') {
            showToast('success', 'Draft Saved', res.message);
          } else {
            showToast('error', 'Action Failed', res.message);
          }
        },
        error: function() {
          btn.prop('disabled', false).html('💾 Save Draft Salary');
          showToast('error', 'Error', 'Failed to save draft salary.');
        }
      });
    });

    // 7. Freeze & Approve Salary
    $('#btnApproveSalary').on('click', function() {
      const btn = $(this);
      const monthNameStr = "<?= esc($monthName) ?>";

      Swal.fire({
        title: 'Freeze & Approve Salary?',
        html: `
          <div class="mb-3 text-start small text-secondary">
            Approving salary will lock Step 2 and generate NEFT disbursement cards & payslips for <strong>${monthNameStr}</strong>.
          </div>
          <div class="mb-2 text-start">
            <label for="salaryConfirmInput" class="form-label small fw-bold text-dark mb-1">
              Type <span class="text-danger fw-bold">FREEZE</span> to confirm:
            </label>
            <input type="text" id="salaryConfirmInput" class="form-control text-center fw-bold" placeholder="Type FREEZE to confirm" autocomplete="off">
          </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Freeze Salary',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
          const val = ($('#salaryConfirmInput').val() || '').trim();
          if (val.toUpperCase() !== 'FREEZE') {
            Swal.showValidationMessage('Please type "FREEZE" to confirm.');
            return false;
          }
          return true;
        }
      }).then((result) => {
        if (result.isConfirmed) {
          btn.prop('disabled', true).html('Approving...');

          $.ajax({
            url: '<?= site_url("payroll/save-salary") ?>',
            method: 'POST',
            data: $('#salaryForm').serialize(),
            dataType: 'json',
            success: function() {
              $.ajax({
                url: '<?= site_url("payroll/approve-salary") ?>',
                method: 'POST',
                data: {
                  month_date: monthDate
                },
                dataType: 'json',
                success: function(res) {
                  btn.prop('disabled', false).html('🔒 Freeze & Approve Salary');
                  if (res.status === 'success') {
                    showToast('success', 'Salary Approved', res.message);
                    setTimeout(() => location.reload(), 1200);
                  } else {
                    showToast('error', 'Action Failed', res.message);
                  }
                },
                error: function() {
                  btn.prop('disabled', false).html('🔒 Freeze & Approve Salary');
                  showToast('error', 'Error', 'Server error while approving salary.');
                }
              });
            }
          });
        }
      });
    });

  });
</script>

<?= $this->endSection() ?>