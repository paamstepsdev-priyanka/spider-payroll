<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
  .btn-add-user {
    background: linear-gradient(180deg, #0f172a 0%, #1d4ed8 100%) !important;
    color: #ffffff !important;
    border: none;
    border-radius: 6px;
    padding: 7px 16px;
    font-size: 13px;
    font-weight: 500;
    transition: opacity 0.15s ease;
  }

  .btn-add-user:hover {
    opacity: 0.92;
    color: #ffffff !important;
  }

  .table-plain,
  .table-plain>tbody,
  .table-plain>tbody>tr,
  .table-plain>tbody>tr>td,
  .table-plain>tbody>tr>th {
    background-color: #ffffff !important;
    background: #ffffff !important;
    color: #1f2937 !important;
  }

  .table-plain thead th {
    background-color: #f8fafc !important;
    background: #f8fafc !important;
    color: #475569 !important;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border-bottom: 1px solid #e2e8f0 !important;
  }

  .table-plain tbody td,
  .table-plain tbody th {
    padding: 12px 16px;
    color: #1f2937 !important;
    font-size: 14px;
    border-bottom: 1px solid #f1f5f9 !important;
  }

  .table-plain tbody tr:hover,
  .table-plain tbody tr:hover>td,
  .table-plain tbody tr:hover>th {
    background-color: #f8fafc !important;
    background: #f8fafc !important;
  }

  /* Status Badges */
  .badge-subtle-active {
    background-color: #dcfce7 !important;
    color: #166534 !important;
    font-weight: 500;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 4px;
  }

  .badge-subtle-inactive {
    background-color: #f1f5f9 !important;
    color: #64748b !important;
    font-weight: 500;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 4px;
  }

  .badge-subtle-admin {
    background-color: #f3e8ff !important;
    color: #7e22ce !important;
    font-weight: 500;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 4px;
  }

  /* Action Buttons */
  .btn-act {
    font-size: 12px;
    font-weight: 500;
    border-radius: 4px;
    padding: 4px 10px;
    text-decoration: none;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
  }

  .btn-act-view,
  .btn-act-edit {
    background-color: #ffffff;
    color: #374151;
    border: 1px solid #d1d5db;
  }

  .btn-act-view:hover,
  .btn-act-edit:hover {
    background-color: #f3f4f6;
    color: #111827;
  }

  .btn-act-warning {
    background-color: #ffffff;
    color: #b45309;
    border: 1px solid #fde68a;
  }

  .btn-act-warning:hover {
    background-color: #fffbeb;
    color: #92400e;
  }

  .btn-act-success {
    background-color: #ffffff;
    color: #15803d;
    border: 1px solid #bbf7d0;
  }

  .btn-act-success:hover {
    background-color: #f0fdf4;
    color: #166534;
  }

  .btn-act-delete {
    background-color: #ffffff;
    color: #dc2626;
    border: 1px solid #fca5a5;
  }

  .btn-act-delete:hover {
    background-color: #fef2f2;
    color: #991b1b;
  }

  /* Pagination Styling */
  .pagination .page-item .page-link {
    color: #0f172a;
    border-color: #e2e8f0;
    padding: 6px 12px;
    font-size: 13px;
  }

  .pagination .page-item.active .page-link {
    background: linear-gradient(180deg, #0f172a 0%, #1d4ed8 100%) !important;
    border-color: #0f172a !important;
    color: #ffffff !important;
  }

  .pagination .page-item.disabled .page-link {
    color: #94a3b8;
    background-color: #f8fafc;
  }
</style>

<div class="card mb-4" style="background-color: #ffffff !important; border: 1px solid #e5e7eb; border-radius: 6px;">
  <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #ffffff !important; border-bottom: 1px solid #e5e7eb;">
    <div>
      <h4 class="mb-0 fw-semibold text-dark fs-5">Users</h4>
      <div class="text-secondary small mt-1">Manage system users</div>
    </div>
    <a href="<?= site_url('users/create') ?>" class="btn btn-add-user text-decoration-none">
      + Add User
    </a>
  </div>
  <div class="card-body p-4" style="background-color: #ffffff !important;">
    <!-- Filter Form with AJAX Live Search -->
    <form id="userFilterForm" class="row g-3 mb-4 align-items-end" onsubmit="return false;">
      <div class="col-md-5">
        <label for="search" class="form-label small fw-semibold text-secondary">Search</label>
        <input type="text" name="search" id="search" class="form-control" placeholder="Type to search by name or username..." value="<?= esc($search ?? '') ?>" autocomplete="off">
      </div>
      <div class="col-md-3">
        <label for="role" class="form-label small fw-semibold text-secondary">Role</label>
        <select name="role" id="role" class="form-select">
          <option value="">-- All Roles --</option>
          <option value="super_admin" <?= ($role ?? '') === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
        </select>
      </div>
      <div class="col-md-3">
        <label for="status" class="form-label small fw-semibold text-secondary">Status</label>
        <select name="status" id="status" class="form-select">
          <option value="">-- All Status --</option>
          <option value="1" <?= ($status ?? '') === '1' ? 'selected' : '' ?>>Active</option>
          <option value="0" <?= ($status ?? '') === '0' ? 'selected' : '' ?>>Inactive</option>
        </select>
      </div>
      <div class="col-md-1 text-end">
        <button type="button" id="resetBtn" class="btn btn-outline-secondary w-100 fw-medium" style="border-radius: 6px; padding: 7px 12px; font-size: 13px;">Reset</button>
      </div>
    </form>

    <!-- AJAX User Table Container -->
    <div id="tableContainer">
      <?= view('users/_table', ['users' => $users, 'pager' => $pager]) ?>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("search");
    const roleSelect = document.getElementById("role");
    const statusSelect = document.getElementById("status");
    const resetBtn = document.getElementById("resetBtn");
    const tableContainer = document.getElementById("tableContainer");

    let debounceTimer;

    function fetchUsers(pageUrl = null) {
      const search = searchInput.value.trim();
      const role = roleSelect.value;
      const status = statusSelect.value;

      let url = pageUrl;
      if (!url) {
        const params = new URLSearchParams({
          search,
          role,
          status
        });
        url = "<?= site_url('users') ?>?" + params.toString();
      }

      fetch(url, {
          headers: {
            "X-Requested-With": "XMLHttpRequest"
          }
        })
        .then(response => response.text())
        .then(html => {
          tableContainer.innerHTML = html;
        })
        .catch(error => console.error("Error fetching users:", error));
    }

    searchInput.addEventListener("input", function() {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => fetchUsers(), 250);
    });

    roleSelect.addEventListener("change", () => fetchUsers());
    statusSelect.addEventListener("change", () => fetchUsers());

    resetBtn.addEventListener("click", function() {
      searchInput.value = "";
      roleSelect.value = "";
      statusSelect.value = "";
      fetchUsers();
    });

    // Handle AJAX Pagination Clicks
    tableContainer.addEventListener("click", function(e) {
      const pageLink = e.target.closest(".ajax-page-link");
      if (pageLink) {
        e.preventDefault();
        const href = pageLink.getAttribute("href");
        if (href && href !== "#") {
          fetchUsers(href);
        }
      }
    });
  });
</script>

<?= $this->endSection() ?>