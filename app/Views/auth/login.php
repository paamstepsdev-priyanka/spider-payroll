<!DOCTYPE html>
<html lang="en" data-coreui-theme="light">
<head>
    <base href="<?= base_url('backend/') ?>/">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?= esc($title ?? 'Login - Spider Payroll') ?></title>

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CoreUI & Custom Styles -->
    <link href="<?= base_url('backend/css/style.css') ?>" rel="stylesheet">
    <link href="<?= base_url('backend/css/custom.css') ?>" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); font-family: 'Inter', sans-serif;">

    <div class="container d-flex justify-content-center">
        <div class="card border-0 rounded-4 shadow-lg overflow-hidden" style="max-width: 440px; width: 100%;">
            
            <!-- Top Decorative Accent Line -->
            <div class="bg-primary" style="height: 5px;"></div>

            <div class="card-body p-4 p-sm-5">
                
                <!-- Brand Header -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-shield-lock-fill fs-3"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Spider Payroll</h3>
                    <p class="text-secondary small mb-0">Sign in to your administrative account</p>
                </div>

                <!-- Flash Alerts -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show text-center py-2 px-3 mb-4 rounded-3" role="alert" style="font-size: 13px;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show text-center py-2 px-3 mb-4 rounded-3" role="alert" style="font-size: 13px;">
                        <i class="bi bi-check-circle-fill me-1"></i> <?= esc(session()->getFlashdata('success')) ?>
                        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form action="<?= site_url('login') ?>" method="POST" id="loginForm" class="needs-validation" novalidate>
                    <?= csrf_field() ?>

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="username" class="form-label small fw-semibold text-secondary mb-1">Email Address</label>
                        <div class="position-relative">
                            <i class="bi bi-envelope text-secondary position-absolute fs-6" style="left: 16px; top: 50%; transform: translateY(-50%); z-index: 5; opacity: 0.7;"></i>
                            <input type="email" class="form-control form-control-modern ps-5" id="username" name="username" placeholder="name@company.com" value="<?= old('username') ?>" required>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <label for="password" class="form-label small fw-semibold text-secondary mb-1">Password</label>
                        <div class="position-relative">
                            <i class="bi bi-lock text-secondary position-absolute fs-6" style="left: 16px; top: 50%; transform: translateY(-50%); z-index: 5; opacity: 0.7;"></i>
                            <input type="password" class="form-control form-control-modern px-5" id="password" name="password" placeholder="••••••••" required>
                            <button type="button" class="btn btn-link text-secondary position-absolute p-0 border-0 shadow-none" id="togglePasswordBtn" style="right: 14px; top: 50%; transform: translateY(-50%); z-index: 5; opacity: 0.7;">
                                <i class="bi bi-eye fs-6" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" style="font-size: 14px; height: 48px;">
                        <span>Sign In</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </form>

            </div>

            <!-- Card Footer -->
            <div class="card-footer bg-light bg-opacity-75 border-top py-3 text-center text-secondary small">
                <div class="d-flex align-items-center justify-content-center gap-2 mb-1" style="font-size: 11px;">
                    <i class="bi bi-shield-check text-success"></i>
                    <span>Secure Administrative Portal</span>
                </div>
                <div class="text-muted" style="font-size: 11px;">
                    &copy; <?= date('Y') ?> <strong>Spider Payroll System v2.0</strong>
                </div>
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
            // Password Visibility Toggle
            $('#togglePasswordBtn').on('click', function() {
                var pwdInput = $('#password');
                var icon = $('#togglePasswordIcon');
                if (pwdInput.attr('type') === 'password') {
                    pwdInput.attr('type', 'text');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    pwdInput.attr('type', 'password');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });

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
