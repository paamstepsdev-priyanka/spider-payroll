<?php
$currentPage = isset($pager) ? $pager->getCurrentPage() : 1;
$perPage = isset($pager) ? $pager->getPerPage() : 10;
$total = isset($pager) ? $pager->getTotal() : count($users);
$offset = ($currentPage - 1) * $perPage;
$startCount = $total > 0 ? $offset + 1 : 0;
$endCount = min($offset + $perPage, $total);
?>

<div class="table-responsive">
  <table class="table table-plain align-middle mb-0" style="background-color: #ffffff !important;">
    <thead>
      <tr>
        <th scope="col" style="width: 70px;">#</th>
        <th scope="col">Name</th>
        <th scope="col">Username</th>
        <th scope="col">Role</th>
        <th scope="col">Status</th>
        <th scope="col">Created Date</th>
        <th scope="col" class="text-end" style="width: 180px;">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($users)): ?>
        <?php $sr = $startCount; ?>
        <?php foreach ($users as $user): ?>
          <tr style="background-color: #ffffff !important;">
            <th scope="row" class="fw-normal text-secondary" style="background-color: #ffffff !important;"><?= $sr++ ?></th>
            <td class="fw-medium text-dark" style="background-color: #ffffff !important;"><?= esc($user['name']) ?></td>
            <td style="background-color: #ffffff !important;"><code class="text-dark bg-light px-2 py-1 rounded" style="font-size: 13px;"><?= esc($user['username']) ?></code></td>
            <td style="background-color: #ffffff !important;">
              <span class="badge border border-primary text-primary">Super Admin</span>
            </td>
            <td style="background-color: #ffffff !important;">
              <?php if ((int)$user['status'] === 1): ?>
                <span class="badge border border-success text-success">Active</span>
              <?php else: ?>
                <span class="badge border border-danger text-danger">Inactive</span>
              <?php endif; ?>
            </td>
            <td style="background-color: #ffffff !important;">
              <span class="text-secondary small">
                <?= !empty($user['created_at']) ? date('d M Y, h:i A', strtotime($user['created_at'])) : '-' ?>
              </span>
            </td>
            <td class="text-end" style="background-color: #ffffff !important;">
              <div class="d-flex justify-content-end gap-1">
                <!-- View Button -->
                <a href="<?= site_url('users/view/' . $user['id']) ?>" class="btn btn-sm btn-outline-secondary" title="View Details">
                  <i class="bi bi-eye"></i>
                </a>

                <!-- Edit Button -->
                <a href="<?= site_url('users/edit/' . $user['id']) ?>" class="btn btn-sm btn-outline-primary" title="Edit User">
                  <i class="bi bi-pencil"></i>
                </a>

                <!-- Delete Button with SweetAlert2 -->
                <form method="post" action="<?= site_url('users/delete/' . $user['id']) ?>" class="d-inline">
                  <?= csrf_field() ?>
                  <button type="button" class="btn btn-sm btn-outline-danger btn-sweet-delete" data-username="<?= esc($user['username']) ?>" title="Delete User">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr style="background-color: #ffffff !important;">
          <td colspan="7" class="text-center py-4 text-secondary small" style="background-color: #ffffff !important;">
            No users found matching the search criteria.
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Pagination Footer Section -->
<div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top" style="border-color: #e2e8f0 !important;">
  <div class="text-secondary small">
    Showing <span class="fw-semibold text-dark"><?= $startCount ?></span> to <span class="fw-semibold text-dark"><?= $endCount ?></span> of <span class="fw-semibold text-dark"><?= $total ?></span> entries
  </div>
  <div>
    <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
      <?= $pager->links('default', 'bootstrap_pagination') ?>
    <?php endif; ?>
  </div>
</div>