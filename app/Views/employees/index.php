<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>



<div class="card mb-4" style="background-color: #ffffff !important; border: 1px solid #e5e7eb; border-radius: 6px;">
  <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #ffffff !important; border-bottom: 1px solid #e5e7eb;">
    <div>
      <h4 class="mb-0 fw-semibold text-dark fs-5">Employees</h4>
      <div class="text-secondary small mt-1">Manage employees</div>
    </div>
    <a href="<?= site_url('employees/create') ?>" class="btn btn-primary px-3 py-2 text-decoration-none" style="border-radius: 6px; font-size: 13px;">
      + Add Employee
    </a>
  </div>
  <div class="card-body p-4" style="background-color: #ffffff !important;">

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 6px; font-size: 14px;">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php
    $currentPage = isset($pager) ? $pager->getCurrentPage() : 1;
    $perPage     = isset($pager) ? $pager->getPerPage() : 10;
    $total       = isset($pager) ? $pager->getTotal() : count($employees);
    $offset      = ($currentPage - 1) * $perPage;
    $startCount  = $total > 0 ? $offset + 1 : 0;
    $endCount    = min($offset + $perPage, $total);
    ?>

    <!-- Search & Filter Form (Standard GET Request) -->
    <form method="GET" action="<?= site_url('employees') ?>" class="row g-3 mb-3 align-items-end">
      <input type="hidden" name="sort_column" value="<?= esc($sortColumn ?? 'employee_id') ?>">
      <input type="hidden" name="sort_order" value="<?= esc($sortOrder ?? 'DESC') ?>">
      <?php if (!empty($status)): ?>
        <input type="hidden" name="status" value="<?= esc($status) ?>">
      <?php endif; ?>
      <div class="col-lg-5 col-md-4">
        <label for="search" class="form-label small fw-semibold text-secondary">Search</label>
        <input type="text" name="search" id="search" class="form-control" placeholder="Search employees..." value="<?= esc($search ?? '') ?>">
      </div>
      <div class="col-lg-2 col-md-5">
        <label for="contractor_id" class="form-label small fw-semibold text-secondary">Contractor</label>
        <select name="contractor_id" id="contractor_id" class="form-select">
          <option value="">-- All Contractors --</option>
          <?php foreach ($contractors as $contractor): ?>
            <option value="<?= $contractor['contractor_id'] ?>" <?= ((string)($contractorId ?? '') === (string)$contractor['contractor_id']) ? 'selected' : '' ?>>
              <?= esc($contractor['contractor_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-lg-3 col-md-3">
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary flex-fill fw-medium" style="border-radius: 6px; padding: 7px 12px; font-size: 13px;">Search</button>
          <a href="<?= site_url('employees') ?>" class="btn btn-outline-secondary flex-fill fw-medium text-center text-decoration-none" style="border-radius: 6px; padding: 7px 12px; font-size: 13px;">Reset</a>
        </div>
      </div>
    </form>

    <!-- Status Quick Filter Tabs (Touching Top of Table) -->
    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
      <?php
      $buildTabUrl = function ($st) use ($search, $contractorId, $sortColumn, $sortOrder) {
        $params = [];
        if (!empty($search)) $params['search'] = $search;
        if (!empty($contractorId)) $params['contractor_id'] = $contractorId;
        if (!empty($st)) $params['status'] = $st;
        if (!empty($sortColumn)) $params['sort_column'] = $sortColumn;
        if (!empty($sortOrder)) $params['sort_order'] = $sortOrder;
        return site_url('employees') . (!empty($params) ? '?' . http_build_query($params) : '');
      };

      $sortHeader = function ($column, $title, $align = 'text-start') use ($search, $contractorId, $status, $sortColumn, $sortOrder) {
        $isCurrent = ($sortColumn === $column);
        $nextOrder = ($isCurrent && $sortOrder === 'ASC') ? 'DESC' : 'ASC';

        $params = [];
        if (!empty($search)) $params['search'] = $search;
        if (!empty($contractorId)) $params['contractor_id'] = $contractorId;
        if (!empty($status)) $params['status'] = $status;
        $params['sort_column'] = $column;
        $params['sort_order']  = $nextOrder;

        $url = site_url('employees') . '?' . http_build_query($params);

        $iconClass = 'bi bi-arrow-down-up opacity-25 ms-1 small';
        if ($isCurrent) {
          if ($sortOrder === 'ASC') {
            $iconClass = 'bi bi-sort-alpha-down text-primary ms-1';
            if (in_array($column, ['monthly_base_salary', 'date_of_joining', 'employee_id'])) {
              $iconClass = 'bi bi-sort-numeric-down text-primary ms-1';
            }
          } else {
            $iconClass = 'bi bi-sort-alpha-down-alt text-primary ms-1';
            if (in_array($column, ['monthly_base_salary', 'date_of_joining', 'employee_id'])) {
              $iconClass = 'bi bi-sort-numeric-down-alt text-primary ms-1';
            }
          }
        }

        return '<a href="' . $url . '" class="text-decoration-none text-dark d-inline-flex align-items-center ' . $align . '" title="Click to sort by ' . esc($title) . '">'
          . esc($title) . ' <i class="' . $iconClass . '" style="font-size: 12px;"></i></a>';
      };
      ?>
      <a href="<?= $buildTabUrl('active') ?>"
        class="btn btn-sm <?= ($status === 'active') ? 'btn-success text-white fw-bold shadow-sm' : 'btn-outline-success' ?> rounded-2 px-3 py-1 text-decoration-none" style="font-size: 13px;">
        Active <span class="badge <?= ($status === 'active') ? 'text-bg-light text-success' : 'text-bg-success' ?> ms-1 rounded-pill"><?= $statusCounts['active'] ?? 0 ?></span>
      </a>
      <a href="<?= $buildTabUrl('inactive') ?>"
        class="btn btn-sm <?= ($status === 'inactive') ? 'btn-danger text-white fw-bold shadow-sm' : 'btn-outline-danger' ?> rounded-2 px-3 py-1 text-decoration-none" style="font-size: 13px;">
        Inactive <span class="badge <?= ($status === 'inactive') ? 'text-bg-light text-danger' : 'text-bg-danger' ?> ms-1 rounded-pill"><?= $statusCounts['inactive'] ?? 0 ?></span>
      </a>
      <a href="<?= $buildTabUrl('relieved') ?>"
        class="btn btn-sm <?= ($status === 'relieved') ? 'btn-warning text-dark fw-bold shadow-sm' : 'btn-outline-warning text-dark' ?> rounded-2 px-3 py-1 text-decoration-none" style="font-size: 13px;">
        Relieved <span class="badge <?= ($status === 'relieved') ? 'text-bg-dark text-warning' : 'text-bg-warning text-dark' ?> ms-1 rounded-pill"><?= $statusCounts['relieved'] ?? 0 ?></span>
      </a>
      <a href="<?= $buildTabUrl('all') ?>"
        class="btn btn-sm <?= ($status === 'all') ? 'btn-primary text-white fw-bold shadow-sm' : 'btn-outline-secondary text-dark' ?> rounded-2 px-3 py-1 text-decoration-none" style="font-size: 13px;">
        All <span class="badge <?= ($status === 'all') ? 'text-bg-light text-primary' : 'text-bg-secondary' ?> ms-1 rounded-pill"><?= $statusCounts['all'] ?? 0 ?></span>
      </a>
    </div>

    <!-- Employees List Table -->
    <div class="table-responsive">
      <table class="table table-plain align-middle mb-0" style="border: 1px solid #e2e8f0; background-color: #ffffff !important;">
        <thead>
          <tr>
            <th scope="col" style="width: 50px;"><?= $sortHeader('employee_id', '#') ?></th>
            <th scope="col" style="width: 130px;"><?= $sortHeader('employee_name', 'Employee Name') ?></th>
            <th scope="col" style="width: 120px;"><?= $sortHeader('biometric_code', 'Biometric Code') ?></th>
            <th scope="col" style="width: 120px;"><?= $sortHeader('contractor_name', 'Contractor') ?></th>
            <th scope="col" style="width: 120px;"><?= $sortHeader('monthly_base_salary', 'Monthly Salary') ?></th>
            <th scope="col" class="text-center" style="width: 110px;"><?= $sortHeader('date_of_joining', 'Joining Date', 'justify-content-center') ?></th>
            <th scope="col" class="text-center" style="width: 90px;"><?= $sortHeader('status', 'Status', 'justify-content-center') ?></th>
            <th scope="col" class="text-center" style="width: 70px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($employees)): ?>
            <?php $sr = $startCount; ?>
            <?php foreach ($employees as $employee): ?>
              <tr>
                <td class="fw-normal text-secondary"><?= $sr++ ?></td>
                <td>
                  <a href="<?= site_url('employees/view/' . $employee['employee_id']) ?>" class="fw-medium text-dark text-decoration-none">
                    <?= esc($employee['employee_name']) ?>
                  </a>
                </td>
                <td>
                  <?php if (!empty($employee['biometric_code'])): ?>
                    <code class="text-dark bg-light px-2 py-1 rounded" style="font-size: 13px; border: 1px solid #e2e8f0;"><?= esc($employee['biometric_code']) ?></code>
                  <?php else: ?>
                    <span class="text-muted small">-</span>
                  <?php endif; ?>
                </td>
                <td class="text-secondary"><?= esc($employee['contractor_name'] ?? 'Unassigned') ?></td>
                <td class="fw-medium text-dark">
                  ₹<?= number_format((float)$employee['monthly_base_salary'], 2) ?>
                </td>
                <td class="text-center text-secondary">
                  <?= !empty($employee['date_of_joining']) ? date('d/m/Y', strtotime($employee['date_of_joining'])) : '-' ?>
                </td>
                <td class="text-center">
                  <?php if ($employee['status'] === 'active'): ?>
                    <span class="badge border border-success text-success">Active</span>
                  <?php elseif ($employee['status'] === 'relieved'): ?>
                    <span class="badge border border-warning text-warning">Relieved</span>
                  <?php else: ?>
                    <span class="badge border border-danger text-danger">Inactive</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <div class="d-flex justify-content-center gap-1">
                    <a href="<?= site_url('employees/view/' . $employee['employee_id']) ?>" class="btn btn-sm btn-outline-secondary" title="View Details">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a href="<?= site_url('employees/edit/' . $employee['employee_id']) ?>" class="btn btn-sm btn-outline-primary" title="Edit Employee">
                      <i class="bi bi-pencil"></i>
                    </a>

                    <form action="<?= site_url('employees/delete/' . $employee['employee_id']) ?>" method="POST" class="d-inline" onsubmit="return confirmDelete(event, '<?= esc($employee['employee_name'], 'js') ?>');">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Employee">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="text-center py-4 text-secondary small">
                No employees found matching your search criteria.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination Footer -->
    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top" style="border-color: #e2e8f0 !important;">
      <div class="text-secondary small">
        Showing <span class="fw-semibold text-dark"><?= $startCount ?></span> to <span class="fw-semibold text-dark"><?= $endCount ?></span> of <span class="fw-semibold text-dark"><?= $total ?></span> entries
      </div>
      <div>
        <?php if (!empty($pager) && $pager->getPageCount() > 1): ?>
          <?= $pager->links('default', 'bootstrap_pagination') ?>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>

<script>
  function confirmDelete(event, employeeName) {
    event.preventDefault();
    const form = event.target;
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Delete Employee?',
        text: `Are you sure you want to delete employee "${employeeName}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    } else {
      if (confirm(`Are you sure you want to delete employee "${employeeName}"?`)) {
        form.submit();
      }
    }
    return false;
  }

  function confirmStatusToggle(event, employeeName, currentStatus) {
    event.preventDefault();
    const form = event.target;
    const nextStatusText = currentStatus === 'active' ? 'deactivate' : 'activate';

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Change Employee Status?',
        text: `Are you sure you want to ${nextStatusText} employee "${employeeName}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: currentStatus === 'active' ? '#b45309' : '#15803d',
        cancelButtonColor: '#6b7280',
        confirmButtonText: `Yes, ${nextStatusText.charAt(0).toUpperCase() + nextStatusText.slice(1)}`,
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    } else {
      if (confirm(`Are you sure you want to ${nextStatusText} employee "${employeeName}"?`)) {
        form.submit();
      }
    }
    return false;
    document.addEventListener("DOMContentLoaded", function() {
      const csrfName = "<?= csrf_token() ?>";
      let csrfHash = "<?= csrf_hash() ?>";

      document.addEventListener("click", function(e) {
        // Handle opening salary edit form
        const displayWrap = e.target.closest(".salary-display-wrap");
        if (displayWrap) {
          const cell = displayWrap.closest("td");
          const editWrap = cell.querySelector(".salary-edit-wrap");
          const input = cell.querySelector(".salary-input");

          displayWrap.classList.add("d-none");
          editWrap.classList.remove("d-none");
          editWrap.classList.add("d-flex");
          input.focus();
          input.select();
          return;
        }

        // Handle cancel salary edit
        const cancelBtn = e.target.closest(".btn-cancel-salary");
        if (cancelBtn) {
          const cell = cancelBtn.closest("td");
          const displayWrap = cell.querySelector(".salary-display-wrap");
          const editWrap = cell.querySelector(".salary-edit-wrap");
          const input = cell.querySelector(".salary-input");

          input.value = displayWrap.getAttribute("data-salary");
          editWrap.classList.add("d-none");
          editWrap.classList.remove("d-flex");
          displayWrap.classList.remove("d-none");
          return;
        }

        // Handle save salary edit
        const saveBtn = e.target.closest(".btn-save-salary");
        if (saveBtn) {
          submitSalaryUpdate(saveBtn.closest("td"));
          return;
        }
      });

      // Save on Enter or cancel on Escape inside salary input
      document.addEventListener("keydown", function(e) {
        if (e.target.classList.contains("salary-input")) {
          if (e.key === "Enter") {
            e.preventDefault();
            submitSalaryUpdate(e.target.closest("td"));
          } else if (e.key === "Escape") {
            const cell = e.target.closest("td");
            const displayWrap = cell.querySelector(".salary-display-wrap");
            const editWrap = cell.querySelector(".salary-edit-wrap");
            e.target.value = displayWrap.getAttribute("data-salary");
            editWrap.classList.add("d-none");
            editWrap.classList.remove("d-flex");
            displayWrap.classList.remove("d-none");
          }
        }
      });

      function submitSalaryUpdate(cell) {
        const displayWrap = cell.querySelector(".salary-display-wrap");
        const editWrap = cell.querySelector(".salary-edit-wrap");
        const input = cell.querySelector(".salary-input");
        const empId = displayWrap.getAttribute("data-id");
        const newSalary = input.value.trim();

        if (!newSalary || isNaN(newSalary) || parseFloat(newSalary) < 0) {
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Invalid Salary',
              text: 'Please enter a valid non-negative salary amount.'
            });
          } else {
            alert('Please enter a valid non-negative salary amount.');
          }
          return;
        }

        const formData = new FormData();
        formData.append('monthly_base_salary', newSalary);
        formData.append(csrfName, csrfHash);

        fetch(`<?= site_url('employees/update-salary/') ?>${empId}`, {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              cell.querySelector(".salary-text").textContent = data.formatted_salary;
              displayWrap.setAttribute("data-salary", data.raw_salary);
              editWrap.classList.add("d-none");
              editWrap.classList.remove("d-flex");
              displayWrap.classList.remove("d-none");

              if (typeof showToast === 'function') {
                showToast('success', data.message, 'Success!');
              }
            } else {
              if (typeof Swal !== 'undefined') {
                Swal.fire({
                  icon: 'error',
                  title: 'Update Failed',
                  text: data.message || 'Failed to update salary.'
                });
              } else {
                alert(data.message || 'Failed to update salary.');
              }
            }
          })
          .catch(err => {
            console.error('Error updating salary:', err);
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An unexpected error occurred while updating salary.'
              });
            }
          });
      }
    });
</script>

<?= $this->endSection() ?>