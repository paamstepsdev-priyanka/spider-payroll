<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<!-- Executive Welcome Header -->
<div class="card dashboard-hero-card shadow-sm rounded-4 mb-4">
  <div class="card-body p-4">
    <div class="row align-items-center">
      <div class="col-lg-8 mb-3 mb-lg-0">
        <span class="badge bg-dark text-white border border-secondary rounded-pill px-3 py-2 mb-2 fs-7 fw-bold shadow-sm">
          <i class="bi bi-shield-check me-1" style="color: #34d399;"></i> Spider Payroll System v2.0
        </span>
        <h2 class="fw-bold hero-title mb-1">Welcome back, Admin 👋</h2>
        <p class="mb-0 hero-subtitle" style="font-size: 0.95rem;">
          Here is your high-level overview of workforce metrics, contractor accounts, and active payroll status for <strong><?= esc($currentMonthName) ?></strong>.
        </p>
      </div>
      <div class="col-lg-4 text-lg-end">
        <a href="<?= site_url("payroll/month/{$currentYear}/{$currentMonth}") ?>" class="btn text-white shadow-sm fw-bold px-4 py-2 rounded-3 me-2" style="background-color: #10b981; border: none;">
          <i class="bi bi-play-circle-fill me-2"></i> Process <?= date('M Y') ?> Payroll
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Key Performance Metrics Cards (4 Stat Grid) -->
<div class="row g-3 mb-4">
  <!-- Active Employees Card -->
  <div class="col-12 col-sm-6 col-xl-6">
    <div class="card border shadow-sm rounded-3 h-100 bg-white">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <span class="text-dark fw-bold text-uppercase fs-7" style="letter-spacing: 0.5px;">Active Employees</span>
          <div class="rounded-3 p-2 text-emerald fw-bold" style="background-color: #d1fae5; color: #047857;">
            <i class="bi bi-people-fill fs-5"></i>
          </div>
        </div>
        <div class="d-flex align-items-baseline justify-content-between">
          <h3 class="fw-bold text-dark mb-0"><?= esc($activeEmployees) ?></h3>
          <span class="badge bg-emerald text-white rounded-pill px-2.5 py-1 fs-7 fw-bold" style="background-color: #047857;">
            <?= esc($totalEmployees) ?> Total
          </span>
        </div>
        <div class="mt-2 text-secondary fw-semibold fs-7">
          <span>Enrolled workforce records</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Active Contractors Card -->
  <div class="col-12 col-sm-6 col-xl-6">
    <div class="card border shadow-sm rounded-3 h-100 bg-white">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <span class="text-dark fw-bold text-uppercase fs-7" style="letter-spacing: 0.5px;">Contractors</span>
          <div class="rounded-3 p-2 text-primary fw-bold" style="background-color: #dbeafe; color: #1d4ed8;">
            <i class="bi bi-building-fill fs-5"></i>
          </div>
        </div>
        <div class="d-flex align-items-baseline justify-content-between">
          <h3 class="fw-bold text-dark mb-0"><?= esc($activeContractors) ?></h3>
          <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 fs-7 fw-bold">
            <?= esc($totalContractors) ?> Total
          </span>
        </div>
        <div class="mt-2 text-secondary fw-semibold fs-7">
          <span>Active contractor firms</span>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Active Month Status Banner & Quick Navigation -->
