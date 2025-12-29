<!DOCTYPE html>
<html lang="en">

<head>
    <link preload href="<?php echo base_url() . 'img/favicon.png'; ?>" rel="icon">
    <link preload href="<?php echo base_url() . 'img/favicon.png'; ?>" rel="apple-touch-icon">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <meta name="title" content="Schlemmer Automotive Indonesia WebApp" />
    <meta name="author" content="Ardy Priyo Sudiyantoko" />

    <!-- Font -->
    <link rel="stylesheet" href="<?= base_url()  ?>@fontsource/source-sans-pro/400.css">
    <!-- Overlay Scrollbars -->
    <link rel="stylesheet" href="<?= base_url() ?>overlayscrollbars/styles/overlayscrollbars.min.css" />
    <!-- Bootstrap -->
    <link href="<?= base_url() ?>bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= base_url() ?>bootstrap-icons/font/bootstrap-icons.min.css" />
    <!-- Select2 CSS -->
    <link href="<?= base_url() ?>select2/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 Bootstrap 5 Theme -->
    <link href="<?= base_url() ?>select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!-- SweetAlert2 -->
    <link href="<?= base_url() ?>sweetalert2/dist/sweetalert2.min/css" rel="stylesheet">
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>DataTables/datatables.min.css" />
    <!-- Summernote -->
    <link href="<?= base_url() ?>summernote/summernote.min.css" rel="stylesheet">
    <!-- AdminLTE CSS -->
    <link href="<?= base_url() ?>admin-lte/dist/css/adminlte.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url() . 'css/loading.css' ?>">

    <style>
        .form-label {
            font-weight: bold;
        }

        /* Chrome, Safari, Edge, Opera */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .swal2-container {
            z-index: 9999 !important;
            top: 0 !important;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <?= csrf_meta() . PHP_EOL ?>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div id="loading-overlay">
        <div class="spinner"></div>
        <p>Loading...</p>
    </div>
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body-secondary" data-bs-theme="dark">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" title="Profile">
                            <img
                                src="<?= base_url() . 'img/' . (session('user_image')) ? session('user_image') : 'default.png'; ?>"
                                class="user-image rounded-circle shadow"
                                alt="User Image" />
                            <span class="d-none d-md-inline"><?= session('full_name') ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-light">
                                <img
                                    src="<?= base_url() . 'img/' . (session('user_image')) ? session('user_image') : 'default.png'; ?>"
                                    class="rounded-circle shadow"
                                    alt="User Image" />
                                <p>
                                    <?= session('full_name') ?>
                                    <small>Member since Nov. 2023</small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <a href="<?= base_url() . 'Profile'; ?>" class="btn btn-light rounded-0" title="Profile">Profile</a>
                                <a href="<?= base_url() . 'logout'; ?>" onclick="loading()" class="btn btn-light rounded-0 float-end" title="Sign Out">Sign Out</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url() . 'setup'; ?>" class="nav-link" title="Setup">
                            <i class="fas fa-cogs"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <?= view('Template/Admin/sidebar.php'); ?>

        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0"><?= $title; ?></h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item active"><a href="<?= base_url() . 'dashboard' ?>" onclick="loading()">Dashboard</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card rounded-0">
                                <div class="card-header rounded-0">
                                    <h3 class="card-title"><?= $title ?></h3>
                                </div>
                                <div class="card-body">
                                    <?php if (ENVIRONMENT !== 'production') : ?>
                                        <?= nl2br(esc($message)) ?>
                                    <?php else : ?>
                                        <?= lang('Errors.sorryMethodNotAllowed') ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!--begin::Footer-->
        <footer class="app-footer">
            <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">
                Schlemmer Automotive Indonesia<br>

            </div>
            <!--end::To the end-->
            <!--begin::Copyright-->
            <strong>
                Copyright &copy; 2014-2024&nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
            </strong>
            All rights reserved.<br>

            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>

    <!-- JQuery -->
    <script src="<?= base_url() ?>js/JQuery/jquery-3.7.1.js"></script>
    <!-- Popper -->
    <script src="<?= base_url() ?>@popperjs/core/dist/umd/popper.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?= base_url() ?>bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Overlay Scrollbar -->
    <script src="<?= base_url() ?>overlayscrollbars/browser/overlayscrollbars.browser.es6.min.js"></script>
    <!-- Select2 JS -->
    <script src="<?= base_url() ?>select2/dist/js/select2.full.min.js"></script>
    <!-- SweetAlert Plugins -->
    <script src="<?= base_url() ?>sweetalert2/dist/sweetalert2.all.min.js"></script>
    <!-- Datatable -->
    <script src="<?= base_url() ?>DataTables/datatables.min.js"></script>
    <!-- AdminLTE JS -->
    <script src="<?= base_url() . 'admin-lte/dist/js/adminlte.min.js'; ?>"></script>
    <!-- Summernote -->
    <script src="<?= base_url() ?>summernote/summernote.min.js"></script>
    <!-- Moment JS -->
    <script src="<?= base_url() ?>momentjs/moment.min.js"></script>
    <script src="<?= base_url() ?>momentjs/moment-with-locales.min.js"></script>
    <!-- My App Custom JS -->
    <script src="<?= base_url() . 'js/App/app.js' ?>"></script>
    <script src="<?= base_url() . 'js/App/fetching.js' ?>"></script>
</body>

</html>