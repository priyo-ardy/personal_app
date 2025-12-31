<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>429 - Too Many Requests</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--dark-color);
        }

        .error-container {
            max-width: 800px;
            width: 100%;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .error-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px;
            text-align: center;
        }

        .error-icon {
            font-size: 5rem;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .error-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .error-subtitle {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 20px;
        }

        .error-body {
            padding: 40px;
        }

        .error-message {
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .countdown-container {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
        }

        .countdown-title {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: var(--dark-color);
        }

        .countdown {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 10px;
        }

        .countdown-text {
            font-size: 1rem;
            color: #6c757d;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn-custom {
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary-custom:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-outline-custom {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-custom:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .tips-list {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }

        .tips-list h5 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .tips-list ul {
            padding-left: 20px;
        }

        .tips-list li {
            margin-bottom: 10px;
        }

        .error-footer {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .error-code {
            font-family: monospace;
            background-color: #f1f1f1;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .error-title {
                font-size: 2.5rem;
            }

            .error-subtitle {
                font-size: 1.2rem;
            }

            .error-body {
                padding: 25px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-custom {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-header">
            <div class="error-icon">
                <i class="fas fa-traffic-light"></i>
            </div>
            <h1 class="error-title">429</h1>
            <h2 class="error-subtitle">Too Many Requests</h2>
        </div>

        <div class="error-body">
            <div class="error-message">
                <p>Maaf, Anda telah mengirim terlalu banyak permintaan ke server dalam waktu singkat. Server menerapkan batasan kecepatan untuk melindungi dari penyalahgunaan.</p>
                <p>Silakan tunggu beberapa saat sebelum mencoba lagi.</p>
            </div>

            <div class="countdown-container">
                <div class="countdown-title">Anda dapat mencoba kembali dalam:</div>
                <div class="countdown" id="countdown">60</div>
                <div class="countdown-text">detik</div>
            </div>

            <div class="action-buttons">
                <button id="retryButton" class="btn btn-primary btn-custom btn-primary-custom" disabled>
                    <i class="fas fa-redo-alt"></i> Coba Lagi (<span id="retry-countdown">60</span>)
                </button>
                <button onclick="goHome()" class="btn btn-outline-primary btn-custom btn-outline-custom">
                    <i class="fas fa-home"></i> Kembali ke Beranda
                </button>
                <button onclick="goBack()" class="btn btn-outline-secondary btn-custom">
                    <i class="fas fa-arrow-left"></i> Kembali ke Halaman Sebelumnya
                </button>
            </div>

            <div class="tips-list">
                <h5><i class="fas fa-lightbulb me-2"></i> Tips untuk menghindari kesalahan ini:</h5>
                <ul>
                    <li>Hindari mengklik atau menekan tombol berulang kali dalam waktu singkat</li>
                    <li>Tunggu respon dari server sebelum mengirim permintaan baru</li>
                    <li>Jika Anda adalah pengembang, pertimbangkan untuk meningkatkan interval permintaan</li>
                    <li>Gunakan fitur cache jika tersedia untuk mengurangi permintaan berulang</li>
                </ul>
            </div>
        </div>

        <div class="error-footer">
            <p>Jika masalah berlanjut, silakan hubungi administrator sistem.</p>
            <p>Kode Error: <span class="error-code">429 - Too Many Requests</span></p>
            <p><?= date('Y'); ?> &copy; <?= env('app.name', 'Aplikasi Saya'); ?></p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- <script>
        // Countdown timer
        let timeLeft = 60; // waktu dalam detik
        const countdownElement = document.getElementById('countdown');
        const retryCountdownElement = document.getElementById('retry-countdown');
        const retryButton = document.getElementById('retryButton');

        function updateCountdown() {
            countdownElement.textContent = timeLeft;
            retryCountdownElement.textContent = timeLeft;

            if (timeLeft <= 0) {
                retryButton.disabled = false;
                retryButton.innerHTML = '<i class="fas fa-redo-alt"></i> Coba Lagi';
                retryButton.classList.remove('btn-primary');
                retryButton.classList.add('btn-success');
                clearInterval(countdownInterval);
            } else {
                timeLeft--;
            }
        }

        // Update countdown setiap detik
        const countdownInterval = setInterval(updateCountdown, 1000);

        // Fungsi untuk mencoba kembali
        retryButton.addEventListener('click', function() {
            if (!this.disabled) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat ulang...';
                this.disabled = true;

                // Coba muat ulang halaman setelah 2 detik
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        });

        // Fungsi untuk kembali ke beranda
        function goHome() {
            window.location.href = '<?= base_url(); ?>';
        }

        // Fungsi untuk kembali ke halaman sebelumnya
        function goBack() {
            if (document.referrer) {
                window.location.href = document.referrer;
            } else {
                window.location.href = '<?= base_url(); ?>';
            }
        }

        // Deteksi jika user mencoba refresh halaman berulang kali
        let refreshAttempts = 0;
        const maxRefreshAttempts = 3;

        // Simpan informasi refresh di sessionStorage
        if (sessionStorage.getItem('refreshCount')) {
            refreshAttempts = parseInt(sessionStorage.getItem('refreshCount'));
        }

        refreshAttempts++;
        sessionStorage.setItem('refreshCount', refreshAttempts);

        // Tampilkan pesan peringatan jika user terlalu sering refresh
        if (refreshAttempts >= maxRefreshAttempts) {
            setTimeout(() => {
                alert('Anda telah terlalu sering mencoba me-refresh halaman. Silakan tunggu beberapa menit sebelum mencoba lagi.');
            }, 1000);
        }

        // Reset refresh count setelah 5 menit
        setTimeout(() => {
            sessionStorage.removeItem('refreshCount');
        }, 5 * 60 * 1000);

        // Auto refresh ketika countdown selesai (opsional)
        const autoRefreshEnabled = true; // Set false jika tidak ingin auto refresh

        if (autoRefreshEnabled) {
            setTimeout(() => {
                // Cek apakah user masih berada di halaman error ini
                if (window.location.pathname.includes('error_429') || document.title.includes('429')) {
                    window.location.reload();
                }
            }, (timeLeft + 2) * 1000); // +2 detik untuk buffer
        }

        // Tambahkan event listener untuk tombol keyboard
        document.addEventListener('keydown', function(event) {
            // Tombol F5 atau Ctrl+R untuk refresh
            if (event.key === 'F5' || (event.ctrlKey && event.key === 'r')) {
                event.preventDefault();
                alert('Mohon tunggu countdown selesai sebelum mencoba refresh halaman.');
            }

            // Tombol Escape untuk kembali ke halaman sebelumnya
            if (event.key === 'Escape') {
                goBack();
            }

            // Tombol Enter untuk coba lagi setelah countdown selesai
            if (event.key === 'Enter' && !retryButton.disabled) {
                retryButton.click();
            }
        });
    </script> -->
</body>

</html>