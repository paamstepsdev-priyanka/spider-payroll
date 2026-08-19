<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between pb-3 mb-4 border-bottom">
    <div>
        <h3 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="text-primary" viewBox="0 0 16 16">
                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
            </svg>
            <?= esc($title) ?>
        </h3>
        <p class="text-secondary mb-0 small"><?= esc($subtitle) ?></p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="<?= site_url('payroll?year=' . $year) ?>" class="btn btn-outline-secondary btn-sm fw-medium d-flex align-items-center gap-2 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H3.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L3.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Back to Monthly Dashboard
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm bg-white rounded-3">
    <div class="card-body p-5 text-center">
        <div class="mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle p-4 mb-4 shadow-sm" style="width: 88px; height: 88px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                    <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                </svg>
            </div>
            <h4 class="fw-bold text-dark mb-2"><?= esc($monthName) ?> Payroll Module</h4>
            <p class="text-secondary max-w-md mx-auto mb-0" style="max-width: 560px; line-height: 1.6;">
                You have opened the payroll workspace for <strong><?= esc($monthName) ?></strong>. The detailed 3-step payroll flow (Step 1: Attendance Register, Step 2: Salary Computation, Step 3: Payslip / NEFT) will be implemented in the next phase.
            </p>
        </div>

        <div class="d-flex justify-content-center gap-3">
            <a href="<?= site_url('payroll?year=' . $year) ?>" class="btn btn-primary px-4 fw-semibold shadow-sm">
                Return to Dashboard
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
