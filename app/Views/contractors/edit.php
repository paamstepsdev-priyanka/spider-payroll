<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb small">
    <li class="breadcrumb-item"><a href="<?= site_url('contractors') ?>" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item">Master</li>
    <li class="breadcrumb-item"><a href="<?= site_url('contractors') ?>" class="text-decoration-none">Contractors</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Contractor</li>
  </ol>
</nav>

<div class="row justify-content-center">
  <div class="col-md-10 col-lg-8">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold">Edit Contractor #<?= esc($contractor['contractor_id']) ?></h5>
        <a href="<?= site_url('contractors') ?>" class="btn btn-sm btn-outline-secondary">
          Back to Contractors
        </a>
      </div>
      <div class="card-body p-4">
        <form action="<?= site_url('contractors/update/' . $contractor['contractor_id']) ?>" method="post" class="jquery-validation" novalidate>
          <?= csrf_field() ?>

          <!-- SECTION 1 — CONTRACTOR DETAILS -->
          <div class="mb-4">
            <h6 class="fw-bold border-bottom pb-2 mb-3 text-secondary text-uppercase small" style="letter-spacing: 0.5px;">
              Section 1 — Contractor Details
            </h6>

            <div class="row">
              <!-- Contractor Name -->
              <div class="col-md-6 mb-3">
                <label for="contractor_name" class="form-label fw-medium">Contractor Name <span class="text-danger">*</span></label>
                <input type="text" name="contractor_name" id="contractor_name" class="form-control <?= session('errors.contractor_name') ? 'is-invalid' : '' ?>" value="<?= old('contractor_name', $contractor['contractor_name']) ?>" required>
                <?php if (session('errors.contractor_name')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.contractor_name') ?></div>
                <?php endif; ?>
              </div>

              <!-- Contractor Code -->
              <div class="col-md-6 mb-3">
                <label for="contractor_code" class="form-label fw-medium">Contractor Code <span class="text-danger">*</span></label>
                <input type="text" name="contractor_code" id="contractor_code" class="form-control <?= session('errors.contractor_code') ? 'is-invalid' : '' ?>" value="<?= old('contractor_code', $contractor['contractor_code']) ?>" required>
                <?php if (session('errors.contractor_code')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.contractor_code') ?></div>
                <?php endif; ?>
              </div>
            </div>

            <div class="row">
              <!-- Phone Number -->
              <div class="col-md-6 mb-3">
                <label for="phone_number" class="form-label fw-medium">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control <?= session('errors.phone_number') ? 'is-invalid' : '' ?>" value="<?= old('phone_number', $contractor['phone_number']) ?>">
                <?php if (session('errors.phone_number')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.phone_number') ?></div>
                <?php endif; ?>
              </div>

              <!-- Email -->
              <div class="col-md-6 mb-3">
                <label for="email" class="form-label fw-medium">Email Address</label>
                <input type="email" name="email" id="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" value="<?= old('email', $contractor['email']) ?>">
                <?php if (session('errors.email')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.email') ?></div>
                <?php endif; ?>
              </div>
            </div>

            <div class="row">
              <!-- Address -->
              <div class="col-12 mb-3">
                <label for="address" class="form-label fw-medium">Address</label>
                <textarea name="address" id="address" rows="2" class="form-control <?= session('errors.address') ? 'is-invalid' : '' ?>"><?= old('address', $contractor['address']) ?></textarea>
                <?php if (session('errors.address')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.address') ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- SECTION 2 — BANK DETAILS -->
          <div class="mb-4">
            <h6 class="fw-bold border-bottom pb-2 mb-3 text-secondary text-uppercase small" style="letter-spacing: 0.5px;">
              Section 2 — Bank Details
            </h6>

            <div class="row">
              <!-- Bank Account Number -->
              <div class="col-md-6 mb-3">
                <label for="bank_account_number" class="form-label fw-medium">Bank Account Number <span class="text-danger">*</span></label>
                <input type="text" name="bank_account_number" id="bank_account_number" class="form-control <?= session('errors.bank_account_number') ? 'is-invalid' : '' ?>" value="<?= old('bank_account_number', $contractor['bank_account_number']) ?>" required>
                <?php if (session('errors.bank_account_number')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.bank_account_number') ?></div>
                <?php endif; ?>
              </div>

              <!-- IFSC Code -->
              <div class="col-md-6 mb-3">
                <label for="ifsc_code" class="form-label fw-medium">IFSC Code <span class="text-danger">*</span></label>
                <input type="text" name="ifsc_code" id="ifsc_code" class="form-control text-uppercase <?= session('errors.ifsc_code') ? 'is-invalid' : '' ?>" value="<?= old('ifsc_code', $contractor['ifsc_code']) ?>" maxlength="20" required>
                <?php if (session('errors.ifsc_code')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.ifsc_code') ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- SECTION 3 — STATUS -->
          <div class="mb-4">
            <h6 class="fw-bold border-bottom pb-2 mb-3 text-secondary text-uppercase small" style="letter-spacing: 0.5px;">
              Section 3 — Status
            </h6>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="status" class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select <?= session('errors.status') ? 'is-invalid' : '' ?>" required>
                  <option value="active" <?= old('status', $contractor['status']) === 'active' ? 'selected' : '' ?>>Active</option>
                  <option value="inactive" <?= old('status', $contractor['status']) === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
                <?php if (session('errors.status')): ?>
                  <div class="invalid-feedback d-block"><?= session('errors.status') ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="d-flex justify-content-end gap-2 border-top pt-3">
            <a href="<?= site_url('contractors') ?>" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
              Update Contractor
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
