<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">
    <div class="card mb-4" style="background-color: #ffffff !important; border: 1px solid #e5e7eb; border-radius: 6px;">
      <div class="card-header fw-bold d-flex justify-content-between align-items-center py-3" style="background-color: #ffffff !important; border-bottom: 1px solid #e5e7eb;">
        <h4 class="mb-0 fw-bold text-dark fs-5">Edit User #<?= esc($user['id']) ?></h4>
        <a href="<?= site_url('users') ?>" class="btn btn-sm btn-outline-secondary" style="border-radius: 6px;">
          Back to Users
        </a>
      </div>
      <div class="card-body p-4" style="background-color: #ffffff !important;">
        <form action="<?= site_url('users/update/' . $user['id']) ?>" method="post" novalidate>
          <?= csrf_field() ?>



          <!-- Name Field -->
          <div class="mb-3">
            <label for="name" class="form-label small fw-semibold text-secondary">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>" value="<?= old('name', $user['name']) ?>" required>
            <?php if (session('errors.name')): ?>
              <div class="invalid-feedback d-block"><?= session('errors.name') ?></div>
            <?php endif; ?>
          </div>

          <!-- Username Field -->
          <div class="mb-3">
            <label for="username" class="form-label small fw-semibold text-secondary">Username <span class="text-danger">*</span></label>
            <input type="text" name="username" id="username" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" value="<?= old('username', $user['username']) ?>" required>
            <?php if (session('errors.username')): ?>
              <div class="invalid-feedback d-block"><?= session('errors.username') ?></div>
            <?php endif; ?>
          </div>

          <!-- Password Field -->
          <div class="mb-3">
            <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
            <input type="password" name="password" id="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" autocomplete="new-password">
            <div class="form-text text-secondary small mt-1">Enter a new password only if you want to change it.</div>
            <?php if (session('errors.password')): ?>
              <div class="invalid-feedback d-block"><?= session('errors.password') ?></div>
            <?php endif; ?>
          </div>

          <!-- Confirm Password Field -->
          <div class="mb-3">
            <label for="confirm_password" class="form-label small fw-semibold text-secondary">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control <?= session('errors.confirm_password') ? 'is-invalid' : '' ?>" autocomplete="new-password">
            <?php if (session('errors.confirm_password')): ?>
              <div class="invalid-feedback d-block"><?= session('errors.confirm_password') ?></div>
            <?php endif; ?>
          </div>

          <div class="row">
            <!-- Role Field -->
            <div class="col-md-6 mb-3">
              <label for="role" class="form-label small fw-semibold text-secondary">Role <span class="text-danger">*</span></label>
              <select name="role" id="role" class="form-select <?= session('errors.role') ? 'is-invalid' : '' ?>" required>
                <option value="super_admin" <?= old('role', $user['role']) === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
              </select>
              <?php if (session('errors.role')): ?>
                <div class="invalid-feedback d-block"><?= session('errors.role') ?></div>
              <?php endif; ?>
            </div>

            <!-- Status Field -->
            <div class="col-md-6 mb-4">
              <label for="status" class="form-label small fw-semibold text-secondary">Status <span class="text-danger">*</span></label>
              <select name="status" id="status" class="form-select <?= session('errors.status') ? 'is-invalid' : '' ?>" required>
                <option value="1" <?= (string)old('status', $user['status']) === '1' ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= (string)old('status', $user['status']) === '0' ? 'selected' : '' ?>>Inactive</option>
              </select>
              <?php if (session('errors.status')): ?>
                <div class="invalid-feedback d-block"><?= session('errors.status') ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 border-top pt-3">
            <a href="<?= site_url('users') ?>" class="btn btn-outline-secondary px-3" style="border-radius: 6px;">Cancel</a>
            <button type="submit" class="btn text-white px-4 fw-medium" style="background: linear-gradient(180deg, #0f172a 0%, #1d4ed8 100%) !important; border-radius: 6px; border: none;">
              Update User
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>