<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
$attFrozen = ($statusRecord['attendance_status'] === 'frozen');
$salFrozen = ($statusRecord['salary_status'] === 'frozen');
?>

<!-- Header Row -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between pb-3 mb-4 border-bottom gap-3">
  <div>
    <h3 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
      <?= esc($companyName) ?>
    </h3>
    <p class="text-body-secondary mb-0 small">
      Monthly Payroll Processing &middot; <strong><?= esc($monthName) ?></strong>
    </p>
  </div>
  <div>
    <a href="<?= site_url('payroll?fy=' . $year) ?>" class="btn btn-outline-secondary btn-sm fw-medium d-inline-flex align-items-center gap-2 shadow-sm">
      &larr; Back to Dashboard
    </a>
  </div>
</div>

<!-- 3-Step Workflow Header Cards -->
<div class="row g-3 mb-4">
  <!-- Step 1 Card -->
  <div class="col-12 col-md-4">
    <div class="card h-100 border rounded-3 p-3 bg-white <?= !$attFrozen ? 'border-primary shadow-sm' : 'border-success-subtle' ?>">
      <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5 <?= !$attFrozen ? 'bg-primary text-white' : 'bg-success text-white' ?>" style="width: 42px; height: 42px;">
          <?= $attFrozen ? '✓' : '1' ?>
        </div>
        <div>
          <div class="fw-bold text-dark mb-0">Step 1: Attendance Register</div>
          <div class="small">
            <?php if ($attFrozen): ?>
              <span class="badge text-bg-success rounded-pill">Frozen / Completed</span>
            <?php else: ?>
              <span class="badge text-bg-primary rounded-pill">Active / In Progress</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Step 2 Card -->
  <div class="col-12 col-md-4">
    <div class="card h-100 border rounded-3 p-3 bg-white <?= ($attFrozen && !$salFrozen) ? 'border-warning shadow-sm' : ($salFrozen ? 'border-success-subtle' : 'opacity-75 bg-light') ?>">
      <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5 <?= $salFrozen ? 'bg-success text-white' : ($attFrozen ? 'bg-warning text-dark' : 'bg-secondary-subtle text-secondary') ?>" style="width: 42px; height: 42px;">
          <?= $salFrozen ? '✓' : '2' ?>
        </div>
        <div>
          <div class="fw-bold text-dark mb-0">Step 2: Salary Computation</div>
          <div class="small text-body-secondary">
            <?php if ($salFrozen): ?>
              <span class="badge text-bg-success rounded-pill">Frozen / Approved</span>
            <?php elseif ($attFrozen): ?>
              <span class="badge text-bg-warning rounded-pill">Active / Unlocked</span>
            <?php else: ?>
              <span class="text-secondary small">🔒 Locked (Complete Step 1)</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Step 3 Card -->
  <div class="col-12 col-md-4">
    <div class="card h-100 border rounded-3 p-3 bg-white <?= $salFrozen ? 'border-success shadow-sm' : 'opacity-75 bg-light' ?>">
      <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5 <?= $salFrozen ? 'bg-success text-white' : 'bg-secondary-subtle text-secondary' ?>" style="width: 42px; height: 42px;">
          <?= $salFrozen ? '✓' : '3' ?>
        </div>
        <div>
          <div class="fw-bold text-dark mb-0">Step 3: Payslip & NEFT Export</div>
          <div class="small text-body-secondary">
            <?php if ($salFrozen): ?>
              <span class="badge text-bg-success rounded-pill">Unlocked & Ready</span>
            <?php else: ?>
              <span class="text-secondary small">🔒 Locked (Approve Step 2)</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Workflow Nav Tabs -->
