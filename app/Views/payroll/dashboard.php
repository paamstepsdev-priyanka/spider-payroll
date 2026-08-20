<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header Row -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 pb-3 border-bottom">
  <div>
    <h3 class="fw-bold mb-1 text-dark"><?= esc($title ?? 'Payroll Processing Status') ?></h3>
    <p class="text-body-secondary mb-0 small">Track attendance recording and salary processing for each month.</p>
  </div>

  <!-- Financial Year Switcher Controls -->
  <div class="d-inline-flex align-items-center bg-white border rounded-pill p-1 shadow-sm">
    <a href="<?= site_url('payroll?fy=' . $prevFyStart) ?>" class="btn btn-sm btn-light rounded-circle p-0 d-inline-flex align-items-center justify-content-center text-secondary border-0" style="width: 32px; height: 32px;" title="Previous Financial Year">
      <i class="bi bi-chevron-left fs-6"></i>
    </a>

    <form method="get" action="<?= site_url('payroll') ?>" class="m-0 d-inline-flex align-items-center px-1">
      <i class="bi bi-calendar-event ms-2 me-1" style="color: #059669; font-size: 1rem;"></i>
      <select name="fy" class="form-select form-select-sm border-0 bg-transparent fw-bold text-dark py-1 px-2 pe-4 shadow-none" style="cursor: pointer; font-size: 0.875rem;" onchange="this.form.submit()">
        <?php foreach ($availableFys as $y => $label): ?>
          <option value="<?= esc($y) ?>" <?= $y == $fyStartYear ? 'selected' : '' ?>>
            <?= esc($label) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </form>

    <a href="<?= site_url('payroll?fy=' . $nextFyStart) ?>" class="btn btn-sm btn-light rounded-circle p-0 d-inline-flex align-items-center justify-content-center text-secondary border-0" style="width: 32px; height: 32px;" title="Next Financial Year">
      <i class="bi bi-chevron-right fs-6"></i>
    </a>
  </div>
</div>

<!-- 4 Summary Metric Cards -->
<div class="row g-3 mb-4">
  <!-- 1. Attendance Completed -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card h-100 rounded-4 p-3 dashboard-stat-card attendance-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="text-body-secondary small fw-semibold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.72rem;">ATTENDANCE COMPLETED</div>
          <div class="fs-3 fw-bold text-dark">
            <?= esc($attendanceCompletedCount) ?> <span class="fs-6 text-body-secondary fw-normal">/ 12</span>
          </div>
          <div class="small text-body-secondary mt-1">Months</div>
        </div>
        <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Salary Processed -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card h-100 rounded-4 p-3 dashboard-stat-card salary-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="text-body-secondary small fw-semibold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.72rem;">SALARY PROCESSED</div>
          <div class="fs-3 fw-bold text-dark">
            <?= esc($salaryProcessedCount) ?> <span class="fs-6 text-body-secondary fw-normal">/ 12</span>
          </div>
          <div class="small text-body-secondary mt-1">Months</div>
        </div>
        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
            <line x1="2" y1="10" x2="22" y2="10"></line>
          </svg>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. In Progress -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card h-100 rounded-4 p-3 dashboard-stat-card progress-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="text-body-secondary small fw-semibold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.72rem;">IN PROGRESS</div>
          <div class="fs-3 fw-bold text-dark">
            <?= esc($inProgressCount) ?> <span class="fs-6 text-body-secondary fw-normal">/ 12</span>
          </div>
          <div class="small text-body-secondary mt-1">Months</div>
        </div>
        <div class="bg-warning-subtle text-warning-emphasis rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
          </svg>
        </div>
      </div>
    </div>
  </div>

  <!-- 4. Payslip Generated -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card h-100 rounded-4 p-3 dashboard-stat-card payslip-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="text-body-secondary small fw-semibold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.72rem;">PAYSLIP GENERATED</div>
          <div class="fs-3 fw-bold text-dark">
            <?= esc($payslipsGeneratedCount) ?> <span class="fs-6 text-body-secondary fw-normal">/ 12</span>
          </div>
          <div class="small text-body-secondary mt-1">Months</div>
        </div>
        <div class="bg-secondary-subtle text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
          </svg>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Overdue Payroll Danger Alert -->
