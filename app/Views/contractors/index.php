<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
  .btn-add-contractor {
    background: linear-gradient(180deg, #0f172a 0%, #1d4ed8 100%) !important;
    color: #ffffff !important;
    border: none;
    border-radius: 6px;
    padding: 7px 16px;
    font-size: 13px;
    font-weight: 500;
    transition: opacity 0.15s ease;
  }

  .btn-add-contractor:hover {
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
    background-color: #0f172a !important;
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
      <h4 class="mb-0 fw-semibold text-dark fs-5">Contractors</h4>
      <div class="text-secondary small mt-1">Manage contractors</div>
    </div>
    <a href="<?= site_url('contractors/create') ?>" class="btn btn-add-contractor text-decoration-none">
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