<ul class="nav nav-tabs mb-4 border-bottom" id="payrollWorkflowTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active fw-bold text-dark" id="tab-step1" data-coreui-toggle="tab" data-coreui-target="#step1-pane" type="button" role="tab">
      1. Attendance Register
      <?php if ($attFrozen): ?>
        <span class="badge text-bg-success ms-1">✓</span>
      <?php endif; ?>
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link fw-bold text-dark <?= !$attFrozen ? 'disabled opacity-50' : '' ?>" id="tab-step2" data-coreui-toggle="tab" data-coreui-target="#step2-pane" type="button" role="tab">
      2. Salary Computation
      <?php if (!$attFrozen): ?>
        🔒
      <?php elseif ($salFrozen): ?>
        <span class="badge text-bg-success ms-1">✓</span>
      <?php endif; ?>
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link fw-bold text-dark <?= !$salFrozen ? 'disabled opacity-50' : '' ?>" id="tab-step3" data-coreui-toggle="tab" data-coreui-target="#step3-pane" type="button" role="tab">
      3. Payslip & NEFT Export
      <?php if (!$salFrozen): ?>
        🔒
      <?php else: ?>
        <span class="badge text-bg-success ms-1">✓</span>
      <?php endif; ?>
    </button>
  </li>
</ul>