<div class="row g-3 mb-4">
  <div class="col-lg-8">
    <div class="card border shadow-sm rounded-3 h-100 bg-white">
      <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
          <i class="bi bi-wallet2 text-emerald"></i>
          Current Month Payroll Status — <?= esc($currentMonthName) ?>
        </h6>
        <a href="<?= site_url('payroll') ?>" class="btn btn-sm btn-dark rounded-pill px-3 fw-bold">
          View All FY Months <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
      <div class="card-body p-4">
        <div class="row align-items-center">
          <div class="col-md-6 mb-3 mb-md-0 border-end">
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-circle p-3 text-emerald" style="background-color: #d1fae5; color: #047857;">
                <i class="bi bi-calendar-check fs-3"></i>
              </div>
              <div>
                <span class="text-dark fw-bold fs-7 d-block">Step 1: Attendance Status</span>
                <h5 class="fw-bold mb-0 text-capitalize">
                  <?php if (in_array($attStatus, ['freeze', 'frozen'])): ?>
                    <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Frozen & Completed</span>
                  <?php else: ?>
                    <span class="text-warning fw-bold"><i class="bi bi-hourglass-split me-1"></i> Draft / Action Needed</span>
                  <?php endif; ?>
                </h5>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-circle p-3 text-primary" style="background-color: #dbeafe; color: #1d4ed8;">
                <i class="bi bi-cash-stack fs-3"></i>
              </div>
              <div>
                <span class="text-dark fw-bold fs-7 d-block">Step 2: Salary Computation</span>
                <h5 class="fw-bold mb-0 text-capitalize">
                  <?php if (in_array($salStatus, ['freeze', 'frozen', 'approved'])): ?>
                    <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Approved & Frozen</span>
                  <?php else: ?>
                    <span class="text-secondary fw-bold"><i class="bi bi-dash-circle me-1"></i> Pending Approval</span>
                  <?php endif; ?>
                </h5>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-4 pt-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
          <span class="text-dark fw-semibold fs-7">
            <i class="bi bi-info-circle me-1 text-primary"></i> Open the month workflow to input attendance or compute final payouts.
          </span>
          <a href="<?= site_url("payroll/month/{$currentYear}/{$currentMonth}") ?>" class="btn text-white fw-bold px-4 py-2 rounded-3 shadow-sm" style="background-color: #047857; border: none;">
            Open <?= date('M Y') ?> Payroll Workflow <i class="bi bi-chevron-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card border shadow-sm rounded-3 h-100 bg-white">
      <div class="card-header bg-white border-bottom py-3">
        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
          <i class="bi bi-lightning-charge-fill text-warning"></i>
          Quick Admin Shortcuts
        </h6>
      </div>
      <div class="card-body p-3">
        <div class="d-grid gap-2">
          <a href="<?= site_url('employees/create') ?>" class="btn btn-white border shadow-sm text-start p-3 rounded-3 d-flex align-items-center justify-content-between hover-shadow">
            <div class="d-flex align-items-center gap-3">
              <i class="bi bi-person-plus-fill text-success fs-4"></i>
              <div>
                <strong class="d-block text-dark fs-6">Add New Employee</strong>
                <small class="text-secondary fw-semibold">Enroll a new staff member</small>
              </div>
            </div>
            <i class="bi bi-chevron-right text-dark fw-bold"></i>
          </a>

          <a href="<?= site_url('contractors/create') ?>" class="btn btn-white border shadow-sm text-start p-3 rounded-3 d-flex align-items-center justify-content-between hover-shadow">
            <div class="d-flex align-items-center gap-3">
              <i class="bi bi-building-add text-primary fs-4"></i>
              <div>
                <strong class="d-block text-dark fs-6">Add Contractor</strong>
                <small class="text-secondary fw-semibold">Register contractor entity</small>
              </div>
            </div>
            <i class="bi bi-chevron-right text-dark fw-bold"></i>
          </a>

          <a href="<?= site_url('users/create') ?>" class="btn btn-white border shadow-sm text-start p-3 rounded-3 d-flex align-items-center justify-content-between hover-shadow">
            <div class="d-flex align-items-center gap-3">
              <i class="bi bi-person-gear text-info fs-4"></i>
              <div>
                <strong class="d-block text-dark fs-6">Add Portal User</strong>
                <small class="text-secondary fw-semibold">Grant admin portal access</small>
              </div>
            </div>
            <i class="bi bi-chevron-right text-dark fw-bold"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Overview Tables Grid (Employees & Contractors) -->
<div class="row g-3">
  <!-- Recent Employees Table -->
  <div class="col-lg-7">
    <div class="card border shadow-sm rounded-3 bg-white">
      <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
          <i class="bi bi-people text-emerald"></i>
          Recently Enrolled Employees
        </h6>
        <a href="<?= site_url('employees') ?>" class="btn btn-sm btn-dark rounded-pill px-3 fw-bold">
          View All Employees <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-dark text-white" style="background-color: #1e293b !important;">
            <tr>
              <th class="ps-3">Employee Name</th>
              <th>Designation</th>
              <th>Contractor</th>
              <th class="text-end pe-3">Base Salary</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($recentEmployees)): ?>
              <?php foreach ($recentEmployees as $emp): ?>
                <tr>
                  <td class="ps-3 fw-bold text-dark">
                    <?= esc($emp['employee_name']) ?>
                    <?php if (!empty($emp['biometric_code'])): ?>
                      <small class="d-block text-secondary fw-semibold">ID: <?= esc($emp['biometric_code']) ?></small>
                    <?php endif; ?>
                  </td>
                  <td><span class="badge text-bg-light border text-dark fw-bold"><?= esc($emp['designation'] ?? 'Staff') ?></span></td>
                  <td class="text-dark fw-medium"><?= esc($emp['contractor_name'] ?? 'Direct / None') ?></td>
                  <td class="text-end pe-3 fw-bold" style="color: #047857;">₹<?= number_format((float)$emp['monthly_base_salary'], 2) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-center py-4 text-secondary fw-semibold">No employees registered yet.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Contractor Directory Overview -->
  <div class="col-lg-5">
    <div class="card border shadow-sm rounded-3 bg-white">
      <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
          <i class="bi bi-building text-primary"></i>
          Contractors Overview
        </h6>
        <a href="<?= site_url('contractors') ?>" class="btn btn-sm btn-dark rounded-pill px-3 fw-bold">
          Manage Contractors <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-dark text-white" style="background-color: #212631;">
            <tr>
              <th class="ps-3">Contractor Name</th>
              <th class="text-end pe-3">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($recentContractors)): ?>
              <?php foreach ($recentContractors as $c): ?>
                <tr>
                  <td class="ps-3 fw-bold text-dark"><?= esc($c['contractor_name']) ?></td>
                  <td class="text-end pe-3">
                    <?php if (($c['status'] ?? 'active') === 'active'): ?>
                      <span class="badge text-bg-success text-white rounded-pill px-2 py-1">Active</span>
                    <?php else: ?>
                      <span class="badge text-bg-danger text-white rounded-pill px-2 py-1">Inactive</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="3" class="text-center py-4 text-muted">No contractors registered yet.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>