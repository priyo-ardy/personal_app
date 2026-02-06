<?= $this->extend('Template/Admin/layout'); ?>

<?= $this->section('content'); ?>

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
                        <li class="breadcrumb-item">Module</li>
                        <li class="breadcrumb-item">HRIS</li>
                        <li class="breadcrumb-item">List of Employee</li>
                        <li class="breadcrumb-item active">Register New Employee</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="btn-group" role="group" aria-label="tooltip">
                        <button type="button" class="btn shadow-none rounded-0 btn-light border-0 btnBack" data-bs-toggle="tooltip" data-bs-placement="top" title="Back">
                            <i class="bi bi-arrow-left"></i>&ensp;Add
                        </button>
                        <button type="button" class="btn shadow-none rounded-0 btn-light border-0 btnSave" data-bs-toggle="tooltip" data-bs-placement="top" title="Save">
                            <i class="bi bi-floppy"></i>&ensp;Save
                        </button>
                        <button type="button" class="btn shadow-none rounded-0 btn-light-order-0 btnCancel" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
                        </button>
                    </div>
                </div>
            </div>

            <form id="formData" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card rounded-0">
                            <div class="card-header rounded-0 bg-primary">
                                <h5 class="card-title text-white fw-bolder">Employee Data</h5>
                            </div>
                            <div class="card-body rounded-0">
                                <div class="row mb-3 g-2">
                                    <div class="form-group col-12 clearfix" style="display: none;">
                                        <input type="text" name="data_token" id="data_token" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $token; ?>">
                                    </div>
                                </div>
                                <div class="row g-2 mb-3 clearfix">
                                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12 clearfix">
                                        <label class="form-label" for="data_nik">NIK</label>
                                        <input type="text" id="data_nik" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $karyawan->nik; ?>">
                                    </div>
                                    <div class="form-group col-xl-10 col-xl-10 col-md-10 col-sm-12 clearfix">
                                        <label class="form-label" for="data_name">Employee Name</label>
                                        <input type="text" id="data_name" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $karyawan->name; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card rounded-0">
                            <div class="card-header rounded-0 bg-primary">
                                <h5 class="card-title text-white fw-bolder">Job Data</h5>
                            </div>
                            <div class="card-body rounded-0">
                                <div class="row mb-3 g-2">
                                    <div class="row g-2">
                                        <div class="form-group col-12 clearfix" style="display: none;">
                                            <input type="text" name="data_token" id="data_token" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $token; ?>">
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="form-label" for="data_action">Action <strong class="text-danger">*</strong></label>
                                            <select name="data_action" id="data_action" class="form-select select2 select2bs5" required>
                                                <option value="">-- Choose --</option>
                                                <?php foreach ($action as $row): ?>
                                                    <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="form-label" for="data_reason">Reason <strong class="text-danger">*</strong></label>
                                            <select name="data_reason" id="data_reason" class="form-select select2 select2bs5" required>
                                                <option value="">-- Choose --</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="form-label" for="data_position">Position <strong class="text-danger">*</strong></label>
                                            <select name="data_position" id="data_position" class="form-select select2 select2bs5" required>
                                                <option value="">-- Choose --</option>
                                                <?php foreach ($position as $row): ?>
                                                    <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="form-label" for="data_hubungan_kerja">Work Relationship <strong class="text-danger">*</strong></label>
                                            <select name="data_hubungan_kerja" id="data_hubungan_kerja" class="form-select select2 select2bs5" required>
                                                <option value="">-- Choose --</option>
                                                <option value="Kontrak">Kontrak</option>
                                                <option value="Tetap">Tetap</option>
                                                <option value="Magang">Magang</option>
                                                <option value="PKL">PKL</option>
                                                <option value="Harian">Harian</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="form-label" for="effective_date">Effective Date <strong class="text-danger">*</strong></label>
                                            <input type="date" name="effective_date" id="effective_date" class="form-control rounded-0" required autocomplete="off">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="form-label" for="data_superior">Superior</label>
                                            <select name="data_superior" id="data_superior" class="form-select select2 select2bs5">
                                                <option value="">-- Choose --</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                            <label class="form-label" for="data_contract">Contract No. <strong class="text-danger">*</strong></label>
                                            <input type="text" name="data_contract" id="data_contract" class="form-control rounded-0" minlength="2" maxlength="50" required autocomplete="off" placeholder="Contract/SK No.">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="form-label" for="data_durasi">Contract Duration <strong class="text-danger">*</strong></label>
                                            <input type="number" name="data_durasi" id="data_durasi" class="form-control rounded-0" minlength="1" maxlength="3" required autocomplete="off" placeholder="Contract Duration">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="form-label" for="data_tipe_durasi">Contract Duration <strong class="text-danger">*</strong></label>
                                            <select name="data_tipe_durasi" id="data_tipe_durasi" class="form-select select2 select2bs5" required>
                                                <option value="">-- Choose --</option>
                                                <option value="Hari">Hari</option>
                                                <option value="Minggu">Minggu</option>
                                                <option value="Bulan">Bulan</option>
                                                <option value="Tahun">Tahun</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="form-label" for="data_akhir_kontrak">End of Contract <strong class="text-danger">*</strong></label>
                                            <input type="date" name="data_akhir_kontrak" id="data_akhir_kontrak" class="form-control rounded-0" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-12 clearfix">
                                            <label class="form-label" for="data_remark">Remark</label>
                                            <textarea name="data_remark" id="data_remark" class="form-control rounded-0" rows="3" placeholder="Remark"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card rounded-0">
                            <div class="card-header rounded-0 bg-primary">
                                <h5 class="card-title fw-bolder text-white">Position Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">NBHX Position Category</label>
                                        <input type="text" id="nbhx_position" class="form-control rounded-0 bg-secondary-subtle" readonly>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Position Status</label>
                                        <input type="text" id="position_status" class="form-control rounded-0 bg-secondary-subtle" readonly>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Position Grade</label>
                                        <input type="text" id="position_grade" class="form-control rounded-0 bg-secondary-subtle" readonly>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Position Rank</label>
                                        <input type="text" id="position_rank" class="form-control rounded-0 bg-secondary-subtle" readonly>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Employee Category</label>
                                        <input type="text" id="employee_category" class="form-control rounded-0 bg-secondary-subtle" readonly>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">NBHX Category</label>
                                        <input type="text" id="nbhx_category" class="form-control rounded-0 bg-secondary-subtle" readonly>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Salary Rank</label>
                                        <input type="text" id="salary_rank" class="form-control rounded-0 bg-secondary-subtle" readonly>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Department</label>
                                        <input type="text" id="data_dept" class="form-control rounded-0 bg-secondary-subtle" readonly>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Section</label>
                                        <input type="text" id="data_section" class="form-control rounded-0 bg-secondary-subtle" readonly>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Report to Position</label>
                                        <input type="text" id="data_report_to" class="form-control rounded-0 bg-secondary-subtle" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class=" row mb-3">
                <div class="col-12">
                    <div class="card rounded-0">
                        <div class="card-body rounded-0">
                            <div class="btn-group" role="group" aria-label="tooltip">
                                <button type="button" class="btn shadow-none rounded-0 btn-light border-0 btnBack" data-bs-toggle="tooltip" data-bs-placement="top" title="Back">
                                    <i class="bi bi-arrow-left"></i>&ensp;Add
                                </button>
                                <button type="button" class="btn shadow-none rounded-0 btn-light border-0 btnSave" data-bs-toggle="tooltip" data-bs-placement="top" title="Save">
                                    <i class="bi bi-floppy"></i>&ensp;Save
                                </button>
                                <button type="button" class="btn shadow-none rounded-0 btn-light border-0 btnCancel" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                                    <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>