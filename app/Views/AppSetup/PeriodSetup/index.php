<?= $this->extend('Template/Admin/layout'); ?>

<?= $this->section('content'); ?>

<?php
// Best Practice: Ambil session di awal agar kode di bawah lebih bersih
$userLevel = session()->get('user_level');
$userName  = session()->get('user_name');
$isAdmin   = in_array($userLevel, ['superadmin', 'administrator']);
?>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><?= $title; ?></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="<?= base_url() . 'dashboard' ?>" onclick="loading()">Dashboard</a></li>
                        <li class="breadcrumb-item">App Setup</li>
                        <li class="breadcrumb-item">HR Setup</li>
                        <li class="breadcrumb-item">Time & Labor</li>
                        <li class="breadcrumb-item active">Period Setup</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <form id="formAllPeriod">
                        <div class="card rounded-0">
                            <div class="card-header rounded-0 text-white fw-bolder bg-primary">
                                <h5 class="card-title">Default Period</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="form-group col-12" style="display: none;">
                                        <input type="text" name="data_user_name" class="form-control rounded-0 bg-secondary-subtle" readonly value="all">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_tgl_awal">Start Date <strong class="text-danger">*</strong></label>
                                        <input type="date" name="data_tgl_awal" id="data_tgl_awal" class="form-control rounded-0" required value="<?= ($all_period) ? $all_period->tgl_awal : '' ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_tgl_akhir">End Date <strong class="text-danger">*</strong></label>
                                        <input type="date" name="data_tgl_akhir" id="data_tgl_akhir" class="form-control rounded-0" required value="<?= ($all_period) ? $all_period->tgl_akhir : '' ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class=" card-footer">
                                <button type="button" class="btn btn-primary rounded-0" id="btnAll" title="Save Default Period"><i class="bi bi-floppy"></i>&ensp;Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php if ($isAdmin): ?>
                <div class="row">
                    <div class="col-12">
                        <form id="formMyPeriod">
                            <div class="card rounded-0 card-warning">
                                <div class="card-header rounded-0 fw-bolder bg-warning">
                                    <h5 class="card-title">My Period</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="form-group col-12" style="display: none;">
                                            <input type="text" name="data_user_name" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= session()->get('user_name'); ?>">
                                        </div>
                                        <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                            <label class="form-label" for="data_tgl_awal">Start Date <strong class="text-danger">*</strong></label>
                                            <input type="date" name="data_tgl_awal" id="data_tgl_awal" class="form-control rounded-0" required value="<?= ($my_period) ? $my_period->tgl_awal : '' ?>">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                            <label class="form-label" for="data_tgl_akhir">End Date <strong class="text-danger">*</strong></label>
                                            <input type="date" name="data_tgl_akhir" id="data_tgl_akhir" class="form-control rounded-0" required value="<?= ($my_period) ? $my_period->tgl_akhir : '' ?>">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" class="btn btn-primary rounded-0" id="btnPeriod" title="Save My Period"><i class="bi bi-floppy"></i>&ensp;Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>