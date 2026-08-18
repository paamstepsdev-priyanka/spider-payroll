<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card mb-4" style="background-color: #ffffff !important; border: 1px solid #e5e7eb; border-radius: 6px;">
  <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #ffffff !important; border-bottom: 1px solid #e5e7eb;">
    <div>
      <h4 class="mb-0 fw-semibold text-dark fs-5">Add Employee</h4>
      <div class="text-secondary small mt-1">Fill in the employee details below</div>
    </div>
    <a href="<?= site_url('employees') ?>" class="btn btn-outline-secondary text-decoration-none" style="border-radius: 6px; font-size: 13px; padding: 7px 16px;">
      &larr; Back to Employees
    </a>
  </div>
  <div class="card-body p-4" style="background-color: #ffffff !important;">

    <!-- Flash Error Notification -->
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 6px; font-size: 14px;">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if (session('errors')): ?>
      <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 6px; font-size: 14px;">
        <div class="fw-semibold mb-1">Please fix the errors in the form below:</div>
        <ul class="mb-0 ps-3">
          <?php foreach (session('errors') as $error): ?>
            <li><?= esc($error) ?></li>
          <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <!-- STEP NAVIGATION INDICATOR -->
    <div class="mb-4">
      <div class="nav nav-pills nav-justified gap-2 border p-2 rounded bg-light" id="employeeStepperNav">
        <button type="button" class="nav-link active text-start d-flex align-items-center gap-2" data-step="1" id="step-tab-1">
          <span class="badge bg-primary text-white rounded-circle p-2 px-3 step-num" id="badge-step-1">1</span>
          <div class="d-none d-sm-block">
            <div class="fw-semibold text-truncate" style="font-size: 13px;">1 Employee Details</div>
          </div>
        </button>

        <button type="button" class="nav-link text-start d-flex align-items-center gap-2 disabled" data-step="2" id="step-tab-2">
          <span class="badge bg-secondary text-white rounded-circle p-2 px-3 step-num" id="badge-step-2">2</span>
          <div class="d-none d-sm-block">
            <div class="fw-semibold text-truncate" style="font-size: 13px;">2 Employment</div>
          </div>
        </button>

        <button type="button" class="nav-link text-start d-flex align-items-center gap-2 disabled" data-step="3" id="step-tab-3">
          <span class="badge bg-secondary text-white rounded-circle p-2 px-3 step-num" id="badge-step-3">3</span>
          <div class="d-none d-sm-block">
            <div class="fw-semibold text-truncate" style="font-size: 13px;">3 Bank Details</div>
          </div>
        </button>

        <button type="button" class="nav-link text-start d-flex align-items-center gap-2 disabled" data-step="4" id="step-tab-4">
          <span class="badge bg-secondary text-white rounded-circle p-2 px-3 step-num" id="badge-step-4">4</span>
          <div class="d-none d-sm-block">
            <div class="fw-semibold text-truncate" style="font-size: 13px;">4 Identification</div>
          </div>
        </button>

        <button type="button" class="nav-link text-start d-flex align-items-center gap-2 disabled" data-step="5" id="step-tab-5">
          <span class="badge bg-secondary text-white rounded-circle p-2 px-3 step-num" id="badge-step-5">5</span>
          <div class="d-none d-sm-block">
            <div class="fw-semibold text-truncate" style="font-size: 13px;">5 Exit Details</div>
          </div>
        </button>
      </div>
    </div>

    <!-- EMPLOYEE FORM -->
    <form action="<?= site_url('employees/store') ?>" method="POST" id="employeeForm" autocomplete="off" novalidate>
      <?= csrf_field() ?>

      <!-- STEP 1 — EMPLOYEE DETAILS -->
      <div class="step-panel" id="step-panel-1">
        <h6 class="text-primary fw-bold text-uppercase fs-7 tracking-wide border-bottom pb-2 mb-3">
          Step 1: Employee Details
        </h6>
        <div class="row">
          <!-- Employee Name -->
          <div class="col-md-6 mb-3">
            <label for="employee_name" class="form-label small fw-semibold text-secondary">Employee Name <span class="text-danger">*</span></label>
            <input type="text" name="employee_name" id="employee_name" class="form-control <?= session('errors.employee_name') ? 'is-invalid' : '' ?>" value="<?= old('employee_name') ?>" placeholder="e.g. Rahul Patil" required>
            <div class="invalid-feedback" id="err_employee_name">
              <?= session('errors.employee_name') ?: 'Employee Name is required.' ?>
            </div>
          </div>

          <!-- Phone Number -->
          <div class="col-md-6 mb-3">
            <label for="phone_number" class="form-label small fw-semibold text-secondary">Phone Number</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control <?= session('errors.phone_number') ? 'is-invalid' : '' ?>" value="<?= old('phone_number') ?>" placeholder="e.g. 9876543210">
            <?php if (session('errors.phone_number')): ?>
              <div class="invalid-feedback d-block"><?= session('errors.phone_number') ?></div>
            <?php endif; ?>
          </div>
        </div>

        <div class="row">
          <!-- Biometric Code -->
          <div class="col-md-6 mb-3">
            <label for="biometric_code" class="form-label small fw-semibold text-secondary">Biometric Code</label>
            <input type="text" name="biometric_code" id="biometric_code" class="form-control <?= session('errors.biometric_code') ? 'is-invalid' : '' ?>" value="<?= old('biometric_code') ?>" placeholder="e.g. BIO001">
            <div class="form-text text-muted small">Unique code for attendance matching.</div>
            <?php if (session('errors.biometric_code')): ?>
              <div class="invalid-feedback d-block"><?= session('errors.biometric_code') ?></div>
            <?php endif; ?>
          </div>

          <!-- Gender -->
          <div class="col-md-3 mb-3">
            <label for="gender" class="form-label small fw-semibold text-secondary">Gender <span class="text-danger">*</span></label>
            <select name="gender" id="gender" class="form-select <?= session('errors.gender') ? 'is-invalid' : '' ?>" required>
              <option value="">Select Gender</option>
              <option value="male" <?= old('gender') === 'male' ? 'selected' : '' ?>>Male</option>
              <option value="female" <?= old('gender') === 'female' ? 'selected' : '' ?>>Female</option>
              <option value="other" <?= old('gender') === 'other' ? 'selected' : '' ?>>Other</option>
            </select>
            <div class="invalid-feedback" id="err_gender">
              <?= session('errors.gender') ?: 'Please select a gender.' ?>
            </div>
          </div>

          <!-- Date of Birth -->
          <div class="col-md-3 mb-3">
            <label for="date_of_birth" class="form-label small fw-semibold text-secondary">Date of Birth <span class="text-danger">*</span></label>
            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control <?= session('errors.date_of_birth') ? 'is-invalid' : '' ?>" value="<?= old('date_of_birth') ?>" required>
            <div class="invalid-feedback" id="err_date_of_birth">
              <?= session('errors.date_of_birth') ?: 'Date of Birth is required.' ?>
            </div>
          </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-end pt-3 border-top mt-3">
          <button type="button" class="btn btn-primary px-4 btn-step-next" style="border-radius: 6px; font-size: 13px;">
            Next &rarr;
          </button>
        </div>
      </div>

      <!-- STEP 2 — EMPLOYMENT DETAILS -->
      <div class="step-panel d-none" id="step-panel-2">
        <h6 class="text-primary fw-bold text-uppercase fs-7 tracking-wide border-bottom pb-2 mb-3">
          Step 2: Employment Details
        </h6>
        <div class="row">
          <!-- Contractor -->
          <div class="col-md-6 mb-3">
            <label for="contractor_id" class="form-label small fw-semibold text-secondary">Contractor</label>
            <select name="contractor_id" id="contractor_id" class="form-select <?= session('errors.contractor_id') ? 'is-invalid' : '' ?>">
              <option value="">Select Contractor</option>
              <?php foreach ($contractors as $contractor): ?>
                <option value="<?= $contractor['contractor_id'] ?>" <?= (string)old('contractor_id') === (string)$contractor['contractor_id'] ? 'selected' : '' ?>>
                  <?= esc($contractor['contractor_name']) ?> (<?= esc($contractor['contractor_code']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
            <div class="form-text text-muted small">Optional. Leave blank if not assigned to a contractor.</div>
            <?php if (session('errors.contractor_id')): ?>
              <div class="invalid-feedback d-block"><?= session('errors.contractor_id') ?></div>
            <?php endif; ?>
          </div>

          <!-- Date of Joining -->
          <div class="col-md-6 mb-3">
            <label for="date_of_joining" class="form-label small fw-semibold text-secondary">Date of Joining <span class="text-danger">*</span></label>
            <input type="date" name="date_of_joining" id="date_of_joining" class="form-control <?= session('errors.date_of_joining') ? 'is-invalid' : '' ?>" value="<?= old('date_of_joining') ?>" required>
            <div class="invalid-feedback" id="err_date_of_joining">
              <?= session('errors.date_of_joining') ?: 'Date of Joining is required.' ?>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- Designation -->
          <div class="col-md-6 mb-3">
            <label for="designation" class="form-label small fw-semibold text-secondary">Designation</label>
            <input type="text" name="designation" id="designation" class="form-control <?= session('errors.designation') ? 'is-invalid' : '' ?>" value="<?= old('designation') ?>" placeholder="e.g. Helper, Supervisor">
            <?php if (session('errors.designation')): ?>
              <div class="invalid-feedback d-block"><?= session('errors.designation') ?></div>
            <?php endif; ?>
          </div>

          <!-- Department -->
          <div class="col-md-6 mb-3">
            <label for="department" class="form-label small fw-semibold text-secondary">Department</label>
            <input type="text" name="department" id="department" class="form-control <?= session('errors.department') ? 'is-invalid' : '' ?>" value="<?= old('department') ?>" placeholder="e.g. Production, Maintenance">
            <?php if (session('errors.department')): ?>
              <div class="invalid-feedback d-block"><?= session('errors.department') ?></div>
            <?php endif; ?>
          </div>
        </div>

        <div class="row">
          <!-- Monthly Base Salary -->
          <div class="col-md-6 mb-3">
            <label for="monthly_base_salary" class="form-label small fw-semibold text-secondary">Monthly Base Salary (₹) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" name="monthly_base_salary" id="monthly_base_salary" class="form-control <?= session('errors.monthly_base_salary') ? 'is-invalid' : '' ?>" value="<?= old('monthly_base_salary', '0.00') ?>" placeholder="18000.00" required>
            <div class="invalid-feedback" id="err_monthly_base_salary">
              <?= session('errors.monthly_base_salary') ?: 'Monthly Base Salary is required.' ?>
            </div>
          </div>

          <!-- Status -->
          <div class="col-md-6 mb-3">
            <label for="status" class="form-label small fw-semibold text-secondary">Status <span class="text-danger">*</span></label>
            <select name="status" id="status" class="form-select <?= session('errors.status') ? 'is-invalid' : '' ?>" required>
              <option value="active" <?= old('status', 'active') === 'active' ? 'selected' : '' ?>>Active</option>
              <option value="relieved" <?= old('status') === 'relieved' ? 'selected' : '' ?>>Relieved</option>
              <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
            <div class="invalid-feedback" id="err_status">
              <?= session('errors.status') ?: 'Status selection is required.' ?>
            </div>
          </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-between pt-3 border-top mt-3">
          <button type="button" class="btn btn-outline-secondary px-4 btn-step-prev" style="border-radius: 6px; font-size: 13px;">
            &larr; Previous
          </button>
          <button type="button" class="btn btn-primary px-4 btn-step-next" style="border-radius: 6px; font-size: 13px;">
            Next &rarr;
          </button>
        </div>
      </div>

      <!-- STEP 3 — BANK DETAILS -->
      <div class="step-panel d-none" id="step-panel-3">
        <h6 class="text-primary fw-bold text-uppercase fs-7 tracking-wide border-bottom pb-2 mb-3">
          Step 3: Bank Details <span class="text-muted fw-normal text-lowercase fs-7">(Optional)</span>
        </h6>
        <div class="row">
          <!-- Bank Name -->
          <div class="col-md-4 mb-3">
            <label for="bank_name" class="form-label small fw-semibold text-secondary">Bank Name</label>
            <input type="text" name="bank_name" id="bank_name" class="form-control <?= session('errors.bank_name') ? 'is-invalid' : '' ?>" value="<?= old('bank_name') ?>" placeholder="e.g. HDFC Bank">
            <?php if (session('errors.bank_name')): ?>
              <div class="invalid-feedback d-block"><?= session('errors.bank_name') ?></div>
            <?php endif; ?>
          </div>

          <!-- Bank Account Number -->
          <div class="col-md-4 mb-3">
            <label for="bank_account_number" class="form-label small fw-semibold text-secondary">Bank Account Number</label>
            <input type="text" name="bank_account_number" id="bank_account_number" class="form-control <?= session('errors.bank_account_number') ? 'is-invalid' : '' ?>" value="<?= old('bank_account_number') ?>" placeholder="e.g. 001234567890">
            <?php if (session('errors.bank_account_number')): ?>
              <div class="invalid-feedback d-block"><?= session('errors.bank_account_number') ?></div>
            <?php endif; ?>
          </div>

          <!-- IFSC Code -->
          <div class="col-md-4 mb-3">
            <label for="ifsc_code" class="form-label small fw-semibold text-secondary">IFSC Code</label>
            <input type="text" name="ifsc_code" id="ifsc_code" class="form-control text-uppercase <?= session('errors.ifsc_code') ? 'is-invalid' : '' ?>" value="<?= old('ifsc_code') ?>" placeholder="e.g. HDFC0001234">
            <?php if (session('errors.ifsc_code')): ?>
              <div class="invalid-feedback d-block"><?= session('errors.ifsc_code') ?></div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-between pt-3 border-top mt-3">
          <button type="button" class="btn btn-outline-secondary px-4 btn-step-prev" style="border-radius: 6px; font-size: 13px;">
            &larr; Previous
          </button>
          <button type="button" class="btn btn-primary px-4 btn-step-next" style="border-radius: 6px; font-size: 13px;">
            Next &rarr;
          </button>
        </div>
      </div>

      <!-- STEP 4 — IDENTIFICATION -->
      <div class="step-panel d-none" id="step-panel-4">
        <h6 class="text-primary fw-bold text-uppercase fs-7 tracking-wide border-bottom pb-2 mb-3">
          Step 4: Identification <span class="text-muted fw-normal text-lowercase fs-7">(Optional)</span>
        </h6>
        <div class="row">
          <!-- PAN Number -->
          <div class="col-md-6 mb-3">
            <label for="pan_number" class="form-label small fw-semibold text-secondary">PAN Number</label>
            <input type="text" name="pan_number" id="pan_number" class="form-control text-uppercase <?= session('errors.pan_number') ? 'is-invalid' : '' ?>" value="<?= old('pan_number') ?>" placeholder="e.g. ABCDE1234F">
            <?php if (session('errors.pan_number')): ?>
              <div class="invalid-feedback d-block"><?= session('errors.pan_number') ?></div>
            <?php endif; ?>
          </div>

          <!-- Aadhaar Number -->
          <div class="col-md-6 mb-3">
            <label for="aadhaar_number" class="form-label small fw-semibold text-secondary">Aadhaar Number</label>
            <input type="text" name="aadhaar_number" id="aadhaar_number" class="form-control <?= session('errors.aadhaar_number') ? 'is-invalid' : '' ?>" value="<?= old('aadhaar_number') ?>" placeholder="e.g. 123456789012">
            <?php if (session('errors.aadhaar_number')): ?>
              <div class="invalid-feedback d-block"><?= session('errors.aadhaar_number') ?></div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-between pt-3 border-top mt-3">
          <button type="button" class="btn btn-outline-secondary px-4 btn-step-prev" style="border-radius: 6px; font-size: 13px;">
            &larr; Previous
          </button>
          <button type="button" class="btn btn-primary px-4 btn-step-next" style="border-radius: 6px; font-size: 13px;">
            Next &rarr;
          </button>
        </div>
      </div>

      <!-- STEP 5 — EXIT DETAILS -->
      <div class="step-panel d-none" id="step-panel-5">
        <h6 class="text-primary fw-bold text-uppercase fs-7 tracking-wide border-bottom pb-2 mb-3">
          Step 5: Exit Details <span class="text-muted fw-normal text-lowercase fs-7" id="exit-required-hint">(Required if Status is Relieved)</span>
        </h6>
        <div class="row">
          <!-- Date of Leaving -->
          <div class="col-md-6 mb-3">
            <label for="date_of_leaving" class="form-label small fw-semibold text-secondary">Date of Leaving <span class="text-danger d-none" id="req_star_date_of_leaving">*</span></label>
            <input type="date" name="date_of_leaving" id="date_of_leaving" class="form-control <?= session('errors.date_of_leaving') ? 'is-invalid' : '' ?>" value="<?= old('date_of_leaving') ?>">
            <div class="invalid-feedback" id="err_date_of_leaving">
              <?= session('errors.date_of_leaving') ?: 'Date of Leaving is required when employee status is Relieved.' ?>
            </div>
          </div>

          <!-- Exit Reason -->
          <div class="col-md-6 mb-3">
            <label for="exit_reason" class="form-label small fw-semibold text-secondary">Exit Reason <span class="text-danger d-none" id="req_star_exit_reason">*</span></label>
            <input type="text" name="exit_reason" id="exit_reason" class="form-control <?= session('errors.exit_reason') ? 'is-invalid' : '' ?>" value="<?= old('exit_reason') ?>" placeholder="e.g. Resigned, Contract ended">
            <div class="invalid-feedback" id="err_exit_reason">
              <?= session('errors.exit_reason') ?: 'Exit Reason is required when employee status is Relieved.' ?>
            </div>
          </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-between pt-3 border-top mt-3">
          <button type="button" class="btn btn-outline-secondary px-4 btn-step-prev" style="border-radius: 6px; font-size: 13px;">
            &larr; Previous
          </button>
          <button type="submit" class="btn btn-primary px-4" style="border-radius: 6px; font-size: 13px;">
            Save Employee
          </button>
        </div>
      </div>

    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  let currentStep = 1;
  const maxSteps = 5;
  let maxVisitedStep = 1;

  const form = document.getElementById('employeeForm');
  const statusSelect = document.getElementById('status');
  const dateOfLeavingInput = document.getElementById('date_of_leaving');
  const exitReasonInput = document.getElementById('exit_reason');
  const reqStarDateLeaving = document.getElementById('req_star_date_of_leaving');
  const reqStarExitReason = document.getElementById('req_star_exit_reason');

  // Check if any step has server-side errors
  <?php if (session('errors')): ?>
    <?php
      $errs = session('errors');
      $step1Errs = isset($errs['employee_name']) || isset($errs['biometric_code']) || isset($errs['phone_number']) || isset($errs['gender']) || isset($errs['date_of_birth']);
      $step2Errs = isset($errs['contractor_id']) || isset($errs['date_of_joining']) || isset($errs['designation']) || isset($errs['department']) || isset($errs['monthly_base_salary']) || isset($errs['status']);
      $step3Errs = isset($errs['bank_name']) || isset($errs['bank_account_number']) || isset($errs['ifsc_code']);
      $step4Errs = isset($errs['pan_number']) || isset($errs['aadhaar_number']);
      $step5Errs = isset($errs['date_of_leaving']) || isset($errs['exit_reason']);

      if ($step5Errs) $initialStep = 5;
      elseif ($step4Errs) $initialStep = 4;
      elseif ($step3Errs) $initialStep = 3;
      elseif ($step2Errs) $initialStep = 2;
      else $initialStep = 1;
    ?>
    currentStep = <?= $initialStep ?>;
    maxVisitedStep = 5;
  <?php endif; ?>

  function updateExitRequiredUI() {
    const isRelieved = (statusSelect.value === 'relieved');
    if (isRelieved) {
      if (reqStarDateLeaving) reqStarDateLeaving.classList.remove('d-none');
      if (reqStarExitReason) reqStarExitReason.classList.remove('d-none');
    } else {
      if (reqStarDateLeaving) reqStarDateLeaving.classList.add('d-none');
      if (reqStarExitReason) reqStarExitReason.classList.add('d-none');
      dateOfLeavingInput.classList.remove('is-invalid');
      exitReasonInput.classList.remove('is-invalid');
    }
  }

  if (statusSelect) {
    statusSelect.addEventListener('change', updateExitRequiredUI);
    updateExitRequiredUI();
  }

  function renderStep(step) {
    // Hide all step panels
    document.querySelectorAll('.step-panel').forEach(panel => panel.classList.add('d-none'));

    // Show current step panel
    const currentPanel = document.getElementById('step-panel-' + step);
    if (currentPanel) {
      currentPanel.classList.remove('d-none');
    }

    if (step > maxVisitedStep) {
      maxVisitedStep = step;
    }

    // Update Nav Header Buttons & Badges
    for (let i = 1; i <= maxSteps; i++) {
      const tab = document.getElementById('step-tab-' + i);
      const badge = document.getElementById('badge-step-' + i);

      if (!tab || !badge) continue;

      if (i === step) {
        tab.classList.add('active');
        tab.classList.remove('disabled');
        badge.className = 'badge bg-primary text-white rounded-circle p-2 px-3 step-num';
        badge.innerHTML = i;
      } else if (i < step || i <= maxVisitedStep) {
        tab.classList.remove('active', 'disabled');
        badge.className = 'badge bg-success text-white rounded-circle p-1 px-2 step-num';
        badge.innerHTML = '&#10003;';
      } else {
        tab.classList.remove('active');
        tab.classList.add('disabled');
        badge.className = 'badge bg-secondary text-white rounded-circle p-2 px-3 step-num';
        badge.innerHTML = i;
      }
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function validateCurrentStep(step) {
    let isValid = true;
    const panel = document.getElementById('step-panel-' + step);
    if (!panel) return true;

    if (step === 1) {
      const empName = document.getElementById('employee_name');
      const dob = document.getElementById('date_of_birth');
      const gender = document.getElementById('gender');

      if (!empName.value.trim()) {
        empName.classList.add('is-invalid');
        isValid = false;
      } else {
        empName.classList.remove('is-invalid');
      }

      if (!dob.value.trim()) {
        dob.classList.add('is-invalid');
        isValid = false;
      } else {
        dob.classList.remove('is-invalid');
      }

      if (!gender.value.trim()) {
        gender.classList.add('is-invalid');
        isValid = false;
      } else {
        gender.classList.remove('is-invalid');
      }
    } else if (step === 2) {
      const doj = document.getElementById('date_of_joining');
      const salary = document.getElementById('monthly_base_salary');
      const status = document.getElementById('status');

      if (!doj.value.trim()) {
        doj.classList.add('is-invalid');
        isValid = false;
      } else {
        doj.classList.remove('is-invalid');
      }

      if (salary.value === '' || parseFloat(salary.value) < 0) {
        salary.classList.add('is-invalid');
        isValid = false;
      } else {
        salary.classList.remove('is-invalid');
      }

      if (!status.value.trim()) {
        status.classList.add('is-invalid');
        isValid = false;
      } else {
        status.classList.remove('is-invalid');
      }
    } else if (step === 5) {
      const statusVal = statusSelect.value;
      if (statusVal === 'relieved') {
        if (!dateOfLeavingInput.value.trim()) {
          dateOfLeavingInput.classList.add('is-invalid');
          isValid = false;
        } else {
          dateOfLeavingInput.classList.remove('is-invalid');
        }

        if (!exitReasonInput.value.trim()) {
          exitReasonInput.classList.add('is-invalid');
          isValid = false;
        } else {
          exitReasonInput.classList.remove('is-invalid');
        }
      } else {
        dateOfLeavingInput.classList.remove('is-invalid');
        exitReasonInput.classList.remove('is-invalid');
      }
    }

    return isValid;
  }

  // Handle Form Submit on Step 5
  form.addEventListener('submit', function(e) {
    if (!validateCurrentStep(currentStep)) {
      e.preventDefault();
    }
  });

  // Handle Next Buttons
  document.querySelectorAll('.btn-step-next').forEach(btn => {
    btn.addEventListener('click', function() {
      if (validateCurrentStep(currentStep)) {
        if (currentStep < maxSteps) {
          currentStep++;
          renderStep(currentStep);
        }
      }
    });
  });

  // Handle Previous Buttons
  document.querySelectorAll('.btn-step-prev').forEach(btn => {
    btn.addEventListener('click', function() {
      if (currentStep > 1) {
        currentStep--;
        renderStep(currentStep);
      }
    });
  });

  // Handle Tab Clicks
  for (let i = 1; i <= maxSteps; i++) {
    const tab = document.getElementById('step-tab-' + i);
    if (tab) {
      tab.addEventListener('click', function() {
        if (i <= maxVisitedStep || validateCurrentStep(currentStep)) {
          currentStep = i;
          renderStep(currentStep);
        }
      });
    }
  }

  // Initial render
  renderStep(currentStep);
});
</script>

<?= $this->endSection() ?>
