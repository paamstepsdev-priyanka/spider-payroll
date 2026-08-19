<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>



<div class="card mb-4" style="background-color: #ffffff !important; border: 1px solid #e5e7eb; border-radius: 6px;">
  <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #ffffff !important; border-bottom: 1px solid #e5e7eb;">
    <div>
      <h4 class="mb-0 fw-semibold text-dark fs-5">Contractors</h4>
      <div class="text-secondary small mt-1">Manage contractors</div>
    </div>
    <a href="<?= site_url('contractors/create') ?>" class="btn btn-primary px-3 py-2 text-decoration-none" style="border-radius: 6px; font-size: 13px;">
      + Add Contractor
    </a>
  </div>
  <div class="card-body p-4" style="background-color: #ffffff !important;">
    <!-- Search & Filter Form with Live AJAX Search -->
    <form id="contractorFilterForm" class="row g-3 mb-4 align-items-end" onsubmit="return false;">
      <div class="col-md-7">
        <label for="search" class="form-label small fw-semibold text-secondary">Search</label>
        <input type="text" name="search" id="search" class="form-control" placeholder="Search contractors..." value="<?= esc($search ?? '') ?>" autocomplete="off">
      </div>
      <div class="col-md-3">
        <label for="status" class="form-label small fw-semibold text-secondary">Status</label>
        <select name="status" id="status" class="form-select">
          <option value="">-- All Status --</option>
          <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
          <option value="inactive" <?= ($status ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
      </div>
      <div class="col-md-2 text-end">
        <button type="button" id="resetBtn" class="btn btn-outline-secondary w-100 fw-medium" style="border-radius: 6px; padding: 7px 12px; font-size: 13px;">Reset</button>
      </div>
    </form>

    <!-- AJAX Contractor Table Container -->
    <div id="tableContainer">
      <?= view('contractors/_table', ['contractors' => $contractors, 'pager' => $pager]) ?>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("search");
    const statusSelect = document.getElementById("status");
    const resetBtn = document.getElementById("resetBtn");
    const tableContainer = document.getElementById("tableContainer");

    let debounceTimer;

    function fetchContractors(pageUrl = null) {
      const search = searchInput.value.trim();
      const status = statusSelect.value;

      let url = pageUrl;
      if (!url) {
        const params = new URLSearchParams({
          search,
          status
        });
        url = "<?= site_url('contractors') ?>?" + params.toString();
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
        .catch(error => console.error("Error fetching contractors:", error));
    }

    searchInput.addEventListener("input", function() {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => fetchContractors(), 250);
    });

    statusSelect.addEventListener("change", () => fetchContractors());

    resetBtn.addEventListener("click", function() {
      searchInput.value = "";
      statusSelect.value = "";
      fetchContractors();
    });

    // Handle AJAX Pagination Clicks
    tableContainer.addEventListener("click", function(e) {
      const pageLink = e.target.closest(".ajax-page-link");
      if (pageLink) {
        e.preventDefault();
        const href = pageLink.getAttribute("href");
        if (href && href !== "#") {
          fetchContractors(href);
        }
      }
    });
  });
</script>

<?= $this->endSection() ?>