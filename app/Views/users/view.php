<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">
    <div class="card mb-4" style="background-color: #ffffff !important; border: 1px solid #e5e7eb; border-radius: 6px;">
      <div class="card-header fw-bold d-flex justify-content-between align-items-center py-3" style="background-color: #ffffff !important; border-bottom: 1px solid #e5e7eb;">
        <h4 class="mb-0 fw-bold text-dark fs-5">User Details #<?= esc($user['id']) ?></h4>
        <a href="<?= site_url('users') ?>" class="btn btn-sm btn-outline-secondary" style="border-radius: 6px;">
          Back to Users
        </a>
      </div>
      <div class="card-body p-4" style="background-color: #ffffff !important;">
        <div class="table-responsive">
          <table class="table table-bordered mb-0 align-middle">
            <tbody>
              <tr>
                <th scope="row" class="bg-light text-secondary" style="width: 35%;">User ID</th>
                <td>#<?= esc($user['id']) ?></td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary">Full Name</th>
                <td class="fw-semibold text-dark"><?= esc($user['name']) ?></td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary">Username</th>
                <td><code>@<?= esc($user['username']) ?></code></td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary">Role</th>
                <td>
                  <span class="badge-subtle-admin">Super Admin</span>
                </td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary">Status</th>
                <td>
                  <?php if ((int)$user['status'] === 1): ?>
                    <span class="badge-subtle-active">Active</span>
                  <?php else: ?>
                    <span class="badge-subtle-inactive">Inactive</span>
                  <?php endif; ?>
                </td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary">Created At</th>
                <td><?= !empty($user['created_at']) ? date('d M Y, h:i A', strtotime($user['created_at'])) : '-' ?></td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary">Updated At</th>
                <td><?= !empty($user['updated_at']) ? date('d M Y, h:i A', strtotime($user['updated_at'])) : '-' ?></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
          <a href="<?= site_url('users/edit/' . $user['id']) ?>" class="btn text-white px-4 fw-medium" style="background: linear-gradient(180deg, #0f172a 0%, #1d4ed8 100%) !important; border-radius: 6px; border: none;">
            Edit User
          </a>
          <a href="<?= site_url('users') ?>" class="btn btn-outline-secondary px-3" style="border-radius: 6px;">
            Back
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>