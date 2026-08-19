<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>



<div class="card mb-4" style="background-color: #ffffff !important; border: 1px solid #e5e7eb; border-radius: 6px;">
  <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #ffffff !important; border-bottom: 1px solid #e5e7eb;">
    <div>
      <h4 class="mb-0 fw-semibold text-dark fs-5">Users</h4>
      <div class="text-secondary small mt-1">Manage system users</div>
    </div>
    <a href="<?= site_url('users/create') ?>" class="btn btn-primary px-3 py-2 text-decoration-none" style="border-radius: 6px; font-size: 13px;">
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