<!-- Tab Contents -->
<div class="tab-content" id="payrollWorkflowTabsContent">

  <!-- ========================================== -->
  <!-- STEP 1: ATTENDANCE REGISTER -->
  <!-- ========================================== -->
  <div class="tab-pane fade show active" id="step1-pane" role="tabpanel">

    <!-- Top Controls Row -->
    <div class="card border shadow-sm rounded-3 p-3 bg-white mb-4">
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <!-- Contractor Filter -->
        <div class="d-flex align-items-center gap-2">
          <label for="contractorFilter" class="form-label fw-bold mb-0 text-nowrap">Filter by Contractor:</label>
          <select id="contractorFilter" class="form-select form-select-sm" style="min-width: 220px;">
            <option value="">All Contractors</option>
            <?php foreach ($contractors as $c): ?>
              <option value="<?= esc($c['contractor_id']) ?>">
                <?= esc($c['contractor_name']) ?> (<?= esc($c['contractor_code']) ?>)
              </option>
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
          <button type="button" class="btn btn-outline-primary btn-sm rounded-2 shadow-sm" id="btnImportExcel" <?= $attFrozen ? 'disabled' : '' ?>>
            Import Attendance Excel
          </button>
          <button type="button" class="btn btn-outline-primary btn-sm rounded-2 shadow-sm" id="btnQuickFill" <?= $attFrozen ? 'disabled' : '' ?>>
            ⚡ Quick Fill (Full Attendance)
          </button>
          <button type="button" class="btn btn-outline-warning btn-sm rounded-2 shadow-sm fw-semibold" id="btnSaveAttendanceDraft" <?= $attFrozen ? 'disabled' : '' ?>>
            💾 Save Draft Attendance
          </button>
          <button type="button" class="btn btn-success btn-sm rounded-2 shadow-sm fw-semibold" id="btnFreezeAttendance" <?= $attFrozen ? 'disabled' : '' ?>>
            🔒 Freeze & Complete Attendance
          </button>
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
                <th>Designation</th>
                <th class="text-center" style="width: 130px;">Total Month Days</th>
                <th class="text-center" style="width: 130px;">Leave Taken</th>
                <th class="text-center" style="width: 140px;">Leave Not Deducted</th>
                <th class="text-center" style="width: 140px;">Net Days Payable</th>
              </tr>
            </thead>
            <tbody>
              <?php $sr = 1; ?>
              <?php foreach ($attendanceRows as $row): ?>
                <?php
                $initial = strtoupper(substr($row['employee_name'], 0, 1));
                ?>
                <tr class="attendance-row" data-contractor-id="<?= esc($row['contractor_id']) ?>">
                  <td class="fw-semibold text-body-secondary"><?= $sr++ ?></td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <div class="bg-primary-subtle text-primary rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 13px;">
                        <?= esc($initial) ?>
                      </div>
                      <div>
                        <div class="fw-bold text-dark mb-0"><?= esc($row['employee_name']) ?></div>
                        <span class="small text-body-tertiary"><?= esc($row['biometric_code'] ?? 'N/A') ?></span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="badge text-bg-light border text-dark fw-normal">
                      <?= esc($row['contractor_name']) ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge text-bg-secondary rounded-pill fw-normal">
                      <?= esc($row['designation']) ?>
                    </span>
                  </td>
                  <td class="text-center fw-bold text-dark">
                    <?= esc($daysInMonth) ?>
                    <input type="hidden" name="attendance[<?= $row['employee_id'] ?>][total_month_days]" value="<?= $daysInMonth ?>">
                  </td>
                  <td>
                    <input type="number" step="0.5" min="0" max="<?= $daysInMonth ?>"
                      class="form-select form-select-sm text-center input-leave"
                      name="attendance[<?= $row['employee_id'] ?>][leave_days]"
                      value="<?= esc($row['leave_days']) ?>"
                      data-emp-id="<?= $row['employee_id'] ?>"
                      <?= $attFrozen ? 'readonly disabled' : '' ?>>
                  </td>
                  <td>
                    <input type="number" step="0.5" min="0" max="<?= $daysInMonth ?>"
                      class="form-select form-select-sm text-center input-leave-nd"
                      name="attendance[<?= $row['employee_id'] ?>][leave_not_deducted]"
                      value="<?= esc($row['leave_not_deducted']) ?>"
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
  <div class="tab-pane fade" id="step2-pane" role="tabpanel">
    <?php if (!$attFrozen): ?>
      <div class="card border border-warning shadow-sm rounded-3 p-5 text-center bg-white">
        <div class="mb-3 fs-1 text-warning">🔒</div>
        <h4 class="fw-bold text-dark mb-2">Step 2 is Locked</h4>
        <p class="text-body-secondary mb-0">Please complete and <strong>Freeze Attendance (Step 1)</strong> before computing salary.</p>
      </div>
    <?php else: ?>
      <!-- Summary Bar Cards -->
      <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-3">
          <div class="card border shadow-sm rounded-3 p-3 bg-white">
            <div class="text-body-secondary small fw-semibold text-uppercase mb-1">TOTAL PAYROLL BUDGET</div>
            <div class="fs-4 fw-bold text-dark">₹ <?= number_format($totalPayrollBudget, 2) ?></div>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <div class="card border shadow-sm rounded-3 p-3 bg-white">
            <div class="text-body-secondary small fw-semibold text-uppercase mb-1">TOTAL FROZEN DAYS</div>
            <div class="fs-4 fw-bold text-dark"><?= number_format($totalFrozenAttendanceDays, 1) ?> Days</div>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <div class="card border shadow-sm rounded-3 p-3 bg-white">
            <div class="text-body-secondary small fw-semibold text-uppercase mb-1">TOTAL EMPLOYEES</div>
            <div class="fs-4 fw-bold text-dark"><?= esc($totalEmployees) ?> Staff</div>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <div class="card border shadow-sm rounded-3 p-3 bg-white">
            <div class="text-body-secondary small fw-semibold text-uppercase mb-1">TOTAL NET PAYABLE DAYS</div>
            <div class="fs-4 fw-bold text-dark"><?= number_format($totalNetPayableDays, 1) ?> Days</div>
          </div>
        </div>
      </div>

      <!-- Salary Calculation Table Card -->
      <div class="card border shadow-sm rounded-3 bg-white p-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pb-3 mb-3 border-bottom gap-2">
          <div>
            <h5 class="fw-bold mb-0 text-dark">Salary Computation Sheet</h5>
            <span class="small text-body-secondary">Formula: (Monthly Base Salary / <?= esc($daysInMonth) ?>) &times; Net Days Payable</span>
          </div>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-warning btn-sm rounded-2 shadow-sm fw-semibold" id="btnSaveSalaryDraft" <?= $salFrozen ? 'disabled' : '' ?>>
              💾 Save Draft Salary
            </button>
            <button type="button" class="btn btn-success btn-sm rounded-2 shadow-sm fw-semibold" id="btnApproveSalary" <?= $salFrozen ? 'disabled' : '' ?>>
              🔒 Freeze & Approve Salary
            </button>
          </div>
        </div>

        <form id="salaryForm">
          <input type="hidden" name="month_date" value="<?= esc($monthDate) ?>">
          <div class="table-responsive">
            <table class="table table-hover align-middle border mb-0">
              <thead class="table-light small text-uppercase">
                <tr>
                  <th style="width: 50px;">#</th>
                  <th>Employee Name</th>
                  <th>Contractor</th>
                  <th class="text-end" style="width: 160px;">Monthly Base Salary</th>
                  <th class="text-center" style="width: 140px;">Net Days Payable</th>
                  <th class="text-end" style="width: 170px;">Calculated Salary</th>
                  <th>Remarks</th>
                </tr>
              </thead>
              <tbody>
                <?php $sSr = 1; ?>
                <?php foreach ($attendanceRows as $row): ?>
                  <tr>
                    <td class="fw-semibold text-body-secondary"><?= $sSr++ ?></td>
                    <td>
                      <div class="fw-bold text-dark"><?= esc($row['employee_name']) ?></div>
                      <span class="small text-body-tertiary"><?= esc($row['designation']) ?></span>
                    </td>
                    <td>
                      <span class="badge text-bg-light border text-dark fw-normal"><?= esc($row['contractor_name']) ?></span>
                    </td>
                    <td class="text-end fw-semibold">₹ <?= number_format($row['monthly_base_salary'], 2) ?></td>
                    <td class="text-center fw-bold text-primary"><?= esc($row['net_days_payable']) ?></td>
                    <td class="text-end fw-bold text-success fs-6">
                      ₹ <input type="number" step="0.01" class="form-control form-control-sm d-inline-block text-end fw-bold text-success border-0 bg-transparent pe-0" style="width: 110px;"
                        name="salaries[<?= $row['employee_id'] ?>][calculated_salary]"
                        value="<?= number_format($row['calculated_salary'], 2, '.', '') ?>"
                        <?= $salFrozen ? 'readonly disabled' : '' ?>>
                    </td>
                    <td>
                      <input type="text" class="form-control form-control-sm" name="salaries[<?= $row['employee_id'] ?>][remarks]" value="<?= esc($row['remarks']) ?>" <?= $salFrozen ? 'readonly disabled' : '' ?> placeholder="Optional note">
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
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-2 shadow-sm fw-semibold" onclick="window.print();">
              🖨️ Print Contractor Summary
            </button>
            <a href="<?= site_url("payroll/export-slips/{$year}/{$month}") ?>" class="btn btn-outline-success btn-sm rounded-2 shadow-sm fw-semibold">
              📄 Export Salary Slips
            </a>
          </div>
        </div>
      </div>

      <!-- Contractor Payout Cards Grid -->
      <h6 class="fw-bold text-dark mb-3">Contractor-wise Payout Summaries</h6>
      <div class="row g-3">
        <?php foreach ($contractorPayouts as $cp): ?>
          <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 border shadow-sm rounded-3 p-3 bg-white">
              <div class="d-flex justify-content-between align-items-start border-bottom pb-2 mb-3">
                <div>
                  <h6 class="fw-bold text-dark mb-0"><?= esc($cp['contractor_name']) ?></h6>
                  <span class="small text-body-secondary">Code: <?= esc($cp['contractor_code']) ?></span>
                </div>
                <span class="badge text-bg-primary rounded-pill"><?= esc($cp['associated_employees']) ?> Staff</span>
              </div>
              <div class="small mb-3">
                <div class="d-flex justify-content-between py-1 border-bottom">
                  <span class="text-body-secondary">Bank Name:</span>
                  <span class="fw-semibold text-dark"><?= esc($cp['bank_name']) ?></span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                  <span class="text-body-secondary">Account Number:</span>
                  <span class="fw-semibold text-dark"><?= esc($cp['bank_account_number']) ?></span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                  <span class="text-body-secondary">IFSC Code:</span>
                  <span class="fw-semibold text-dark"><?= esc($cp['ifsc_code']) ?></span>
                </div>
              </div>
              <div class="d-flex justify-content-between align-items-center bg-light rounded-2 p-2 mt-auto">
                <span class="small fw-semibold text-body-secondary">Total Payout:</span>
                <span class="fs-5 fw-bold text-success">₹ <?= number_format($cp['total_payout'], 2) ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</div>

