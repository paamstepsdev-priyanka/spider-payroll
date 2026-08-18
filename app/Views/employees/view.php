<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
// Sensitive data masking helpers
$maskBankAccount = function ($acc) {
    if (empty($acc)) return '-';
    $len = strlen($acc);
    if ($len <= 4) return $acc;
    return str_repeat('X', max(0, $len - 4)) . substr($acc, -4);
};

$maskPan = function ($pan) {
    if (empty($pan)) return '-';
    $len = strlen($pan);
    if ($len < 5) return $pan;
    return str_repeat('X', 5) . substr($pan, 5);
};

$maskAadhaar = function ($aadhaar) {
    if (empty($aadhaar)) return '-';
    $clean = str_replace(' ', '', $aadhaar);
    $len = strlen($clean);
    if ($len <= 4) return $clean;
    return 'XXXX XXXX ' . substr($clean, -4);
};
?>

<div class="card mb-4" style="background-color: #ffffff !important; border: 1px solid #e5e7eb; border-radius: 6px;">
  <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #ffffff !important; border-bottom: 1px solid #e5e7eb;">
    <div>
      <h4 class="mb-0 fw-semibold text-dark fs-5"><?= esc($employee['employee_name']) ?></h4>
      <div class="text-secondary small mt-1">
        Employee Code: <?= !empty($employee['biometric_code']) ? esc($employee['biometric_code']) : 'N/A' ?> &bull; Contractor: <?= esc($employee['contractor_name'] ?? 'N/A') ?>
      </div>
    </div>
    <div class="d-flex gap-2">
      <a href="<?= site_url('employees/edit/' . $employee['employee_id']) ?>" class="btn btn-primary text-decoration-none" style="border-radius: 6px; font-size: 13px; padding: 7px 16px;">
        Edit Employee
      </a>
      <a href="<?= site_url('employees') ?>" class="btn btn-outline-secondary text-decoration-none" style="border-radius: 6px; font-size: 13px; padding: 7px 16px;">
        &larr; Back to Employees
      </a>
    </div>
  </div>
  <div class="card-body p-4" style="background-color: #ffffff !important;">

    <!-- SECTION 1 — EMPLOYEE DETAILS -->
    <div class="mb-4">
      <h6 class="text-primary fw-bold text-uppercase fs-7 tracking-wide border-bottom pb-2 mb-3">
        1. Employee Details
      </h6>
      <div class="row g-3">
        <div class="col-md-4">
          <div class="text-secondary small fw-semibold">Employee Name</div>
          <div class="fs-6 text-dark fw-medium mt-1"><?= esc($employee['employee_name']) ?></div>
        </div>
        <div class="col-md-4">
          <div class="text-secondary small fw-semibold">Phone Number</div>
          <div class="fs-6 text-dark mt-1"><?= esc($employee['phone_number'] ?: '-') ?></div>
        </div>
        <div class="col-md-4">
          <div class="text-secondary small fw-semibold">Biometric Code</div>
          <div class="fs-6 text-dark mt-1">
            <?php if (!empty($employee['biometric_code'])): ?>
              <span class="badge bg-light text-dark border"><?= esc($employee['biometric_code']) ?></span>
            <?php else: ?>
              <span class="text-muted">-</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="row g-3 mt-1">
        <div class="col-md-6">
          <div class="text-secondary small fw-semibold">Gender</div>
          <div class="fs-6 text-dark mt-1"><?= ucfirst(esc($employee['gender'])) ?></div>
        </div>
        <div class="col-md-6">
          <div class="text-secondary small fw-semibold">Date of Birth</div>
          <div class="fs-6 text-dark mt-1">
            <?= !empty($employee['date_of_birth']) ? date('d/m/Y', strtotime($employee['date_of_birth'])) : '-' ?>
          </div>
        </div>
      </div>
    </div>

    <!-- SECTION 2 — EMPLOYMENT DETAILS -->
    <div class="mb-4">
      <h6 class="text-primary fw-bold text-uppercase fs-7 tracking-wide border-bottom pb-2 mb-3">
        2. Employment Details
      </h6>
      <div class="row g-3">
        <div class="col-md-3">
          <div class="text-secondary small fw-semibold">Contractor</div>
          <div class="fs-6 text-dark fw-medium mt-1"><?= esc($employee['contractor_name'] ?? '-') ?></div>
        </div>
        <div class="col-md-3">
          <div class="text-secondary small fw-semibold">Designation</div>
          <div class="fs-6 text-dark mt-1"><?= esc($employee['designation'] ?: '-') ?></div>
        </div>
        <div class="col-md-3">
          <div class="text-secondary small fw-semibold">Department</div>
          <div class="fs-6 text-dark mt-1"><?= esc($employee['department'] ?: '-') ?></div>
        </div>
        <div class="col-md-3">
          <div class="text-secondary small fw-semibold">Monthly Base Salary</div>
          <div class="fs-6 text-dark fw-bold mt-1">₹<?= number_format((float)$employee['monthly_base_salary'], 2) ?></div>
        </div>
      </div>
      <div class="row g-3 mt-1">
        <div class="col-md-3">
          <div class="text-secondary small fw-semibold">Date of Joining</div>
          <div class="fs-6 text-dark mt-1">
            <?= !empty($employee['date_of_joining']) ? date('d/m/Y', strtotime($employee['date_of_joining'])) : '-' ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="text-secondary small fw-semibold">Status</div>
          <div class="mt-1">
            <?php if ($employee['status'] === 'active'): ?>
              <span class="badge bg-success bg-opacity-10 text-success fw-semibold px-2 py-1">Active</span>
            <?php elseif ($employee['status'] === 'relieved'): ?>
              <span class="badge bg-warning bg-opacity-10 text-warning fw-semibold px-2 py-1">Relieved</span>
            <?php else: ?>
              <span class="badge bg-secondary bg-opacity-10 text-secondary fw-semibold px-2 py-1">Inactive</span>
            <?php endif; ?>
          </div>
        </div>
        <?php if (!empty($employee['date_of_leaving']) || $employee['status'] === 'relieved'): ?>
          <div class="col-md-3">
            <div class="text-secondary small fw-semibold">Date of Leaving</div>
            <div class="fs-6 text-dark mt-1">
              <?= !empty($employee['date_of_leaving']) ? date('d/m/Y', strtotime($employee['date_of_leaving'])) : '-' ?>
            </div>
          </div>
          <div class="col-md-3">
            <div class="text-secondary small fw-semibold">Exit Reason</div>
            <div class="fs-6 text-dark mt-1"><?= esc($employee['exit_reason'] ?: '-') ?></div>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- SECTION 3 — BANK DETAILS -->
    <div class="mb-4">
      <h6 class="text-primary fw-bold text-uppercase fs-7 tracking-wide border-bottom pb-2 mb-3">
        3. Bank Details
      </h6>
      <div class="row g-3">
        <div class="col-md-4">
          <div class="text-secondary small fw-semibold">Bank Name</div>
          <div class="fs-6 text-dark mt-1"><?= esc($employee['bank_name'] ?: '-') ?></div>
        </div>
        <div class="col-md-4">
          <div class="text-secondary small fw-semibold">Bank Account Number</div>
          <div class="fs-6 text-dark fw-monospace mt-1">
            <?= esc($maskBankAccount($employee['bank_account_number'])) ?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="text-secondary small fw-semibold">IFSC Code</div>
          <div class="fs-6 text-dark fw-monospace mt-1"><?= esc($employee['ifsc_code'] ?: '-') ?></div>
        </div>
      </div>
    </div>

    <!-- SECTION 4 — IDENTIFICATION -->
    <div class="mb-4">
      <h6 class="text-primary fw-bold text-uppercase fs-7 tracking-wide border-bottom pb-2 mb-3">
        4. Identification
      </h6>
      <div class="row g-3">
        <div class="col-md-6">
          <div class="text-secondary small fw-semibold">PAN Number</div>
          <div class="fs-6 text-dark fw-monospace mt-1">
            <?= esc($maskPan($employee['pan_number'])) ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="text-secondary small fw-semibold">Aadhaar Number</div>
          <div class="fs-6 text-dark fw-monospace mt-1">
            <?= esc($maskAadhaar($employee['aadhaar_number'])) ?>
          </div>
        </div>
      </div>
    </div>

    <!-- SECTION 5 — SYSTEM INFORMATION -->
    <div class="mb-2">
      <h6 class="text-primary fw-bold text-uppercase fs-7 tracking-wide border-bottom pb-2 mb-3">
        5. System Information
      </h6>
      <div class="row g-3">
        <div class="col-md-6">
          <div class="text-secondary small fw-semibold">Created Date</div>
          <div class="fs-6 text-dark mt-1">
            <?= !empty($employee['created_at']) ? date('d M Y, h:i A', strtotime($employee['created_at'])) : '-' ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="text-secondary small fw-semibold">Last Updated</div>
          <div class="fs-6 text-dark mt-1">
            <?= !empty($employee['updated_at']) ? date('d M Y, h:i A', strtotime($employee['updated_at'])) : '-' ?>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<?= $this->endSection() ?>
