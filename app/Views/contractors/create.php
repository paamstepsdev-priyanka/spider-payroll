<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
  <div class="col-md-10 col-lg-8">
    <div class="card mb-4" style="background-color: #ffffff !important; border: 1px solid #e5e7eb; border-radius: 6px;">
      <div class="card-header fw-bold d-flex justify-content-between align-items-center py-3" style="background-color: #ffffff !important; border-bottom: 1px solid #e5e7eb;">
        <h4 class="mb-0 fw-bold text-dark fs-5">Add Contractor</h4>
        <a href="<?= site_url('contractors') ?>" class="btn btn-sm btn-outline-secondary" style="border-radius: 6px;">
          Back to Contractors
        </a>
      </div>
      <div class="card-body p-4" style="background-color: #ffffff !important;">
        <form action="<?= site_url('contractors/store') ?>" method="post" class="jquery-validation" novalidate>
          <?= csrf_field() ?>



          <!-- SECTION 1 — CONTRACTOR DETAILS -->
          <div class="mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3" style="font-size: 14px; letter-spacing: 0.5px; text-transform: uppercase; color: #374151 !important;">
              Section 1 — Contractor Details
            </h6>

            <div class="row">
              <!-- Contractor Name -->
              <div class="col-md-4 mb-3">
                <label for="contractor_name" class="form-label small fw-semibold text-secondary">Contractor Name <span class="text-danger">*</span></label>
                <input type="text" name="contractor_name" id="contractor_name" class="form-control <?= session('errors.contractor_name') ? 'is-invalid' : '' ?>" value="<?= old('contractor_name') ?>" placeholder="e.g. ABC Contractor" required>
                <?php if (session('errors.contractor_name')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.contractor_name') ?></div>
                <?php endif; ?>
              </div>

              <!-- Phone Number -->
              <div class="col-md-4 mb-3">
                <label for="phone_number" class="form-label small fw-semibold text-secondary">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control <?= session('errors.phone_number') ? 'is-invalid' : '' ?>" value="<?= old('phone_number') ?>" placeholder="e.g. 9876543210">
                <?php if (session('errors.phone_number')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.phone_number') ?></div>
                <?php endif; ?>
              </div>

              <!-- Date of Birth (DOB) -->
              <div class="col-md-4 mb-3">
                <label for="dob" class="form-label small fw-semibold text-secondary">Date of Birth (DOB)</label>
                <input type="date" name="dob" id="dob" class="form-control flatpickr-date <?= session('errors.dob') ? 'is-invalid' : '' ?>" value="<?= old('dob') ?>" placeholder="Select DOB">
                <?php if (session('errors.dob')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.dob') ?></div>
                <?php endif; ?>
              </div>
            </div>

            <div class="row">
              <!-- Email -->
              <div class="col-md-6 mb-3">
                <label for="email" class="form-label small fw-semibold text-secondary">Email Address</label>
                <input type="email" name="email" id="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" value="<?= old('email') ?>" placeholder="e.g. abc@example.com">
                <?php if (session('errors.email')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.email') ?></div>
                <?php endif; ?>
              </div>
            </div>

            <div class="row">
              <!-- Address -->
              <div class="col-12 mb-3">
                <label for="address" class="form-label small fw-semibold text-secondary">Address</label>
                <textarea name="address" id="address" rows="2" class="form-control <?= session('errors.address') ? 'is-invalid' : '' ?>" placeholder="e.g. Pune"><?= old('address') ?></textarea>
                <?php if (session('errors.address')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.address') ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- SECTION 2 — BANK DETAILS -->
          <div class="mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3" style="font-size: 14px; letter-spacing: 0.5px; text-transform: uppercase; color: #374151 !important;">
              Section 2 — Bank Details
            </h6>

            <div class="row">
              <!-- Account Holder Name -->
              <div class="col-md-6 mb-3">
                <label for="account_holder_name" class="form-label small fw-semibold text-secondary">Account Holder Name <span class="text-danger">*</span></label>
                <input type="text" name="account_holder_name" id="account_holder_name" class="form-control <?= session('errors.account_holder_name') ? 'is-invalid' : '' ?>" value="<?= old('account_holder_name') ?>" placeholder="Name as per bank records" required>
                <?php if (session('errors.account_holder_name')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.account_holder_name') ?></div>
                <?php endif; ?>
              </div>

              <!-- Bank Account Number -->
              <div class="col-md-6 mb-3">
                <label for="bank_account_number" class="form-label small fw-semibold text-secondary">Bank Account Number <span class="text-danger">*</span></label>
                <input type="text" name="bank_account_number" id="bank_account_number" class="form-control <?= session('errors.bank_account_number') ? 'is-invalid' : '' ?>" value="<?= old('bank_account_number') ?>" placeholder="e.g. 001234567890" required>
                <?php if (session('errors.bank_account_number')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.bank_account_number') ?></div>
                <?php endif; ?>
              </div>
            </div>

            <div class="row">
              <!-- IFSC Code -->
              <div class="col-md-4 mb-3">
                <label for="ifsc_code" class="form-label small fw-semibold text-secondary">IFSC Code <span class="text-danger">*</span></label>
                <input type="text" name="ifsc_code" id="ifsc_code" class="form-control text-uppercase <?= session('errors.ifsc_code') ? 'is-invalid' : '' ?>" value="<?= old('ifsc_code') ?>" placeholder="e.g. HDFC0001234" maxlength="20" required style="text-transform: uppercase;">
                <?php if (session('errors.ifsc_code')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.ifsc_code') ?></div>
                <?php endif; ?>
              </div>

              <!-- Bank Name -->
              <div class="col-md-4 mb-3">
                <label for="bank_name" class="form-label small fw-semibold text-secondary">Bank Name <span class="text-danger">*</span></label>
                <input type="text" name="bank_name" id="bank_name" class="form-control <?= session('errors.bank_name') ? 'is-invalid' : '' ?>" value="<?= old('bank_name') ?>" placeholder="Auto-filled from IFSC or enter manually" required>
                <?php if (session('errors.bank_name')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.bank_name') ?></div>
                <?php endif; ?>
              </div>

              <!-- Branch Name -->
              <div class="col-md-4 mb-3">
                <label for="branch_name" class="form-label small fw-semibold text-secondary">Branch Name <span class="text-danger">*</span></label>
                <input type="text" name="branch_name" id="branch_name" class="form-control <?= session('errors.branch_name') ? 'is-invalid' : '' ?>" value="<?= old('branch_name') ?>" placeholder="Auto-filled from IFSC or enter manually" required>
                <?php if (session('errors.branch_name')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.branch_name') ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- SECTION 3 — STATUS -->
          <div class="mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3" style="font-size: 14px; letter-spacing: 0.5px; text-transform: uppercase; color: #374151 !important;">
              Section 3 — Status
            </h6>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="status" class="form-label small fw-semibold text-secondary">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select <?= session('errors.status') ? 'is-invalid' : '' ?>" required>
                  <option value="active" <?= old('status', 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                  <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
                <?php if (session('errors.status')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.status') ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Submit Action Buttons -->
          <div class="d-flex justify-content-end gap-2 border-top pt-3">
            <a href="<?= site_url('contractors') ?>" class="btn btn-outline-secondary px-3" style="border-radius: 6px;">Cancel</a>
            <button type="submit" class="btn btn-primary px-4 fw-medium" style="border-radius: 6px;">
              Save Contractor
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const ifscInput = document.getElementById("ifsc_code");
    if (ifscInput) {
      ifscInput.addEventListener("input", function() {
        this.value = this.value.toUpperCase().trim();
      });
    }
  });
</script>

<?= $this->section('scripts') ?>
<script>
  $(document).ready(function () {
    initAjaxForm('form.jquery-validation', {
      rules: {
        contractor_name: {
          required: true,
          maxlength: 150
        },
        phone_number: {
          indianPhone: true
        },
        email: {
          email: true,
          maxlength: 100
        },
        account_holder_name: {
          required: true,
          maxlength: 150
        },
        bank_account_number: {
          required: true,
          digits: true,
          maxlength: 50
        },
        ifsc_code: {
          required: true,
          ifscCode: true
        },
        bank_name: {
          required: true,
          maxlength: 100
        },
        branch_name: {
          required: true,
          maxlength: 100
        },
        status: {
          required: true
        }
      },
      messages: {
        contractor_name: {
          required: "Please enter the contractor name.",
          maxlength: "Contractor name cannot exceed 150 characters."
        },
        phone_number: {
          indianPhone: "Please enter a valid 10-digit mobile number."
        },
        email: {
          email: "Please enter a valid email address.",
          maxlength: "Email address cannot exceed 100 characters."
        },
        account_holder_name: {
          required: "Please enter the account holder name.",
          maxlength: "Account holder name cannot exceed 150 characters."
        },
        bank_account_number: {
          required: "Please enter the bank account number.",
          digits: "Bank account number must contain numbers only.",
          maxlength: "Bank account number cannot exceed 50 digits."
        },
        ifsc_code: {
          required: "Please enter the bank IFSC code.",
          ifscCode: "Please enter a valid 11-character IFSC code (e.g. HDFC0001234)."
        },
        bank_name: {
          required: "Please enter the bank name.",
          maxlength: "Bank name cannot exceed 100 characters."
        },
        branch_name: {
          required: "Please enter the branch name.",
          maxlength: "Branch name cannot exceed 100 characters."
        },
        status: {
          required: "Please select contractor status."
        }
      }
    });
  });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>