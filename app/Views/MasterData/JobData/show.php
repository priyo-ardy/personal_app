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
                        <li class="breadcrumb-item active">JoB Data Change</li>
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
                        <button type="button" class="btn shadow-none rounded-0 btn-light border-0" id="btnBack" data-bs-toggle=" tooltip" data-bs-placement="top" title="Back">
                            <i class="bi bi-arrow-left"></i>&ensp;Back
                        </button>
                        <button type="button" class="btn shadow-none rounded-0 btn-light border-0" id="btnEdit" data-bs-toggle=" tooltip" data-bs-placement="top" title="Edit">
                            <i class="bi bi-pencil-square"></i>&ensp;Edit
                        </button>
                        <button type="button" hidden class="btn shadow-none rounded-0 btn-light border-0" id="btnUpdate" data-bs-toggle=" tooltip" data-bs-placement="top" title="Update">
                            <i class="bi bi-floppy"></i>&ensp;Update
                        </button>
                        <button type="button" hidden class="btn shadow-none rounded-0 btn-light-order-0" id="btnCancel" data-bs-toggle=" tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
                        </button>
                        <button type="button" class="btn shadow-none rounded-0 btn-light border-0" id="btnAdd" data-bs-toggle=" tooltip" data-bs-placement="top" title="Add">
                            <i class="bi bi-file-earmark-plus"></i>&ensp;Add
                        </button>
                        <button type="button" class="btn shadow-none rounded-0 btn-light border-0" id="btnDelete" data-bs-toggle=" tooltip" data-bs-placement="top" title="Delete">
                            <i class="bi bi-trash3"></i>&ensp;Delete
                        </button>
                    </div>
                </div>
            </div>

            <form id="formData">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card rounded-0">
                            <div class="card-body rounded-0">
                                <div class="row g-2">
                                    <div class="form-group col-12 clearfix">
                                        <input type="hidden" name="data_token" id="data_token" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $data['token'] ?>">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_employee">Employee <strong class="text-danger">*</strong></label>
                                        <input type="text" name="data_employee" id="data_employee" class="form-control rounded-0 bg-secondary-subtle text-primary fw-bolder" readonly value="<?= "$employee->nik - $employee->name" ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_action">Register Action <strong class="text-danger">*</strong></label>
                                        <select name="data_action" id="data_action" class="form-select select2 select2bs5" required disabled>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($action as $row): ?>
                                                <option <?= ($data['action'] == $row->id) ? 'selected' : ''; ?> value="<?= $row->id; ?>"><?= $row->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_reason">Register Reason <strong class="text-danger">*</strong></label>
                                        <select name="data_reason" id="data_reason" class="form-select select2 select2bs5" required disabled>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($reason as $r): ?>
                                                <option <?= ($data['reason'] == $r->id) ? 'selected' : ''; ?> value="<?= $r->id; ?>"><?= $r->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_position">Employee Position <strong class="text-danger">*</strong></label>
                                        <select name="data_position" id="data_position" class="form-select select2 select2bs5" required disabled>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($position as $row): ?>
                                                <option <?= ($data['position'] == $row->id) ? 'selected' : ''; ?> value="<?= $row->id; ?>"><?= $row->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_relasi">Work Relationship <strong class="text-danger">*</strong></label>
                                        <select name="data_relasi" id="data_relasi" class="form-select select2 select2bs5" required disabled>
                                            <option <?= ($data['work_relationship'] == '') ? 'selected' : '' ?> value="">-- Choose --</option>
                                            <option <?= ($data['work_relationship'] == 'Kontrak') ? 'selected' : '' ?> value="Kontrak">Kontrak</option>
                                            <option <?= ($data['work_relationship'] == 'Tetap') ? 'selected' : '' ?> value="Tetap">Tetap</option>
                                            <option <?= ($data['work_relationship'] == 'Magang') ? 'selected' : '' ?> value="Magang">Magang</option>
                                            <option <?= ($data['work_relationship'] == 'PKL') ? 'selected' : '' ?> value="PKL">PKL</option>
                                            <option <?= ($data['work_relationship'] == 'Harian') ? 'selected' : '' ?> value="Harian">Harian</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="effective_date">Effective Date <strong class="text-danger">*</strong></label>
                                        <input type="date" name="effective_date" id="effective_date" class="form-control rounded-0" required autocomplete="off" value="<?= $data['effective_date'] ?>" disabled>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_superior">Superior <strong class="text-danger">*</strong></label>
                                        <select name="data_superior" id="data_superior" class="form-select select2 select2bs5" required disabled>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($superior as $row): ?>
                                                <option <?= ($data['superior'] == $row->employee_id) ? 'selected' : '';  ?> value="<?= $row->employee_id ?>"><?= "$row->nik - $row->employee_name" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_contract">Contract No. <strong class="text-danger">*</strong></label>
                                        <input type="text" name="data_contract" id="data_contract" class="form-control rounded-0" required maxlength="50" autocomplete="off" placeholder="PKWT No./PKWTT No." value="<?= $data['no_contract'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_durasi">Contract Duration <strong class="text-danger">*</strong></label>
                                        <input type="number" name="data_durasi" id="data_durasi" class="form-control rounded-0" minlength="1" maxlength="3" required autocomplete="off" placeholder="Contract Duration" value="<?= $data['durasi_kontrak'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_tipe_durasi">Contract Duration <strong class="text-danger">*</strong></label>
                                        <select name="data_tipe_durasi" id="data_tipe_durasi" class="form-select select2 select2bs5" required disabled>
                                            <option <?= ($data['tipe_durasi'] == '') ? 'selected' : "" ?> value="">-- Choose --</option>
                                            <option <?= ($data['tipe_durasi'] == 'Hari') ? 'selected' : "" ?> value="Hari">Hari</option>
                                            <option <?= ($data['tipe_durasi'] == 'Minggu') ? 'selected' : "" ?> value="Minggu">Minggu</option>
                                            <option <?= ($data['tipe_durasi'] == 'Bulan') ? 'selected' : "" ?> value="Bulan">Bulan</option>
                                            <option <?= ($data['tipe_durasi'] == 'Tahun') ? 'selected' : "" ?> value="Tahun">Tahun</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_akhir_kontrak">End of Contract <strong class="text-danger">*</strong></label>
                                        <input type="date" name="data_akhir_kontrak" id="data_akhir_kontrak" class="form-control rounded-0 <?= ($data['work_relationship'] == 'Tetap') ? 'bg-secondary-subtle' : '' ?>" required value="<?= $data['akhir_kontrak'] ?>" <?= ($data['work_relationship'] == 'Tetap') ? 'readonly' : '' ?> disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-12">
                                        <label class="form-label" for="data_remark">Remark</label>
                                        <textarea name="data_remark" id="data_remark" class="form-control summernote rounded-0" rows="3" placeholder="Remark"><?= $data['remark'] ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card rounded-0">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">NBHX Position Category</label>
                                        <input type="text" id="nbhx_position" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $position_info['nbhx_position'] ?>">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Position Status</label>
                                        <input type="text" id="position_status" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $position_info['status'] ?>">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Position Grade</label>
                                        <input type="text" id="position_grade" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $position_info['grade'] ?>">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Position Rank</label>
                                        <input type="text" id="position_rank" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $position_info['rank'] ?>">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Employee Category</label>
                                        <input type="text" id="employee_category" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $position_info['category'] ?>">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">NBHX Category</label>
                                        <input type="text" id="nbhx_category" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $position_info['nbhx_category'] ?>">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Salary Rank</label>
                                        <input type="text" id="salary_rank" class="form-control rounded-0 bg-secondary-subtle" readonly>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Department</label>
                                        <input type="text" id="data_dept" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $position_info['dept'] ?>">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Section</label>
                                        <input type="text" id="data_section" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $position_info['section'] ?>">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label">Report to Position</label>
                                        <input type="text" id="data_report_to" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $position_info['report_to_position'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>