<?php
$currentPage = isset($pager) ? $pager->getCurrentPage() : 1;
$perPage     = isset($pager) ? $pager->getPerPage() : 10;
$total       = isset($pager) ? $pager->getTotal() : count($contractors);
$offset      = ($currentPage - 1) * $perPage;
$startCount  = $total > 0 ? $offset + 1 : 0;
$endCount    = min($offset + $perPage, $total);
?>

<div class="table-responsive">
  <table class="table table-plain align-middle mb-0" style="background-color: #ffffff !important;">
    <thead>
      <tr>
        <th scope="col" style="width: 60px;">#</th>
        <th scope="col">Contractor Name</th>
        <th scope="col">Phone</th>
        <th scope="col">Status</th>
        <th scope="col">Created Date</th>
        <th scope="col" class="text-end" style="width: 180px;">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($contractors)): ?>
        <?php $sr = $startCount; ?>
        <?php foreach ($contractors as $item): ?>
          <tr style="background-color: #ffffff !important;">
            <th scope="row" class="fw-normal text-secondary" style="background-color: #ffffff !important;"><?= $sr++ ?></th>
            <td class="fw-medium text-dark" style="background-color: #ffffff !important;">
              <a href="javascript:void(0)" class="text-decoration-none fw-semibold text-dark btn-view-contractor" data-id="<?= $item['contractor_id'] ?>" title="Click to view details">
                <?= esc($item['contractor_name']) ?>
              </a>
            </td>
            <td style="background-color: #ffffff !important;"><?= esc($item['phone_number'] ?: '-') ?></td>
            <td style="background-color: #ffffff !important;">
              <?php if ($item['status'] === 'active'): ?>
                <span class="badge border border-success text-success">Active</span>
              <?php else: ?>
                <span class="badge border border-danger text-danger">Inactive</span>
              <?php endif; ?>
            </td>
            <td style="background-color: #ffffff !important;">
              <span class="text-secondary small">
                <?= !empty($item['created_at']) ? date('d M Y', strtotime($item['created_at'])) : '-' ?>
              </span>
            </td>
            <td class="text-end" style="background-color: #ffffff !important;">
              <div class="d-flex justify-content-end gap-1">
                <!-- View Button -->
                <button type="button" class="btn btn-sm btn-outline-secondary btn-view-contractor" data-id="<?= $item['contractor_id'] ?>" title="View Details">
                  <i class="bi bi-eye"></i>
                </button>

                <!-- Edit Button -->
                <a href="<?= site_url('contractors/edit/' . $item['contractor_id']) ?>" class="btn btn-sm btn-outline-primary" title="Edit Contractor">
                  <i class="bi bi-pencil"></i>
                </a>

                <!-- Delete Button with SweetAlert2 -->
                <form method="post" action="<?= site_url('contractors/delete/' . $item['contractor_id']) ?>" class="d-inline">
                  <?= csrf_field() ?>
                  <button type="button" class="btn btn-sm btn-outline-danger btn-sweet-delete-contractor" data-name="<?= esc($item['contractor_name']) ?>" title="Delete Contractor">
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
            No contractors found matching the search criteria.
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