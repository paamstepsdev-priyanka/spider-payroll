<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>



<div class="card mb-4" style="background-color: #ffffff !important; border: 1px solid #e5e7eb; border-radius: 6px;">
  <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #ffffff !important; border-bottom: 1px solid #e5e7eb;">
    <div>
      <h4 class="mb-0 fw-semibold text-dark fs-5">Contractors</h4>
      <div class="text-secondary small mt-1">Manage contractors</div>
    </div>
    <div class="d-flex gap-2">
      <a href="<?= site_url('contractors/export') ?>" class="btn btn-outline-success px-3 py-2 text-decoration-none" style="border-radius: 6px; font-size: 13px;">
        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
      </a>
      <a href="<?= site_url('contractors/create') ?>" class="btn btn-primary px-3 py-2 text-decoration-none" style="border-radius: 6px; font-size: 13px;">
        + Add Contractor
      </a>
    </div>
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

<!-- Contractor View Modal -->
<div class="modal fade" id="viewContractorModal" tabindex="-1" aria-labelledby="viewContractorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light py-3 border-bottom">
        <h5 class="modal-title fw-semibold text-dark mb-0" id="viewContractorModalLabel">
          <i class="bi bi-person-bounding-box me-2 text-primary"></i>Contractor Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4" id="modalViewBody">
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <div class="text-muted small mt-2">Loading contractor view page...</div>
        </div>
      </div>
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

    // Handle AJAX Pagination Clicks & View Modal Click
    document.addEventListener("click", function(e) {
      const pageLink = e.target.closest(".ajax-page-link");
      if (pageLink) {
        e.preventDefault();
        const href = pageLink.getAttribute("href");
        if (href && href !== "#") {
          fetchContractors(href);
        }
        return;
      }

      const viewBtn = e.target.closest(".btn-view-contractor");
      if (viewBtn) {
        e.preventDefault();
        const contractorId = viewBtn.getAttribute("data-id");
        if (!contractorId) return;

        const viewModalEl = document.getElementById("viewContractorModal");
        const modalBody = document.getElementById("modalViewBody");
        
        modalBody.innerHTML = `
          <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <div class="text-muted small mt-2">Loading contractor details...</div>
          </div>
        `;

        if (typeof coreui !== 'undefined' && coreui.Modal) {
          coreui.Modal.getOrCreateInstance(viewModalEl).show();
        } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
          bootstrap.Modal.getOrCreateInstance(viewModalEl).show();
        } else if (typeof $ !== 'undefined' && $(viewModalEl).modal) {
          $(viewModalEl).modal('show');
        }

        fetch("<?= site_url('contractors/view/') ?>" + contractorId, {
          headers: {
            "X-Requested-With": "XMLHttpRequest"
          }
        })
        .then(res => res.text())
        .then(html => {
          modalBody.innerHTML = html;
        })
        .catch(err => {
          console.error(err);
          modalBody.innerHTML = '<div class="alert alert-danger mb-0">An error occurred while loading contractor details.</div>';
        });
      }
    });
  });
</script>

<?= $this->endSection() ?>