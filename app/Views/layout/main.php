<!DOCTYPE html>
<html lang="en" data-coreui-theme="light">

<head>
    <base href="<?= base_url('backend/') ?>/">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?= esc($title ?? 'Spider Payroll') ?></title>
    <!-- Vendors styles-->
    <link rel="stylesheet" href="<?= base_url('backend/vendors/simplebar/css/simplebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('backend/css/vendors/simplebar.css') ?>">
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Main styles for this application-->
    <link href="<?= base_url('backend/css/style.css') ?>" rel="stylesheet">
    <link href="<?= base_url('backend/css/custom.css') ?>" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Flatpickr Datepicker CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
    <script src="<?= base_url('backend/js/config.js') ?>"></script>
</head>

<body>
    <!-- Theme Sidebar -->
    <div class="sidebar sidebar-fixed" id="sidebar">
        <div class="sidebar-header border-0">
            <div class="sidebar-brand me-auto">
                <a href="<?= site_url('users') ?>" class="text-white text-decoration-none fw-bold fs-5 d-flex align-items-center">
                    <i class="bi bi-shield-check me-2 fs-4" style="color: #10b981 !important;"></i>
                    <span>Spider Payroll</span>
                </a>
            </div>
            <button class="btn-close btn-close-white d-lg-none" type="button" aria-label="Close" data-coreui-toggle="sidebar" data-coreui-target="#sidebar" onclick="coreui.Sidebar.getOrCreateInstance(document.querySelector('#sidebar')).toggle()"></button>
        </div>

        

        <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
            <li class="nav-item">
                <a class="nav-link <?= url_is('dashboard*') ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>">
                    <i class="bi bi-grid-1x2 nav-icon"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (url_is('payroll*') || url_is('attendance*')) ? 'active' : '' ?>" href="<?= site_url('payroll') ?>">
                    <i class="bi bi-wallet2 nav-icon"></i>
                    <span>Payroll</span>
                </a>
            </li>

            <!-- Master -> Users, Contractors & Employees Menu Item -->
            <li class="nav-group <?= (url_is('users*') || url_is('contractors*') || url_is('employees*')) ? 'show' : '' ?>">
                <a class="nav-link nav-group-toggle" href="#">
                    <i class="bi bi-layers nav-icon"></i>
                    <span>Master</span>
                    <i class="bi bi-caret-down-fill nav-chevron"></i>
                </a>
                <ul class="nav-group-items compact">
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('users*') ? 'active' : '' ?>" href="<?= site_url('users') ?>">
                            <i class="bi bi-person nav-icon"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('contractors*') ? 'active' : '' ?>" href="<?= site_url('contractors') ?>">
                            <i class="bi bi-building nav-icon"></i>
                            <span>Contractors</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('employees*') ? 'active' : '' ?>" href="<?= site_url('employees') ?>">
                            <i class="bi bi-people nav-icon"></i>
                            <span>Employees</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

    </div>

    <!-- Main Wrapper -->
    <div class="wrapper d-flex flex-column min-vh-100">
        <!-- Header -->
        <header class="header header-sticky p-0 mb-4">
            <div class="container-fluid px-4 py-2 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <button class="header-toggler" type="button" data-coreui-toggle="sidebar" data-coreui-target="#sidebar" onclick="coreui.Sidebar.getOrCreateInstance(document.querySelector('#sidebar')).toggle()" style="margin-inline-start: -8px">
                        <svg class="icon icon-lg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="var(--ci-primary-color, currentcolor)" d="M80 96h352v32H80zm0 144h352v32H80zm0 144h352v32H80z" class="ci-primary" />
                        </svg>
                    </button>

                    <!-- Subtle Breadcrumb in Topbar -->
                    <nav aria-label="breadcrumb" class="d-none d-sm-block">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= site_url('users') ?>">Home</a></li>
                            <li class="breadcrumb-item">Master</li>
                            <li class="breadcrumb-item active"><?= esc($breadcrumb_item ?? 'Users') ?></li>
                        </ol>
                    </nav>
                </div>

                <ul class="header-nav ms-auto mb-0 d-flex align-items-center gap-2">
                    <li class="nav-item">
                        <a href="<?= site_url('logout') ?>" class="btn btn-light btn-sm text-primary fw-semibold d-flex align-items-center gap-1 px-3 py-1 shadow-sm" style="border-radius: 6px; font-size: 13px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 1h-8A1.5 1.5 0 0 0 0 2.5v9A1.5 1.5 0 0 0 1.5 13h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                            </svg>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="body flex-grow-1">
            <div class="container-fluid px-4 py-2">

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Page Specific Content -->
                <?= $this->renderSection('content') ?>

            </div>
        </div>
    </div>

    <!-- CoreUI and necessary plugins-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="<?= base_url('backend/vendors/@coreui/coreui/js/coreui.bundle.min.js') ?>"></script>
    <script src="<?= base_url('backend/vendors/simplebar/js/simplebar.min.js') ?>"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Flatpickr Datepicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="<?= base_url('backend/js/global-form-validation.js') ?>"></script>
    <script>
        const header = document.querySelector("header.header");

        document.addEventListener("scroll", () => {
            if (header) {
                header.classList.toggle("shadow-sm", document.documentElement.scrollTop > 0);
            }
        });

        // Global SweetAlert Confirm Handlers
        document.addEventListener("click", function(e) {
            const deleteBtn = e.target.closest(".btn-sweet-delete");
            if (deleteBtn) {
                e.preventDefault();
                const form = deleteBtn.closest("form");
                const username = deleteBtn.getAttribute("data-username") || "this user";

                Swal.fire({
                    title: 'Delete User?',
                    text: `Are you sure you want to delete user '@${username}'? This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel',
                    borderRadius: '8px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }

            const toggleBtn = e.target.closest(".btn-sweet-toggle");
            if (toggleBtn) {
                e.preventDefault();
                const form = toggleBtn.closest("form");
                const action = toggleBtn.getAttribute("data-action") || "toggle";
                const username = toggleBtn.getAttribute("data-username") || "this user";

                Swal.fire({
                    title: `${action.charAt(0).toUpperCase() + action.slice(1)} User?`,
                    text: `Are you sure you want to ${action} user '@${username}'?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: action === 'deactivate' ? '#b45309' : '#15803d',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: `Yes, ${action}`,
                    cancelButtonText: 'Cancel',
                    borderRadius: '8px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }

            const deleteContractorBtn = e.target.closest(".btn-sweet-delete-contractor");
            if (deleteContractorBtn) {
                e.preventDefault();
                const form = deleteContractorBtn.closest("form");
                const name = deleteContractorBtn.getAttribute("data-name") || "this contractor";

                Swal.fire({
                    title: 'Delete Contractor?',
                    text: `Are you sure you want to delete contractor '${name}'?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel',
                    borderRadius: '8px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }

            const toggleContractorBtn = e.target.closest(".btn-sweet-toggle-contractor");
            if (toggleContractorBtn) {
                e.preventDefault();
                const form = toggleContractorBtn.closest("form");
                const action = toggleContractorBtn.getAttribute("data-action") || "toggle";
                const name = toggleContractorBtn.getAttribute("data-name") || "this contractor";

                Swal.fire({
                    title: `${action.charAt(0).toUpperCase() + action.slice(1)} Contractor?`,
                    text: `Are you sure you want to ${action} contractor '${name}'?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: action === 'deactivate' ? '#b45309' : '#15803d',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: `Yes, ${action}`,
                    cancelButtonText: 'Cancel',
                    borderRadius: '8px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    </script>

    <?php if (session()->getFlashdata('success')): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof showToast === 'function') {
                    showToast('success', '<?= esc(session()->getFlashdata('success'), 'js') ?>', 'Success!');
                }
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof showToast === 'function') {
                    showToast('error', '<?= esc(session()->getFlashdata('error'), 'js') ?>', 'Action Failed');
                }
            });
        </script>
    <?php endif; ?>
    <!-- Global Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;" id="globalToastContainer"></div>
</body>

</html>