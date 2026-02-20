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
                        <li class="breadcrumb-item">Organization</li>
                        <li class="breadcrumb-item">Position</li>
                        <li class="breadcrumb-item">Edit Position</li>
                        <li class="breadcrumb-item active"><?= $data->name ?></li>
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
                        <button type="button" id="btnAdd" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Add">
                            <i class="bi bi-file-earmark-plus"></i>&ensp;Add
                        </button>
                        <button type="button" id="btnEdit" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                            <i class="bi bi-pencil-square"></i>&ensp;Edit
                        </button>
                        <button type="button" hidden id="btnUpdate" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Update">
                            <i class="bi bi-floppy"></i>&ensp;Update
                        </button>
                        <button type="button" hidden id="btnCancel" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
                        </button>
                        <button type="button" id="btnDelete" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                            <i class="bi bi-trash3"></i>&ensp;Delete
                        </button>
                        <button type="button" id="btnPrev" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Previous">
                            <i class="bi bi-chevron-double-left"></i>&ensp;Previous
                        </button>
                        <button type="button" id="btnNext" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Previous">
                            Next&ensp;<i class="bi bi-chevron-double-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-12 clearfix">
                    <div class="card rounded-0 card-primary card-outline">
                        <div class="card-body">
                            <form id="formData">
                                <div class="row g-2">
                                    <div class="form-group col-12 mb-3 clearfix" style="display: none;">
                                        <input type="text" name="data_token" id="data_token" class=" form-control rounded-0 bg-secondary-subtle" readonly value="<?= enkripsi($data->id) ?>">
                                    </div>
                                    <div class="form-group col-12 mb-3 clearfix">
                                        <input type="text" name="data_code" id="data_code" class="form-control form-control-lg text-primary fw-bolder rounded-0 bg-secondary-subtle" readonly value="<?= $data->code ?>">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_name">Position Name <strong class="text-danger fw-bolder">*</strong></label>
                                        <input type="text" name="data_name" id="data_name" class="form-control rounded-0" disabled placeholder="Position Name" autocomplete="off" autofocus maxlength="150" required value="<?= $data->name ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="nbhx_position">NBHX Position Category <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="nbhx_position" id="nbhx_position" class="form-control select2 select2bs5 rounded-0" disabled required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($nbhx_position as $nbp): ?>
                                                <option <?= ($data->nbhx_position == $nbp->id) ? 'selected' : '' ?> value="<?= $nbp->id ?>"><?= "$nbp->code - $nbp->name" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-5 col-lg-5 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_remark">Description</label>
                                        <input type="text" name="data_remark" id="data_remark" class="form-control rounded-0" disabled placeholder="Description" autocomplete="off" maxlength="255" value="<?= $data->description ?>">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_dept">Department <strong class="text-danger">*</strong></label>
                                        <select name="data_dept" id="data_dept" class="form-control select2 select2bs5 rounded-0" disabled required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($dept as $dpt): ?>
                                                <option <?= ($data->dept == $dpt->id) ? 'selected' : '' ?> value="<?= $dpt->id ?>"><?= "$dpt->code - $dpt->name" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_section">Section <strong class="text-danger">*</strong></label>
                                        <select name="data_section" id="data_section" class="form-select select2 select2bs5 rounded-0" disabled required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($section as $sec): ?>
                                                <option <?= ($data->section == $sec['token']) ? 'selected' : '' ?> value=" <?= trim($sec['token']) ?>"><?= "$sec[code] - $sec[name]" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_report_to">Report to Position <strong class="text-danger">*</strong></label>
                                        <select name="data_report_to" id="data_report_to" class="form-select select2 select2bs5 rounded-0" disabled required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($position as $pos): ?>
                                                <option <?= ($data->report_to == $pos['token']) ? 'selected' : '' ?> value="<?= $pos['token'] ?>"><?= "$pos[code] - $pos[name]" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_grade">Position Grade <strong class="text-danger">*</strong></label>
                                        <select name="data_grade" id="data_grade" class="form-select select2 select2bs5 rounded-0" disabled required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($grade as $grd): ?>
                                                <option <?= ($data->grade == $grd->id) ? 'selected' : '' ?> value="<?= $grd->id ?>"><?= "$grd->code - $grd->name" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_rank">Position Rank <strong class="text-danger">*</strong></label>
                                        <select name="data_rank" id="data_rank" class="form-select  select2 select2bs5 rounded-0" disabled required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($rank as $rnk): ?>
                                                <option <?= ($data->rank == $rnk->id) ? 'selected' : '' ?> value="<?= $rnk->id ?>"><?= "$rnk->code - $rnk->name" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_status">Position Status <strong class="text-danger">*</strong></label>
                                        <select name="data_status" id="data_status" class="form-select select2 select2bs5 rounded-0" disabled required>
                                            <option <?= ($data->emp_status == '') ? 'selected' : '' ?> value="">-- Choose --</option>
                                            <option <?= ($data->emp_status == 'REG') ? 'selected' : '' ?> value="REG">Reguler</option>
                                            <option <?= ($data->emp_status == 'TMP') ? 'selected' : '' ?> value="TMP">Temporary</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_category">Position Category <strong class="text-danger">*</strong></label>
                                        <select name="data_category" id="data_category" class="form-select  select2 select2bs5 rounded-0" disabled required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($emp_category as $ec): ?>
                                                <option <?= ($data->category == $ec->id) ? 'selected' : '' ?> value="<?= $ec->id ?>"><?= "$ec->code - $ec->name" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="nbhx_category">NBHX Category <strong class="text-danger">*</strong></label>
                                        <select name="nbhx_category" id="nbhx_category" class="form-select select2 select2bs5 rounded-0" disabled required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($class_nbhx as $cnb): ?>
                                                <option <?= ($data->nbhx_category == $cnb->id) ? 'selected' : '' ?> value="<?= $cnb->id ?>"><?= "$cnb->code - $cnb->name" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="effective_date">Effective Date <strong class="text-danger">*</strong></label>
                                        <input type="date" name="effective_date" id="effective_date" class="form-control rounded-0" disabled required value="<?= $data->effective_date ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_absen">Allow Finger <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_absen" id="data_absen" class="form-control select2 select2bs5" disabled required>
                                            <option <?= ($data->hitung_absen == '1') ? 'selected' : '' ?> value="1">Yes</option>
                                            <option <?= ($data->hitung_absen == '0') ? 'selected' : '' ?> value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_lembur">Allow Overtime <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_lembur" id="data_lembur" class=" form-control select2 select2bs5" disabled required>
                                            <option <?= ($data->hitung_lembur == '0') ? 'selected' : '' ?> value="0">No</option>
                                            <option <?= ($data->hitung_lembur == '1') ? 'selected' : '' ?> value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</main>

<?= $this->endSection(); ?>