<?php if (!empty($alertMonth) && $monthsBehind > 0): ?>
  <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger-emphasis d-flex flex-column flex-md-row align-items-md-center justify-content-between p-3 mb-4 rounded-3 shadow-sm" role="alert">
    <div class="d-flex align-items-center gap-3 mb-2 mb-md-0">
      <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
        ⚠️
      </div>
      <div>
        Payroll is <strong><?= esc($monthsBehind) ?> <?= $monthsBehind === 1 ? 'month' : 'months' ?> behind</strong> — <strong><?= esc($alertMonth['name']) ?> <?= esc($alertMonth['year']) ?></strong> still needs attention: attendance hasn't been locked yet.
      </div>
    </div>
    <a href="<?= esc($alertMonth['url']) ?>" class="btn btn-danger text-white btn-sm px-3 py-2 rounded-2 fw-semibold text-nowrap align-self-start align-self-md-center">
      Continue <?= esc($alertMonth['name']) ?> Payroll &rarr;
    </a>
  </div>
<?php endif; ?>

<!-- Financial Year Payroll Timeline Section -->
<div class="card border shadow-sm rounded-3 p-4 mb-4 bg-white">
  <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
    <h5 class="fw-bold mb-0 text-dark"><?= esc($fyLabel) ?> Payroll Timeline</h5>
    <span class="small fw-semibold text-body-secondary text-uppercase"><?= esc($fyDateRangeText) ?></span>
  </div>

  <!-- Horizontal Scrollable Timeline -->
  <div class="position-relative pt-2 pb-2">
    <div class="position-absolute top-0 start-0 end-0 border-bottom border-2 border-secondary-subtle" style="margin-top: 15px; z-index: 1;"></div>

    <div class="d-flex flex-nowrap align-items-start gap-3 overflow-x-auto pb-3" style="z-index: 2; position: relative;">
      <?php foreach ($months as $m): ?>
        <?php $cat = $m['status_category']; ?>

        <?php if ($cat === 'action_needed'): ?>
          <!-- ACTION REQUIRED / OVERDUE Card -->
          <div class="flex-shrink-0" style="min-width: 250px;">
            <div class="d-flex justify-content-center mb-3">
              <span class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 24px; height: 24px; font-size: 11px;">!</span>
            </div>
            <div class="card border border-danger shadow-sm rounded-3 p-3 bg-white">
              <div class="mb-2">
                <span class="badge text-bg-danger text-white rounded-pill px-2 py-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">ACTION NEEDED - OVERDUE</span>
              </div>
              <h6 class="fw-bold text-dark mb-1"><?= esc($m['name']) ?> <?= esc($m['year']) ?></h6>
              <p class="small text-body-secondary mb-3" style="font-size: 0.78rem;">Attendance hasn't been locked yet</p>
              <a href="<?= esc($m['url']) ?>" class="btn btn-danger text-white btn-sm w-100 rounded-2 fw-semibold py-2">
                Continue <?= esc($m['short_name']) ?> payroll &rarr;
              </a>
            </div>
          </div>

        <?php elseif ($cat === 'closed'): ?>
          <!-- COMPLETED / CLOSED Card -->
          <div class="flex-shrink-0 text-center" style="min-width: 115px;">
            <div class="d-flex justify-content-center mb-3">
              <span class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 22px; height: 22px; font-size: 11px;">✓</span>
            </div>
            <div class="card border border-success-subtle rounded-3 p-2 bg-success-subtle text-center">
              <div class="fw-bold text-dark small mb-1"><?= esc($m['short_name']) ?></div>
              <div class="small text-body-secondary mb-2" style="font-size: 0.7rem;"><?= esc($m['year']) ?></div>
              <span class="badge text-bg-success text-white rounded-pill mb-2" style="font-size: 0.65rem;">✓ Closed</span>
              <div>
                <a href="<?= esc($m['url']) ?>" class="btn btn-sm btn-outline-success py-0 px-2 rounded-2 fw-semibold" style="font-size: 0.7rem;">View</a>
              </div>
            </div>
          </div>

        <?php elseif ($cat === 'incomplete'): ?>
          <!-- INCOMPLETE / PAST Card -->
          <div class="flex-shrink-0 text-center" style="min-width: 115px;">
            <div class="d-flex justify-content-center mb-3">
              <span class="bg-warning text-warning-emphasis rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 22px; height: 22px; font-size: 11px;">!</span>
            </div>
            <div class="card border border-warning-subtle rounded-3 p-2 bg-warning-subtle text-center">
              <div class="fw-bold text-dark small mb-1"><?= esc($m['short_name']) ?></div>
              <div class="small text-body-secondary mb-2" style="font-size: 0.7rem;"><?= esc($m['year']) ?></div>
              <span class="badge text-bg-warning text-white rounded-pill mb-2" style="font-size: 0.65rem;">⚠️ Incomplete</span>
              <div>
                <a href="<?= esc($m['url']) ?>" class="btn btn-sm btn-outline-warning py-0 px-2 rounded-2 fw-semibold" style="font-size: 0.7rem;">View</a>
              </div>
            </div>
          </div>

        <?php elseif ($cat === 'current'): ?>
          <!-- CURRENT MONTH Card -->
          <div class="flex-shrink-0 text-center" style="min-width: 140px;">
            <div class="d-flex justify-content-center mb-3">
              <span class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 22px; height: 22px; font-size: 11px;">●</span>
            </div>
            <div class="card border border-primary shadow-sm rounded-3 p-2 bg-primary-subtle text-center">
              <span class="badge text-bg-primary text-white rounded-pill mb-1" style="font-size: 0.62rem;">CURRENT MONTH</span>
              <div class="fw-bold text-dark small mb-1"><?= esc($m['short_name']) ?> <?= esc($m['year']) ?></div>
              <div class="small text-primary mb-2" style="font-size: 0.72rem;">Attendance in progress</div>
              <div>
                <a href="<?= esc($m['url']) ?>" class="btn btn-sm btn-primary py-1 px-2 rounded-2 fw-semibold" style="font-size: 0.72rem;">View details</a>
              </div>
            </div>
          </div>

        <?php else: ?>
          <!-- FUTURE / LOCKED Card -->
          <div class="flex-shrink-0 text-center" style="min-width: 100px;">
            <div class="d-flex justify-content-center mb-3">
              <span class="bg-secondary-subtle text-secondary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 10px;">🔒</span>
            </div>
            <div class="card border border-secondary-subtle rounded-3 p-2 bg-light text-center opacity-75">
              <div class="fw-semibold text-secondary small mb-1"><?= esc($m['short_name']) ?></div>
              <div class="text-body-tertiary mb-1" style="font-size: 1rem;">🔒</div>
              <div class="text-body-tertiary" style="font-size: 0.65rem;"><?= esc($m['opens_text']) ?></div>
            </div>
          </div>
        <?php endif; ?>

      <?php endforeach; ?>
    </div>
  </div>

  <!-- Legend Footer -->
  <div class="d-flex flex-wrap align-items-center gap-4 pt-3 mt-3 border-top small text-body-secondary">
    <div class="d-flex align-items-center gap-1">
      <span class="badge text-bg-success rounded-circle p-1">✓</span>
      <span>Completed</span>
    </div>
    <div class="d-flex align-items-center gap-1">
      <span class="badge text-bg-danger rounded-circle p-1">⚠️</span>
      <span>Action Required / Overdue</span>
    </div>
    <div class="d-flex align-items-center gap-1">
      <span class="badge text-bg-warning rounded-circle p-1">⚠️</span>
      <span>Incomplete (Past)</span>
    </div>
    <div class="d-flex align-items-center gap-1">
      <span class="badge text-bg-primary rounded-circle p-1">●</span>
      <span>Current Month</span>
    </div>
    <div class="d-flex align-items-center gap-1">
      <span class="text-secondary">🔒</span>
      <span>Future / Locked</span>
    </div>
  </div>
</div>

<?= $this->endSection() ?>