<!-- JavaScript Integration -->
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
        const rowCId = $(this).attr('data-contractor-id');
        if (!contractorId || rowCId === contractorId) {
          $(this).show();
          visibleCount++;

          const leaveVal = parseFloat($(this).find('.input-leave').val()) || 0;
          const leaveNdVal = parseFloat($(this).find('.input-leave-nd').val()) || 0;
          if (leaveVal > 0 || leaveNdVal > 0 || daysInMonth > 0) {
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

    // 2. Real-time Net Days calculation on leave input change
    $(document).on('input change', '.input-leave, .input-leave-nd', function() {
      const empId = $(this).data('emp-id');
      const row = $(this).closest('tr');
      const leaveDays = parseFloat(row.find('.input-leave').val()) || 0;
      const leaveNd = parseFloat(row.find('.input-leave-nd').val()) || 0;

      let netDays = daysInMonth - leaveDays + leaveNd;
      if (netDays < 0) netDays = 0;
      if (netDays > daysInMonth) netDays = daysInMonth;

      $('#net-days-' + empId).text(netDays.toFixed(1));
    });

    // Toast Notification Utility
    function showToast(icon, title, text) {
      if (typeof Swal !== 'undefined' && Swal.mixin) {
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3500,
          timerProgressBar: true
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

    // 3. Quick Fill Attendance
    $('#btnQuickFill').on('click', function() {
      const contractorId = $('#contractorFilter').val();
      const btn = $(this);
      btn.prop('disabled', true).html('Processing...');

      $.ajax({
        url: '<?= site_url("payroll/quick-fill-attendance") ?>',
        method: 'POST',
        data: {
          month_date: monthDate,
          contractor_id: contractorId,
          days_in_month: daysInMonth
        },
        dataType: 'json',
        success: function(res) {
          btn.prop('disabled', false).html('⚡ Quick Fill (Full Attendance)');
          if (res.status === 'success') {
            showToast('success', 'Quick Fill Completed', res.message);
            setTimeout(() => location.reload(), 1200);
          } else {
            showToast('error', 'Action Failed', res.message);
          }
        },
        error: function() {
          btn.prop('disabled', false).html('⚡ Quick Fill (Full Attendance)');
          showToast('error', 'Error', 'Failed to communicate with server.');
        }
      });
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

      Swal.fire({
        title: 'Freeze & Complete Attendance?',
        text: 'Freezing attendance will lock Step 1 and enable Step 2 Salary Computation for <?= esc($monthName) ?>.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Freeze Attendance',
        cancelButtonText: 'Cancel'
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
                  btn.prop('disabled', false).html('🔒 Freeze & Complete Attendance');
                  if (res.status === 'success') {
                    showToast('success', 'Attendance Frozen', res.message);
                    setTimeout(() => location.reload(), 1200);
                  } else {
                    showToast('error', 'Action Failed', res.message);
                  }
                },
                error: function() {
                  btn.prop('disabled', false).html('🔒 Freeze & Complete Attendance');
                  showToast('error', 'Error', 'Server error while freezing attendance.');
                }
              });
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

      Swal.fire({
        title: 'Freeze & Approve Salary?',
        text: 'Approving salary will lock Step 2 and generate NEFT disbursement cards & payslips for <?= esc($monthName) ?>.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Approve Salary',
        cancelButtonText: 'Cancel'
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
