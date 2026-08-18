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
    <!-- Main styles for this application-->
    <link href="<?= base_url('backend/css/style.css') ?>" rel="stylesheet">
    <script src="<?= base_url('backend/js/config.js') ?>"></script>
    <style>
        /* Force Light Theme CSS Variables & Override Dark Mode */
        :root,
        html,
        body,
        [data-coreui-theme="dark"],
        [data-coreui-theme="light"],
        [data-coreui-theme="auto"] {
            --cui-body-bg: #F8F9FA !important;
            --cui-card-bg: #ffffff !important;
            --cui-table-bg: #ffffff !important;
            --cui-table-color: #1f2937 !important;
            --cui-table-hover-bg: #f8fafc !important;
            --cui-table-hover-color: #1f2937 !important;
            --cui-table-active-bg: #ffffff !important;
            --cui-table-striped-bg: #ffffff !important;
            --cui-body-color: #1f2937 !important;
        }

        /* Layout & Page Background */
        body,
        .wrapper,
        .body {
            background-color: #F8F9FA !important;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #1f2937 !important;
        }

        /* Linear Gradient Bluish Sidebar */
        #sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #1d4ed8 100%) !important;
            border-right: none !important;
        }

        #sidebar .sidebar-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding: 16px 20px !important;
        }

        #sidebar .sidebar-brand {
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        #sidebar .sidebar-nav {
            padding: 12px 8px !important;
        }

        #sidebar .nav-item {
            margin-bottom: 2px;
        }

        #sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            border-radius: 6px !important;
            padding: 9px 14px !important;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        #sidebar .nav-link .nav-icon {
            color: rgba(255, 255, 255, 0.85) !important;
            fill: rgba(255, 255, 255, 0.85) !important;
            margin-right: 10px;
        }

        #sidebar .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
        }

        #sidebar .nav-link:hover .nav-icon {
            color: #ffffff !important;
            fill: #ffffff !important;
        }

        #sidebar .nav-link.active,
        #sidebar .nav-link.active .nav-icon {
            color: #ffffff !important;
            fill: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.2) !important;
            font-weight: 600;
        }

        /* Sidebar Dropdown Chevron Icon (Pure White) */
        #sidebar .nav-group-toggle::after {
            filter: brightness(0) invert(1) !important;
            opacity: 0.95 !important;
        }

        /* Submenu Items Left Padding & Indentation */
        #sidebar .nav-group-items {
            padding-left: 0 !important;
        }

        #sidebar .nav-group-items .nav-link {
            padding-left: 42px !important;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8) !important;
        }

        #sidebar .nav-group-items .nav-link .nav-icon {
            margin-right: 6px;
        }

        #sidebar .nav-title {
            color: rgba(255, 255, 255, 0.6) !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 14px 12px 6px 12px !important;
        }

        #sidebar .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
            background-color: transparent !important;
        }

        /* Clean White Navbar */
        header.header {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e5e7eb !important;
            box-shadow: none !important;
        }

        .breadcrumb {
            font-size: 13px;
        }

        .breadcrumb-item a {
            color: #6b7280;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #111827;
            font-weight: 600;
        }

        /* Content Cards */
        .card {
            background-color: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 6px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
        }

        .card-header {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e5e7eb !important;
            border-top-left-radius: 6px !important;
            border-top-right-radius: 6px !important;
            padding: 16px 20px !important;
        }

        /* Form Controls */
        .form-control,
        .form-select {
            background-color: #ffffff !important;
            border: 1px solid #d1d5db !important;
            border-radius: 6px !important;
            font-size: 14px;
            color: #111827 !important;
            padding: 8px 12px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4b5563 !important;
            box-shadow: 0 0 0 2px rgba(75, 85, 99, 0.15) !important;
        }

        /* Global Table Styling - Force Plain White Rows */
        table,
        .table,
        .table>tbody,
        .table>tbody>tr,
        .table>tbody>tr>td,
        .table>tbody>tr>th {
            background-color: #ffffff !important;
            background: #ffffff !important;
            color: #1f2937 !important;
        }

        .table thead,
        .table thead>tr,
        .table thead>tr>th {
            background-color: #f8fafc !important;
            background: #f8fafc !important;
            color: #475569 !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .table tbody tr:hover,
        .table tbody tr:hover>td,
        .table tbody tr:hover>th {
            background-color: #f8fafc !important;
            background: #f8fafc !important;
        }

        /* Footer */
        footer.footer {
            background-color: #ffffff !important;
            border-top: 1px solid #e5e7eb !important;
            font-size: 13px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <!-- Theme Sidebar -->
    <div class="sidebar sidebar-fixed" id="sidebar">
        <div class="sidebar-header border-bottom">
            <div class="sidebar-brand me-auto">
                <a href="<?= site_url('users') ?>" class="text-white text-decoration-none fw-bold fs-5 d-flex align-items-center">
                    <svg class="sidebar-brand-full me-2" width="26" height="26" viewBox="0 0 512 512" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M256 32C132.3 32 32 132.3 32 256s100.3 224 224 224 224-100.3 224-224S379.7 32 256 32zm0 400c-97 0-176-79-176-176S159 80 256 80s176 79 176 176-79 176-176 176z" />
                    </svg>
                    Spider Payroll
                </a>
            </div>
            <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"></button>
        </div>

        <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
            <li class="nav-item">
                <a class="nav-link" href="<?= site_url('users') ?>">
                    <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="var(--ci-primary-color, currentcolor)" d="M425.706 142.294A240 240 0 0 0 16 312v88h144v-32H48v-56c0-114.691 93.309-208 208-208s208 93.309 208 208v56H352v32h144v-88a238.43 238.43 0 0 0-70.294-169.706" class="ci-primary" />
                    </svg>
                    Dashboard
                </a>
            </li>

            <!-- Master -> Users, Contractors & Employees Menu Item -->
            <li class="nav-group <?= (url_is('users*') || url_is('contractors*') || url_is('employees*')) ? 'show' : '' ?>">
                <a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="var(--ci-primary-color, currentcolor)" d="M496 496h-47.229l-69.522-128H40a24.03 24.03 0 0 1-24-24V40a24.03 24.03 0 0 1 24-24h432a24.03 24.03 0 0 1 24 24ZM48 336h350.284L464 456.993V48H48Z" class="ci-primary" />
                    </svg>
                    Master
                </a>
                <ul class="nav-group-items compact">
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('users*') ? 'active' : '' ?>" href="<?= site_url('users') ?>">
                            <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                            Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('contractors*') ? 'active' : '' ?>" href="<?= site_url('contractors') ?>">
                            <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                            Contractors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('employees*') ? 'active' : '' ?>" href="<?= site_url('employees') ?>">
                            <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                            Employees
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <div class="sidebar-footer border-top d-none d-md-flex">
            <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
        </div>
    </div>

    <!-- Main Wrapper -->
    <div class="wrapper d-flex flex-column min-vh-100">
        <!-- Header -->
        <header class="header header-sticky p-0 mb-4">
            <div class="container-fluid px-4 py-2 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <button class="header-toggler" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()" style="margin-inline-start: -8px">
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
                    <li class="nav-item dropdown">
                        <button class="btn btn-link nav-link py-1 px-2 d-flex align-items-center text-dark" type="button" aria-expanded="false" data-coreui-toggle="dropdown">
                            <svg class="icon icon-lg theme-icon-active" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="var(--ci-primary-color, currentcolor)" d="M256 16C123.452 16 16 123.452 16 256s107.452 240 240 240 240-107.452 240-240S388.548 16 256 16" class="ci-primary" />
                            </svg>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem">
                            <li><button class="dropdown-item" type="button" data-coreui-theme-value="light">Light</button></li>
                            <li><button class="dropdown-item" type="button" data-coreui-theme-value="dark">Dark</button></li>
                            <li><button class="dropdown-item active" type="button" data-coreui-theme-value="auto">Auto</button></li>
                        </ul>
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

        <!-- Footer -->
        <footer class="footer px-4 py-3">
            <div>
                <a href="<?= site_url('users') ?>" class="text-decoration-none fw-semibold">Spider Payroll</a> &copy; <?= date('Y') ?>
            </div>
            <div class="ms-auto text-body-secondary">
                Powered by CoreUI Admin Template
            </div>
        </footer>
    </div>

    <!-- CoreUI and necessary plugins-->
    <script src="<?= base_url('backend/vendors/@coreui/coreui/js/coreui.bundle.min.js') ?>"></script>
    <script src="<?= base_url('backend/vendors/simplebar/js/simplebar.min.js') ?>"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '<?= esc(session()->getFlashdata('success'), 'js') ?>'
                });
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                Toast.fire({
                    icon: 'error',
                    title: 'Action Failed',
                    text: '<?= esc(session()->getFlashdata('error'), 'js') ?>'
                });
            });
        </script>
    <?php endif; ?>
</body>

</html>