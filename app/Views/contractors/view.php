<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
  <div class="col-md-10 col-lg-8">
    <div class="card mb-4" style="background-color: #ffffff !important; border: 1px solid #e5e7eb; border-radius: 6px;">
      <div class="card-header fw-bold d-flex justify-content-between align-items-center py-3" style="background-color: #ffffff !important; border-bottom: 1px solid #e5e7eb;">
        <h4 class="mb-0 fw-bold text-dark fs-5">Contractor Details #<?= esc($contractor['contractor_id']) ?></h4>
        <a href="<?= site_url('contractors') ?>" class="btn btn-sm btn-outline-secondary" style="border-radius: 6px;">
          Back to Contractors
        </a>
      </div>
      <div class="card-body p-4" style="background-color: #ffffff !important;">

        <!-- SECTION 1: CONTRACTOR DETAILS -->
        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3" style="font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase; color: #374151 !important;">
          Contractor Details
        </h6>
        <div class="table-responsive mb-4">
          <table class="table table-bordered mb-0 align-middle">
            <tbody>
              <tr>
                <th scope="row" class="bg-light text-secondary" style="width: 35%;">Contractor Name</th>
                <td class="fw-semibold text-dark"><?= esc($contractor['contractor_name']) ?></td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary">Phone Number</th>
                <td><?= esc($contractor['phone_number'] ?: '-') ?></td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary">Date of Birth (DOB)</th>
                <td><?= !empty($contractor['dob']) ? date('d/m/Y', strtotime($contractor['dob'])) : '-' ?></td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary">Email Address</th>
                <td><?= esc($contractor['email'] ?: '-') ?></td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary">Address</th>
                <td><?= nl2br(esc($contractor['address'] ?: '-')) ?></td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- SECTION 2: BANK DETAILS -->
        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3" style="font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase; color: #374151 !important;">
          Bank Details
        </h6>
        <div class="table-responsive mb-4">
          <table class="table table-bordered mb-0 align-middle">
            <tbody>
              <tr>
                <th scope="row" class="bg-light text-secondary" style="width: 35%;">Account Holder Name</th>
                <td class="fw-medium text-dark"><?= esc($contractor['account_holder_name'] ?? '-') ?></td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary" style="width: 35%;">Bank Name</th>
                <td class="fw-medium text-dark"><?= esc($contractor['bank_name'] ?: '-') ?></td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary">Branch Name</th>
                <td class="fw-medium text-dark"><?= esc($contractor['branch_name'] ?: '-') ?></td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary">Bank Account Number</th>
                <td><code class="text-dark bg-light px-2 py-1 rounded" style="font-size: 13px;"><?= esc($contractor['bank_account_number']) ?></code></td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary">IFSC Code</th>
                <td><code class="text-dark fw-bold bg-light px-2 py-1 rounded" style="font-size: 13px;"><?= esc($contractor['ifsc_code']) ?></code></td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- SECTION 3: SYSTEM INFORMATION -->
        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3" style="font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase; color: #374151 !important;">
          System Information
        </h6>
        <div class="table-responsive mb-4">
          <table class="table table-bordered mb-0 align-middle">
            <tbody>
              <tr>
                <th scope="row" class="bg-light text-secondary" style="width: 35%;">Status</th>
                <td>
                  <?php if ($contractor['status'] === 'active'): ?>
                    <span class="badge border border-success text-success">Active</span>
                  <?php else: ?>
                    <span class="badge border border-danger text-danger">Inactive</span>
                  <?php endif; ?>
                </td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary">Created Date</th>
                <td><?= !empty($contractor['created_at']) ? date('d M Y, h:i A', strtotime($contractor['created_at'])) : '-' ?></td>
              </tr>
              <tr>
                <th scope="row" class="bg-light text-secondary">Updated Date</th>
                <td><?= !empty($contractor['updated_at']) ? date('d M Y, h:i A', strtotime($contractor['updated_at'])) : '-' ?></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="d-flex justify-content-end gap-2 border-top pt-3">
          <a href="<?= site_url('contractors/edit/' . $contractor['contractor_id']) ?>" class="btn btn-primary px-4 fw-medium" style="border-radius: 6px;">
            Edit Contractor
          </a>
          <a href="<?= site_url('contractors') ?>" class="btn btn-outline-secondary px-3" style="border-radius: 6px;">
            Back to Contractors
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>