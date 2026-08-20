<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Back Link Pill -->
<div class="mb-3">
  <a href="<?= site_url('employees') ?>" class="btn btn-sm btn-white border rounded-pill px-3 shadow-sm text-dark fw-bold">
    <i class="bi bi-arrow-left me-1"></i> Back to Employees
  </a>
</div>

<!-- Main Stepper Card Container -->
<div class="card shadow-sm rounded-4 border-0 mb-4 overflow-hidden">
  <!-- Card Header -->
  <div class="card-header bg-white p-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div class="d-flex align-items-center gap-3">
      <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
        <i class="bi bi-person-plus-fill fs-4"></i>
      </div>
      <div>
        <h4 class="fw-bold mb-0 text-dark">Add New Employee</h4>
        <span class="text-secondary small">Create a new employee record by filling in the details step by step.</span>
      </div>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="<?= site_url('employees') ?>" class="btn btn-light rounded-3 px-3 fw-bold border text-secondary" style="font-size: 13px;">Cancel</a>
      <button type="submit" form="employeeForm" class="btn btn-primary rounded-3 px-4 fw-bold shadow-sm" style="font-size: 13px;">
        <i class="bi bi-check-lg me-1"></i> Create Employee
      </button>
    </div>
  </div>

  <!-- Card Body with Left Sidebar Stepper + Right Form Area -->
  <div class="card-body p-0">
    <div class="row g-0">
      
      <!-- LEFT SIDEBAR STEPPER -->
      <div class="col-lg-3 border-end bg-light bg-opacity-50 p-4 d-flex flex-column justify-content-between" style="min-height: 540px;">
        <div>
          <!-- Vertical Stepper Nav -->
          <div class="vertical-stepper-list" id="employeeStepperNav">
            
            <!-- Step 1 -->
            <div class="stepper-step-item active-step" id="step-item-1">
              <div class="stepper-step-line"></div>
              <button type="button" class="stepper-step-button" data-step="1" id="step-tab-1">
                <span class="stepper-step-circle" id="badge-step-1">1</span>
                <div class="stepper-step-content">
                  <div class="stepper-step-title">Basic Information</div>
                  <div class="stepper-step-sub">Personal & contact</div>
                </div>
              </button>
            </div>

            <!-- Step 2 -->
            <div class="stepper-step-item" id="step-item-2">
              <div class="stepper-step-line"></div>
              <button type="button" class="stepper-step-button disabled" data-step="2" id="step-tab-2">
                <span class="stepper-step-circle" id="badge-step-2">2</span>
                <div class="stepper-step-content">
                  <div class="stepper-step-title">Employment Details</div>
                  <div class="stepper-step-sub">Job & organization</div>
                </div>
              </button>
            </div>

            <!-- Step 3 -->
            <div class="stepper-step-item" id="step-item-3">
              <div class="stepper-step-line"></div>
              <button type="button" class="stepper-step-button disabled" data-step="3" id="step-tab-3">
                <span class="stepper-step-circle" id="badge-step-3">3</span>
                <div class="stepper-step-content">
                  <div class="stepper-step-title">Bank Details</div>
                  <div class="stepper-step-sub">Bank account info</div>
                </div>
              </button>
            </div>

            <!-- Step 4 -->
            <div class="stepper-step-item" id="step-item-4">
              <div class="stepper-step-line"></div>
              <button type="button" class="stepper-step-button disabled" data-step="4" id="step-tab-4">
                <span class="stepper-step-circle" id="badge-step-4">4</span>
                <div class="stepper-step-content">
                  <div class="stepper-step-title">Identification</div>
                  <div class="stepper-step-sub">PAN & verification</div>
                </div>
              </button>
            </div>

            <!-- Step 5 -->
            <div class="stepper-step-item" id="step-item-5">
              <div class="stepper-step-line"></div>
              <button type="button" class="stepper-step-button disabled" data-step="5" id="step-tab-5">
                <span class="stepper-step-circle" id="badge-step-5">5</span>
                <div class="stepper-step-content">
                  <div class="stepper-step-title">Review & Submit</div>
                  <div class="stepper-step-sub">Review and confirm</div>
                </div>
              </button>
            </div>

          </div>
        </div>

        <!-- Left Sidebar Bottom Help Widget -->
        <div class="mt-4 p-3 rounded-3 bg-white border shadow-sm">
          <div class="d-flex align-items-center gap-2 mb-1 text-primary fw-bold fs-7">
            <i class="bi bi-shield-check"></i> Need Help?
          </div>
          <p class="text-secondary mb-2" style="font-size: 11px; line-height: 1.4;">
            If you need help filling out this form, contact HR support.
          </p>
          <a href="mailto:support@spiderpayroll.com" class="btn btn-sm btn-light border text-secondary w-100 fw-semibold" style="font-size: 11px;">
            Contact HR
          </a>
        </div>
      </div>

      <!-- RIGHT MAIN CONTENT AREA -->
      <div class="col-lg-9 p-4 p-lg-5 d-flex flex-column justify-content-between">
        <div>
          <!-- Flash Errors -->
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

          <!-- Step Header Title & Linear Progress Bar -->
          <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="bi bi-person-vcard fs-5" id="stepHeaderIcon"></i>
              </div>
              <div>
                <h5 class="fw-bold text-dark mb-0" id="stepHeaderTitle">Basic Information</h5>
                <span class="text-secondary small" id="stepHeaderSubtitle">Enter employee personal and contact information</span>
              </div>
            </div>
            <div class="text-end">
              <div class="text-secondary fw-semibold small mb-1" id="stepProgressText" style="font-size: 12px;">Step 1 of 5</div>
              <div class="progress" style="width: 140px; height: 6px;">
                <div class="progress-bar bg-primary rounded-pill" id="stepProgressBar" role="progressbar" style="width: 20%;"></div>
              </div>
            </div>
          </div>

          <!-- EMPLOYEE FORM -->
          <form action="<?= site_url('employees/store') ?>" method="POST" id="employeeForm" autocomplete="off" novalidate>
            <?= csrf_field() ?>

            <!-- STEP 1 — EMPLOYEE DETAILS -->
            <div class="step-panel" id="step-panel-1">
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
                  <label for="date_of_birth" class="form-label small fw-semibold text-secondary">Date of Birth</label>
                  <input type="date" name="date_of_birth" id="date_of_birth" class="form-control <?= session('errors.date_of_birth') ? 'is-invalid' : '' ?>" value="<?= old('date_of_birth') ?>" max="<?= date('Y-m-d') ?>">
                  <div class="invalid-feedback" id="err_date_of_birth">
                    <?= session('errors.date_of_birth') ?>
                  </div>
                </div>
              </div>
            </div>

            <!-- STEP 2 — EMPLOYMENT DETAILS -->
            <div class="step-panel d-none" id="step-panel-2">
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

              <!-- Exit Details (Shown if Status is Relieved) -->
              <div class="row d-none" id="exit-details-container">
                <div class="col-12 mb-3">
                  <div class="p-3 bg-light border rounded">
                    <h6 class="text-secondary fw-semibold mb-3 fs-7">
                      Exit Details <span class="text-muted fw-normal text-lowercase fs-7">(Required when status is Relieved)</span>
                    </h6>
                    <div class="row">
                      <!-- Date of Leaving -->
                      <div class="col-md-6 mb-2">
                        <label for="date_of_leaving" class="form-label small fw-semibold text-secondary">Date of Leaving <span class="text-danger d-none" id="req_star_date_of_leaving">*</span></label>
                        <input type="date" name="date_of_leaving" id="date_of_leaving" class="form-control <?= session('errors.date_of_leaving') ? 'is-invalid' : '' ?>" value="<?= old('date_of_leaving') ?>">
                        <div class="invalid-feedback" id="err_date_of_leaving">
                          <?= session('errors.date_of_leaving') ?: 'Date of Leaving is required when employee status is Relieved.' ?>
                        </div>
                      </div>

                      <!-- Exit Reason -->
                      <div class="col-md-6 mb-2">
                        <label for="exit_reason" class="form-label small fw-semibold text-secondary">Exit Reason <span class="text-danger d-none" id="req_star_exit_reason">*</span></label>
                        <input type="text" name="exit_reason" id="exit_reason" class="form-control <?= session('errors.exit_reason') ? 'is-invalid' : '' ?>" value="<?= old('exit_reason') ?>" placeholder="e.g. Resigned, Contract ended">
                        <div class="invalid-feedback" id="err_exit_reason">
                          <?= session('errors.exit_reason') ?: 'Exit Reason is required when employee status is Relieved.' ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- STEP 3 — BANK DETAILS -->
            <div class="step-panel d-none" id="step-panel-3">
              <div class="row">
                <!-- 1. Bank Account Number -->
                <div class="col-md-3 mb-3">
                  <label for="bank_account_number" class="form-label small fw-semibold text-secondary">Bank Account Number</label>
                  <input type="text" name="bank_account_number" id="bank_account_number" class="form-control <?= session('errors.bank_account_number') ? 'is-invalid' : '' ?>" value="<?= old('bank_account_number') ?>" placeholder="e.g. 001234567890">
                  <?php if (session('errors.bank_account_number')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.bank_account_number') ?></div>
                  <?php endif; ?>
                </div>

                <!-- 2. IFSC Code -->
                <div class="col-md-3 mb-3">
                  <label for="ifsc_code" class="form-label small fw-semibold text-secondary">IFSC Code</label>
                  <input type="text" name="ifsc_code" id="ifsc_code" class="form-control text-uppercase <?= session('errors.ifsc_code') ? 'is-invalid' : '' ?>" value="<?= old('ifsc_code') ?>" placeholder="e.g. HDFC0001234" maxlength="11">
                  <?php if (session('errors.ifsc_code')): ?>
                    <div class="invalid-feedback d-block ifsc-feedback-msg"><?= session('errors.ifsc_code') ?></div>
                  <?php endif; ?>
                </div>

                <!-- 3. Bank Name -->
                <div class="col-md-3 mb-3">
                  <label for="bank_name" class="form-label small fw-semibold text-secondary">Bank Name</label>
                  <input type="text" name="bank_name" id="bank_name" class="form-control <?= session('errors.bank_name') ? 'is-invalid' : '' ?>" value="<?= old('bank_name') ?>" placeholder="e.g. HDFC Bank">
                  <?php if (session('errors.bank_name')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.bank_name') ?></div>
                  <?php endif; ?>
                </div>

                <!-- 4. Bank Branch -->
                <div class="col-md-3 mb-3">
                  <label for="bank_branch" class="form-label small fw-semibold text-secondary">Bank Branch</label>
                  <input type="text" name="bank_branch" id="bank_branch" class="form-control <?= session('errors.bank_branch') ? 'is-invalid' : '' ?>" value="<?= old('bank_branch') ?>" placeholder="e.g. Andheri West">
                  <?php if (session('errors.bank_branch')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.bank_branch') ?></div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <!-- STEP 4 — IDENTIFICATION -->
            <div class="step-panel d-none" id="step-panel-4">
              <div class="row">
                <!-- PAN Number -->
                <div class="col-md-6 mb-3">
                  <label for="pan_number" class="form-label small fw-semibold text-secondary">PAN Number</label>
                  <input type="text" name="pan_number" id="pan_number" class="form-control text-uppercase <?= session('errors.pan_number') ? 'is-invalid' : '' ?>" value="<?= old('pan_number') ?>" placeholder="e.g. ABCDE1234F">
                  <?php if (session('errors.pan_number')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.pan_number') ?></div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <!-- STEP 5 — REVIEW & SUBMIT -->
            <div class="step-panel d-none" id="step-panel-5">
              <div class="text-secondary small mb-3">
                <i class="bi bi-info-circle me-1"></i> Please review all information carefully before final submission.
              </div>

              <!-- 1. BASIC INFORMATION -->
              <div class="card border mb-3">
                <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom">
                  <span class="fw-bold text-primary text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">
                    <i class="bi bi-person-fill me-1"></i> BASIC INFORMATION
                  </span>
                  <button type="button" class="btn btn-sm btn-link text-primary text-decoration-none p-0 btn-jump-step fw-semibold" data-jump="1" style="font-size: 12px;">
                    Edit
                  </button>
                </div>
                <div class="card-body p-3">
                  <div class="row g-3">
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Full Name</div>
                      <div class="fw-bold text-dark mt-1" id="rev_employee_name">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Biometric Code</div>
                      <div class="fw-bold text-dark mt-1" id="rev_biometric_code">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Mobile Number</div>
                      <div class="fw-bold text-dark mt-1" id="rev_phone_number">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Gender</div>
                      <div class="fw-bold text-dark mt-1" id="rev_gender">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Date of Birth</div>
                      <div class="fw-bold text-dark mt-1" id="rev_date_of_birth">-</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- 2. EMPLOYMENT DETAILS -->
              <div class="card border mb-3">
                <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom">
                  <span class="fw-bold text-primary text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">
                    <i class="bi bi-briefcase-fill me-1"></i> EMPLOYMENT DETAILS
                  </span>
                  <button type="button" class="btn btn-sm btn-link text-primary text-decoration-none p-0 btn-jump-step fw-semibold" data-jump="2" style="font-size: 12px;">
                    Edit
                  </button>
                </div>
                <div class="card-body p-3">
                  <div class="row g-3">
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Contractor</div>
                      <div class="fw-bold text-dark mt-1" id="rev_contractor_id">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Date of Joining</div>
                      <div class="fw-bold text-dark mt-1" id="rev_date_of_joining">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Designation</div>
                      <div class="fw-bold text-dark mt-1" id="rev_designation">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Department</div>
                      <div class="fw-bold text-dark mt-1" id="rev_department">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Base Salary</div>
                      <div class="fw-bold text-success mt-1" id="rev_monthly_base_salary">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Status</div>
                      <div class="fw-bold text-dark mt-1" id="rev_status">-</div>
                    </div>
                    <div class="col-6 col-md-3 d-none" id="rev_exit_col_date">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Date of Leaving</div>
                      <div class="fw-bold text-danger mt-1" id="rev_date_of_leaving">-</div>
                    </div>
                    <div class="col-6 col-md-3 d-none" id="rev_exit_col_reason">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Exit Reason</div>
                      <div class="fw-bold text-dark mt-1" id="rev_exit_reason">-</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- 3. BANK DETAILS -->
              <div class="card border mb-3">
                <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom">
                  <span class="fw-bold text-primary text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">
                    <i class="bi bi-bank2 me-1"></i> BANK DETAILS
                  </span>
                  <button type="button" class="btn btn-sm btn-link text-primary text-decoration-none p-0 btn-jump-step fw-semibold" data-jump="3" style="font-size: 12px;">
                    Edit
                  </button>
                </div>
                <div class="card-body p-3">
                  <div class="row g-3">
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Account Number</div>
                      <div class="fw-bold text-dark font-monospace mt-1" id="rev_bank_account_number">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">IFSC Code</div>
                      <div class="fw-bold text-dark font-monospace mt-1" id="rev_ifsc_code">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Bank Name</div>
                      <div class="fw-bold text-dark mt-1" id="rev_bank_name">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">Bank Branch</div>
                      <div class="fw-bold text-dark mt-1" id="rev_bank_branch">-</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- 4. IDENTIFICATION -->
              <div class="card border mb-3">
                <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom">
                  <span class="fw-bold text-primary text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">
                    <i class="bi bi-card-heading me-1"></i> IDENTIFICATION
                  </span>
                  <button type="button" class="btn btn-sm btn-link text-primary text-decoration-none p-0 btn-jump-step fw-semibold" data-jump="4" style="font-size: 12px;">
                    Edit
                  </button>
                </div>
                <div class="card-body p-3">
                  <div class="row g-3">
                    <div class="col-6 col-md-3">
                      <div class="text-secondary text-capitalize" style="font-size: 12px;">PAN Number</div>
                      <div class="fw-bold text-dark font-monospace mt-1" id="rev_pan_number">-</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </form>
        </div>

        <!-- Wizard Action Bottom Bar -->
        <div class="d-flex align-items-center justify-content-between pt-4 border-top mt-4">
          <a href="<?= site_url('employees') ?>" class="btn btn-light border text-secondary px-4 rounded-3 fw-semibold" style="font-size: 13px;">
            Cancel
          </a>
          <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-secondary px-4 rounded-3 btn-step-prev d-none" id="btnStepPrev" style="font-size: 13px;">
              &larr; Previous
            </button>
            <button type="button" class="btn btn-primary px-4 rounded-3 fw-bold btn-step-next shadow-sm" id="btnStepNext" style="font-size: 13px;">
              Next &rarr;
            </button>
            <button type="submit" form="employeeForm" class="btn btn-primary px-4 rounded-3 fw-bold shadow-sm d-none" id="btnStepSubmit" style="font-size: 13px;">
              <i class="bi bi-check-lg me-1"></i> Create Employee
            </button>
          </div>
        </div>

      </div>
    </div>
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

  const stepMetadata = {
    1: { title: 'Basic Information', subtitle: 'Enter employee personal and contact information', icon: 'bi-person-vcard', progress: 20 },
    2: { title: 'Employment Details', subtitle: 'Configure employee job profile, designation & salary', icon: 'bi-briefcase-fill', progress: 40 },
    3: { title: 'Bank Details', subtitle: 'Enter employee bank account details for payroll transfer', icon: 'bi-bank2', progress: 60 },
    4: { title: 'Identification', subtitle: 'Enter employee PAN & identity documentation', icon: 'bi-card-heading', progress: 80 },
    5: { title: 'Review & Submit', subtitle: 'Review all information carefully before final submission', icon: 'bi-check2-circle', progress: 100 }
  };

  // Check server errors
  <?php if (session('errors')): ?>
    <?php
      $errs = session('errors');
      $step1Errs = isset($errs['employee_name']) || isset($errs['biometric_code']) || isset($errs['phone_number']) || isset($errs['gender']) || isset($errs['date_of_birth']);
      $step2Errs = isset($errs['contractor_id']) || isset($errs['date_of_joining']) || isset($errs['designation']) || isset($errs['department']) || isset($errs['monthly_base_salary']) || isset($errs['status']) || isset($errs['date_of_leaving']) || isset($errs['exit_reason']);
      $step3Errs = isset($errs['bank_name']) || isset($errs['bank_account_number']) || isset($errs['ifsc_code']) || isset($errs['bank_branch']);
      $step4Errs = isset($errs['pan_number']);

      if ($step4Errs) $initialStep = 4;
      elseif ($step3Errs) $initialStep = 3;
      elseif ($step2Errs) $initialStep = 2;
      else $initialStep = 1;
    ?>
    currentStep = <?= $initialStep ?>;
    maxVisitedStep = 5;
  <?php endif; ?>

  function updateExitRequiredUI() {
    const exitContainer = document.getElementById('exit-details-container');
    const isRelieved = (statusSelect && statusSelect.value === 'relieved');
    if (exitContainer) {
      if (isRelieved) {
        exitContainer.classList.remove('d-none');
      } else {
        exitContainer.classList.add('d-none');
        if (dateOfLeavingInput) dateOfLeavingInput.classList.remove('is-invalid');
        if (exitReasonInput) exitReasonInput.classList.remove('is-invalid');
      }
    }
    if (reqStarDateLeaving) reqStarDateLeaving.classList.toggle('d-none', !isRelieved);
    if (reqStarExitReason) reqStarExitReason.classList.toggle('d-none', !isRelieved);
  }

  if (statusSelect) {
    statusSelect.addEventListener('change', updateExitRequiredUI);
    updateExitRequiredUI();
  }

  function formatDate(dStr) {
    if (!dStr) return '-';
    const parts = dStr.split('-');
    if (parts.length === 3) {
      return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }
    return dStr;
  }

  function populateReview() {
    const empName = document.getElementById('employee_name')?.value.trim() || '-';
    const phone = document.getElementById('phone_number')?.value.trim() || '-';
    const bioCode = document.getElementById('biometric_code')?.value.trim() || '-';
    const genderSel = document.getElementById('gender');
    const genderText = genderSel && genderSel.selectedIndex >= 0 ? genderSel.options[genderSel.selectedIndex].text : '-';
    const dob = formatDate(document.getElementById('date_of_birth')?.value.trim());

    const contractorSel = document.getElementById('contractor_id');
    const contractorText = contractorSel && contractorSel.value ? contractorSel.options[contractorSel.selectedIndex].text : '-';
    const doj = formatDate(document.getElementById('date_of_joining')?.value.trim());
    const designation = document.getElementById('designation')?.value.trim() || '-';
    const department = document.getElementById('department')?.value.trim() || '-';
    const salaryVal = parseFloat(document.getElementById('monthly_base_salary')?.value || 0);
    const salaryText = '₹' + salaryVal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    const statusSel = document.getElementById('status');
    const statusVal = statusSel ? statusSel.value : '';
    const dateLeaving = formatDate(document.getElementById('date_of_leaving')?.value.trim());
    const exitReason = document.getElementById('exit_reason')?.value.trim() || '-';

    const accNum = document.getElementById('bank_account_number')?.value.trim() || '-';
    const ifsc = document.getElementById('ifsc_code')?.value.trim().toUpperCase() || '-';
    const bankName = document.getElementById('bank_name')?.value.trim() || '-';
    const bankBranch = document.getElementById('bank_branch')?.value.trim() || '-';

    const pan = document.getElementById('pan_number')?.value.trim().toUpperCase() || '-';

    document.getElementById('rev_employee_name').textContent = empName;
    document.getElementById('rev_phone_number').textContent = phone;
    document.getElementById('rev_biometric_code').textContent = bioCode;
    document.getElementById('rev_gender').textContent = genderText;
    document.getElementById('rev_date_of_birth').textContent = dob;

    document.getElementById('rev_contractor_id').textContent = contractorText;
    document.getElementById('rev_date_of_joining').textContent = doj;
    document.getElementById('rev_designation').textContent = designation;
    document.getElementById('rev_department').textContent = department;
    document.getElementById('rev_monthly_base_salary').textContent = salaryText;

    let statusBadge = '<span class="badge text-bg-secondary"><i class="bi bi-pause-circle me-1"></i>Inactive</span>';
    if (statusVal === 'active') {
      statusBadge = '<span class="badge text-bg-success"><i class="bi bi-check-circle me-1"></i>Active</span>';
    } else if (statusVal === 'relieved') {
      statusBadge = '<span class="badge text-bg-danger"><i class="bi bi-x-circle me-1"></i>Relieved</span>';
    }
    const revStatusEl = document.getElementById('rev_status');
    if (revStatusEl) revStatusEl.innerHTML = statusBadge;

    const exitColDate = document.getElementById('rev_exit_col_date');
    const exitColReason = document.getElementById('rev_exit_col_reason');
    if (statusVal === 'relieved') {
      if (exitColDate) exitColDate.classList.remove('d-none');
      if (exitColReason) exitColReason.classList.remove('d-none');
      document.getElementById('rev_date_of_leaving').textContent = dateLeaving;
      document.getElementById('rev_exit_reason').textContent = exitReason;
    } else {
      if (exitColDate) exitColDate.classList.add('d-none');
      if (exitColReason) exitColReason.classList.add('d-none');
    }

    document.getElementById('rev_bank_account_number').textContent = accNum;
    document.getElementById('rev_ifsc_code').textContent = ifsc;
    document.getElementById('rev_bank_name').textContent = bankName;
    document.getElementById('rev_bank_branch').textContent = bankBranch;

    document.getElementById('rev_pan_number').textContent = pan;
  }

  function renderStep(step) {
    if (step === 5) {
      populateReview();
    }

    // Hide all panels
    document.querySelectorAll('.step-panel').forEach(panel => panel.classList.add('d-none'));

    // Show current panel
    const currentPanel = document.getElementById('step-panel-' + step);
    if (currentPanel) {
      currentPanel.classList.remove('d-none');
    }

    if (step > maxVisitedStep) {
      maxVisitedStep = step;
    }

    // Update Header Title, Subtitle, Progress
    const meta = stepMetadata[step];
    if (meta) {
      const titleEl = document.getElementById('stepHeaderTitle');
      const subEl = document.getElementById('stepHeaderSubtitle');
      const iconEl = document.getElementById('stepHeaderIcon');
      const progText = document.getElementById('stepProgressText');
      const progBar = document.getElementById('stepProgressBar');

      if (titleEl) titleEl.textContent = meta.title;
      if (subEl) subEl.textContent = meta.subtitle;
      if (iconEl) iconEl.className = 'bi ' + meta.icon + ' fs-5';
      if (progText) progText.textContent = `Step ${step} of 5`;
      if (progBar) progBar.style.width = `${meta.progress}%`;
    }

    // Update Bottom Action Buttons
    const btnPrev = document.getElementById('btnStepPrev');
    const btnNext = document.getElementById('btnStepNext');
    const btnSubmit = document.getElementById('btnStepSubmit');

    if (btnPrev) btnPrev.classList.toggle('d-none', step === 1);
    if (btnNext) btnNext.classList.toggle('d-none', step === maxSteps);
    if (btnSubmit) btnSubmit.classList.toggle('d-none', step !== maxSteps);

    // Update Left Sidebar Stepper Buttons & Badges
    for (let i = 1; i <= maxSteps; i++) {
      const tab = document.getElementById('step-tab-' + i);
      const badge = document.getElementById('badge-step-' + i);
      const item = document.getElementById('step-item-' + i);

      if (item) {
        item.classList.toggle('active-step', i === step);
        item.classList.toggle('completed', i < step);
      }

      if (!tab || !badge) continue;

      if (i === step) {
        tab.classList.remove('disabled');
        badge.innerHTML = i;
      } else if (i < step || i <= maxVisitedStep) {
        tab.classList.remove('disabled');
        badge.innerHTML = '&#10003;';
      } else {
        tab.classList.add('disabled');
        badge.innerHTML = i;
      }
    }
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

      const todayStr = new Date().toISOString().split('T')[0];
      if (dob.value.trim() && dob.value > todayStr) {
        dob.classList.add('is-invalid');
        const errDiv = document.getElementById('err_date_of_birth');
        if (errDiv) errDiv.textContent = 'Date of Birth cannot be a future date.';
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

      if (status.value === 'relieved') {
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
      }
    } else if (step === 5) {
      const isStep1Valid = validateCurrentStep(1);
      const isStep2Valid = validateCurrentStep(2);
      isValid = isStep1Valid && isStep2Valid;
    }

    return isValid;
  }

  // Handle Jump to Step from Review Cards
  document.querySelectorAll('.btn-jump-step').forEach(btn => {
    btn.addEventListener('click', function() {
      const stepToJump = parseInt(this.getAttribute('data-jump'));
      currentStep = stepToJump;
      renderStep(currentStep);
    });
  });

  // Handle Form Submit
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
        if (!this.classList.contains('disabled') || i <= maxVisitedStep) {
          if (validateCurrentStep(currentStep)) {
            currentStep = i;
            renderStep(currentStep);
          }
        }
      });
    }
  }

  // Initial render
  renderStep(currentStep);
});
</script>

<?= $this->endSection() ?>
