<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Ardy Priyo Sudiyantoko">
    <meta name="company" content="PT. Informasi Anonim Indonesia">

    <title>Forgot Password</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.17/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Logo Perusahaan -->
            <div class="company-logo">
                <div class="logo-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h1 class="company-name">PT. Informasi Anonim Indonesia</h1>
                <p class="company-tagline">Data anda adalah asset kami</p>
            </div>

            <!-- Judul Form -->
            <div class="divider"></div>

            <p style="font-size: smaller;">
                If your email address is registered in our system, a new password will be sent to your email address.
            </p>

            <div class="divider"></div>

            <div class="alert alert-success align-items-center" id="successAlert" role="alert" hidden>
                <div id="successMessage"></div>
            </div>
            <div class="alert alert-danger align-items-center" id="errorAlert" role="alert" hidden>
                <div id="errorMessage"></div>
            </div>

            <!-- Form Autorisasi -->
            <form id="formForgot">
                <!-- Username Field -->
                <div class="mb-4" style="display: none;">
                    <label for="username" class="form-label">
                        <i class="bi bi-person-fill me-1"></i> Username
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" class="form-control" id="username" name="user_name" placeholder="Enter username" autocomplete="off">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="bi bi-key-fill me-1"></i> Email Address
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="email" class="form-control" id="email" name="user_email" placeholder="Enter email address" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="d-grid gap-2 mb-4">
                    <button type="button" id="btnReset" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Reset Password
                    </button>
                    <a href="<?= base_url() ?>" id="btnReset" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i> Back to login
                    </a>
                </div>
            </form>

            <!-- Footer Form -->
            <div class="form-footer">
                <p>
                    Forgot password? <a href="<?= base_url() . 'forgot-password' ?>">Reset here</a><br>
                    Don't have an account? <a href="#">Contact our administrator</a><br>
                    <small class="text-muted">© 2023 Ardy Priyo Sudiyantoko, All rights reserved.</small>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper (untuk tooltip) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.17/dist/sweetalert2.all.min.js"></script>
    <!-- Custom JS -->
    <script src="<?= base_url() . 'js/App/fetching.js' ?>"></script>
    <script src="<?= base_url() . 'js/Auth/reset.js' ?>"></script>
</body>

</html>