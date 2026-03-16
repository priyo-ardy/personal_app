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
                        <li class="breadcrumb-item">Schedulle Setup</li>
                        <li class="breadcrumb-item active">Add</li>
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
                        <button type="button" id="btnCancel" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
                        </button>
                    </div>
                </div>
            </div>
            <form id="formData">
                <div class="row g-2 mb-3">
                    <div class="col-12 clearfix">
                        <div class="card rounded-0 card-primary card-outline">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_code">Code</label>
                                        <input type="text" name="data_code" id="data_code" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Automatic generate after save the document">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_name">Schedulle Name <strong class="text-danger">*</strong></label>
                                        <input type="text" name="data_name" id="data_name" class="form-control rounded-0" placeholder="Schedulle name" autocomplete="off" minlength="2" maxlength="150" autofocus required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_hari">Total Day Period <strong class="text-danger">*</strong></label>
                                        <input type="number" name="data_hari" id="data_hari" class="form-control rounded-0" placeholder="Total day period" autocomplete="off" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="effective_date">Effective Date <strong class="text-danger">*</strong></label>
                                        <input type="date" name="effective_date" id="effective_date" class="form-control rounded-0" autocomplete="off" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_remark">remark</label>
                                        <input type="text" name="data_remark" id="data_remark" class="form-control rounded-0" placeholder="Remark" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btnGenerate" class="btn btn-success rounded-0" title="Generate Shift Table"><i class="bi bi-gear"></i>&ensp;Generate Shift Table</button>
                                <button type="button" id="btnClear" class="btn btn-secondary rounded-0" title="Clear Shift Table"><i class="bi bi-arrow-counterclockwise"></i>&ensp;Clear Shift Table</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card rounded-0">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="align-middle bg-secondary-subtle text-center col-2">Day</th>
                                                <th class="align-middle bg-secondary-subtle text-center col-7">Shift</th>
                                                <th class="align-middle bg-secondary-subtle text-center col-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="shiftList"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<div id="daftarShift" style="display: none;">
    <?php foreach ($shift as $s): ?>
        <option value="<?= $s->id ?>"><?= "$s->code - $s->name" ?></option>
    <?php endforeach; ?>
</div>

<?= $this->endSection(); ?>