<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Autorisasi User</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-hover: #3a56d4;
            --secondary-color: #6c757d;
            --light-bg: #f8f9fa;
            --border-radius: 10px;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }

        .auth-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
        }

        .auth-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 40px;
            border: none;
        }

        .company-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            background-color: var(--primary-color);
            color: white;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 32px;
        }

        .company-name {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            letter-spacing: 0.5px;
        }

        .company-tagline {
            color: var(--secondary-color);
            font-size: 14px;
            margin-top: 5px;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            color: #555;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-right: none;
            border-radius: 8px 0 0 8px;
        }

        .password-toggle {
            cursor: pointer;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-left: none;
            border-radius: 0 8px 8px 0;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0;
            color: #aaa;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #eee;
        }

        .divider span {
            padding: 0 15px;
        }

        .form-footer {
            text-align: center;
            margin-top: 25px;
            color: var(--secondary-color);
            font-size: 14px;
        }

        .form-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
        }

        /* Responsiveness */
        @media (max-width: 576px) {
            .auth-card {
                padding: 30px 25px;
            }

            body {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Logo Perusahaan -->
            <div class="company-logo">
                <div class="logo-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h1 class="company-name">SecureTech</h1>
                <p class="company-tagline">Sistem Autorisasi Terpercaya</p>
            </div>

            <!-- Pesan Informasi -->
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <div>Silakan masuk dengan kredensial Anda untuk mengakses sistem</div>
            </div>

            <!-- Form Autorisasi -->
            <form method="post" action="#">
                <!-- Username Field -->
                <div class="mb-4">
                    <label for="username" class="form-label">
                        <i class="bi bi-person-fill me-1"></i> Username
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                    </div>
                    <div class="form-text">Gunakan username yang terdaftar di sistem</div>
                </div>

                <!-- Password Field -->
                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="bi bi-key-fill me-1"></i> Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                        <span class="input-group-text password-toggle" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                    <div class="form-text">Password bersifat case-sensitive</div>
                </div>

                <!-- Opsi Ingat Saya -->
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">
                            Ingat saya
                        </label>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="d-grid gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Masuk ke Sistem
                    </button>
                </div>
            </form>

            <!-- Footer Form -->
            <div class="form-footer">
                <p>
                    Lupa password? <a href="#">Reset di sini</a><br>
                    Tidak memiliki akun? <a href="#">Hubungi administrator</a><br>
                    <small class="text-muted">© 2023 SecureTech. Hak cipta dilindungi undang-undang.</small>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper (untuk tooltip) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>