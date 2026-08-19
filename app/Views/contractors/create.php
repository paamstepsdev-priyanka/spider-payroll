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
        <form action="<?= site_url('contractors/store') ?>" method="post" novalidate>
          <?= csrf_field() ?>



          <!-- SECTION 1 — CONTRACTOR DETAILS -->
          <div class="mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3" style="font-size: 14px; letter-spacing: 0.5px; text-transform: uppercase; color: #374151 !important;">
              Section 1 — Contractor Details
            </h6>

            <div class="row">
              <!-- Contractor Name -->
              <div class="col-md-6 mb-3">
                <label for="contractor_name" class="form-label small fw-semibold text-secondary">Contractor Name <span class="text-danger">*</span></label>
                <input type="text" name="contractor_name" id="contractor_name" class="form-control <?= session('errors.contractor_name') ? 'is-invalid' : '' ?>" value="<?= old('contractor_name') ?>" placeholder="e.g. ABC Contractor" required>
                <?php if (session('errors.contractor_name')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.contractor_name') ?></div>
                <?php endif; ?>
              </div>

              <!-- Contractor Code -->
              <div class="col-md-6 mb-3">
                <label for="contractor_code" class="form-label small fw-semibold text-secondary">Contractor Code <span class="text-danger">*</span></label>
                <input type="text" name="contractor_code" id="contractor_code" class="form-control <?= session('errors.contractor_code') ? 'is-invalid' : '' ?>" value="<?= old('contractor_code') ?>" placeholder="e.g. ABC001" required>
                <?php if (session('errors.contractor_code')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.contractor_code') ?></div>
                <?php endif; ?>
              </div>
            </div>

            <div class="row">
              <!-- Phone Number -->
              <div class="col-md-6 mb-3">
                <label for="phone_number" class="form-label small fw-semibold text-secondary">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control <?= session('errors.phone_number') ? 'is-invalid' : '' ?>" value="<?= old('phone_number') ?>" placeholder="e.g. 9876543210">
                <?php if (session('errors.phone_number')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.phone_number') ?></div>
                <?php endif; ?>
              </div>

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
              <!-- Bank Account Number -->
              <div class="col-md-6 mb-3">
                <label for="bank_account_number" class="form-label small fw-semibold text-secondary">Bank Account Number <span class="text-danger">*</span></label>
                <input type="text" name="bank_account_number" id="bank_account_number" class="form-control <?= session('errors.bank_account_number') ? 'is-invalid' : '' ?>" value="<?= old('bank_account_number') ?>" placeholder="e.g. 001234567890" required>
                <?php if (session('errors.bank_account_number')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.bank_account_number') ?></div>
                <?php endif; ?>
              </div>

              <!-- IFSC Code -->
              <div class="col-md-6 mb-3">
                <label for="ifsc_code" class="form-label small fw-semibold text-secondary">IFSC Code <span class="text-danger">*</span></label>
                <input type="text" name="ifsc_code" id="ifsc_code" class="form-control text-uppercase <?= session('errors.ifsc_code') ? 'is-invalid' : '' ?>" value="<?= old('ifsc_code') ?>" placeholder="e.g. HDFC0001234" maxlength="20" required style="text-transform: uppercase;">
                <?php if (session('errors.ifsc_code')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.ifsc_code') ?></div>
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

<?= $this->endSection() ?>