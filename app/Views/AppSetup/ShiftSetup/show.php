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
                        <li class="breadcrumb-item">Application Setup</li>
                        <li class="breadcrumb-item">HR Setup</li>
                        <li class="breadcrumb-item">Time & Labor Setup</li>
                        <li class="breadcrumb-item">Shift Setup</li>
                        <li class="breadcrumb-item">Show</li>
                        <li class="breadcrumb-item active"><?= $data['name'] ?></li>
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
                        <button type="button" id="btnBack" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Back">
                            <i class="bi bi-arrow-left"></i>&ensp;Back
                        </button>
                        <button type="button" id="btnEdit" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                            <i class="bi bi-pencil-square"></i>&ensp;Edit
                        </button>
                        <button type="button" hidden id="btnSave" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Save">
                            <i class="bi bi-floppy"></i>&ensp;Save
                        </button>
                        <button type="button" hidden id="btnCancel" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
                        </button>
                        <button type="button" id="btnAdd" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Add New Shift">
                            <i class="bi bi-file-earmark-plus"></i>&ensp;Add
                        </button>
                        <button type="button" id="btnDelete" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                            <i class="bi bi-trash3"></i>&ensp;Delete
                        </button>
                        <button type="button" id="btnPrev" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                            <i class="bi bi-chevron-double-left"></i>&ensp;Prev
                        </button>
                        <button type="button" id="btnNext" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                            Next&ensp;<i class="bi bi-chevron-double-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-12 clearfix">
                    <form id="formData">
                        <div class="card rounded-0 card-primary card-outline">
                            <div class="card-body">
                                <div class="row g-2">
                                    <input type="hidden" name="data_token" id="data_token" value="<?= $token ?>" readonly>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_code">Shift Code</label>
                                        <input type="text" name="data_code" id="data_code" class="form-control rounded-0 bg-secondary-subtle text-primary fw-bolder" placeholder="Shift code auto generate after saving data" readonly autocomplete="off" value="<?= $data['code'] ?>">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_name">Shift Name <strong class="text-danger">*</strong></label>
                                        <input type="text" name="data_name" disabled id="data_name" class="form-control rounded-0" placeholder="Shift name" autocomplete="off" minlength="2" maxlength="150" required autofocus value="<?= $data['name'] ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="working_day">Working Day <strong class="text-danger">*</strong></label>
                                        <select name="working_day" id="working_day" class="form-control select2 select2bs5" required disabled>
                                            <option <?= ($data['working_day'] == '') ? 'selected' : '' ?> value="">-- Choose --</option>
                                            <option <?= ($data['working_day'] == '4') ? 'selected' : '' ?> value="4">4 Working Day</option>
                                            <option <?= ($data['working_day'] == '5') ? 'selected' : '' ?> value="5">5 Working Day</option>
                                            <option <?= ($data['working_day'] == '6') ? 'selected' : '' ?> value="6">6 Working Day</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="working_hour_type">Working Hour Type <strong class="text-danger">*</strong></label>
                                        <select name="working_hour_type" id="working_hour_type" class="form-control select2 select2bs5" required disabled>
                                            <option <?= ($data['working_hour_type'] == '') ? 'selected' : '' ?> value="">-- Choose --</option>
                                            <option <?= ($data['working_hour_type'] == 'LBR') ? 'selected' : '' ?> value="LBR">Holiday</option>
                                            <option <?= ($data['working_hour_type'] == 'REG') ? 'selected' : '' ?> value="REG">Regular Working Hours</option>
                                            <option <?= ($data['working_hour_type'] == 'PDK') ? 'selected' : '' ?> value="PDK">Short Working Hours</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_overday">Overday <strong class="text-danger">*</strong></label>
                                        <select name="data_overday" id="data_overday" class="form-control select2 select2bs5" required disabled>
                                            <option <?= ($data['overday'] == '') ? 'selected' : '' ?> value="">-- Choose --</option>
                                            <option <?= ($data['overday'] == 't') ? 'selected' : '' ?> value="true">Yes</option>
                                            <option <?= ($data['overday'] == 'f') ? 'selected' : '' ?> value="false">No</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="overtime_type">Overtime Type <strong class="text-danger">*</strong></label>
                                        <select name="overtime_type" id="overtime_type" class="form-control select2 select2bs5" required disabled>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($overtime as $ovt): ?>
                                                <option <?= ($data['default_overtime'] == $ovt->id) ? 'selected' : '' ?> value="<?= $ovt->id ?>"><?= $ovt->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="min_overtime">Min. Overtime <strong class="text-danger">*</strong></label>
                                        <input type="number" name="min_overtime" id="min_overtime" class="form-control rounded-0" placeholder="Min. Overtime" autocomplete="off" required value="<?= $data['min_overtime'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="std_in">Std. Clock In <strong class="text-danger">*</strong></label>
                                        <input type="time" name="std_in" id="std_in" class="form-control rounded-0" placeholder="Std. Clock In" autocomplete="off" required lang="id-ID" value="<?= $data['std_in'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="std_out">Std. Clock Out <strong class="text-danger">*</strong></label>
                                        <input type="time" name="std_out" id="std_out" class="form-control rounded-0" placeholder="Std. Clock Out" autocomplete="off" required lang="id-ID" value="<?= $data['std_out'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" data="istirahat">Break Time <strong><sup class="text-danger">*</sup></strong></label>
                                        <input type="number" class="form-control rounded-0" name="istirahat" id="istirahat" placeholder="60 (Minutes)" autocomplete="off" required value="<?= $data['break'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" data="jam_kerja">Working Hour <strong><sup class="text-danger">*</sup></strong></label>
                                        <input type="number" class="form-control rounded-0 bg-secondary-subtle" name="jam_kerja" id="jam_kerja" required placeholder="0.00" autocomplete="off" readonly value="<?= $data['working_hour'] ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="absent_status">Default Absent Status <strong class="text-danger">*</strong></label>
                                        <select name="absent_status" id="absent_status" class="form-control select2 select2bs5" required disabled>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($absence as $row): ?>
                                                <option <?= ($data['default_absence_status'] == $row->id) ? 'selected' : '' ?> value="<?= $row->id ?>"><?= "$row->code - $row->name" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="effective_date">Effective Date <strong class="text-danger">*</strong></label>
                                        <input type="date" name="effective_date" id="effective_date" class="form-control rounded-0" placeholder="Effective Date" autocomplete="off" required value="<?= $data['effective_date'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-8 col-lg-8 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_remark">Remark</label>
                                        <input type="text" name="data_remark" id="data_remark" class="form-control rounded-0" placeholder="Remark" autocomplete="off" value="<?= $data['remark'] ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row mb-3">
                                    <div class="form-group col-xl-3 col-lg-3 col-md-12 col-sm-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" <?= ($data['auto_overtime'] == 't') ? 'checked' : '' ?> type="checkbox" value="1" name="otomatis_hitung_lembur" role="switch" id="flexSwitchCheckDefault" disabled>
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Automatically Calculate Overtime</label>
                                        </div>
                                    </div>
                                </div>
                                <div id="form-auto-lembur" <?= ($data['auto_overtime'] == 't') ? '' : 'hidden' ?>>
                                    <div class="row g-2 mb-3">
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="col-form-label">Overtime Type</label>
                                            <select name="auto_overtime_type" id="auto_overtime_type" class="form-control select2 select2bs5" disabled>
                                                <option <?= ($data['overtime_type'] == '') ? 'selected' : '' ?> value="">-- Choose --</option>
                                                <option <?= ($data['overtime_type'] == '1') ? 'selected' : '' ?> value="1">Overtime In</option>
                                                <option <?= ($data['overtime_type'] == '2') ? 'selected' : '' ?> value="2">Overtime Out</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="col-form-label">Overtime Start</label>
                                            <input type="time" name="lembur_mulai" id="lembur_mulai" class="form-control rounded-0" placeholder="00.00" maxlenght="5" step="any" lang="id-ID" value="<?= $data['overtime_in'] ?>" disabled>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="col-form-label">Overtime Finish</label>
                                            <input type="time" name="lembur_selesai" id="lembur_selesai" class="form-control rounded-0" placeholder="00.00" maxlenght="5" step="any" lang="id-ID" value="<?= $data['overtime_out'] ?>" disabled>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="col-form-label">Overtime Duration</label>
                                            <input type="number" name="lama_lembur" id="lama_lembur" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Overtime duration" placeholder="Overtime duration" value="<?= $data['overtime_index'] ?>" disabled>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="col-form-label">Overtime break length</label>
                                            <input type="number" name="lama_istirahat" id="lama_istirahat" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Overtime break length" value="<?= $data['overtime_break'] ?>" disabled>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="col-form-label">Overtime Rate</label>
                                            <input type="number" name="rate_lembur" id="rate_lembur" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="00.00" maxlenght="5" placeholder="00.00" maxlenght="5" value="<?= $data['overtime_rate'] ?>" disabled>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="col-form-label">x15</label>
                                            <input type="number" name="lembur_x15" id="lembur_x15" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="00.00" maxlenght="5" placeholder="00.00" maxlenght="5" value="<?= $data['x15'] ?>" disabled>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="col-form-label">x20</label>
                                            <input type="number" name="lembur_x20" id="lembur_x20" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="00.00" maxlenght="5" placeholder="00.00" maxlenght="5" value="<?= $data['x20'] ?>" disabled>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="col-form-label">x30</label>
                                            <input type="number" name="lembur_x30" id="lembur_x30" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="00.00" maxlenght="5" placeholder="00.00" maxlenght="5" value="<?= $data['x30'] ?>" disabled>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                            <label class="col-form-label">x40</label>
                                            <input type="number" name="lembur_x40" id="lembur_x40" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="00.00" maxlenght="5" value="<?= $data['x40'] ?>" disabled>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</main>

<?= $this->endSection(); ?>