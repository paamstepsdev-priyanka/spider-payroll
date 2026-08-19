<!DOCTYPE html>
<html lang="en" data-coreui-theme="light">
<head>
    <base href="<?= base_url('backend/') ?>/">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?= esc($title ?? 'Login - Spider Payroll') ?></title>
    <!-- CoreUI & Main Styles -->
    <link href="<?= base_url('backend/css/style.css') ?>" rel="stylesheet">
    <link href="<?= base_url('backend/css/custom.css') ?>" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .login-card {
            max-width: 420px;
            width: 100%;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        .login-header {
            background-color: #212631;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100 py-5">

    <div class="container d-flex justify-content-center">
        <div class="card login-card">
            <!-- Header Logo Banner -->
            <div class="login-header text-white text-center py-4 px-3">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <svg class="me-2" width="32" height="32" viewBox="0 0 512 512" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M256 32C132.3 32 32 132.3 32 256s100.3 224 224 224 224-100.3 224-224S379.7 32 256 32zm0 400c-97 0-176-79-176-176S159 80 256 80s176 79 176 176-79 176-176 176z" />
                    </svg>
                    <span class="fs-4 fw-bold">Spider Payroll</span>
                </div>
                <p class="text-white-50 small mb-0">Sign in to access your administrative dashboard</p>
            </div>

            <div class="card-body p-4">
                <!-- Flash Alerts -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show text-center py-2 px-3 mb-3" role="alert" style="font-size: 13px;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show text-center py-2 px-3 mb-3" role="alert" style="font-size: 13px;">
                        <i class="bi bi-check-circle-fill me-1"></i> <?= esc(session()->getFlashdata('success')) ?>
                        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form action="<?= site_url('login') ?>" method="POST" id="loginForm" class="needs-validation" novalidate>
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="username" class="form-label text-secondary fw-semibold" style="font-size: 13px;">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" id="username" name="username" placeholder="name@company.com" value="<?= old('username') ?>" required style="font-size: 14px;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label text-secondary fw-semibold" style="font-size: 13px;">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required style="font-size: 14px;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm" style="border-radius: 6px; font-size: 14px;">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                    </button>
                </form>
            </div>

            <div class="card-footer bg-light text-center py-3 border-0 text-secondary" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; font-size: 12px;">
                &copy; <?= date('Y') ?> <strong>Spider Payroll</strong>. All rights reserved.
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= base_url('backend/js/global-form-validation.js') ?>"></script>
    <script>
        $(document).ready(function () {
            if (typeof initAjaxForm === 'function') {
                initAjaxForm('#loginForm', {
                    onSuccess: function (response) {
                        var targetUrl = response.redirect || response.redirect_url || '<?= site_url('users') ?>';
                        setTimeout(function () {
                            window.location.href = targetUrl;
                        }, 500);
                    }
                });
            }
        });
    </script>
</body>
</html>
