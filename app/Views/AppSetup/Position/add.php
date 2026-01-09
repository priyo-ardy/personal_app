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
                        <li class="breadcrumb-item">User Management</li>
                        <li class="breadcrumb-item active">List of Users</li>
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
                        <button type="button" id="btnSave" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Save">
                            <i class="bi bi-floppy"></i>&ensp;Save
                        </button>
                        <button type="button" id="btncancel" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
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
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_name">Position Name <strong class="text-danger fw-bolder">*</strong></label>
                                        <input type="text" name="data_name" id="data_name" class="form-control rounded-0" placeholder="Position Name" autocomplete="off" autofocus maxlength="150" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="nbhx_position">NBHX Position Category <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="nbhx_position" id="nbhx_position" class="form-control select2 select2bs5 rounded-0" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($nbhx_position as $nbp): ?>
                                                <option value="<?= $nbp->id ?>"><?= "$nbp->code - $nbp->name" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-5 col-lg-5 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_remark">Description</label>
                                        <input type="text" name="data_remark" id="data_remark" class="form-control rounded-0" placeholder="Description" autocomplete="off" maxlength="255">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_dept">Department <strong class="text-danger">*</strong></label>
                                        <select name="data_dept" id="data_dept" class="form-control select2 select2bs5 rounded-0" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($dept as $dpt): ?>
                                                <option value="<?= $dpt->id ?>"><?= "$dpt->code - $dpt->name" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_section">Section <strong class="text-danger">*</strong></label>
                                        <select name="data_section" id="data_section" class="form-select select2 select2bs5 rounded-0" required>
                                            <option value="">-- Choose --</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_report_to">Report to Position <strong class="text-danger">*</strong></label>
                                        <select name="data_report_to" id="data_report_to" class="form-select select2 select2bs5 rounded-0" required>
                                            <option value="">-- Choose --</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_grade">Position Grade <strong class="text-danger">*</strong></label>
                                        <select name="data_grade" id="data_grade" class="form-select select2 select2bs5 rounded-0" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($grade as $grd): ?>
                                                <option value="<?= $grd->id ?>"><?= "$grd->code - $grd->name" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_rank">Position Rank <strong class="text-danger">*</strong></label>
                                        <select name="data_rank" id="data_rank" class="form-select  select2 select2bs5 rounded-0" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($rank as $rnk): ?>
                                                <option value="<?= $rnk->id ?>"><?= "$rnk->code - $rnk->name" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_status">Position Status <strong class="text-danger">*</strong></label>
                                        <select name="data_status" id="data_status" class="form-select select2 select2bs5 rounded-0" required>
                                            <option value="">-- Choose --</option>
                                            <option value="REG">Reguler</option>
                                            <option value="TMP">Temporary</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_category">Position Category <strong class="text-danger">*</strong></label>
                                        <select name="data_category" id="data_category" class="form-select  select2 select2bs5 rounded-0" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($emp_category as $ec): ?>
                                                <option value="<?= $ec->id ?>"><?= "$ec->code - $ec->name" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="nbhx_category">NBHX Category <strong class="text-danger">*</strong></label>
                                        <select name="nbhx_category" id="nbhx_category" class="form-select select2 select2bs5 rounded-0" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($class_nbhx as $cnb): ?>
                                                <option value="<?= $cnb->id ?>"><?= "$cnb->code - $cnb->name" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="effective_date">Effective Date <strong class="text-danger">*</strong></label>
                                        <input type="date" name="effective_date" id="effective_date" class="form-control rounded-0" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_absen">Allow Finger <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_absen" id="data_absen" class="form-control select2 select2bs5" required>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-1 clearfix">
                                        <label class="form-label" for="data_lembur">Allow Overtime <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_lembur" id="data_lembur" class=" form-control select2 select2bs5" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
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